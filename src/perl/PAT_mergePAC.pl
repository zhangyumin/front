#!/usr/bin/perl -w
#2015/9/16
#合并PAC表，允许指定多个列
#不用重新提供gff表，直接根据PAC表的区域
#输出列数有所改变 chr,strand,coord,tot_tagnum,<samples>,ftr,ftr_start,ftr_end,transcript,gene,gene_type,UPA_start,UPA_end,<samples,tot_tagnum>

#与 PAT_mergePAC.pl 的不同
#没有经过alterPA，没有进行mapGFF
#输出的列数与通常的PAC表有所不同。。。少了一些不重要的列（懒得存了）
#直接用hash按chr进行group，最后取PAC cluster中PAC的tot最大的一行为ftr信息

#use lib '/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi/';
use lib '/var/www/front/src/perl';
#use lib '/home/zym/soft/cpan/lib/perl5/';

use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;
use List::MoreUtils qw/ uniq /; 

require ('funclib.pl');

##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","uu:s","smptbls=s","smpcols=s","smplbls:s","reftbl:s","udist=i","otbl=s","conf=s");
my $USAGE=<< "_USAGE_";
Usage: 
 
  PAT_mergePACv2.pl -smptbls "t_ae_pacs_pistil_d24;t_ae_pacs_dry_seed_d24" -smpcols "pistil1:pistil2;dry_seed2:dry_seed3" -smplbls "p1:p2;d1:d2" -otbl t_pistil_dry -udist 24 -conf dbconf_ricepac.xml
  
  PAT_mergePACv2.pl -smptbls "t_test;t_ae_pacs_dry_seed_d24" -reftbl t_test -smpcols "pistil1:pistil2;dry_seed2:dry_seed3" -smplbls "p1:p2;d1:d2" -otbl t_pistil_dry -udist 24  -conf dbconf_ricepac.xml

-h=help
-smptbls=PAC tables like wt_g24;oxt_g24.
-reftbl=若设置，则先筛选其它tbl与reftbl同gene的所有PAC，再进行合并
比如 user smp+sys smp，想要搜索出与user smp对应的sys smp，而不是所有的sys数据
-smpcols=smp cols name in out table. like WT1:wt2;oxt1:oxt2
-smplbls=lables for smpcols, like WT1:wt2;oxt1:oxt2; if none then as smpcols
-udist=dist to cluster uPA. if udist<=0, then cluster the same pos.
-otbl=out PAC table
-conf=db conf
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'smptbls'}||!$opts{'conf'}||!$opts{'smpcols'}||!$opts{'otbl'};
my $smptbls=$opts{'smptbls'};
my $reftbl=$opts{'reftbl'};
my $smpcol=$opts{'smpcols'};
my $otbl=$opts{'otbl'};
my $udist=$opts{'udist'};
$udist=0 if $udist<0;

my @intbls=split(/[,;:]/,$smptbls);
my $have=0;
my $refidx=-1;
for my $i(0..$#intbls) {
  die "otbl in intbls" if $otbl eq $intbls[$i];
  if ($intbls[$i] eq $reftbl) {
	$have=1;
	$refidx=$i;
  }
}
die "reftbl not in intbl" if $reftbl and !$have;
my $smpcols=[]; #每行表示一个tbl的smps列
my $olbls=[];
my @items=split(/;/,$smpcol);
my $nsmp=scalar(split(/[,;:]/,$smpcol));

for my $i(0..$#items) {
  push(@$smpcols,[split(/:/,$items[$i])])
  
}

my $smplbls=$opts{'smplbls'};
if ($smplbls) {
  @items=split(/;/,$smplbls);
  for my $i(0..$#items) {
    push(@$olbls,[split(/:/,$items[$i])])
  }
} else {
  $olbls=$smpcols;
  $smplbls=$smpcol;
}

if ($smplbls and scalar(uniq split(/[,;:]/,$smplbls)) != scalar(split(/[,;:]/,$smpcol)) ) {
  die "error smplbls or smpcols";
}

die "At least two intbls!" if ($#intbls<=0);
die "smptbls count not equal to smpcols count!" if $#intbls!=$#$olbls;
die "smpcols count not equal to smpcols count!" if $#$smpcols!=$#$olbls;

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my($dbh)=connectDB($opts{'conf'},1);

#############################################################################
#  transcript List                                                  
#############################################################################
my $mastertbl="merge_PAC_master_xx";
$dbh->do("drop table if exists $mastertbl") or die;
my $gtbl="merge_PAC_master_gxx";;
if ($reftbl) { #先取得ref_PAC表的所有基因
  $dbh->do("drop table if exists $gtbl") or die;
  my $sql="create table $gtbl select distinct(gene) from $reftbl where ".join('+',@{$smpcols->[$refidx]}).">0"; 
  $dbh->do($sql);
  $dbh->do("create index idx_g on $gtbl(gene)");
}

#建chr,strand,coord的哈希表，将所有样本拉成一行；两个哈希表分别存info和tag
my %info=();
my %tags=();

my $sql='';
my ($is,$ie)=(0,-1);
for my $i(0..$#$smpcols) {
  if (!$reftbl or $refidx==$i) {
    $sql="select chr,strand,coord,ftr,ftr_start,ftr_end,transcript,gene,gene_type,UPA_start,UPA_end,".join(',',@{$smpcols->[$i]})." from $intbls[$i] where ".join('+',@{$smpcols->[$i]}).">0";
  } elsif ($refidx!=$i) {
    $dbh->do("create index idx_g on $intbls[$i](gene)");
    $sql="select chr,strand,coord,ftr,ftr_start,ftr_end,transcript,gene,gene_type,UPA_start,UPA_end,".join(',',@{$smpcols->[$i]})." from $intbls[$i] where ".join('+',@{$smpcols->[$i]}).">0 and gene in (select gene from $gtbl)";
  }

  my $ns=scalar(@{$smpcols->[$i]});
  $is=$ie+1;
  $ie=$is+$ns-1;
  my ($set,$rv)=execSql($dbh,$sql);
  next if $rv<=0;
  print "$rv PAC from $intbls[$i] (reftbl=$reftbl)\n";
  for my $j(0..$#$set) {
    my $key=join(':',@{$set->[$j]}[0..2]);
	my @t=@{$set->[$j]}[11..(10+$ns)];
    #$info{$key}=join("\t",@{$set->[$j]}[3..10]);
    @{$tags{$key}}[0..7]=@{$set->[$j]}[3..10];
	for my $k($is..$ie) {
      $tags{$key}[8+$k]+=$t[$k-$is]; #最后几列是样本列
	}
 }
}

	#open(OF,">f:/tags.txt") or die "cannot write tags.xx";
	#  foreach my $k(keys(%tags)) {
	#	print OF join("\t",@{$tags{$k}})."\n";
	#  }
   # close(OF);

print "PA2PAC...\n";
##将hash转换为矩阵，并按chr,strand,coord排序
my $mtx=[];
foreach my $k(keys(%tags)) {
  my $tot=0;
  for my $i(1..$nsmp) {
	if (!defined($tags{$k}[7+$i])) {
	  $tags{$k}[7+$i]=0;
	} else {
	  $tot+=$tags{$k}[7+$i];
	}
  }
  push(@$mtx,[(split(/:/,$k),@{$tags{$k}},$tot)]); #0..10是ftr列，再是sample列，最后1列是tot
}
@$mtx = sort{$a->[0] cmp $b->[0] or $a->[1] cmp $b->[1] or $a->[2] <=> $b->[2]} @{$mtx};
#saveMtx2File($mtx,'f:/mtx.txt');

my $last=11+$nsmp;

##将相邻的group
my $tot=$mtx->[0][$last];
my @smptag=@{$mtx->[0]}[11..(10+$nsmp)];
my $max=$tot;
my $maxi=0;
my $ofile=getTmpPath(1).'mergePAC.tmp';
open(OF,">$ofile") or die "cannot write $ofile";
for my $i(1..$#$mtx) {
  if ($mtx->[$i][0] eq $mtx->[$i-1][0] and $mtx->[$i][1] eq $mtx->[$i-1][1]) {
	 if ($mtx->[$i][2]-$mtx->[$i-1][2]<=$udist) {
		$tot+=$mtx->[$i][$last]; #tot列
		for my $j(11..(10+$nsmp)) { #样本列的和
		  $smptag[$j-11]+=$mtx->[$i][$j];
		}
		if ($max<$mtx->[$i][$last]) {
		  $max=$mtx->[$i][$last];
		  $maxi=$i;
		}
	 } else {
	   print OF join("\t",(@{$mtx->[$maxi]}[0..10],@smptag,$tot))."\n";
       $tot=$mtx->[$i][$last];
	   $maxi=$i;
	   $max=$tot;
	   @smptag=@{$mtx->[$i]}[11..(10+$nsmp)];
	 }	
  } else {#chr/strand
	   print OF join("\t",(@{$mtx->[$maxi]}[0..10],@smptag,$tot))."\n";
       $tot=$mtx->[$i][$last];
	   $maxi=$i;
	   $max=$tot;
	   @smptag=@{$mtx->[$i]}[11..(10+$nsmp)];
  }
}
close(OF);

if ($reftbl) { #先取得ref_PAC表的所有基因
  $dbh->do("drop table if exists $gtbl") or die;
}

##导入数据库
$dbh->do("drop table if exists $otbl") or die;
my $txt=join(', coord ',split(/[,;:]/,$smplbls));
$txt="coord ".$txt.", coord tot_tagnum";
my $sql="create table $otbl select chr,strand,coord,ftr,ftr_start,ftr_end,transcript,gene,gene_type,UPA_start,UPA_end, $txt from $intbls[0] where 1<>1";
$dbh->do($sql) or die;
my $rv=loadFile2Tbl($dbh,$otbl,$ofile,0);
print "$rv PACs to $otbl\n";
unlink $ofile;

$dbh->disconnect();


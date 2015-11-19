#!/usr/bin/perl -w
#2015/6/2
#合并PAC表，允许指定多个列
#必须重新提供gff表进行关联

#过程
#1 取所有PAC表的chr,strand,coord,smps列，重新组装成masterPA表
#2 再将masterPA按dist聚类（PA2PAC）以及比对到gfftbl
#3 合并后的表与PAC表的格式完全相同
#注意：这种方式得到的UPA_start..end就变成是PAC聚类的起始和终止PAC（因为PAC被当作是PA处理了）

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
GetOptions(\%opts,"h","uu:s","smptbls=s","smpcols=s","smplbls:s","reftbl:s","udist=i","otbl=s","gfftbl=s","conf=s");
my $USAGE=<< "_USAGE_";
Usage: 
 
  PAT_mergePAC.pl -smptbls "t_ae_pacs_pistil_d24;t_ae_pacs_dry_seed_d24" -smpcols "pistil1:pistil2;dry_seed2:dry_seed3" -smplbls "p1:p2;d1:d2" -otbl t_pistil_dry -udist 24 -gff rice_gff7.t_gff7_org -conf dbconf_ricepac.xml
  
  PAT_mergePAC.pl -smptbls "t_test;t_ae_pacs_dry_seed_d24" -reftbl t_test -smpcols "pistil1:pistil2;dry_seed2:dry_seed3" -smplbls "p1:p2;d1:d2" -otbl t_pistil_dry -udist 24 -gff rice_gff7.t_gff7_org -conf dbconf_ricepac.xml

-h=help
-smptbls=PAC tables like wt_g24;oxt_g24.
-reftbl=若设置，则先筛选其它tbl与reftbl同gene的所有PAC，再进行合并
比如 user smp+sys smp，想要搜索出与user smp对应的sys smp，而不是所有的sys数据
-smpcols=smp cols name in out table. like WT1:wt2;oxt1:oxt2
-smplbls=lables for smpcols, like WT1:wt2;oxt1:oxt2; if none then as smpcols
-udist=dist to cluster uPA. if udist<=0, then cluster the same pos.
-gff=gff table
-otbl=out PAC table
-conf=db conf
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
my $ALTERPL='PAT_alterPAs.pl';;
$ALTERPL='/var/www/front/src/perl/PAT_alterPAs.pl' if !(-e 'PAT_alterPAs.pl');
die "PAT_alterPAs.pl not exists" if !(-e $ALTERPL);

my $PA2PACPL='PAT_PA2PAC.pl';;
$PA2PACPL='/var/www/front/src/perl/PAT_PA2PAC.pl' if !(-e 'PAT_PA2PAC.pl');
die "PAT_PA2PAC.pl not exists" if !(-e $PA2PACPL);

die $USAGE if $opts{'h'}||!$opts{'smptbls'}||!$opts{'conf'}||!$opts{'smpcols'}||!$opts{'otbl'}||!$opts{'gfftbl'};
my $smptbls=$opts{'smptbls'};
my $reftbl=$opts{'reftbl'};
my $smpcol=$opts{'smpcols'};
my $otbl=$opts{'otbl'};
my $udist=$opts{'udist'};
$udist=0 if $udist<0;
my $gfftbl=$opts{'gfftbl'};

my $uu=1;
$uu=0 if $opts{'uu'} eq 'F';

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
#读表存为文件
#将文件连成一个masterPA表
my $mastertbl="merge_PAC_master_xx";
$dbh->do("drop table if exists $mastertbl") or die;
my $gtbl="merge_PAC_master_gxx";;
if ($reftbl) { #先取得ref_PAC表的所有基因
  $dbh-do("drop table if exists $gtbl") or die;
  my $sql="create table $gtbl select distinct(gene) from $reftbl where ".join('+',@{$smpcols->[$refidx]}).">0"; 
  $dbh->do($sql);
  $dbh->do("create index idx_g on $gtbl(gene)");
}
my $sql='';
for my $i(0..$#$smpcols) {
  my $PAfile=getTmpPath(1)."$intbls[$i].PA.xx";
  unlink $PAfile if -e $PAfile;
  if (!$reftbl or $refidx==$i) {
    $sql="select chr,strand,coord,".join(',',@{$smpcols->[$i]})." from $intbls[$i] where ".join('+',@{$smpcols->[$i]}).">0 into outfile \'$PAfile\'";
  } elsif ($refidx!=$i) {
    $dbh->do("create index idx_g on $intbls[$i](gene)");
    $sql="select chr,strand,coord,".join(',',@{$smpcols->[$i]})." from $intbls[$i] where ".join('+',@{$smpcols->[$i]}).">0 and gene in (select gene from $gtbl) into outfile \'$PAfile\'";
  }
  my $rv=$dbh->do($sql) or die;
  print "$rv PAC from $intbls[$i] (reftbl=$reftbl)\n";
  next if $rv<=0;
  #PAT_alterPAs.pl -flds 0:1:2:4:5 -master t_test -aptbl F:/test.PA -owsmp mp5:mp6 -format file -conf dbconf_ricepa.xml
  my $cmd="$ALTERPL -master $mastertbl -owtbl \"$PAfile\" -owsmp ".join(':',@{$olbls->[$i]})." -format file -conf \"$opts{'conf'}\"";
  print "\n\n$cmd\n\n";
  system $cmd;
  print "\n\n$PAfile\n\n"
  #unlink $PAfile if -e $PAfile;
}

if ($reftbl) { #先取得ref_PAC表的所有基因
  $dbh-do("drop table if exists $gtbl") or die;
}

#PA2PAC
#PAT_PA2PAC.pl -anti T -d 24 -mtbl masterPA -gfftbl gff_ae120 -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml

my $cmd="$PA2PACPL -remap T -anti F -d $udist -mtbl $mastertbl -gfftbl $gfftbl -otbl $otbl -smps ".join(':',split(/[,;:]/,$smplbls))." -conf \"$opts{'conf'}\"";
print "$cmd\n";
system $cmd;
#$dbh->do("drop table if exists $mastertbl") or die;

$dbh->disconnect();


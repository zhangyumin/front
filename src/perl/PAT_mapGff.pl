#!/usr/bin/perl -w
#2010/01/30
#�ñ����ķ�ʽ����PAtbl��gfftbl,���Ϊ�����ֶε�ȫ��(ȥ���ظ���chr,strand)
#���ַ�ʽ�п��ܻ��mapGff.pl�Ľ���ټ���,��Ϊȥ�������ظ�ftr�г��ֵ�PA..

#2010/8/20
#����-posfldѡ��,default(polyA_site_in_genome);

#2010/8/21
#����-sfld,-efldѡ��,��������tag��Χ
#��������1�� in_boudary, ���ڱȶԽ��,�������ȫ������ftr��,��in_boudary=1

#!!map���PA�����ܻ����,��ȡ���ڸ�����gfftbl��������.
#!!��Щgff������ͬһgene��5UTR֮��û�����intron..

#2010/8/23
#����-len:i, �������posfld+lenȷ��start~end
#��posfld+len����mode=3, posfld+len-1�൱��efld
#�޸�push(@map),ֱ��print����

#2010/8/24
#����ѡ�� -page:i=0(default)
#��ҳ����,limit offset,rowcount
#���ڱ�Ƚϴ��,�бȽ϶��PA��ʹ��.. ����rice_sbs��.

#2011/5/3
#ʹ��findOverlaps��д
#����ӿڲ���..ֻ���޸��ڲ�����

#2011/5/3
#����ogflds��opflds�� -- ���ں��ڵ�PA��Ĺ淶��(ֻ��gff_id),PAC��ȫ��ע��
#��ogflds='ALL',����ԭ����ͬ; 
#��ogflds=gff_id,��ֻ���gff_id��
#��ogflds=xx:yy, ��ֻ���xx,yy��
#�������PA���ظ�,��ֻ���PA���,�����GFF���

#2011/5/17
#���� anti=T/F ѡ��,��T,����ȶ�
#���� -preѡ��,��Ϊxx,���ogflds��opflds����preǰ׺.. ��Ҫ���ڱ�ʶanti����,���罫��sense ftr��PAC,�ٹ���anti��GFF

#2012-09-10
#mtr����������long/short �� -- ��Ҫdbconf.longchr��

#2012-09-10 ����Mtr
#use test;
#create table wt_pas select chr,strand,coord,wt from arab_aghoxt.t_wt_pac;
#PAT_mapGff.pl -anti T -gff arab_tair9.t_gf9_ae120 -posfld coord -pa wt_pas -out wt_pas_gf9 -conf dbconf_test.xml
#PAT_mapGff_pnas.pl  -anti T -gff arab_tair9.t_gf9_ae120 -posfld coord -pa wt_pas -out wt_pas_gf9_pnas -conf dbconf_test.xml
#select * from wt_pas_gf9 order by chr,strand,coord into outfile 'c:/1.txt';
#select * from wt_pas_gf9_pnas order by chr,strand,coord into outfile 'c:/2.txt';

push(@INC,"/var/www/front/src/perl/");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/");

require ('funclib.pl');

use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;


use strict;

our ($ANY,$WITHIN,$CONTAIN,$EQUAL,$OVP);

##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","gff=s","pa=s","posfld:s","len:i","sfld:s","efld:s","page:i","out=s","ogflds:s","opflds:s","anti:s","ogpre:s","oppre:s","conf=s");
my $USAGE=<< "_USAGE_";
Usage:
  1) PAT_mapGff.pl -gff t_gff9_ae120 -pa t_est_8k_pas_ip0 -out yy -conf dbconf_xx.xml
  mode=1
  2) PAT_mapGff.pl -gff t_gff9_ae120 -pa t_est_8k_pas_ip0 -posfld pos -out yy -conf dbconf_xx.xml
  mode=2
  3) PAT_mapGff.pl -gff t_gff9_ae120 -pa xx -sfld tag_start -efld tag_end -out yy -conf dbconf_xx.xml
  mode=3
  4) PAT_mapGff.pl -gff t_gff9_ae120 -pa xx -posfld pos -len 20 -out yy -conf dbconf_xx.xml
  PAGE
  5) PAT_mapGff.pl -gff t_gff9_ae120 -pa xx -posfld pos -len 20 -out yy -page 400000 -conf dbconf_xx.xml

  6) output fields
   PAT_mapGff.pl -gff t_gff9_ae120 -pa xx -posfld pos -len 20 -out yy -ogflds gff_id -opflds chr:strand:polyA_site_in_genome -conf dbconf_xx.xml

  7) anti map
   PAT_mapGff.pl -anti T -gff t_gff9_ae120 -pa xxPAC -posfld coord -out yy_anti -ogflds gff_id:strand:ftr:ftr_start:ftr_end:transcript:gene:gene_type -ogpre anti_ -conf dbconf_xx.xml

  8) ͬʱsense��anti���ȶ�
  PAT_mapGff.pl -gff arab_tair10.t_gff10_ext50 -pa t_ext50_wtAN_pac_bg -posfld coord -out t_ext50_wtAN_pac_bgmap -anti F -conf dbconf_arabigt2.xml
  ע��anti=TʱҪ�Զ���ogflds������ogpre!
  PAT_mapGff.pl -anti T -gff arab_tair10.t_gff10_ext50 -pa t_ext50_wtAN_pac_bgmap -posfld coord -out t_ext50_wtAN_pac_bgmap2 -ogflds strand:ftr:ftr_start:ftr_end:transcript:gene:gene_type -ogpre anti_ -conf dbconf_arabigt2.xml


-h=help
-gff=gff table (HAS columns: chr,strand,ftr_start,ftr_end)
-pa=pa table with PA (HAS columns: chr,strand,(\$posfld) or (\$sfld,\$efld))
@-posfld=pos field; default(polyA_site_in_genome)
  mode=1
@-len=0; length of tag
  mode=3
*-sfld/efld=start/end field for tag 
  *xor with posfld. !!sfld/efld cannot be start/end!!
  *mode=2
*-page=0(default). if page>0, then at least 40,0000, and split pa table each page-rowcount
-out=table <GFF>+<PA>-chr,strand+is_boundary(sfld/efld or posfld/len)
[���PA��GFF��������ʱ���غϵ��У���ȡ����PA����У�����GFF���]
-ogflds=output fields of GFF table, like '' to output all, xx:yy to output xx and yy
-opflds=output fields of PA table, like '' to output all
*if ogflds in PA table, then not output ogflds
-anti=map patbl to antisense gfftbl
-ogpre/oppre=add prefix to output gffFlds/paFlds
-conf=db config
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
my($PLEN,$patbl,$otname,$gfftbl);
die $USAGE if $opts{'h'}||!$opts{'gff'}||!$opts{'conf'}||!$opts{'out'};
$patbl=$opts{'pa'};
$gfftbl=$opts{'gff'};
$otname=$opts{'out'};
my $posfld=$opts{'posfld'};
my $len=$opts{'len'};
my $sfld=$opts{'sfld'};
my $efld=$opts{'efld'};
my $page=$opts{'page'};
$page=400000 if $page and $page<400000;
my $ogflds=$opts{'ogflds'};
my $opflds=$opts{'opflds'};
my $anti=$opts{'anti'};
my $ogpre=$opts{'ogpre'};
my $oppre=$opts{'oppre'};
$oppre='' if !defined($oppre);
$ogpre='' if !defined($ogpre);
if ($anti eq 'T') {
  $anti=1 ;
} else {
  $anti=0;
}
$posfld='polyA_site_in_genome' if !$posfld and !$sfld and !$efld;
die "Cannot sfld/efld and posfld" if $posfld and ($sfld or $efld);
die "Must both sfld/efld" if ($sfld or $efld) and (($sfld and !$efld) or ($efld and !$sfld));
die "Must both posfld/len" if (!$posfld and $len);
die "Cannot sfld/efld and posfld/len" if (($posfld or $len) and ($sfld or $efld));
die "pa cannot same as out" if $patbl eq $otname;
die "gff cannot same as out" if $gfftbl eq $otname;

my $mode=1 if $posfld;
$mode=2 if $sfld;
$mode=3 if $len>0;

my $txt="($posfld)" if $mode==1;
$txt="($sfld~$efld)" if $mode==2;
$txt="($posfld,len=${len}nt)" if $mode==3;

die "Error mode" if ($mode!=1 and $mode!=2 and $mode!=3);

print "anti=$anti, mode=$mode$txt, from $patbl, $gfftbl to $otname\n";

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my $conf=$opts{'conf'};
my($dbh,$longchr)=connectDB($conf,1,('longchr'));

#############################################################################
#  MAP                                               
#############################################################################
#��ȡ����ֶε��ֶ��±�
my($posFld,$sFld2,$eFld2,$all);
if ($mode==1 or $mode==3) {
  $posFld=getFldsIdx($dbh,$patbl,$posfld) ;
  if ($posFld==-1) {
    die "$posfld not found in table $patbl!";
  }
} elsif ($mode==2) {
  ($sFld2,$eFld2,$all)=getFldsIdx($dbh,$patbl,($sfld,$efld)) ;
  if ($all==-1) {
    die "$sfld or $efld not found in table $patbl!";
  }
}

my ($sFld,$eFld,$all)=getFldsIdx($dbh,$gfftbl,('ftr_start','ftr_end')) ;
die "ftr_start or ftr_end not in $gfftbl" if $all==-1;

my (@gflds,@gIdxs,@pflds,@pIdxs);
if ($ogflds) {
  @gflds=split(/:/,$ogflds);
  @gIdxs=getFldsIdx($dbh,$gfftbl,@gflds) ;
  die "$ogflds not in $gfftbl" if $gIdxs[$#gIdxs]==-1;
  pop(@gIdxs) if $gIdxs[$#gIdxs]==-1;
}  else {
  @gflds=getTblFlds($dbh,$gfftbl);
}
if ($opflds) {
  @pflds=split(/:/,$opflds);
  @pIdxs=getFldsIdx($dbh,$patbl,@pflds) ;
  die "$opflds not in $patbl" if $pIdxs[$#pIdxs]==-1;
  pop(@pIdxs) if $pIdxs[$#pIdxs]==-1;
} else {
  @pflds=getTblFlds($dbh,$patbl);
}

#���δ����opflds��opflds,��pflds=gflds,��ȥ��gflds����Ӧ��
if (!$opflds and !$ogflds) {
	my (@temp,$have);
	for my $i(0..$#gflds) {
	  $have=0;
	  for my $j(0..$#pflds) {
		if ($pflds[$j] eq $gflds[$i]) {
		  $have=1;
		  last;
		}
	  }
	  if (!$have) {
		push(@temp,$gflds[$i]);
	  }
	}
	die "no output gffFlds, all in PA table" if $#temp==-1;
	@gflds=@temp;
} else {
	#���oppre+pflds=ogpre+gflds,�򱨴�!
	for my $i(0..$#gflds) {
	  for my $j(0..$#pflds) {
		if ($oppre.$pflds[$j] eq $ogpre.$gflds[$i]) {
		   die "opflds:$oppre$pflds[$j] = ogflds:$ogpre$gflds[$i], when not opflds and not ogflds";
		}
	  }
	}
}
print "\n***PA fields: @pflds; prefix=$oppre\n";
print "***GFF fields: @gflds; prefix=$ogpre\n\n";

#����pflds��gflds��
my ($fs)='';
for my $i(0..$#pflds) {
  $fs.="$patbl.$pflds[$i] $oppre$pflds[$i],";
}
for my $i(0..$#gflds) {
  $fs.="$gfftbl.$gflds[$i] $ogpre$gflds[$i],";
}
$fs=substr($fs,0,length($fs)-1);
$dbh->do("drop table if exists $otname") or die; 
#print "create table $otname select $fs from $patbl,$gfftbl where 1<>1\n";
$dbh->do("create table $otname select $fs from $patbl,$gfftbl where 1<>1") or die; 
#����is_boundary��.
if ($mode==2 or $mode==3) {
  $dbh->do("alter table $otname add column is_boundary int") or die; 
}

#���յ�����ֶ���ԭ���е��±�
@gIdxs=getFldsIdx($dbh,$gfftbl,@gflds) ;
@pIdxs=getFldsIdx($dbh,$patbl,@pflds) ;
pop(@gIdxs) if $#gIdxs>0;
pop(@pIdxs) if $#pIdxs>0;

my(@strands)=('+','-');
my($ofile)=getTmpPath(1)."$otname";
unlink($ofile) if (-e $ofile);
open(OO,">$ofile") or die "Cannot write $ofile\n";

my ($tmptbl);
if ($page) {
  my @tots=getFldValues($dbh,"SELECT count(*) FROM $patbl",0);
  my $tot=$tots[0];
  my ($nbin)=int($tot/$page); #����
  my ($res)=0;
  if ($tot%$page) {	
	$res=$tot%$page;
	$nbin++;
  }
  print "total=$tot; bin=$nbin; page=$page; res=$res\n";
  for my $nb(1..$nbin) {	
	my $offset=($nb-1)*$page;
	$tmptbl='MAPGFF_TMP';
	print "processing page$nb of $nbin (offset $offset)...\n";
    $dbh->do("drop table if exists $tmptbl");
    $dbh->do("create TEMPORARY table $tmptbl select * from $patbl limit $offset,$page") or die;
	for_chr_strand();
	$dbh->do("drop table if exists $tmptbl");
  }
} else {
  $tmptbl=$patbl;
  for_chr_strand();
}

close(OO);

##�������ݿ���
#print "load data into $otname.\n";
my $rv=loadFile2Tbl($dbh,$otname,$ofile,0);

print "********************\n";
print "$rv rows to $otname\n";
unlink($ofile) if (-e $ofile);
$dbh->disconnect();


##################
## SUB FUNC
##################
#��chr,strand����tmptbl
sub for_chr_strand {

#����longchr
my (@chrs,$sqlchr);
if ($longchr) {
  $sqlchr=dotStr2sqlStr($longchr);
  @chrs=getFldValues($dbh,"SELECT distinct(chr) FROM $tmptbl where chr in ($sqlchr) order by chr",0);
} else {
  @chrs=getFldValues($dbh,"SELECT distinct(chr) chr FROM $tmptbl order by chr",0);
}

my $orderby=$posfld if ($mode==1 or $mode==3);
$orderby=$sfld if $mode==2;

foreach my $chr (@chrs) {      
   foreach my $s(@strands) {

	  my ($gff,$PA,$rv)=([],[],0);
	  if (!$anti) {
		  my $sql="select * from $gfftbl where chr=\'$chr\' and strand=\'$s\' order by ftr_start";
		  ($gff,$rv)=execSql($dbh,$sql);
		  print "$chr $s $rv Gffs; ";
	  } else {
		  my $as='+' if $s eq '-';
		  $as='-' if $s eq '+';
		  my $sql="select * from $gfftbl where chr=\'$chr\' and strand=\'$as\' order by ftr_start";
		  ($gff,$rv)=execSql($dbh,$sql);
		  print "$chr $s $rv antiGffs; ";
	  }

	  my $sql="select * from $tmptbl where chr=\'$chr\' and strand=\'$s\' order by $orderby";
	  ($PA,$rv)=execSql($dbh,$sql);
	  print "$rv PAs; ";

	  my $n=output($gff,$PA);
	  print "$n maps\n";
   } #s
} #chr 

#����ж�chr
if ($longchr) { #Mtr
  my($gffchrFld,$gffstrandFld)=getFldsIdx($dbh,$gfftbl,('chr','strand'));
  my($pachrFld,$pastrandFld)=getFldsIdx($dbh,$tmptbl,('chr','strand'));
  #ȡ��ʣ���CS (chr1,+)
  my @CS=getFldValues($dbh,"SELECT distinct(concat(chr,\',\',strand)) CS FROM $tmptbl where chr not in ($sqlchr)",0);
  my $sql="select * from $gfftbl where chr not in ($sqlchr) order by chr,strand,ftr_start";
  my ($gffs,$rv)=execSql($dbh,$sql);
  $sql="select * from $tmptbl where chr not in ($sqlchr) order by chr,strand,$orderby";
  my ($PAs,$rv2)=execSql($dbh,$sql);

  print "********************\n";
  print scalar(@CS)." short chr-strands; $rv2 PAs\n";
  if  ($#$gffs==-1 or $#$PAs==-1) {
	print "0 short maps\n";
	return;
  }
  my $idxsGff=getIntervals($gffs,"$gffchrFld:$gffstrandFld",1);
  my $idxsPA=getIntervals($PAs,"$pachrFld:$pastrandFld",1);  

  my (%hashGff,%hashPA);
  for my $i(0..$#$idxsGff) {
	$hashGff{$idxsGff->[$i][2]}=($idxsGff->[$i][0].','.$idxsGff->[$i][1]);
  }
  for my $i(0..$#$idxsPA) {
	$hashPA{$idxsPA->[$i][2]}=($idxsPA->[$i][0].','.$idxsPA->[$i][1]);
  }
  $idxsGff=$idxsPA=[];

  my $shortN=0;
##ÿ��ȡ��һ��chr,strand
  for my $cs(@CS) {
	my $s=substr($cs,length($cs)-1,1);
	my ($pis,$pie,$gis,$gie)=(-1,-1,-1,-1);
	my @tmps=split(/,/,$hashPA{$cs});
    ($pis,$pie)=@tmps;
	#ȡGFF��Ӧ������
	if ($anti) { #ȡ����gff
	  my $as='+' if $s eq '-';
	  $as='-' if $s eq '+';
	  my $tmp=substr($cs,0,length($cs)-1).$as;
	  if ($hashGff{$tmp}) {
	    my @tmps=split(/,/,$hashGff{$tmp});
        ($gis,$gie)=@tmps;
	  }  	  
	} else {
	  if ($hashGff{$cs}) {
		 my @tmps=split(/,/,$hashGff{$cs});
        ($gis,$gie)=@tmps;
	  }
	}#anti
    next if $pis==-1 or $pie==-1 or $gis==-1 or $gie==-1;
	#ȡ��gff��PA
	my ($gff,$PA)=([],[]);
	for my $i($pis..$pie) {
	  push(@$PA,[@{$PAs->[$i]}]);
	}
	for my $i($gis..$gie) {
	  push(@$gff,[@{$gffs->[$i]}]);
	}
	$shortN+=output($gff,$PA);
    $gff=$PA=[];
  }#cs
 %hashGff=%hashPA=();
 $gffs=$PAs=[];
 print "$shortN short maps\n";
}#long

} #sub


##���
sub output {
      my($gff,$PA)=@_;
	  my $n=0;
	  if ($#{$gff}<0 or $#{$PA}<0 ) {
		return (0);
	  }

	  #mode=1,2,3��������,��������ͬһ��while��,�����ж�ʱ��
	  #findOverlaps($qry,$sbj,$qsCol,$qeCol,$ssCol,$seCol,$leftMargin,$rightMargin,$select,$drop,$type,$minOverlap,$outputType):$mtxIdx
	  my $mtxIdx=[];
	  if ($mode==1) {#pos vs. GFF	  
		  $mtxIdx=findOverlaps($PA,$gff,$posFld,$posFld,$sFld,$eFld,0,0,'first',1,'any',1);
          for my $i(0..$#$mtxIdx) {
			 print OO join("\t",@{$PA->[$mtxIdx->[$i][0]]}[@pIdxs])."\t";
			 print OO join("\t",@{$gff->[$mtxIdx->[$i][1]]}[@gIdxs]);
			 print OO "\n";		
          }
	  } elsif($mode==2) { #start/end vs. GFF
		  $mtxIdx=findOverlaps($PA,$gff,$sFld2,$eFld2,$sFld,$eFld,0,0,'first',1,'any',1,1);
          for my $i(0..$#$mtxIdx) {
			 print OO join("\t",@{$PA->[$mtxIdx->[$i][0]]}[@pIdxs])."\t";
			 print OO join("\t",@{$gff->[$mtxIdx->[$i][2]]}[@gIdxs]);
			 #�Ƿ���ȫ������
			 if ($mtxIdx->[$i][1]==$WITHIN) {
				print OO "\t0\n";
			  } else {
				print OO "\t1\n";
			  }	
          }
	  } elsif($mode==3) { #tag/len vs. GFF
	      #���1������end��
          for my $i(0..$#$PA) {
		    push(@{$PA->[$i]},$PA->[$i][$posFld]+$len-1);
          }
          $eFld2=$#{$PA->[0]};
		  $mtxIdx=findOverlaps($PA,$gff,$posFld,$eFld2,$sFld,$eFld,0,0,'first',1,'any',1,1);
          for my $i(0..$#$mtxIdx) {
			 print OO join("\t",@{$PA->[$mtxIdx->[$i][0]]}[@pIdxs])."\t";
			 print OO join("\t",@{$gff->[$mtxIdx->[$i][2]]}[@gIdxs]);
			 #�Ƿ���ȫ������
			 if ($mtxIdx->[$i][1]==$WITHIN) {
				print OO "\t0\n";
			  } else {
				print OO "\t1\n";
			  }	
          } 
	  } 
     $n=$#$mtxIdx+1;
	 $gff=$PA=$mtxIdx=[];
	 return($n);
}

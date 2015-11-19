#!/usr/bin/perl -w
#2010/6/26
#��PAT_mergeSmps�ı���д���,����high confident switch genes/PAs
#���.plֻ�Ƕ�PAT_mergeSmps����Ĵ���,����漰���ϲ�WT��Oxt6��PAC,����ļ�����,���ǵ��ȴ���PAT_mergeSmps.pl

## ������ ##
#mysql> select gene,wt_tpm,seed_tpm,wt_tag_ratio,seed_tag_ratio,pair_num from t_merge_ws_ae120_g24_tpm3_sm2_NT2 where gene_class='C5' limit 10;
#+-----------+--------+----------+--------------+----------------+----------+
#| gene      | wt_tpm | seed_tpm | wt_tag_ratio | seed_tag_ratio | pair_num |
#+-----------+--------+----------+--------------+----------------+----------+
#| AT1G01260 |      0 |       19 |            0 |       0.588235 |        1 |
#| AT1G01260 |      4 |        4 |            1 |       0.117647 |        1 |
#| AT1G01320 |     31 |        4 |     0.302326 |      0.0555556 |        1 |
#| AT1G01320 |      0 |       13 |            0 |       0.194444 |        1 |
#....

#ע��: top2=F,ʵ�ʽ����,������pair_num>>1�����ζ���exon,����mRNA��..
#ȡ�ú�switch-PA��gene����: 
#select gene_type,count(distinct(gene)) gene_num,floor(count(*)/2) pairPA_num from test group by gene_type;
#+---------------------+----------+------------+
#| gene_type           | gene_num | pairPA_num |
#+---------------------+----------+------------+
#| protein_coding_gene |       62 |         76 |
#| pseudogene          |        1 |          1 |
#| rRNA                |        9 |        151 | ==> ʵ�ʶ����ӵ�switch-PA������rRNA��..
#| tRNA                |        1 |          2 |
#+---------------------+----------+------------+
 
#2010/7/5
#���� p:s ѡ��,����fishers. ���ͬʱ��� C4��C5�Ľ��,C5����C4.

#2010/7/6
#�� abs($scols[0]-$scols[1]) desc ����ȡ�� ($scols[0]+$scols[1]) desc
#����gene�ж��PA,��ɸѡ2��PAʱ,��wt��oxt�������2��.
#�����ԭ�����. ��31��/29��,�����ظ�28��...

#2010/7/6
#����ѡ�� -top2 T(default)/F. When T, then only used top2 PA.

#2010/7/8
#���Ӷ�>2 samples��֧��
#			s1	s2	s3
#PA1	i1	WT	OXT	XX
#PA2	i2	WT	OXT	XX

#1.1	Pre-filter
#1) Genes with exact two PA
#2) At least one PA>=5 (N1)
#1.2	Filter switching
#1)	At least one stage has PA1>PA2, and one other stage PA2>PA1
#2)	At least PA1 or PA2 in all stage >=20 (N2)
#1.3	High confident
#1)	At least one stage PA1/PA2>=2, and one other stage PA2/PA1>=2 (NF)
#2)	In the two stages of 1), |PA1-PA2|>=5 (N3)
#3) Fishers exact test: use the most diff two stages to do test.

### ����switch�Ƕ�ͬһ����������PAC�ıȽϣ�����ͬһPAC�����������µıȽϡ�
### ����
#     G30	GM
#PAC1 208	0
#PAC2 34	10
#����һ��switch����Ϊ��G30��PAC1>PAC2������GM�У�PAC2>PAC1
#����G30��PAC1�õö࣬����GM��PAC2�õöࡣ��Ȼʵ��PAC1��PAC2������G30�ж���GM��



#2010/7/9
#����dist:iѡ��,����switch-PA�����̾���. ��PA����<dist,���ж�switch

#2010/7/27
#��ѡ���������,���û������� -conds ѡ��,�����������Ӳ����򸲸�-cond�е�.
#-conds sm1 ==> -top2 T -dist 50 -N1 6 -N2 24 -N3 6 -NF 4 -p 0.01
#_conds sm2 ==> -top2 T -dist 50 -N1 3 -N2 12 -N3 3 -NF 2 -p 0.05

#2010/9/1
#���� -conds sm0 ==> -top2 T -dist 50 -N1 12 -N2 48 -N3 12 -NF 8 -p 0.01

#2011/7/2
#���°��PAC�����ԭ����merge��,ֻ���C5�Ľ��
#�����ϲ��ø�,ֻҪ���˵�intergenic��,�͸�������

#2012-12-17
#������ g1+g2�����ķ�ʽ����smpcols��
#���� sqlcondѡ��������intbl��
#������������� smpcol�У����� g1_v1�������ԭ���еĻ����� g1_g2��ʾ��g1+g2֮���smpcol��

#2015/3/26
#ȥ��fisher��Ҫ��

#2015/5/5
#����lbls�������smpcols���������������� (dry1+dry2)/2 ��ԭ���Ļ�ѷ������ַ��滻��_����ܳ�
#����ofile ����������ļ����Ǳ���
#��gene_class��ԭ��������C5���ĳ� ftr-ftr����3UTR-3UTR��ʾ��һ��pair��������3UTR��, �� 3UTR-intron

#use lib '/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi/';
use lib '/var/www/front/src/perl';
#use lib '/home/zym/soft/cpan/lib/perl5/';

use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;
use File::Basename;
#use Text::NSP::Measures::2D::Fisher::twotailed;

require ('funclib.pl');

##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","intbl=s","smpcols=s","otbl:s","ofile:s","lbls:s","conds:s","N1:i","N2:i","N3:i","NF:s","p:s","top2:s","dist:i","sqlcond:s","conf=s");
my $USAGE=<< "_USAGE_";
Usage: 
  1) CMP_switchGene.pl -intbl t_pat2pm_s1_pac -conds sm1 -smpcols "s1_WT:s1_Oxt" -otbl t_pat2pm_s1_pac_sg -conf dbconf_arabpat22.xml
  2) CMP_switchGene.pl -intbl t_pat2pm_s1_pac -conds sm1 -dist -1 -smpcols "wt:oxt6" -otbl t_pat2pm_s1_pac_sg -conf dbconf_arabpat2.xml
  3) CMP_switchGene.pl -intbl t_pat2pm_s1_pac -conds sm2 -top2 F -smpcols "wt:oxt6" -otbl t_pat2pm_s1_pac_sg -conf dbconf_arabpat2.xml
  4) CMP_switchGene.pl -intbl t_pat2pm_s1_pac -conds sm2 -NF 4 -smpcols "wt:oxt6:xx" -otbl t_pat2pm_s1_pac_sg -conf dbconf_arabpat2.xml
  
  5) ֻѡ��3UTR�ģ�����g1+g2:gm1+gm2�ķ�ʽ
  CMP_switchGene.pl -intbl t_4_pac_fnls_gmg -conds sm2 -NF 4 -smpcols "g1+g2:gm1+gm2" -sqlcond "ftr='3UTR'" -otbl t_4_pac_fnls_gmg_switch -conf dbconf_arabmanroot.xml

  6) �Զ�������
  CMP_switchGene.pl -intbl t_4_pac_fnls_gmg -conds sm2 -NF 4 -smpcols "(g1+g2)/2:(gm1+gm2+gm3)/3" -lbls g:gm -sqlcond "ftr='3UTR'" -otbl t_4_pac_fnls_gmg_switch -conf dbconf_arabmanroot.xml

  7) ������ļ� -ofile

-h=help
-intbl=table from PAT_mergeSmps.pl <HAS: gene,coord,smp1,smp2,..smpN>
-sqlcond=
-smpcols=smpcols in intbl, like 'wt:oxt6' or "wt:oxt6:xx" or "g1+g2:gm1+gm2"
-lbls='' or dryseed:embryo same length as smpcols
-conds=sm0, sm1 or sm2 
 **sm0 ==> -top2 T -dist 50 -N1 12 -N2 48 -N3 12 -NF 8 -p 0.01
 **sm1 ==> -top2 T -dist 50 -N1 6 -N2 24 -N3 6 -NF 4 -p 0.01
 **sm2 ==> -top2 T -dist 50 -N1 3 -N2 12 -N3 3 -NF 2 -p 0.05
-N1=at least one PA>=N1; 
-N2=At least PA1 or PA2 in all stage >=N2;
-N3=In the two stages, |PA1-PA2|>=N3; 
-NF=At least one stage PA1/PA2>=NF, and one other stage switch; #C4
-p=fishers exact test. #C5 
-top2=T/F. When T, then only used top2 PA (wt-oxt��������ǰ2��PA)
-dist=min distance between two switch PA. (PA1-PA2>=dist) if -1, then discard this option.
-otbl=output stat table <intbl>+<smpcols>+<gene_class=C5>+<pair_num=1..N> (pair_num within gene)
-ofile=output file
-conf=db conf
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'intbl'}||!$opts{'conf'}||!$opts{'smpcols'};

my $intbl=$opts{'intbl'};
my $otbl=$opts{'otbl'};
my $ofile=$opts{'ofile'};
die "otbl or ofile at least one" if !$otbl and !$ofile;
my $sqlcond=$opts{'sqlcond'};
my $smpcols=$opts{'smpcols'};
my @scols=split(/:/,$smpcols);
die "smpcols must>=2!" if $#scols<1;
my @lbls=();
if (defined($opts{'lbls'})) {
  @lbls=split(/:/,$opts{'lbls'});
}
die "error lbls" if defined($opts{'lbls'}) and $#lbls!=$#scols;

my $conds=$opts{'conds'};
die "-conds must be sm0~2!" if ($conds and $conds ne 'sm0' and $conds ne 'sm1' and $conds ne 'sm2');
#-conds sm1 ==> -top2 T -dist 50 -N1 6 -N2 24 -N3 6 -NF 4 -p 0.01
#_conds sm2 ==> -top2 T -dist 50 -N1 3 -N2 12 -N3 3 -NF 2 -p 0.05
my ($N1,$N2,$N3,$NF,$pvalue,$top2,$dist);
if ($conds and $conds eq 'sm1') {
  $N1=6; $N2=24; $N3=6; $NF=4; $pvalue=0.01; $top2=1;$dist=50;
} elsif ($conds and $conds eq 'sm2')  {
  $N1=3; $N2=12; $N3=3; $NF=2; $pvalue=0.05; $top2=1;$dist=50;
} elsif ($conds and $conds eq 'sm0')  {
  $N1=12; $N2=48; $N3=12; $NF=8; $pvalue=0.01; $top2=1;$dist=50;
}
$N1=$opts{'N1'} if ($opts{'N1'}>0);
$N2=$opts{'N2'} if ($opts{'N2'}>0);
$N3=$opts{'N3'} if ($opts{'N3'}>0);
$NF=$opts{'NF'} if ($opts{'NF'}>0);
$pvalue=$opts{'p'} if ($opts{'p'}>0);
$top2=0 if ($opts{'top2'} and $opts{'top2'} eq 'F');
$top2=1 if ($opts{'top2'} and $opts{'top2'} ne 'F');
$dist=-1 if ($opts{'dist'} and $opts{'dist'}<=0);
$dist=$opts{'dist'} if ($opts{'dist'}>0);

print "-conds $conds -N1 $N1 -N2 $N2 -N3 $N3 -NF $NF -p $pvalue -top2 $top2 -dist $dist -smpcols $smpcols\n";
die "Error options!" if ($N1<=0 or $N2<=0 or $N3<=0 or $NF<=0 or ($dist!=-1 and $dist<=0) or ($top2!=0 and $top2!=1));

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my($dbh)=connectDB($opts{'conf'},1);

#############################################################################
#  Stat PA                                                 
#############################################################################
my ($sth,$rv,$tbl);
#��ȡ����,��oxt-wt����
my $sql="select *,".join(",",@scols)." from $intbl where ftr not like \'intergenic%\' order by gene,abs(($scols[0])-($scols[1])) desc"; #���˵�intergenic��
if ($sqlcond) {
  $sql="select *,".join(",",@scols)." from $intbl where ftr not like \'intergenic%\' and ($sqlcond) order by gene,abs(($scols[0])-($scols[1])) desc";
}
($tbl,$rv)=execSql($dbh,$sql);
print "$rv\tPAC# in $intbl.\n";

my($posFld,$gFld,$ftrFld,$all)=getFldsIdx($dbh,$intbl,('coord','gene','ftr'));
die "coord,gene,ftr not in $intbl!" if $all==-1;
#my(@sFlds)=getFldsIdx($dbh,$intbl,@scols);
#pop(@sFlds) if ($#sFlds>$#scols);
#for my $i(0..$#sFlds) {
#  die "$scols[$i] not in $intbl!" if $sFlds[$i]==-1;
#}
my(@sFlds); #smp�ֶ��ڱ������
my $nfld=scalar(getTblFlds($dbh,$intbl));
for my $i(0..$#scols) {
  push(@sFlds,$nfld+$i);
}

my($cnt1,$cnt2,$cnt3,$cnt4,$cc,$cnt5,$OO,$cnt0);
($cnt1,$cnt2,$cnt3,$cnt4,$cnt5,$cnt0)=(0,0,0,0,0,0);

my ($idxs,$startIdx,$endIdx);
$idxs=getIntervals($tbl,$gFld);
for my $i(0..$#$idxs) {
  $startIdx=$idxs->[$i][0];
  $endIdx=$idxs->[$i][1];
  doThisFragment($startIdx,$endIdx);
}	

my($twocnt)=$cc-$cnt1;
print("$cc\tTotal gene#\n$cnt1\t1PAC gene#\n$twocnt\t>=2PAC gene#\n<<-----2PAC-pair#----->>\n$cnt0\tdist>=$dist\n$cnt2\ttag>=$N1\n$cnt3\tsum_tag>=$N2 & switch\n$cnt4\t[C4] diff>=$N3 & Fold>=$NF\n$cnt5\t[C5] pvalue<$pvalue\n");

for my $i(0..$#scols) {
  my $org=$scols[$i];
  if ($#lbls!=-1) {
	$scols[$i]=$lbls[$i];
  } else {
    $scols[$i]=~s/[^0-9a-zA-Z_]/_/g; #�ѷ���ĸ�»��ߵĸ�Ϊ�»���
    if ($scols[$i] eq $org) {
	  $scols[$i].='_V1';
    }  
 }
}

my($opath)=getTmpPath(1);
if (!$ofile) {
  $ofile=$opath."switchGene.tmp";
}
saveMtx2File($OO,$ofile);
if ($ofile ne "$opath.switchGene.tmp") { #������ļ�
  my $header=join("\t",(getTblFlds($dbh,$intbl),@scols,'gene_class','pair_num'));
  insertLine2File($ofile,$header,1);
}

if ($otbl) {
	$dbh->do("drop table if exists $otbl")or die; 
	$dbh->do("create table $otbl select * from $intbl where 1<>1")or die; 
	#���smp�ֶ���
	for my $i(0..$#scols) {
	  $dbh->do("alter table $otbl add column $scols[$i] float");
	}
	$dbh->do("alter table $otbl add column gene_class varchar(50),add column pair_num int")or die; 
    if ($ofile ne "$opath.switchGene.tmp") { 
	  $rv=loadFile2Tbl($dbh,$otbl,$ofile,1);
	} else {
	  $rv=loadFile2Tbl($dbh,$otbl,$ofile,0);
	}
	print "$rv C5-PAC into $otbl!\n";
	#unlink($ofile) if (-e $ofile);
	$dbh->do("create index idx_gene on $otbl(gene)")or die; 
	$dbh->disconnect();
}
if ($ofile eq "$opath.switchGene.tmp") { 
  unlink $ofile;
}

#############################################################################
#  doThisFragment($startIdx,$endIdx);
#  use global vars:  
#############################################################################
sub doThisFragment {
	my ($si,$ei)=@_;
	my $pair_num=1;
	$cc++;
    if ($ei==$si) { #����PA
      $cnt1++;
	  return;
    }
    #ÿ��sample������PA��tag����
	my (@totals);
	for my $i($si..$ei) {
	  for my $j(0..$#sFlds) {
		$totals[$j]+=$tbl->[$i][$sFlds[$j]];
	  }	  
	}

    my $class='';
	if ($top2) { #ֻȡǰ2��PA
	  $ei=$si+1; #ֻȡͷ2��,ǰ���Ѿ��������
	  $class=isSwitch($si,$ei,@totals);
	  if ($class eq 'C5') {
		my @tmp=($tbl->[$si][$ftrFld],$tbl->[$ei][$ftrFld]);
		@tmp=sort {$a cmp $b} @tmp;
		$class=join('-',@tmp);
		push(@$OO,[@{$tbl->[$si]},$class,$pair_num]);
		push(@$OO,[@{$tbl->[$ei]},$class,$pair_num]);
		return;
	  }
	} else { #����PA
	  for my $i($si..($ei-1)) {
		for my $j(($i+1)..$ei) {
          $class=isSwitch($i,$j,@totals);
		  if ($class eq 'C5') { #ֻ����C5�İ�
			my @tmp=($tbl->[$i][$ftrFld],$tbl->[$j][$ftrFld]);
			@tmp=sort {$a cmp $b} @tmp;
			$class=join('-',@tmp);
			push(@$OO,[@{$tbl->[$i]},$class,$pair_num]);
			push(@$OO,[@{$tbl->[$j]},$class,$pair_num]);
			$pair_num++;
		  }
		}
	  }
	}
}  

#############################################################################
#  $class=isSwitch($i1,$i2);
#  use global vars: �ж�i1,i2��Ӧ�е�PA�Ƿ�Ϊswitch,����class=C4/C5,�����Ƿ�''.
#############################################################################
#1.1	Pre-filter
#1) Genes with exact two PA
#2) At least one PA>=5 (N1)
#1.2	Filter switching
#1)	At least one stage has PA1>PA2, and one other stage PA2>PA1
#2)	At least PA1 or PA2 in all stage >=20 (N2)
#1.3	High confident
#1)	At least one stage PA1/PA2>=2, and one other stage PA2/PA1>=2 (NF)
#2)	In the two stages of 1), |PA1-PA2|>=5 (N3)

#			s1	s2	s3
#PA1	i1	WT	OXT	XX
#PA2	i2	WT	OXT	XX

sub isSwitch {
   my ($i1,$i2,@ts)=@_;

   #��������dist
   return('') if ($dist>0 and abs($tbl->[$i1][$posFld]-$tbl->[$i2][$posFld])<$dist);
   $cnt0++;

   #At least one PA>=N1
   my ($have)=0;
	for my $i ($i1..$i2) {
	  for my $j(0..$#ts) {
	   if ($tbl->[$i][$sFlds[$j]]>=$N1) {
		$have=1;
		last;
	   }
	  }
	}
    return('') if $have==0;
	$cnt2++; 

   #At least one stage has PA1>PA2, and one other stage PA2>PA1   
   $have=0;
   for my $i(0..($#ts-1)) {
	   for my $j(1..$#ts) {
         if (($tbl->[$i1][$sFlds[$i]]-$tbl->[$i2][$sFlds[$i]])*($tbl->[$i1][$sFlds[$j]]-$tbl->[$i2][$sFlds[$j]])<0) {
		   $have=1;
		   last;
	   }
	 }
	 last if $have;
   }
   return('') if $have==0;

  #At least PA1 or PA2 in all stage >=20 (N2)
  my ($tot1,$tot2)=0;
  $have=0;
  for my $i(0..$#ts) {
    $tot1+=$tbl->[$i1][$sFlds[$i]];
	$tot2+=$tbl->[$i2][$sFlds[$i]];
  }
  if ($tot1>=$N2 or $tot2>=$N2) {
	$have=1;
  }
  return('') if $have==0;
  $cnt3++; 

 #1)	At least one stage PA1/PA2>=2, and one other stage PA2/PA1>=2 (NF)
 #2)	In the two stages of 1), |PA1-PA2|>=5 (N3)
   my($s1Fld,$s2Fld,$t1,$t2); #��¼�б仯�Ҳ������ͷ2��sample(��ֻȡͷ2��û��-.-),����fisher test
   my($abs1,$abs2)=(0,0);
   my($m1,$m2)=(0,0);
   $have=0;   
   for my $i(0..$#ts) {	 
	 $abs1=abs($tbl->[$i1][$sFlds[$i]]-$tbl->[$i2][$sFlds[$i]]);
	# print "i1-i2($i)"."***".$tbl->[$i1][$sFlds[$i]]."\t".$tbl->[$i2][$sFlds[$i]]."\n";
	 if (($tbl->[$i1][$sFlds[$i]]+1)/($tbl->[$i2][$sFlds[$i]]+1)>=$NF and $abs1>=$N3) {   
	   for my $j(0..$#ts) {
		 $abs2=abs($tbl->[$i1][$sFlds[$j]]-$tbl->[$i2][$sFlds[$j]]);
         if (($tbl->[$i2][$sFlds[$j]]+1)/($tbl->[$i1][$sFlds[$j]]+1)>=$NF and $abs2>=$N3) {
		  # print "i2-i1($j)"."***".$tbl->[$i2][$sFlds[$j]]."\t".$tbl->[$i1][$sFlds[$j]]."\n";
		   if ($abs2>$m2) {
			 $s2Fld=$sFlds[$j];
			 $t2=$ts[$j];
			 $m2=$abs2;
		   }
		   if ($abs1>$m1) {
			 $s1Fld=$sFlds[$i];
			 $t1=$ts[$i];
			 $m1=$abs1;
		   }	
		   $have=1;
		 }
	   }
	 }
   }
   return('') if $have==0;
   $cnt4++; #high conf switch gene
   my $class='C5';

return($class);

#��PA1��PA2�ֱ���һ��fisher test,��������<pvalue,�����C5
#-------------------
#      InPA  notInPA
#WT  | WT1   WT2
#Oxt | Oxt1  Oxt2
#-------------------

	#pvalue
	my($n11,$n1p,$np1,$npp,$p);
	$n11=$tbl->[$i1][$s1Fld];
	$n1p=$t1;
	$np1=$tbl->[$i1][$s1Fld]+$tbl->[$i1][$s2Fld];
	$npp=$t1+$t2;	  
	$p=calculateStatistic( n11=>$n11,n1p=>$n1p,np1=>$np1,npp=>$npp);
	if ($p<$pvalue) {
	  $n11=$tbl->[$i2][$s1Fld];
	  $np1=$tbl->[$i2][$s1Fld]+$tbl->[$i2][$s2Fld];
	  $p=calculateStatistic( n11=>$n11,n1p=>$n1p,np1=>$np1,npp=>$npp);
	}
	if ($p<$pvalue) {
	  $class='C5';
	  $cnt5++;
	}  
	return($class);
}

#--------------------------------------
#          word2   ~word2
#  word1    *n11      n12 | *n1p
# ~word1     n21      n22 |  n2p
#           --------------
#           *np1      np2   *npp
#$pvalue=calculateStatistic( n11=>$n11,n1p=>$n1p,np1=>$np1,npp=>$npp);


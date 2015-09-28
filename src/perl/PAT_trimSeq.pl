#!/usr/bin/perl -w
#��polyA_site_in_genome��strand��ȡλ������
#�����: test �� test chromosome
#����ļ�: intron_tagsnum2_400nts �� _C_intron_400nt
##���б���:�ֶ���[�ֶ�ֵ];
##1) ����Ҫȡ��uPA������:
##   1)by=grp_ftr,���ȡ4��grp_ftr, ��������utag,grp_ftr
##   2)by=class, ���ȡ4��class,��������class,grp_ftr
##   ����ÿ��chr
##2) ��strand=+ ȡ����300,1,����99, 
##	 ��strand=- ȡ����99,1,����300,�ٷ�ת����
##3) ���ļ��Ѵ�����׷��.

#(���λ���ڵ�301)

#  �޸���ʷ
#  20091128: �ļ���������_xxs��ʾ������,��test_painfos_20_grp_three_prime_UTR_tagsnum2_400nt_154s
#  2009/12/28: ����by all��ѡ��,���Բ���grp_ftr��class���ȫ������

#2010/6/4
#����-from/-toѡ��,ʹ���Խ�ȡ���ⷶΧ. �� 1~100��ȡPA����1~100nt,����PA. -100~-1,��ȡ����. Ĭ��-300~99,��ȡ����300,����99,�ٰ���PA

#2011/3/24
#�����ģ���byfld,byval,condȡ��ԭ����ѡ��
#��������ֶ�����������-byfld grp_ftr:class:strand
#�޸�������е��ļ��������⣬ֻ��>chr,strand,polyA_pos��ʾ,�� >Chr1;-;12453928

## ԭpl����
#0) PAT_trimseq_org.pl -tbl test -by grp_ftr  -conf dbconf_arabpaper.xml
#1) PAT_trimseq_org.pl -tbl test -by grp_ftr -ftr 3UTR -n 2  -conf dbconf_arabpaper.xml
#2) PAT_trimseq_org.pl -tbl test -by class  -conf dbconf_arabpaper.xml
#3) PAT_trimseq_org.pl -tbl test -by class_ftr -class M -ftr 3UTR  -conf dbconf_arabpaper.xml
#4) PAT_trimseq_org.pl -tbl test -by class_ftr -class M  -conf dbconf_arabpaper.xml
#5) PAT_trimseq_org.pl -tbl test -by class_ftr -ftr 3UTR  -conf dbconf_arabpaper.xml
#6) PAT_trimseq_org.pl -tbl test -by class_ftr -ftr intergenic  -conf dbconf_arabpaper.xml
#7) PAT_trimseq_org.pl -tbl test -by  all -conf dbconf_arabpaper.xml
#8) PAT_trimseq_org.pl -tbl test -by class -class M -from 1 -to 100 -conf dbconf_arabpaper.xml

## ��Ӧ����pl����
#0) PAT_trimseq.pl -tbl test -byfld grp_ftr -conf dbconf_arabpaper.xml
#1) PAT_trimseq.pl -tbl test -byfld grp_ftr -byval 3UTR -cond "utag_num>=2" -conf dbconf_arabpaper.xml
#2) PAT_trimseq.pl -tbl test -byfld class -conf dbconf_arabpaper.xml
#3) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval 3UTR:M -conf dbconf_arabpaper.xml
#4) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval :M -conf dbconf_arabpaper.xml
#5) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval 3UTR: -conf dbconf_arabpaper.xml
#6) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval intergenic: -conf dbconf_arabpaper.xml
#7) PAT_trimseq.pl -tbl test -conf dbconf_arabpaper.xml
#8) PAT_trimseq.pl -tbl test -byfld class -byval M -from 1 -to 100 -conf dbconf_arabpaper.xml

#2011/5/10
#����flds:chr:strand:coordѡ��

#2012-08-28
#����sufѡ�������һ����׺��ʶ

#2012-09-11 Mtr�޸� -- ��Ҫlongchr

#2013-12-22
#����opathѡ�Ĭ��ΪgetTmpPath)

#use lib '/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi/';
use lib '/var/www/front/src/perl';
#use lib '/home/zym/soft/cpan/lib/perl5/';

require ('funclib.pl');

use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;



##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","tbl=s","flds:s","byfld:s","byval:s","cond:s","from:i","to:i","suf:s","opath:s","conf=s");
my $USAGE=<< "_USAGE_";
Require: <chromosome(title,seq)>
Purpose: trim 400nt seqs by grp_ftr(dist0) or class(WCSM)
Usage: 0) PAT_trimseq.pl -tbl test -byfld grp_ftr -conf dbconf_arabpaper.xml                                  
	   1) PAT_trimseq.pl -tbl test -byfld grp_ftr -byval 3UTR -cond "utag_num>=2" -conf dbconf_arabpaper.xml  
       2) PAT_trimseq.pl -tbl test -byfld class -conf dbconf_arabpaper.xml                                    
	   3) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval 3UTR:M -conf dbconf_arabpaper.xml              
       4) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval :M -conf dbconf_arabpaper.xml                  
       5) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval 3UTR: -conf dbconf_arabpaper.xml               
	   6) PAT_trimseq.pl -tbl test -byfld grp_ftr:class -byval intergenic: -conf dbconf_arabpaper.xml         
	   7) PAT_trimseq.pl -tbl test -conf dbconf_arabpaper.xml                                                 
	   8) PAT_trimseq.pl -tbl test -byfld class -byval M -from 1 -to 100 -conf dbconf_arabpaper.xml  
       #multiple flds
	   9) PAT_trimseq.pl -tbl test -byfld grp_ftr:class:strand -conf dbconf_arabpaper.xml 
	   10) PAT_trimseq.pl -tbl test -byfld grp_ftr:class:strand -byval 3UTR:: -conf dbconf_arabpaper.xml 
       #3UTR:: same as 3UTR here
	   11) PAT_trimseq.pl -tbl test -byfld grp_ftr:class:strand -byval 3UTR -conf dbconf_arabpaper.xml 
       #user define flds
       PAT_trimseq.pl -tbl test -flds chr:strand:polyA_site_in_genome -byfld grp_ftr:class:strand -byval 3UTR -conf dbconf_arabpaper.xml 

       12) suffix
	   PAT_trimseq.pl -tbl t_4_pac_fnls -byfld ftr -cond "wt1+wt2>0 and tot_tagnum-wt1-wt2=0 and ftr in ('intron','3UTR','CDS')" -suf WT -conf dbconf_arabmanroot.xml 
	   PAT_trimseq.pl -tbl t_4_pac_fnls -byfld ftr -cond "oxt1+oxt2>0 and tot_tagnum-oxt1-oxt2=0 and ftr in ('intron','3UTR','CDS')" -suf OXT  -conf dbconf_arabmanroot.xml 

-h=help
-tbl=input table HAS columns [chr,strand,polyA_site_in_genome or flds]+[byflds,conds]
-flds=chr:strand:coord for tbl
-byfld=field to group, if '' then not group, canbe multiple flds, like grp_ftr:class
-byval=value for byfld, correspond to byfld, like [intron:C, or intron:, or :C] "intron:" for all class with grp_ftr=intron 
-cond=extra conditions for SQL, like (utag_num>=2 and strand='+')
-from/-to=default(-300,99). position of PA is 0, -300~-1+PA+1~99=400nt. if -100~-1, then only upstream of PA.
-suf=''
-opath=outputpath like 'c:/' default is getTmpPath
-conf=db
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'tbl'}||!$opts{'conf'};

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my $conf=$opts{'conf'};
my($dbh,$chrtbl,$longchr)=connectDB($conf,1,('chromosome','longchr'));
die "chromosome not in xml" if !$chrtbl;

my $intbl=$opts{'tbl'};
my $fld=$opts{'flds'};
my $byfld=$opts{'byfld'};
my $cond=$opts{'cond'};
my $suf=$opts{'suf'};
my $opath=$opts{'opath'};
$opath=getTmpPath(1) if !$opath;

if (!$fld) {
  $fld='chr:strand:coord';
}
my @flds=split(/:/,$fld);
die "flds=$fld must 3 flds" if @flds!=3;
my ($chrFld,$strandFld,$posFld,$all)=getFldsIdx($dbh,$intbl,@flds);
die "No $fld in $intbl; idx=$posFld,$chrFld,$strandFld" if ($all==-1);
my ($chrfld,$strandfld,$posfld)=@flds;

my (@byflds);
if ($byfld) {
  @byflds=split(/:/,$byfld);
}
my $byval=$opts{'byval'};
my (@byvals);
if ($byval) {
  @byvals=split(/:/,$byval);
  if ($#byvals<$#byflds) { #�� -byvals 3utr::�����Զ���������յ�
    for my $i(1..($#byflds-$#byvals)) {
	  push(@byvals,'');
    }    
  }
  die "byval not same number as byfld" if $#byvals!=$#byflds;
}

my @byFlds;
if ($#byflds>=0) {
  (@byFlds)=getFldsIdx($dbh,$intbl,(@byflds));
  if ($byFlds[$#byFlds]==-1) {
    die "No @byflds in $intbl";
  } else {
	pop(@byFlds) if $#byFlds>0;
  }
}

my $from=$opts{'from'};
my $to=$opts{'to'};
if ($from ne '0' and !$from) {
  $from=-300;
}
if ($to ne '0' and !$to) {
  $to=99;
}
die "Error from/to!" if $from>$to;
my($nt)=$to-$from+1;

print "from $from to $to; total ${nt}nt\ncond: $cond\n";

#############################################################################
#  Trim Seq                                                  
#############################################################################
my($sth,$rv);

#�ֶ���,�������>title
#$sth = $dbh->prepare("desc $intbl") or die $dbh->errstr;
#$sth->execute or die "can't execute the query: $sth->errstr\n";
#my($flds)= $sth->fetchall_arrayref();

#ȷ������ļ��ź�:filesInfo[suffix,seqnum]
my ($suffix);
my $fldEnum; #��ά����ÿ�д��һ��fld��ö��ֵ
my $nf=1; #����ļ�����
if ($#byflds>=0) {
  for my $i(0..$#byflds) {
   if ($byvals[$i] eq '') { 
    my (@v)=getFldValues($dbh,"SELECT distinct($byflds[$i]) FROM $intbl",0);
	push(@{$fldEnum},[@v]);
   } else {
	push(@{$fldEnum},[$byvals[$i]]);
   }
   $nf*=scalar(@{$fldEnum->[$i]});
  } #for
}
die "Output file#>50!" if $nf>50; 

#for my $i(0..$#$fldEnum) {
#  print "fldEnum:@{$fldEnum->[$i]}\n";
#}

my $filesInfo; #����ļ�����SQL��������������
if ($#byflds<0) {
    if ($cond ne '') {
      $suffix=".SQL.${nt}nt";
	  $suffix.=".$suf" if $suf;
	  push(@$filesInfo,[$suffix,"and $cond",0]);
	}else{
      $suffix=".${nt}nt";
	  $suffix.=".$suf" if $suf;
	  push(@$filesInfo,[$suffix,"",0]);
	}	
} else { 
  my $condtxt;
  my $permute=getPermute($fldEnum); #�õ��� ([3UTR,C],[3UTR,M]..)
  for my $i(0..$#$permute) {
	 $condtxt="and (";
     if ($cond ne '') {
       $suffix='.'.join('.',@{$permute->[$i]}).".SQL.${nt}nt";
	   $suffix.=".$suf" if $suf;
	 }else{
       $suffix='.'.join('.',@{$permute->[$i]}).".${nt}nt";
	   $suffix.=".$suf" if $suf;
	 }	
	 for my $j(0..$#{$permute->[$i]}) {
	   $condtxt.="$byflds[$j]=\'$permute->[$i][$j]\' and ";
	 }	 
	 $condtxt=substr($condtxt,0,length($condtxt)-length(" and"));
	 $condtxt.=" and $cond)" if $cond ne '';
	 $condtxt.=")" if $cond eq '';
	 push(@$filesInfo,[$suffix,$condtxt,0]);	
  } #i
}

#for my $i(0..$#$filesInfo) {
#  print "$filesInfo->[$i][0],$filesInfo->[$i][1],$filesInfo->[$i][2]\n";
#}

my ($curFileIdx,$chrfas)=(0,'');

#��ÿchr ����polyA_site_in_genome,trim400nt
#����longchr
my (@chrs,$sqlchr);
if ($longchr) {
  $sqlchr=dotStr2sqlStr($longchr);
  my $wheretmp='';
  if ($cond) {
    $wheretmp=" and ($cond) "
  }
  @chrs=getFldValues($dbh,"SELECT distinct($chrfld) FROM $intbl where $chrfld in ($sqlchr) $wheretmp order by $chrfld",0);
} else {
  my $wheretmp='';
  if ($cond) {
    $wheretmp=" where ($cond) "
  }
  @chrs=getFldValues($dbh,"SELECT distinct($chrfld) FROM $intbl $wheretmp order by $chrfld",0);
}
foreach my $chr(@chrs) {
    #��ȡȾɫ��seq
	print "$chr: ";
	my $sql="select seq from $chrtbl where title=\'$chr\'";
	my ($fastbl,$rv)=execSql($dbh,$sql);
	#print "$sql; $fastbl->[0][1]\n";
    next if $#{$fastbl}<0;
	$chrfas=$fastbl->[0][0];
	$fastbl=[];
	$curFileIdx=0;
	#��ȡ����������uPA
	for my $i (0..$#$filesInfo) {
	  $curFileIdx=$i;
      $sql="select * from $intbl where $chrfld=\'$chr\' $filesInfo->[$i][1]";
	  #print "\n$sql\n";
	  my ($tbl,$rv)=execSql($dbh,$sql);
	  $rv=trimAll($tbl); #��������������ȡ����
	  $tbl=[];
	  print "$rv+";
	}
	print "\n";
} #foreach

#����ж�chr
$curFileIdx=0;
if ($longchr) { #Mtr
    my $sql="select seq,title from $chrtbl where title not in ($sqlchr) order by title";
	my ($fastbls,$rv1)=execSql($dbh,$sql);
	print "********************\n";
	print "For short chrs\n";
	my %hashfas=();
	for my $i(0..$#$fastbls) {
	  $hashfas{$fastbls->[$i][1]}=$fastbls->[$i][0];
	}
    $fastbls=[];
   
	for my $i (0..$#$filesInfo) {
	  my $num=0;
	  $curFileIdx=$i;
      $sql="select * from $intbl where $chrfld not in ($sqlchr) $filesInfo->[$i][1] order by $chrfld,$strandfld,$posfld";
	  my ($tbl,$rv)=execSql($dbh,$sql);    
	  next if $rv<=0;
	  #��ÿ��chr����
	  print "  $filesInfo->[$i][1]: ";
	  my $idxsChr=getIntervals($tbl,$chrFld,1);
	  #print "\n".mtx2str($idxsChr)."\n";
	  for my $j(0..$#$idxsChr) {
		$chrfas=$hashfas{$idxsChr->[$j][2]};
        $num+=trimAll($tbl,$idxsChr->[$j][0],$idxsChr->[$j][1]); #��������������ȡ����
	  }
	  print "$num\n";	
	}
} #long

#�޸��ļ���,�������� _xxxs
my ($newf);
print "Output FILES in $opath: \n";
for my $i (0..$#{$filesInfo}) {
  $newf="$intbl$filesInfo->[$i][0]".".$filesInfo->[$i][2]".'s';
  rename("$opath$intbl"."$filesInfo->[$i][0]","$opath$newf");
  print "  $newf\n";
}

$dbh->disconnect();

#############################################################################
#  trimAll(): ��������������ȡ����
#  useage: trimAll();    
#  ˵��: ʹ�ú��޸�ȫ�ֲ��� (chrfas,curFileIdx,num...)
#############################################################################
sub trimAll {
	my ($tbl,$is,$ie)=@_;
	$is=0 if !defined($is) or $is<0;
	$ie=$#$tbl if !defined($ie) or $ie>$#$tbl;
	my $rv=$ie-$is+1;
	return(0) if ($rv<=0 or $chrfas eq '');
	#��¼������
	$filesInfo->[$curFileIdx][2]+=$rv;

	#��ÿ��polyA_site,��ȡ����
	my @seqs=();
    for my $i ( $is .. $ie ) {
      my $p=$tbl->[$i][$posFld];
      my $s=$tbl->[$i][$strandFld];
      
	  #���б�����
	  my $title='>';
	  #for $j ( 0 .. $#{$flds} ) {
      #  $title.=$flds->[$j][0].'['.$tbl->[$i][$j].'];';
     # } 
	   $title.="$tbl->[$i][$chrFld];$tbl->[$i][$strandFld];$tbl->[$i][$posFld]";
      my $start;
	  if ($s eq '+') {
		$start=$p+$from;
		push(@seqs,$title,substr($chrfas,$start-1,$nt));
	  } else {
		$start=$p-$to;
		if (length($chrfas)-$start+1>=$nt) {
		  push(@seqs,$title,reverseAndComplement(substr($chrfas,$start-1,$nt),1,1));
		}
	  }
	} # for $i
    
	#׷��������ļ� 
    my $suffix=$filesInfo->[$curFileIdx][0];
    my $ofile="$opath$intbl"."$suffix";
	open(FH, ">>$ofile");	
	for my $i ( 0 .. $#seqs ) {
      print FH "@seqs[$i]";
	  print FH "\n";	
	} 
	close(FH);  
	@seqs=();
	return($rv); #��������
}


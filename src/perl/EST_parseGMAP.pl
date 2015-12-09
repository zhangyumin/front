#!/usr/bin/perl -w

use strict;
use Getopt::Long;

use lib '/var/www/front/src/perl';
require ('funclib.pl');

#2012-08-05
#����GMAP��-f 3��GFF3��ʽ�Ľ��
#Chr1	tair9	cDNA_match	9150012	9150063	100	-	.	
#Chr1	tair9	cDNA_match	9149643	9149882	100	-	.	
#ID=594:N:0:0|gi|29028819|gb|BT005854|U23487.path1;Name=594:N:0:0|gi|29028819|gb|BT005854|U23487;Target=594:N:0:0|gi|29028819|gb|BT005854|U23487 1 52;Gap=M52                                                                                                                                                                                                                                                                                              
#ID=594:N:0:0|gi|29028819|gb|BT005854|U23487.path1;Name=594:N:0:0|gi|29028819|gb|BT005854|U23487;Target=594:N:0:0|gi|29028819|gb|BT005854|U23487 53 292;Gap=M240                                                                                                                                                                                                                                                                                           
#Chr1	tair9	cDNA_match	25127727	25128310	100	+	.	
#Chr1	tair9	cDNA_match	25128444	25128589	100	+	.	
#ID=1080:N:0:0|gi|29028877|gb|BT005883|U23535.path1;Name=1080:N:0:0|gi|29028877|gb|BT005883|U23535;Target=1080:N:0:0|gi|29028877|gb|BT005883|U23535 1 584;Gap=M584                                                                                                                                                                                                                                                                                                                                                          
#ID=1080:N:0:0|gi|29028877|gb|BT005883|U23535.path1;Name=1080:N:0:0|gi|29028877|gb|BT005883|U23535;Target=1080:N:0:0|gi|29028877|gb|BT005883|U23535 585 730;Gap=M146                                                                                                                                                                                                                                                                                                                                                        
																																																																																																																														   
## �µ�EST�ȶ����̣�
#1. ��ȡpolyA/T��EST���У������ı����У�����ͷ���� ����,A/T/N,tail����,ĩ������300:A:15:2
#��tail��Ҫ����ĩβpolyA��ͷpolyT����������A/T������������״�������ֻ��<=2nt�ķ�A/T��tail�ĳ���ֱ�Ӵ�poly��ʼ����������ĩβ�����п�ͷ�����ܿ�ͷ/ĩβ�Ƿ�Ϊ��AT��
#2. ����ȡ������н���GMAP�����gff3��ʽ
#3. ����gff3��ʽ��PA
#���㣺
#chr��1��λ�� 28500
#chr���1��λ�� 31183
#�ȶԺ�ȫ����31183-28500 ���ǰ���intron��Ƭ�εĳ��ȣ���������poly�ĳ��ȣ�
#cdna��1���ȶ�λ�ã�1 306�е�1
#cdna���1���ȶ�λ�ã�1976 2257�е�2257
#chr,strand
#score=ȫ��score��ƽ��
#
#�ж�
#1. strand=+
#���һ��λ�õ���һλ��ΪpolyA
#1) ���tail=N������ ԭ���г���-cdna�ȶ����1��λ�ã������Ϊ20��
#������ڣ�ֱ�Ӽ�¼polyA��λ�á���
#�������������һ�ļ�������¼��־��Ϣ
#2) ���tail=A������ diffԭ���г���-cdna�ȶ����1��λ��
#���diff<=raw_tail_len����tail_len��raw_tail_len-diff����¼tail=A
#���diff>raw_tail_len���ұȶ�ĩ�˵�raw_tail�ľ���<=������5nt�������Ծɼ�¼tail=A��tail_len=raw_tail_len
#�����ʾraw_tail��ʵ����genome�ϣ���tail=N����Ҫ��diff<=X
#3) ���tail=T
#������������ܣ���tail=N����
#
#2. strand=-
#��1��λ�õ�ǰ1��λ��ΪpolyA
#diff����1��λ��-1
#1) tail=N������diff����1��λ��-1
#���diff<=X����¼polyAλ��
#>X�������
#2) tail=A 
#������������ܣ���tail=N����
#3) tail=T
#���diff<=raw_tail_len����tail_len��raw_tail_len-diff����¼tail=A
#���diff>raw_tail_len��diff-raw_tail_len<=������5nt�������Ծɼ�¼tail=A��tail_len=raw_tail_len
#���򣬰�tail=N����

#���OPA��
#chr	strand	coord	N_or_Tail_diff	ATN	tail_len	seq_len	match_len	score	EST_start	EST_end	chr_start	chr_end	ID
#Chr1	+	25129146	0	N	0	1080	1080	100	1	1080	25127727	25129145	1080:N:0:0|gi|29028877|gb|BT005883|U23535.path1
#Chr1	-	9149002	0	N	0	594	594	100	1	594	9149003	9150063	594:N:0:0|gi|29028819|gb|BT005854|U23487.path1

#���LOG��
#chr	strand	DIFF	Why	ATN	tail_len	seq_len	match_len	score	EST_start	EST_end	chr_start	chr_end
#Chr3	-	27	ATN=N(NoTail),diff=(28-1)>20	N	0	942	915	99	28	942	22801237	22803054
#Chr4	+	34	ATN=N(NoTail),diff=(1799-1765)>20	N	0	1799	1748	100	18	1765	15935429	15937728

#2012-08-09
#����otbl��confѡ�����ֱ����ɱ�

#2015/11/28
#������ļ�����

my %opts=();
GetOptions(\%opts,"h","gmap=s","opa:s","otbl:s","ndiff:i","taildiff:i","conf:s");
my $USAGE=<< "_USAGE_";
Usage: 
1) output file (OPA+LOG)
EST_parseGMAP.pl -ndiff 20 -taildiff 5 -gmap F:/script_out/est/ATH_cDNA_sequences_20101108.fas.gmap3.head20k -opa F:/script_out/est/ATH_cDNA_sequences_20101108.fas.gmap3.head20k.PA 
2) output table (OTBL+LOG)
EST_parseGMAP.pl -ndiff 20 -taildiff 5 -gmap F:/script_out/est/ATH_cDNA_sequences_20101108.fas.gmap3.head20k -otbl t_tair_cdna_pa -conf dbconf_arabmypa.xml
3) output table and file (OPA+OTBL+LOG)
EST_parseGMAP.pl -ndiff 20 -taildiff 5 -gmap F:/script_out/est/ATH_cDNA_sequences_20101108.fas.gmap3.head20k -opa F:/script_out/est/ATH_cDNA_sequences_20101108.fas.gmap3.head20k.PA  -otbl t_tair_cdna_pa -conf dbconf_arabmypa.xml

-h=help
-gmap=GMAP file (-f 3) GFF3 format
-opa=output file (And opa.log)
-ndiff=diff for N-type (noTailA/T); from the GMAP-end to the EST-end
-taildiff=diff for A/T-type; from the GMAP-end to the tail-start
-otbl <chr,strand,coord,N_or_Tail_diff,ATN,tail_len,seq_len,match_len,score,EST_start,EST_end,chr_start,chr_end,ID>
-conf=output to database
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'gmap'};

my $gmap=$opts{'gmap'};
my $opa=$opts{'opa'};
my $otbl=$opts{'otbl'};
my $conf=$opts{'conf'};
die "$gmap donnot exist!" if !(-e $gmap);
die "opa or otbl, at least one!" if !$opa and !$otbl;
die "otbl must with conf" if (($otbl and !$conf) or (!$otbl and $conf));

my $tmp=getTmpPath(1).$otbl.'xxx';
if (!$opa) {
  $opa=$tmp;
}

my $ndiff=$opts{'ndiff'};
$ndiff=20 if $ndiff<=0;
my $taildiff=$opts{'taildiff'};
$taildiff=5 if $taildiff<=0;

open(GMAP,"<$gmap") or die "cannot read $gmap";
open (OPA,">$opa") or die "cannot write $opa";
if ($opa ne $tmp) {
  open (LOG,">${opa}.log") or die "cannot write ${opa}.log";
} else {
  my $logfile=getTmpPath(1).$otbl.'.log';
  open (LOG,">$logfile") or die "cannot write ${opa}.log";
}
print OPA "chr\tstrand\tcoord\tN_or_Tail_diff\tATN\ttail_len\tseq_len\tmatch_len\tscore\tEST_start\tEST_end\tchr_start\tchr_end\tID\n";
print LOG "chr\tstrand\tDIFF\tWhy\tATN\ttail_len\tseq_len\tmatch_len\tscore\tEST_start\tEST_end\tchr_start\tchr_end\tID\n";

print "From:   $gmap\n";
if ($opa ne $tmp) {
  print "ToFile:    $opa\n";
}
if ($otbl) {
  print "ToTable:   $otbl\n";
}
print "ndiff=$ndiff; tailDiff=$taildiff\n";

my ($preline,$preID,$line,$ID,$target);
my ($cntAT,$cntLog,$cntGmap)=(0,0,0);
my (@path);
while($line=<GMAP>) {
  if ($line=~/^#/) {
	next;
  } else {
	$preline=trim($line);
	$preID=getID($preline);
	push(@path,$preline);
	last;
  }
}

while($line=<GMAP>) {
  $line=trim($line);
  $ID=getID($line);
  my @t=getTarget($line);
  if ($ID eq $preID) {
    push(@path,$line);
  } else {
    doPath();
	@path=();
	push(@path,$line);
	$preID=$ID;
  }
}

if ($#path!=-1) {
  doPath();
}

close(OPA);
close(LOG);
close(GMAP);

print "Total_Path=$cntGmap\nATN=$cntAT\nLOG=$cntLog\n";


sub doPath {
  $cntGmap++;
  my($chrstart,$chrend,$newTaillen,$coord,$newATN,$toOutput,$toLog,$diff)=(0,0,0,0,0,0,0,0,0,0,0);
  my $ID=getID($path[0]);  
  my @items1=split(/\t/,$path[0]);
  my ($chr,$score,$strand)=($items1[0],$items1[5],$items1[6]);
  my @itemsLast=split(/\t/,$path[$#path]);

  my ($seqlen,$ATN,$taillen,$rest,$eststart,$estend1)=getTarget($path[0]);
  my ($eststartL,$estend);
  ($seqlen,$ATN,$taillen,$rest,$eststartL,$estend)=getTarget($path[$#path]);

  $score=$items1[5]+$itemsLast[5];
  for my $i(1..($#path-1)) {
    my @items=split(/\t/,$path[$i]);
	$score+=$items[5];
  }
  $score=int($score/scalar(@path));
  $newATN=$ATN;
  $newTaillen=$taillen;
  $toOutput=0;
  $toLog='';
  my $matchlen=$estend-$eststart+1;
  if ($strand eq '+') {
    $chrstart=$items1[3];
    $chrend=$itemsLast[4];
	$diff=abs($seqlen-$estend);
	$coord=$chrend+1;
    if ($ATN eq 'N') {
	  if ($diff<=$ndiff) {
		$toOutput=1;
	  } else {
		$toLog="ATN=N(NoTail),diff=($seqlen-$estend)>$ndiff";
	  }
    } elsif ($ATN eq 'A') {
	  if ($diff<=$taillen) {
		$newTaillen=$taillen-$diff;
		$diff=0;
		$toOutput=1;
	  } elsif ($diff>$taillen and abs($estend-($seqlen-$taillen-$rest))<=$taildiff) {
		$newTaillen=$taillen;
		$diff=abs($estend-($seqlen-$taillen-$rest));
		$toOutput=1;
	  }	elsif ($diff<=$ndiff) {
        $newATN='N';
		$newTaillen=0;
		$toOutput=1;
	  } else {
		$toLog="ATN=A(Tail),NoTailDiff,diff=($seqlen-$estend)>$ndiff";
	  }
	} elsif ($ATN eq 'T') {
       if ($diff<=$ndiff) {
        $newATN='N';
		$newTaillen=0;
		$toOutput=1;
	  } else {
		$toLog="ATN=T(Tail),diff=($seqlen-$estend)>$ndiff";
	  }
	}

  } else { #strand-
    $chrstart=$itemsLast[3];
    $chrend=$items1[4];
	$coord=$chrstart-1;
	$diff=$eststart-1;
    if ($ATN eq 'N') {
	  if ($diff<=$ndiff) {
		$toOutput=1;
	  } else {
		$toLog="ATN=N(NoTail),diff=($eststart-1)>$ndiff";
	  }
    } elsif ($ATN eq 'T') {
	  if ($diff<=$taillen) {
		$newTaillen=$taillen-$diff;
		$diff=0;
		$toOutput=1;
	  } elsif ($diff>$taillen and abs($diff-$taillen-$rest)<=$taildiff) {
		$newTaillen=$taillen;
		$diff=abs($diff-$taillen-$rest);
		$toOutput=1;
	  }	elsif ($diff<=$ndiff) {
        $newATN='N';
		$newTaillen=0;
		$toOutput=1;
	  } else {
		$toLog="ATN=T(Tail),NoTailDiff,diff=($eststart-1)>$ndiff";
	  }
	} elsif ($ATN eq 'A') {
       if ($diff<=$ndiff) {
        $newATN='N';
		$newTaillen=0;
		$toOutput=1;
	  } else {
		$toLog="ATN=A(Tail),diff=($eststart-1)>$ndiff";
	  }
	}
  }

#��������chr,strand,coord��tail=A/T/N��tail_len��raw_tail_len��cdna_len��cdna_match_len��match_score��cdna_match_start/end��chr_match_start/end,$ID
if ($toOutput) {
  $cntAT++;
  print OPA "$chr\t$strand\t$coord\t1\n";
}
if ($toLog ne '') {
  $cntLog++;
  print LOG "$chr\t$strand\t$diff\t$toLog\t$ATN\t$taillen\t$seqlen\t$matchlen\t$score\t$eststart\t$estend\t$chrstart\t$chrend\t$ID\n";
}
}

#�������
if ($otbl) {
  print "output to $otbl (";
  my($dbh)=connectDB($conf,1);
  $dbh->do("drop table if exists $otbl") or die;
  #<chr,strand,coord,N_or_Tail_diff,ATN,tail_len,seq_len,match_len,score,EST_start,EST_end,chr_start,chr_end,ID>
  $dbh->do("create table $otbl (chr varchar(20),strand char(1),coord int,N_or_Tail_diff int,ATN char(1),tail_len int, seq_len int,match_len int,score int,EST_start int,EST_end int,chr_start int,chr_end int, ID varchar(200))") or die "cannot create $otbl";
  my $rm=$dbh->do("load data infile \'$opa\' into table $otbl fields terminated by '\t' enclosed by '' Lines Terminated By '\n' ignore 1 lines") or die "cannot load $opa to $otbl!";
  print "Total $rm lines)\n";
  if ($opa eq $tmp) {
	unlink($opa) if (-e $opa);
  } 
}

sub getID {
my $line=shift;
my $ididx=index($line,'ID=');
my $nameidx=index($line,';Name=');
return(substr($line,$ididx+3,$nameidx-$ididx-3));
}

sub getTarget { 
	#Target=594:N:0:0|gi|29028819|gb|BT005854|U23487 1 52
	#��������: 594 N 0 0 1(eststart-4) 52(estend-5)
my $line=shift;
my $targetidx=index($line,';Target=');
my $gapindex=index($line,';Gap=');
my @tmp=(split(/ /,substr($line,$targetidx+8,$gapindex-$targetidx-8)));
my @tmp1=split(/\|/,$tmp[0]);
my @tmp2=split(/:/,$tmp1[0]);
return (@tmp2,@tmp[1..2]);
}

#!/usr/bin/perl -w

use strict;
use Getopt::Long;

use lib '/var/www/front/src/perl';
require ('funclib.pl');

#2012-08-05
#处理GMAP的-f 3的GFF3格式的结果
#Chr1	tair9	cDNA_match	9150012	9150063	100	-	.	
#Chr1	tair9	cDNA_match	9149643	9149882	100	-	.	
#ID=594:N:0:0|gi|29028819|gb|BT005854|U23487.path1;Name=594:N:0:0|gi|29028819|gb|BT005854|U23487;Target=594:N:0:0|gi|29028819|gb|BT005854|U23487 1 52;Gap=M52                                                                                                                                                                                                                                                                                              
#ID=594:N:0:0|gi|29028819|gb|BT005854|U23487.path1;Name=594:N:0:0|gi|29028819|gb|BT005854|U23487;Target=594:N:0:0|gi|29028819|gb|BT005854|U23487 53 292;Gap=M240                                                                                                                                                                                                                                                                                           
#Chr1	tair9	cDNA_match	25127727	25128310	100	+	.	
#Chr1	tair9	cDNA_match	25128444	25128589	100	+	.	
#ID=1080:N:0:0|gi|29028877|gb|BT005883|U23535.path1;Name=1080:N:0:0|gi|29028877|gb|BT005883|U23535;Target=1080:N:0:0|gi|29028877|gb|BT005883|U23535 1 584;Gap=M584                                                                                                                                                                                                                                                                                                                                                          
#ID=1080:N:0:0|gi|29028877|gb|BT005883|U23535.path1;Name=1080:N:0:0|gi|29028877|gb|BT005883|U23535;Target=1080:N:0:0|gi|29028877|gb|BT005883|U23535 585 730;Gap=M146                                                                                                                                                                                                                                                                                                                                                        
																																																																																																																														   
## 新的EST比对流程：
#1. 提取polyA/T的EST序列，并更改标题行，在行头加入 长度,A/T/N,tail长度,末数：如300:A:15:2
#对tail的要求：在末尾polyA或开头polyT，允许个别非A/T，但必须最形状或最后面只有<=2nt的非A/T。tail的长度直接从poly开始计算至序列末尾或序列开头（不管开头/末尾是否为非AT）
#2. 对提取后的序列进行GMAP，输出gff3格式
#3. 处理gff3格式成PA
#计算：
#chr第1个位置 28500
#chr最后1个位置 31183
#比对后全长：31183-28500 这是包含intron的片段的长度（但不包含poly的长度）
#cdna第1个比对位置：1 306中的1
#cdna最后1个比对位置：1976 2257中的2257
#chr,strand
#score=全部score的平均
#
#判断
#1. strand=+
#最后一个位置的下一位置为polyA
#1) 如果tail=N，计算 原序列长度-cdna比对最后1个位置（设误差为20）
#在误差内，直接记录polyA的位置。。
#超过误差，输出到另一文件，并记录日志信息
#2) 如果tail=A，计算 diff原序列长度-cdna比对最后1个位置
#如果diff<=raw_tail_len，则tail_len＝raw_tail_len-diff，记录tail=A
#如果diff>raw_tail_len，且比对末端到raw_tail的距离<=误差（比如5nt），则仍旧记录tail=A，tail_len=raw_tail_len
#否则表示raw_tail其实是在genome上，按tail=N处理，要求diff<=X
#3) 如果tail=T
#这种情况不可能，按tail=N处理
#
#2. strand=-
#第1个位置的前1个位置为polyA
#diff＝第1个位置-1
#1) tail=N，计算diff＝第1个位置-1
#如果diff<=X，记录polyA位置
#>X，则不输出
#2) tail=A 
#这种情况不可能，按tail=N处理
#3) tail=T
#如果diff<=raw_tail_len，则tail_len＝raw_tail_len-diff，记录tail=A
#如果diff>raw_tail_len，diff-raw_tail_len<=误差（比如5nt），则仍旧记录tail=A，tail_len=raw_tail_len
#否则，按tail=N处理

#输出OPA：
#chr	strand	coord	N_or_Tail_diff	ATN	tail_len	seq_len	match_len	score	EST_start	EST_end	chr_start	chr_end	ID
#Chr1	+	25129146	0	N	0	1080	1080	100	1	1080	25127727	25129145	1080:N:0:0|gi|29028877|gb|BT005883|U23535.path1
#Chr1	-	9149002	0	N	0	594	594	100	1	594	9149003	9150063	594:N:0:0|gi|29028819|gb|BT005854|U23487.path1

#输出LOG：
#chr	strand	DIFF	Why	ATN	tail_len	seq_len	match_len	score	EST_start	EST_end	chr_start	chr_end
#Chr3	-	27	ATN=N(NoTail),diff=(28-1)>20	N	0	942	915	99	28	942	22801237	22803054
#Chr4	+	34	ATN=N(NoTail),diff=(1799-1765)>20	N	0	1799	1748	100	18	1765	15935429	15937728

#2012-08-09
#增加otbl和conf选项，允许直接输成表

#2015/11/28
#简化输出文件列数

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

#最后输出：chr,strand,coord，tail=A/T/N，tail_len，raw_tail_len，cdna_len，cdna_match_len，match_score，cdna_match_start/end，chr_match_start/end,$ID
if ($toOutput) {
  $cntAT++;
  print OPA "$chr\t$strand\t$coord\t1\n";
}
if ($toLog ne '') {
  $cntLog++;
  print LOG "$chr\t$strand\t$diff\t$toLog\t$ATN\t$taillen\t$seqlen\t$matchlen\t$score\t$eststart\t$estend\t$chrstart\t$chrend\t$ID\n";
}
}

#输出到表
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
	#返回数组: 594 N 0 0 1(eststart-4) 52(estend-5)
my $line=shift;
my $targetidx=index($line,';Target=');
my $gapindex=index($line,';Gap=');
my @tmp=(split(/ /,substr($line,$targetidx+8,$gapindex-$targetidx-8)));
my @tmp1=split(/\|/,$tmp[0]);
my @tmp2=split(/:/,$tmp1[0]);
return (@tmp2,@tmp[1..2]);
}

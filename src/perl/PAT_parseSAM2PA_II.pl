#!/usr/bin/perl -w

#2011/8/10
#��DrHunt��.sam�ļ�,�õ�PA (��ͬ��10���SAM�������ж�tail��

#NC_003070
#NC_003071
#NC_003074
#NC_003075
#NC_003076

#MCIC-SOLEXA:2:29:1299:707#0/2	16	NC_003070	1076	60	24M1S	*	0	0	CCCCACACCCCCCCCACCCCCCAAC	#########################	NH:i:1
#MCIC-SOLEXA:2:54:719:1978#0/2	0	NC_003070	1077	60	24M2S	*	0	0	CCCACCCCCCCCCCACCCCCCAAACA	##########################	NH:i:1
#MCIC-SOLEXA:2:60:1152:652#0/2	0	NC_003070	2279	60	5S61M	*	0	0	TTTTTTTTTGTTTTTAGAATGTCGTTCCTTTTTCATCATCTTAGCTATATCTACANCTATATATCC	CCCCCCCCCCCCCCA<@><AA?>ABB@>ABCCB@5>21?3?B;7-><B@BAA@04%3?>@:A4;%;	NH:i:1
#MCIC-SOLEXA:2:53:479:691#0/2	16	NC_003070	5912	60	13M1D2M1I17M2S	*	0	0	CATGAGACCCAAAATGGAGTAAAGGGTGTTGGCCC	##############################;::<A	NH:i:1

#flag=16 + coord=1076+25
#flag=0 - coord=1077-1

#2011/8/11
#��profile�ķ��֣�strand=-��ʱ��λ����302�ˣ�����+��ʱ��λ��û��
#���� flag=0 - coord=1077-1

#2011/8/14
#����chr1=chr1�Ķ�Ӧ����ʱ�����SAM�ֲ�����NC_003070����ʾChr1�ģ�Shit��

#2014/9/16
#��֮ǰȷ��SAM�е�SN�Ƿ��ڽű�����{}����ʾ������Ȼ����warning���ٶȺ���

#2015/1/2
#����flag�жϣ�����Ϊ0��16ʱ�������
#flag���ͣ�http://broadinstitute.github.io/picard/explain-flags.html

#2015/1/3
#���Ӷ�mismatch��Ҫ�󣨰�D��IҲ����Ϊmatch���Լ��������Сsoft clip��
#�� cigar=60M2I5D4M11S ��ʾM=71,S=11����soft clip����10���������

#2015/1/5
#�����ж� FILE_PAT2PA.pl �Ƿ�Ϊ��ǰplͬһĿ¼

use strict;
use Getopt::Long;
use File::Basename;


use lib '/var/www/front/src/perl';
#push(@INC,"/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/");

require ('funclib.pl');

##########################################################################################
#  parameters & usage
##########################################################################################
my $pldir = File::Basename::dirname($0);
$pldir=~s/\\/\//g;

my %opts=();
GetOptions(\%opts,"h","sam=s","m:i","s:i","ofile=s");
my $USAGE=<< "_USAGE_";
Require: FILE_PAT2PA.pl -pat TAN -pa PA -tcols 1:2:6
Usage: 

  ������flag�ж�
  PAT_parseSAM2PA_II.pl -sam oxt.sam -ofile oxt.PA 

  ����CLC�����������cigarλ�� xMyS��һ������
  PAT_parseSAM2PA_II.pl -sam oxt.sam -m 30 -s 10 -ofile oxt.PA 
-h=help
��֮ǰȷ��SAM�е�SN�Ƿ��ڽű�����{}����ʾ������Ȼ����warning���ٶȺ���
-sam=sam file
-ofile=output PA file (chr,strand,coord,tagnum) -- ��˫ĩ�˱ȶԵõ���ͬ��PA��ʽ
-m=cigar match (M+D+I) (>=M)
-s=cigar soft clip (S) (<=S)
_USAGE_

#############################################################################
#  invoke the program                               
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'sam'};
my $samfile=$opts{'sam'};
my $ofile=$opts{'ofile'};
my $cigarM=0;
$cigarM=$opts{'m'} if $opts{'m'} and $opts{'m'}>0;
my $cigarS=0;
$cigarS=$opts{'s'} if $opts{'m'} and $opts{'s'}>0;
my $minM=0;
my $maxS=999;
$minM=$cigarM if $cigarM;
$maxS=$cigarS if $cigarS;

die "samfile not exists!" if !(-e $samfile);
die "sam cannot sam as ofile" if $samfile eq $ofile;

my $PAT2PApl='/var/www/front/src/perl/FILE_PAT2PA.pl';
if (!(-e $PAT2PApl)) {
  $PAT2PApl=$pldir.'/home/zym/Myperl/FILE_PAT2PA.pl';
  if (!(-e $PAT2PApl)) {
	  $PAT2PApl='E:/sys/code/MAP/FILE_PAT2PA.pl';
	  if (!(-e $PAT2PApl)) {
	  $PAT2PApl='/home/zym/Myperl/FILE_PAT2PA.pl';
		if (!(-e $PAT2PApl)) {
		  die "FILE_PAT2PA.pl not exists!"
		}
	  }
  }
}

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
#�����arab��SAM����ʱ����������chr���⣬��Ҫ�����
my(%chrs);
$chrs{NC_003070}='Chr1';
$chrs{NC_003071}='Chr2';
$chrs{NC_003074}='Chr3';
$chrs{NC_003075}='Chr4';
$chrs{NC_003076}='Chr5';
$chrs{Chr1}='Chr1';
$chrs{Chr2}='Chr2';
$chrs{Chr3}='Chr3';
$chrs{Chr4}='Chr4';
$chrs{Chr5}='Chr5';
$chrs{1}='Chr1';
$chrs{2}='Chr2';
$chrs{3}='Chr3';
$chrs{4}='Chr4';
$chrs{5}='Chr5';
$chrs{Pt}='chloroplast';
$chrs{Mt}='mitochondria';


#skip @ lines
open(IN,"<$samfile");
my $line;
while ($line=<IN>) {
  if ($line=~/^@/) {
	next;
  }else {
	last;
  }
}

my($flagFld,$chrFld,$posFld,$cigarFld,$seqFld)=(1,2,3,5,9);

#MCIC-SOLEXA:2:29:1299:707#0/2	16	NC_003070	1076	60	24M1S	*	0	0	CCCCACACCCCCCCCACCCCCCAAC	#########################	NH:i:1
#MCIC-SOLEXA:2:54:719:1978#0/2	0	NC_003070	1077	60	24M2S	*	0	0	CCCACCCCCCCCCCACCCCCCAAACA	##########################	NH:i:1

print "SAM2PAT...\n";
my $ofile1=$ofile.'_PAT';
my (@items,$flag,$strand,$chr,$j,$k,$pos,$PApos,$seq);
my ($flag0cnt,$flag1cnt)=(0,0);
my ($cigarcnt,$noflagcnt)=(0,0);
$chr=$strand='';
open(OO,">$ofile1");
while ($line=<IN>) {
  @items=split(/\t/,$line);
  $flag=$items[$flagFld];
  #$chr=$chrs{$items[$chrFld]};  
  $chr=$items[$chrFld];  #2014/12/26 ���SAM��chrֱ�ӿ��õĻ�
  $pos=$items[$posFld]; 
  #2015/1/3 ���Ҫ�ж� cigar
   my ($m,$s)=(0,0);
   if ($cigarM or $cigarS) {
     my $cigar=$items[$cigarFld]; 
	 ($m,$s)=cigar2MS($cigar,1);
	 if ($m<$minM or $s>$maxS) {
	   $cigarcnt++;
	   next;
	 }
   }
  if ($flag==0) {
	 $flag0cnt++;
	 $strand='-';
	 $PApos=$pos-1;
  }elsif ($flag==16) { #flag==16
	 $flag1cnt++;
	 $strand='+';	
	 $seq=$items[$seqFld];
	 $PApos=$pos+length($seq);
  } else { #flagû��
	$noflagcnt++;
	next;
  }
  print OO "$chr\t$strand\t$PApos\n";
  next;
}

close(IN);
close(OO);
if ($cigarM or $cigarS) {
  print "M<$cigarM or S>$cigarS\t$cigarcnt\n";
}
print "flag=*\t$noflagcnt\nflag=0(-)\t$flag0cnt\nflag=16(+)\t$flag1cnt\n";

print "PAT2PA...\n";
my $cmd="\"$PAT2PApl\" -pat \"$ofile1\" -pa \"$ofile\" -tcols 0:1:2";
#print "$cmd\n";
system $cmd;
#unlink $ofile1 if -e $ofile1; 


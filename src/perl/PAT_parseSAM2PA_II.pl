#!/usr/bin/perl -w

#2011/8/10
#由DrHunt的.sam文件,得到PA (不同于10年的SAM，不用判断tail）

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
#画profile的发现，strand=-的时候，位点变成302了，而＝+的时候，位点没错。
#所以 flag=0 - coord=1077-1

#2011/8/14
#增加chr1=chr1的对应，有时候给的SAM又不是以NC_003070来表示Chr1的，Shit！

#2014/9/16
#用之前确认SAM中的SN是否在脚本中有{}被表示到，不然会有warning且速度很慢

#2015/1/2
#增加flag判断，当不为0或16时，不输出
#flag解释：http://broadinstitute.github.io/picard/explain-flags.html

#2015/1/3
#增加对mismatch的要求（把D和I也计算为match）以及允许的最小soft clip数
#如 cigar=60M2I5D4M11S 表示M=71,S=11，则soft clip超过10个，不输出

#2015/1/5
#增加判断 FILE_PAT2PA.pl 是否为当前pl同一目录

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

  仅根据flag判断
  PAT_parseSAM2PA_II.pl -sam oxt.sam -ofile oxt.PA 

  处理CLC结果，允许用cigar位的 xMyS进一步过滤
  PAT_parseSAM2PA_II.pl -sam oxt.sam -m 30 -s 10 -ofile oxt.PA 
-h=help
用之前确认SAM中的SN是否在脚本中有{}被表示到，不然会有warning且速度很慢
-sam=sam file
-ofile=output PA file (chr,strand,coord,tagnum) -- 与双末端比对得到相同的PA格式
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
#如果是arab的SAM，有时候用其它的chr标题，需要用这段
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
  $chr=$items[$chrFld];  #2014/12/26 如果SAM的chr直接可用的话
  $pos=$items[$posFld]; 
  #2015/1/3 如果要判断 cigar
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
  } else { #flag没有
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


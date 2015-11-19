#!/usr/bin/perl -w

use strict;
use Getopt::Long;
use File::Basename;

push(@INC,"/var/www/front/src/perl/");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/");

require ('funclib.pl');

#修改历史
#2009/12/13
#增加-kfile,能够对kfile指定的kgram统计个数
#2010/3/4
#增加gap_once:i选项,能用常规/gap/once方式计算联子数 

##########################################################################################
#  parameters & usage
#  说明: 对file或dir下的所有满足pattern的文件 统计从from~to的k联子数,或统计指定kgram文件的k联子数
#  1) -file选项优先. -from或-to有1个<1,则统计整条序列
#  2) 输出如: "AAAAAA"	26
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","seqfile:s","seqdir:s","pat:s","k:i","kfile:s","suffix:s","from=i","to=i","sort=s","topn:i","gap_once:i");
my $USAGE=<< "_USAGE_";
File:    PAS_kcount.pl
Purpose: 
Usage: 
  specify k or kfile:
  1) perl PAS_kcount.pl -seqfile xx.fasta -from 1 -to 40 -k 6 -sort T
  2) perl PAS_kcount.pl -seqfile xx.fasta -from 1 -to 40 -kfile xx.kgram -sort T
  specify seqfile or seqdir:
  3) perl PAS_kcount.pl -seqfile xx.fasta -from -1 -to -1 -k 6  -sort T -topn 50
  4) perl PAS_kcount.pl -seqdir xx/xx -pat "\.txt" -from 1 -to 40 -k 6  -sort T -topn 50 -suffix "NUE"
  Use gap/once way
  5) perl PAS_kcount.pl -seqfile xx.fasta -from 1 -to 40 -k 6 -sort T -gap_once 2
  6) perl PAS_kcount.pl -seqfile xx.fasta -from 1 -to 40 -k 6 -sort T -gap_once "-1"
-h=help
-seqfile=
-seqdir=
-pat=pattern for input seqfiles. default ""
-suffix=suffix for output files. default like _1to40_k6_top50_sort when specify k, or _1to40_ks_top50_sort when specify kfile.
-from=1 if -1 then whole seq
-to=40 if -1 then whole seq
-k=6
-kfile=kgram file. one kgram per line, length of kgrams can be different.
-sort=T/F  
-topn=50
-gap_one=0/2/-1 if 0 or not specified then normal_way, if >0 then gap_way, if <0 then once_way
_USAGE_

#############################################################################
#  invoke the program                               
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'sort'}||!$opts{'from'}||!$opts{'to'};
die "Must specify seqfile or seqdir!" if (!(-e $opts{'seqfile'}) and !$opts{'seqdir'});
die "Must specify k or kfile!" if ($opts{'k'}<=0 and !(-e $opts{'kfile'}));
die "Either k or kfile, cannot both!" if ($opts{'k'}>0 and $opts{'kfile'});
die "Either seqfile or seqdir, cannot both!" if ($opts{'seqfile'} and $opts{'seqdir'});
die "Either seqfile or seqdir-pat, cannot both!" if ($opts{'seqfile'} and $opts{'pat'});


#取得所有文件名
my(@files,$f,$from,$to,$suffix,$line,@log,$logpath,$k,$kfile,$topn,$i,$sort);
if ($opts{'seqfile'}) {
	if  (-e $opts{'seqfile'}) {
      push(@files,$opts{'seqfile'});
      $logpath=dirname($opts{'seqfile'});	  
    } else {
      die "$opts{'seqfile'} not exists!\n";
    }
} else {
	@files=getFileNames($opts{'seqdir'},$opts{'pat'});
	$logpath=$opts{'seqdir'};
}
$logpath.='/' if (substr($logpath, length($logpath)-1, 1) ne '/');
if ($#files==-1) {
  print "No match file! (file=$opts{'seqfile'} or -dir=$opts{'seqdir'} and -pat=$opts{'pat'})\n";
  exit 0;
}

if ($opts{'topn'} and $opts{'topn'}>0) {
 $topn=$opts{'topn'};
} else {
 $topn=-1;
}

if ($opts{'sort'} eq 'F') {
 $sort=0;
} else {
 $sort=1;
}

my $go=$opts{'gap_once'};
$from=$opts{'from'};
$to=$opts{'to'};
$k=$opts{'k'} if $opts{'k'}>0;
$kfile='';
$kfile=$opts{'kfile'} if ($opts{'kfile'} and -e $opts{'kfile'});
if (!$opts{'suffix'}) { 
 $suffix="_${from}to${to}_k";
 $suffix.="$k" if $k>0;
 $suffix.="s" if $kfile;
 $suffix="_all_k$k" if ( ($from<1 or $to<1) and $k);
 $suffix="_all_ks" if ( ($from<1 or $to<1) and $kfile ne '');
 $suffix.='_'."top$topn" if $topn>0;
 $suffix.="_sort" if $sort;
 $suffix.="_gap$go" if ($go and $go>0);
 $suffix.="_once" if ($go and $go<0);
} else {
 $suffix=$opts{'suffix'};
}



#对每个文件中的序列统计k数 (非>开头的行)
my(@cnts,@order,$lidx,@grams,$g);
#得到grams
if ($k) { #选项-k
	@grams=genKgrams($k,0);
} elsif (-e $kfile) {
  open (KG,"<$kfile");
  while ($g=<KG>) {
	$g=trim($g);
    push(@grams,$g) if $g ne '';
  }
  close(KG);
}
#计算个数
foreach $f(@files) {	  
  if ($k) {
	@cnts=cntKgramsByK($f,$from,$to,$k,$go);
  } elsif (-e $kfile) {
	@cnts=cntKgrams($f,$from,$to,$go,@grams);
  }
  open (OUT,">$f$suffix");
  if ($sort) {
	@order=shellSort(0,$#cnts,'desc',@cnts);
  } else {
	@order=(0..$#grams);
  }
  if ($topn>0 and $topn<=$#order) {
	$lidx=$topn-1;
  } else {
	$lidx=$#order;
  }
  for $i(0..$lidx) {
	print OUT "\"$grams[$order[$i]]\""."\t"."$cnts[$order[$i]]\n";
  }
  close OUT;
  my($name)=basename($f.$suffix);
  #print "kcount into file: $name\n";
} #for




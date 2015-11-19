#!/usr/bin/perl -w

#use lib '/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi/';
use lib '/var/www/front/src/perl';
#use lib '/home/zym/soft/cpan/lib/perl5/';

require ('funclib.pl');
use strict;
use Getopt::Long;
use File::Basename;


#2009/12/29
#增加-kfile选项,可以统计给定kgram的,虽然这种情况下可以计算pssm,但不一定准确了!!
#输出如: 
#motif	1	2	3	
#AAUAAA	12	11	12
#AUAAAA	7	10	12
#UAAUAA	5	11	7

#2010/3/17
#增加gap_once:i选项,能用常规/gap/once方式计算联子数 

#2010/6/22
#去除sum列的输出
#增加-tran:T/F选项,若tran=T,则转置输出. 第一行为motif
#增加-freq:T/F选项,若freq=T,则输出频率=该位置数/该区域所有位置数
#主要为了输出400nt的profile

#2010/8/23
#gram统一uc处理

##########################################################################################
#  parameters & usage
#  说明: 统计每个位置的kgram和pssm值,有合计列. 
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","seqfile:s","seqdir:s","pat:s","from=i","to=i","k:i","kfile:s","sort=s","topn:i","suffix:s","pssm:s","cnt:s","gap_once:i","tran:s","freq:s");
my $USAGE=<< "_USAGE_";
About: 
Purpose: 
Usage: 
  specify k:
  1) PAS_kpssm.pl -seqfile testdata/S_3UTR_400nt_5029s -from 265 -to 290 -k 6 -sort T -topn 50 -cnt T -pssm T
  2) PAS_kpssm.pl -seqdir testdata -pat "12s" -from 1 -to 40 -k 6  -sort T -cnt T -pssm T -topn 50 -suffix "kp"
  specify kfile:
  3) PAS_kpssm.pl -seqfile testdata/S_3UTR_400nt_5029s -from 265 -to 290 -kfile xx.gram -sort T -topn 50 -cnt T -pssm T
  Use gap/once way
  4) PAS_kpssm.pl -seqdir testdata -pat "12s" -from 1 -to 40 -k 6  -sort T -cnt T -pssm T -topn 50 -gap_once "-1"
  one-nucleotide profile
  5) PAS_kpssm.pl -seqdir testdata -pat "s$" -from 1 -to 400 -k 1  -sort F -cnt T -freq T -tran T -suffix _atcg

  匹配如 wtg.xxxxs 或 wtgm.xxxxs
  PAS_kpssm.pl -seqdir "F:/script_out_2/" -pat "wtgm.*s$" -from 1 -to 400 -k 1  -sort F -cnt T -freq T -tran T -suffix _atcg
  匹配如 wtg.xxxxxs
  PAS_kpssm.pl -seqdir "F:/script_out_2/" -pat "wtg\..*s$" -from 1 -to 400 -k 1  -sort F -cnt T -freq T -tran T -suffix _atcg


-h=help
-seqfile=
-seqdir=
-pat=pattern for input seqfiles. default ""
-from=1 if -1 then whole seq
-to=40 if -1 then whole seq
-k=6
-kfile=kgram file. one line one gram. length is the first gram.
-sort=T(default)/F  
-topn=50
-pssm=T/F(default)
-cnt=T/F(default)
-gap_one=0/2/-1 if 0 or not specified then normal_way, if >0 then gap_way, if <0 then once_way
-suffix=suffix for output files. default like _1to40_k6_top50_sort,output pssmfile as <input><suffix>.pssm, cntfile as <input><suffix>.cnt
*-tran=T/F(default); when T, then transform result.
*-freq=T/F(default); when T, then output frequency.
*only apply for .cnt output. mianly used for one-nucleotide profile.
_USAGE_

#############################################################################
#  invoke the program                               
#############################################################################
die $USAGE if $opts{'h'}||(!$opts{'seqfile'} and !$opts{'seqdir'})||!$opts{'sort'}||!$opts{'from'}||!$opts{'to'};
die "seqfile not exists!" if ($opts{'seqfile'} and !(-e $opts{'seqfile'}));
$opts{'pssm'}='' if !$opts{'pssm'};
$opts{'cnt'}='' if !$opts{'cnt'};
die "At least output pssm or cnt!" if ($opts{'pssm'} ne 'T' and $opts{'cnt'} ne 'T');
$opts{'kfile'}='' if !$opts{'kfile'};
die "At least kfile or k!" if (!(-e $opts{'kfile'}) and $opts{'k'}<=0);
die "Either kfile or k, cannot both!" if ((-e $opts{'kfile'}) and $opts{'k'}>0);
die "Either seqfile or seqdir, cannot both!" if ($opts{'seqfile'} and $opts{'seqdir'});
die "Either seqfile or seqdir-pat, cannot both!" if ($opts{'seqfile'} and $opts{'pat'});
die "When tran=T or freq=T, need -cnt=T!" if ( ( ($opts{'tran'} and $opts{'tran'} eq 'T') or ($opts{'freq'} and $opts{'freq'} eq 'T')) and ($opts{'cnt'} and $opts{'cnt'} ne 'T'));

#取得所有文件名
my(@files,$f,$from,$to,$suffix,$line,@log,$logpath,$k,$kfile,$topn,$i,$sort,$j,$klen);
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

#print($opts{'pat'}."\n");
#print (join("\n",@files));
#exit 0;

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
$kfile=$opts{'kfile'} if ($opts{'kfile'} and  -e $opts{'kfile'});
if (!$opts{'suffix'}) { 
 $suffix="_${from}to${to}_k";
 $suffix.=$k if $k>0;
 $suffix.='s' if (-e $opts{'kfile'});
 $suffix.='_'."top$topn" if $topn>0;
 $suffix.="_sort" if $sort;
 $suffix.="_gap$go" if $go>0;
 $suffix.="_once" if $go<0;
} else {
 $suffix=$opts{'suffix'};
}

my($ocnt)=1 if ($opts{'cnt'} and $opts{'cnt'} eq 'T');
my($opssm)=1 if ($opts{'pssm'} and $opts{'pssm'} eq 'T');
my($cntSuffix)=$suffix.'.cnt';
my($pssmSuffix)=$suffix.'.pssm';

my($ofreq,$tran)=(0,0);
$ofreq=1 if ($opts{'freq'} eq 'T');
$tran=1 if ($opts{'tran'} eq 'T');

#对每个文件中的序列统计k数 (非>开头的行)
my($cnts,@order,$lidx,@grams,@orderp,$pssm,@rowsum,@rowmax,@colsum);

#得到grams
my($g);
if ($k) { #选项-k
	@grams=genKgrams($k,0);
} elsif (-e $kfile) {
  open (KG,"<$kfile");
  while ($g=<KG>) {
	$g=uc(trim($g));
    push(@grams,$g) if $g ne '';
  }
  close(KG);
}
#联子长度
$klen=length($grams[0]);

#计算个数
foreach $f(@files) {	
  if ($k) { #cnt是行为motif,列为pos
	$cnts=cntEachPosByK($f,$from,$to,$k,$go);
  } elsif (-e $kfile) {
	$cnts=cntEachPosByGrams($f,$from,$to,$go,@grams);
  }  
  if ($opssm) {
	$pssm=kcnt2pssm($cnts);
  }

  #排序,cnt按sum,pssm按max
  if ($sort) {
    #计算第N行的所有列的和 (每个motif在所有位置的和.)
	@rowsum=();
    for $i(0..$#$cnts) {
     for $j(0..$#{$cnts->[$i]}) {
        $rowsum[$i]+=$cnts->[$i][$j];
     }
    }
	#@order=sortKcnt($cnts);
    @order=shellSort(0,$#rowsum,'desc',@rowsum);

    if ($opssm) {
      #@orderp=sortPssm($pssm);
	  #计算第N行的所有列的max
	  for $i(0..$#$pssm) {
		#$rowmax[$i]=NONPSSM()-1;
		$rowmax[$i]=-99999999;
	  }
	  for $i(0..$#$pssm) {
		for $j(0..$#{$pssm->[$i]}) {
		  $rowmax[$i]=$pssm->[$i][$j] if $rowmax[$i]<$pssm->[$i][$j];
		}
	  }
	  @orderp=shellSort(0,$#rowmax,'desc',@rowmax);
    }

  } else {
	@order=(0..$#grams);
    @orderp=(0..$#grams);
  }
  if ($topn>0 and $topn<=$#order) {
	$lidx=$topn-1;
  } else {
	$lidx=$#order;
  }

#输出
if ($ocnt) {
  open (OUT,">$f$cntSuffix");
  #标题行 motif\t位置
  print OUT "motif\t";
  for $j($from..$to-$klen) {
	print OUT "$j\t";
  }	
  print OUT ($to-$klen+1);
  print OUT "\n";
  #if ($sort) {
#	print OUT "\tsum\n";
 # } else {
#	print OUT "\n";
 # } 
  if (!$ofreq) { #个数
	  for $i(0..$lidx) {
		print OUT "$grams[$order[$i]]\t";
		for $j(0..$#{$cnts->[0]}-1) {
		  print OUT "$cnts->[$order[$i]][$j]\t";
		}	
		print OUT "$cnts->[$order[$i]][$#{$cnts->[0]}]";
		print OUT "\n";
		#if ($sort) {
		  #print OUT "\t$rowsum[$order[$i]]\n"; #不输出sum列
		#} else {
		#  print OUT "\n";
	   # }
	  }
  } else {#频率
      my $mf;
	  #每个位置所有motif的和
	  @colsum=();
	   for $i(0..$#{$cnts->[0]}) {
		 for $j(0..$#$cnts) {
			$colsum[$i]+=$cnts->[$j][$i];
		 }
       }  
	  for $i(0..$lidx) {
		print OUT "$grams[$order[$i]]\t";
		for $j(0..$#{$cnts->[0]}-1) {
		  $mf=0;
		  if ($colsum[$j]>0) {
			$mf=$cnts->[$order[$i]][$j]/$colsum[$j];
		  }		  
		  printf OUT ("%.4f\t",$mf);
		}	
		  $mf=0;
		  if ($colsum[$#{$cnts->[0]}]>0) {
			$mf=$cnts->[$order[$i]][$#{$cnts->[0]}]/$colsum[$#{$cnts->[0]}];
		  }		  
		  printf OUT ("%.4f\n",$mf);
	  }
  }

  close OUT;

  #如果tran,则直接处理输出好了
  if ($tran) {
	my $mtx2=loadFile2Mtx("$f$cntSuffix","\t",0);
	my $flipmatrix=flipMatrix($mtx2);
    saveMtx2File($flipmatrix,"$f$cntSuffix");
  }
  my($name)=basename($f.$cntSuffix);
  print "KPSSM_cnt into file: $name\n";
}

if ($opssm) {
  open (OUT,">$f$pssmSuffix");
  #标题行 motif\t位置
  print OUT "motif\t";
  for $j($from..$to-$klen) {
	print OUT "$j\t";
  }	
  print OUT ($to-$klen+1);
  if ($sort) {
	print OUT "\tmax\n";
  } else {
	print OUT "\n";
  }
  for $i(0..$lidx) {
    print OUT "$grams[$orderp[$i]]\t";
	for $j(0..$#{$pssm->[0]}-1) {
	  print OUT "$pssm->[$orderp[$i]][$j]\t";
	}	
    print OUT "$pssm->[$orderp[$i]][$#{$pssm->[0]}]";
    if ($sort) {
	  print OUT "\t$rowmax[$orderp[$i]]\n";
    } else {
	  print OUT "\n";
    }
  }
  close OUT;
  my($name)=basename($f.$pssmSuffix);
  print "KPSSM_Pssm into file: $name\n";
}

} #foreach




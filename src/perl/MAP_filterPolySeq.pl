#!/usr/bin/perl -w

#use strict;
use Getopt::Long;
use File::Basename;

push(@INC,"/var/www/front/src/perl/");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/");

require ('funclib.pl');

#2010/7/30
#修改自: CIP_filterPolySeq.pl #主要用于_2tag和_1tag的判断
#增加选项 -poly A/T/AT/TA 允许只搜索开头的T或末尾的A
#增加选项 -st suffix for trim seqs file. 如果有设置,则输出trim后的序列
#增加选项 -ml 25 minlen after trim-poly

#-----------------------------------------------------------
#处理fasta或fq文件,得到含有polyA/T的序列,并记录序列的poly信息.
#用于: 处理RNA-seq的序列,得到含PolyA的序列,再用于GMAP比对,得到PA

#输入: fq或fa文件或文件夹
#输出: .ts (含tail的seq) .ti (tail的信息) -- 单文件.ts/ti,或设置-mg,生成<-mg>和<-mg>.ti
#ti文件如: 45	A	31	11 表示: <序列名/A或T/tail开始位置/tail长度>

#polyA/T的判断: 连续N个(BT中设置为8个).
#5'---------------------------3'
#--------------30AAAAAAAA----
#--6TTTTTTTT10----------------
#polyA_start=30 第1个A出现的位置,首1.
#polyT_start=6 第1个T出现的位置,首1.
#poly_region(tr)是完全包含An或Tn的区域.
#即使设置了tr=20,只搜索前20个nt,看是否有连续的polyT,但得到的tail长度仍可以延伸出20nt的范围. 
#主要为了对_2tag, XXXNNTTTTTTTTTTTT,为了使XXXNN后面直接是tail,而设置tr=15,tl=8,即允许XXXNN后面的10个nt内有2个非T,但至少有8个T.
#对于polyA也相似,在末尾的tr范围内搜A-tail,若找到A-tail,则向左右扩展看是否还有连续的A..

## Example ##
#MAP_filterPolySeq.pl -sd c:/ -sp "\.in" -if fa -tl 8 -tr 15 -poly AT -ml 25 -of fa -ss .ts -si .ti -st .tt -mg c:/mg

#MAP_filterPolySeq.pl -s "c:/filter.in" -if fa -tl 8 -tr 15 -poly AT -of fa -ml 25 -ss .ts -si .ti -st .tt -qc T
#>T0
#GAACCTTTTTTTTTTTCTTTTTTCTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT <==被qc T过滤掉了
#>T1
#TGTCCTTTTTTTTTTTTTGATAAGAGTTGAGCACTATGAACTTTATTAAAATAAAAAAACGTAGATCGGCATAGC
#>A1
#GGGGGTGTTCTACAGTCTTCCGGTTGGGTGGTAAAAAAAAAAAAAGAAAA
#>TA1
#GGTCCCTTTTTTTTTTTTTTTTTTCCCAAAACGAGGCCCACTTCTGCTTTGAAAAAAACAAAAAAAAAAAAAAACAAAAAA
#>N2
#CCCAAAACGAGGCCCACTTCTGCTTTGAAAAAAACAAAGGTCCCTTTTTTTTTTTTTTTTTTGGTCCCTTTTTTTTTTTTTTTTTT

#==> -si .ti
#T1	75	18	0	T	6	13
#A1	75	0	x	A	33	13
#TA1	75	x	A	60	15 <==虽然TA1也含有AT,但-poly=AT,搜索时优先A,因此只输出'A'.

#==> -st .tt
#>A1
#GGGGGTGTTCTACAGTCTTCCGGTTGGGTGGT
#>A2
#CGAGGCCCACTTCTGCTTTGAAAAAAAC
#>TA1
#GGTCCCTTTTTTTTTTTTTTTTTTCCCAAAACGAGGCCCACTTCTGCTTTGAAAAAAAC

#2010/8/1
#使trim后的score也是由原fq得到的,而不是IIII

#2010/8/3
#修改.ti的输出,得到 #(seq_name,seqlen,trimleft,trimright,A/T/N,tailstart,taillen)
#增加选项 -un 用于输出无P/A-tail的原始序列

my %opts=();
GetOptions(\%opts,"h","s:s","sd:s","sp:s","if=s","tl=i","tr:i","ml:i","poly:s","of=s","qc:s","ss:s","si:s","st:s","un:s","mg:s");
my $USAGE=<< "_USAGE_";
Usage: 
  specify seqfile or seqdir:
  1)MAP_filterPolySeq.pl -s xx.fq -if fq -tl 8 -tr 20 -of fq -ss .ts -si .ti -qc T
  2)MAP_filterPolySeq.pl -sd c:/ -sp "\.fq" -if fa -tl 8 -tr 20 -of fq -ss .ts -si .ti -qc T -mg c:/xx.ts
  3)MAP_filterPolySeq.pl -sd c:/ -sp "\.in" -if fa -tl 8 -tr 15 -poly T -of fq -ss .ts -si .ti -qc T -mg c:/mg
  4)MAP_filterPolySeq.pl -s "c:/filter.in" -if fq -tl 8 -tr 15 -poly T -ml 25 -qc T -of fa -ss .ts -si .ti -st .tt #for _2tag
  5)MAP_filterPolySeq.pl -s "c:/filter.in" -if fq -tl 8 -tr 15 -poly A -ml 25 -qc T -of fa -ss .ts -si .ti -st .tt #for _1tag
  6)MAP_filterPolySeq.pl -s "c:/filter.in" -if fq -tl 8 -tr 15 -poly T -ml 25 -qc T -of fa -un .un #for non-tail tags
  7)MAP_filterPolySeq.pl -s "c:/filter.in" -if fq -tl 8 -tr 0 -poly A -ml 25 -qc T -of fq -st .tt #for _1tag 不限制区域，只输出去除tail后的序列 
-h=help
-s=seq
-sd=seq dir
-sp=seq pattern
-if=input format (fa/fq)
-tl=tail length default 8
-tr=region to search tail. if >0, then search the end tr region for polyA or begin tr region for T. tr contains <AAAAAAA---->
-ml=0(default). min length after trim to output. if 0, then not restrict.
-poly=A/T/AT/TA(default); for A/T, only search polyA/T; for AT/TA, search both. if AT/TA, then first polyA-prior,if find polyA, then won't find polyT.
-of=output format (fa/fq)
*-ss=suffix for seq file. 
*-si=suffix for seq information file. #(seq_name,seqlen,trimleft,trimright,A/T/N,tailstart,taillen)
*-st=suffix for trimed seq.
*-un=suffix for no-tail seq.
* if ne '', then output file. The si/st/ss files are corresponding to each other. 
-qc=T/F(default),quality control. only valid when -st if T, then discard seqs with 80% ATCG or 10%N
-mg=merge file. if not '', then merge all output files into mg./ss/si/st
_USAGE_

#############################################################################
#  invoke the program                               
#############################################################################
my $QT=0.8; #threshold for qc%;

die $USAGE if $opts{'h'}||!$opts{'if'}||!$opts{'tl'}||!$opts{'of'}||($opts{'tr'}<0);
die "Must specify file(s) or seqdir(sd)!" if (!($opts{'s'}) and !$opts{'sd'});
die "file(s) not exists!" if (!(-e $opts{'s'}) and !$opts{'sd'});
die "Either seqfile(s) or seqdir(sd), cannot both!" if ($opts{'s'} and $opts{'sd'});
die "Either seqfile(s) or seqdir-pat(sd-sp), cannot both!" if ($opts{'s'} and $opts{'sp'});
die "Either seqfile(s) or mg, cannot both!" if ($opts{'s'} and $opts{'mg'});
die "-if=fa or fq !" if (lc($opts{'if'}) ne 'fa' and lc($opts{'if'}) ne 'fq');
die "-of=fa or fq !" if (lc($opts{'of'}) ne 'fa' and lc($opts{'of'}) ne 'fq');
die "-qc cannot be T when No -st!" if (lc($opts{'qc'}) eq 't' and !($opts{'st'}));

#取得所有文件名
my(@files);
if ($opts{'s'}) {
	if  (-e $opts{'s'}) {
      push(@files,$opts{'s'});
    } else {
      die "$opts{'s'} not exists!\n";
    }
} else {
	@files=getFileNames($opts{'sd'},$opts{'sp'});
}
if ($#files==-1) {
  print "No match file! (file=$opts{'s'} or -dir=$opts{'sd'} and -sp=$opts{'sp'})\n";
  exit 0;
}

my $tr=0;
$tr=$opts{'tr'} if ($opts{'tr'}>0);
my $tl=$opts{'tl'};
$tl=8 if $tl<=0;
my $qc=0;
$qc=1 if ($opts{'qc'} and $opts{'qc'} eq 'T');
my $mg='';
$mg=$opts{'mg'} if $opts{'mg'};

my $if=lc($opts{'if'});
my $of=lc($opts{'of'});
my $ss=$opts{'ss'};
my $si=$opts{'si'};
my $st=$opts{'st'};
my $un=$opts{'un'};
$ss='' if !defined($ss);
$si='' if !defined($si);
$st='' if !defined($st);
$un='' if !defined($un);
die "At least ss/si/st/un!" if (!$si and !$ss and !$st and !$un);
die "-si cannot equal to -ss!" if (($si or $ss) and ($si eq $ss));
die "-si cannot equal to -st!" if (($si or $st) and ($si eq $st));
die "-ss cannot equal to -st!" if (($ss or $st) and ($ss eq $st));
die "-ss cannot equal to -st!" if (($ss or $st) and ($ss eq $st));

my $poly=$opts{'poly'};
$poly=2 if ($poly ne 'A' and $poly ne 'T');
$poly=0 if $poly eq 'A';
$poly=1 if $poly eq 'T';
my $polytxt='polyA' if $poly==0;
$polytxt='polyT' if $poly==1;
$polytxt='polyAT' if $poly==2;

my $ml=$opts{'ml'};
$ml=0 if $ml<=0;

my $As=('A' x $tl);
my $Ts=('T' x $tl);

my ($f,$line,$name,$seq,$idx,@nt,$i);
my ($AorT,$trimstr)=('','');
my ($isT,$isA)=(0,0);
foreach $f(@files) {
 my ($nA,$nT,$nNAT)=(0,0,0);
 print basename($f)." $polytxt minlen=$ml qc=$qc; ";
 open (IS,"<$f") or die "Cannot read $f";
 if ($ss) {
  open (OS,">$f$ss") or die "Cannot write $f$ss";
 }
 if ($si) { 
   open (OI,">$f$si") or die "Cannot write $f$si";
 }
 if ($st){
   open (OT,">$f$st") or die "Cannot write $f$st";
 }
 if ($un) {
   open (OU,">$f$un") or die "Cannot write $f$un";
 }
 $nA=$nT=0; 
 my $tot=0;
 my $slen=0;
 my ($polylen,$polystart,$trimstart,$trimlen,$trimleft,$trimright,$score,$seqname)=(0,0,0,0,0,0,'','');
 while($name=<IS>) {
   last if trim($name) eq '';
   $line=<IS>;
   $line=trim($line);
   $tot++;
   $isT=$isA=0;
   $slen=length($line);
   if ($if eq 'fq') {
     <IS>;
	 $score=<IS>;
   }
   $seqname=substr($name,1,length($name)-1);

   if ($poly!=1) { #AT/A  
      $idx=rindex($line,$As); #返回最后一个As的位置
       if ($idx!=-1 and ($tr<=0 or $idx>=$slen-$tr)) {    	  
    	  $AorT='A';
    	  $polylen=$tl;
    	  $polystart=$idx+1; # 往左扩展
          @nt=split(//,$line);
    	  for ($i=$idx-1;$i>=0;$i--) {
    		if ($nt[$i] eq 'A') {
    		  $polylen++;
			  $polystart--;
    		} else {
    		  last;
    		}
    	  }
		  #AAAAAAAAAxx 一排的A
		  ($trimstart,$trimlen,$trimleft,$trimright)=(0,$polystart-1,0,$slen-$polystart+1);
		  if ($polystart>0) {
		   if ($polystart-1>=$ml) { #长度满足要求
			$isA=1;
			$trimstr=substr($line,0,$polystart-1) if $st;
		   }
		  }       
		} 
     } #if poly

  if ($poly!=0 and !$isA) { #AT(when no polyA)/T
	 $idx=index($line,$Ts);
	 if ($idx!=-1 and ($tr<=0 or $idx<=$tr-$tl)) {
		$AorT='T';
		#$polystart=$idx+$tl;
		$polystart=$idx+1;
		$polylen=$tl;
		#print "idx=$idx; line=$line\n"."substr=".substr($line,$idx+$tl,$tr-$idx-$tl-1)."\n";
		#if ($tr>0) {
		#  @nt=split(//,substr($line,$idx+$tl,$tr-$idx-$tl));
		#} else {
		  @nt=split(//,substr($line,$idx+$tl,$slen-$idx-$tl));
		#}        
	    for $i(0..$#nt) {
		  if ($nt[$i] eq 'T') {
		  $polylen++;
		  #$polystart++;
		  } else {
		  last;
		  }
	    }
		#如果XXXXTTTTTT..TTTT 一排的T,则舍去
		($trimstart,$trimlen,$trimleft,$trimright)=($polystart+$polylen-1,$slen,$polystart+$polylen-1,0);
		if ($trimstart<$slen) {
		 if (length($line)-$trimstart>=$ml) {
		  $isT=1;
		  #$nT++;
		  $trimstr=substr($line,$trimstart,$slen) if $st;
		 }
		}
	 }	 
   } #polyT

   #输出
   if ($if eq 'fq') {
	 if (($isT or $isA) and (!$qc or !isBad($trimstr))) {
	  $nT++ if $isT;
	  $nA++ if $isA;
	  if ($ss) {	   
       if ($of eq 'fq') {
         print OS "$name";
		 print OS "$line\n";
		 print OS '+'.$seqname;
		 print OS $score;
	   } else {
         print OS '>'.$seqname;
		 print OS "$line\n";
	   }	   
	  }

      if ($st) {
       if ($of eq 'fq') {
         print OT "$name";
		 print OT "$trimstr\n";
         print OT '+'.$seqname;  
		 $score=trim($score);
		 print OT (substr($score,$trimstart,$trimlen));
		 print OT "\n";
	   } else {
         print OT '>'.$seqname;
		 print OT "$trimstr\n";
	   }
      }

      if ($si) {
	   chomp($seqname);
	   #(seq_name,seqlen,trimleft,trimright,A/T/N,tailstart,taillen)
	   print OI $seqname."\t$slen\t$trimleft\t$trimright\t$AorT\t$polystart\t$polylen\n";
      }
	 } else { #输出un
	  $nNAT++;
      if ($un) {   
       if ($of eq 'fq') {
         print OU "$name";
		 print OU "$line\n";
		 print OU '+'.$seqname;
		 print OU $score;
	   } else {
         print OU '>'.$seqname;
		 print OU "$line\n";
	   }	   
      } 
	 }
   } #fq
  else { #if=fa
	 if (($isT or $isA) and (!$qc or !isBad($trimstr))) {
	   $nT++ if $isT;
	   $nA++ if $isA;
	   if ($ss) {
        if ($of eq 'fq') {
         print OS '@'.$seqname;
		 print OS "$line\n";
		 print OS '+'.$seqname;
		 print OS ('I' x length($line));
		 print OS "\n";
	    } else {
         print OS "$name";
		 print OS "$line\n";
	    }	
	   }

	   if ($st) {
        if ($of eq 'fq') {
         print OT '@'.$seqname;
		 print OT "$line\n";
		 print OT '+'.$seqname;
		 print OT ('I' x length($trimstr));
		 print OT "\n";
	    } else {
         print OT "$name";
		 print OT "$trimstr\n";
	    }	
	   }
   
	   if ($si) {
	     chomp($seqname);
	     print OI $seqname."\t$slen\t$trimleft\t$trimright\t$AorT\t$polystart\t$polylen\n";
	   }
	 } else {#输出un
	  $nNAT++;
      if ($un) {   
        if ($of eq 'fq') {
         print OS '@'.$seqname;
		 print OS "$line\n";
		 print OS '+'.$seqname;
		 print OS ('I' x length($line));
		 print OS "\n";
	    } else {
         print OS "$name";
		 print OS "$line\n";
	    }	   
      } 
	 }
  } #else fa
} #while

 close(IS);
 close(OS) if $ss;
 close(OI) if $si;
 close(OT) if $st;
 close(OU) if $un;
 print "total=$tot; noTail=$nNAT; polyA=$nA; polyT=$nT\n";
 $tot=0;
} #for

if ($mg ne '' and $#files>0) {
  print "Merge into $mg\[$ss $si $st\].\n";
  my $cmd;
  if ($ss) {
   $cmd='cat ';
  for my $i(0..$#files) {
	$cmd.="\"$files[$i]$ss\" ";    
  }
  $cmd.=">$mg$ss";
  system $cmd;
  }

 if ($si) {
  $cmd='cat ';
  for my $i(0..$#files) {
	$cmd.="\"$files[$i]$si\" ";    
  }
  $cmd.=">$mg$si";
  system $cmd;
 }

 if ($st) {
  $cmd='cat ';
  for my $i(0..$#files) {
	$cmd.="\"$files[$i]$st\" ";    
  }
  $cmd.=">$mg$st";
  system $cmd;
 }
  if ($un) {
   $cmd='cat ';
  for my $i(0..$#files) {
	$cmd.="\"$files[$i]$un\" ";    
  }
  $cmd.=">$mg$un";
  system $cmd;
  }
  for my $i(0..$#files) {
	unlink("$files[$i]$ss") if $ss;  
	unlink("$files[$i]$si") if $si;  
	unlink("$files[$i]$st") if $st;  
	unlink("$files[$i]$un") if $un; 
  }
}

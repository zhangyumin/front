#!/usr/bin/perl -w

use strict;
use Getopt::Long;
use File::Basename;

use lib '/var/www/front/src/perl';
require ('funclib.pl');

#2012-08-04
#�����е�title����������ͷ���� ����:A/T/N:tail����:ĩ������300:A:15:2
#ֻ�ж�������As��Ts�������Ĳ���

#�������: ��tr=10,tl=8����ʾ����ĩ��<=2�����
#1. ....ATGGTGGAAGGAAAAAAAAAAAAAAAAAAAAAXXX
#904:N:0:0 ��Ϊĩβ��TTT������2��

#2. XXTTTTTTTTTTTTTTTTTTTATGCGG...
#256:T:19:2 ĩ����2����������tail����Ϊ19

#3. ...ATGTCCTACAAAAAAAAAAAAAAAX
#688:A:15:1 polyAΪ15nt��������1nt


my %opts=();
GetOptions(\%opts,"h","s=s","tl=i","tr=i","poly=s","ss=s");
my $USAGE=<< "_USAGE_";
Usage: 
EST_markPoly.pl -s "F:/script_out/est/test2.fa" -tl 8 -tr 10 -ss ".m" -poly AT

-h=help
-s=seq
!!input format (fa)
-tl=tail length default 8
-tr=region to search tail. if >0, then search the end tr region for polyA or begin tr region for T. tr contains <AAAAAAA---->
-poly=A/T/AT/TA(default); for A/T, only search polyA/T; for AT/TA, search both. if AT/TA, then first polyA-prior,if find polyA, then won't find polyT.
-ss=suffix for seq file (>seqlen:A/T/N:tail_len:endnt|org_title)
_USAGE_

#############################################################################
#  invoke the program                               
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'s'}||!$opts{'tl'}||($opts{'tr'}<0)||!$opts{'ss'}||!$opts{'poly'};

my $tr=$opts{'tr'};
my $tl=$opts{'tl'};
$tr=0 if !$tr;
die "tr must >= tl" if $tr<=$tl or $tr<=0;
die "tl must >0" if $tl<=0;

my $is=$opts{'s'};
die "$is not exist!" if !(-e $is);
my $ss=$opts{'ss'};
my $poly=$opts{'poly'};
$poly=2 if ($poly ne 'A' and $poly ne 'T');
$poly=0 if $poly eq 'A';
$poly=1 if $poly eq 'T';

my $As=('A' x $tl);
my $Ts=('T' x $tl);

open(OS,">$is$ss") or die "cannot write $is$ss";
open(IS,"<$is") or die "cannot read $is";

my ($title,$line,$slen,$tot,$nA,$nT,$nN,@aseq)=('','',0,0,0,0,0,());

while($line=<IS>) {
	$line=trim($line);
	if($line=~/^>/)	{		
		output();
        $title=$line;
		@aseq=();
	}	else	{
		push(@aseq,$line);
	}
}

output();

close(IS);
close(OS);
print "total=$tot; noTail=$nN; polyA=$nA; polyT=$nT\n";


sub output { #slen:AorT:polylen
   return if $#aseq==-1;
   $tot++;
   my ($polylen,$polystart,$idx,$i,$AorT,@nt)=(0,0,0,0,'N',());
   my $endnt=0;
   my $seq=join("",@aseq);
   my $slen=length($seq); 
   if ($poly!=1) { #AT/A  
      $idx=rindex($seq,$As); #�������һ��As��λ��
	  $endnt=$slen-$tl-$idx;
       if ($idx!=-1 and ($tr<=0 or $idx>=$slen-$tr)) {    	  
    	  $AorT='A';
    	  $polylen=$tl;
    	  $polystart=$idx+1; # ������չ
          @nt=split(//,$seq);
    	  for ($i=$idx-1;$i>=0;$i--) {
    		if ($nt[$i] eq 'A') {
    		  $polylen++;
			  $polystart--;
    		} else {
    		  last;
    		}
    	  }
     }
   }#A

  if ($poly!=0 and $AorT ne 'A') { #AT(when no polyA)/T
     $endnt=0;
	 $idx=index($seq,$Ts);
	 if ($idx!=-1 and ($tr<=0 or $idx<=$tr-$tl)) {
		$endnt=$idx;
		$AorT='T';
		$polystart=$idx+1;
		$polylen=$tl;
		@nt=split(//,substr($seq,$idx+$tl,$slen-$idx-$tl));
	    for $i(0..$#nt) {
		  if ($nt[$i] eq 'T') {
		  $polylen++;
		  } else {
		  last;
		  }
	    }
	 }	 
   } #polyT
  
  if ($AorT eq 'A') {
	$nA++;
  } elsif ($AorT eq 'T') {
	$nT++;
  } else {
	$nN++;
	$endnt=0;
  }
  $title='>'."$slen:$AorT:$polylen:$endnt|".substr($title,1,length($title)-1)."\n";
  print OS $title.join("\n",@aseq)."\n";  
} 



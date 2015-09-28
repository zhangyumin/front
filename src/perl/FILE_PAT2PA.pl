#!/usr/bin/perl -w

#2011/5/6
#Ŀ��: ��PAT���ΪPA
#��chr/strand���в�ֺ���hash��group

#����:PAT�ļ�,�����趨chr,strand,coord��
#���:�淶��PA�ļ� (chr,strand,coord,tagnum)

#����
#chmod +x /home/wux3/soft/mypl/*.pl
#module load perl
#FILE_PAT2PA.pl -pat xx.TANi.tab.rep1_CC -pa xx.TANi.tab.rep1_CC.PA -tcols 1:2:6


#2011/10/11 
#����-sep=T/Fѡ��,��T,�������paΪ�����ļ�: .pair,.unpair
#Ŀ��: ���ڵķ�������unpair������intergenic��,�һᵼ��profile��Ť��,��Ҫ���ⲿ��PA�ֿ�����.

#2011/10/12
#�Ľ��� FILE_TAN2PAT.pl ʹ���1�����type,Ϊ pair/psudo/alone
#�����ٸ������1��,ȷ��sep file (sep=0 ������, .A/.N/.unpair for sep=2; .pair/.unpair for sep=1)

#2012-09-06
#splitFileByCols ��� nochk (������Mtr)

#2013-12-23
#����ѡ�� -l ��ʾ���ݳ�����ɸѡ����groupPAT
#����ѡ�� -z=T/F(default) ��ʾgzipѹ���ļ�
#polyseq2PA.pl�������Ϊ<seq_name0,chr1,strand2,match_start,m_end,len5,pos6,trim5,trim3>

use strict;
use Getopt::Long;

use lib '/var/www/front/src/perl';
#push(@INC,"/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi");
#ush(@INC,"/home/zym/soft/cpan/lib/perl5/");

require ('funclib.pl');

##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","pat=s","pa:s","tcols:s","sep:s","z:s","l:i");
my $USAGE=<< "_USAGE_";
Usage: 
  NOT seperate
  1) FILE_PAT2PA.pl -pat PAT -pa PA
  2) FILE_PAT2PA.pl -pat TAN -pa PA -tcols 1:2:6
  seperate pair unpair
  3) FILE_PAT2PA.pl -pat TAN -pa PA -tcols 1:2:6 -sep 1
  seperate A/N/unpair
  4) FILE_PAT2PA.pl -pat TAN -pa PA -tcols 1:2:6 -sep 2

  5) prefilter by length
  FILE_PAT2PA.pl -pat TAN -pa PA -l 30 -tcols 1:2:6:5

-h=help
-pat=input PAT file (chr,strand,coord).. or user defined tcols
-pa=default is <pat>s or <pat>s.L30; output PA file (chr,strand,coord,tagnum)
*** filenames: .A/.N/.unpair for sep=2; .pair/.unpair for sep=1
-sep=0/1(pair/unpair)/2(A/N/unpair) (default 0): if 1, then seperate unpair PAT in <pa>.unpair, <pa>.pair, 
*** seperate by the LAST COLUMN in pat file (type=Gpair/Ipair/psudo/alone) SEE: FILE_TAN2PAT.pl
-tcols=user defined flds (start with 0) for chr:strand:coord, 0:1:2 default 
-l=0(default), tag length pre filter, like L>=30nt�� 
  ��ʱ������-tcols���ó����е��У���1:2:6:5 ��ʾ����pa�ļ��ĵ�5�У���0����length
  ����pair/unpair �� AN/U�ģ���pair��AN������len�����������Ϊunpair
-z=T/F(default), if T then gzip the output pa
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'pat'};
my $patfile=$opts{'pat'};
my $pafile=$opts{'pa'};
my $tcols=$opts{'tcols'};
my $sep=$opts{'sep'};
my $len=$opts{'l'};
$len=0 if !$opts{'l'};
die "-l must with -tcols" if $len>0 and !$tcols;
my $zip=0;
$zip=1 if $opts{'z'} and $opts{'z'} eq 'T';

$pafile=$patfile.'s' if !$opts{'pa'} and !$len;
$pafile=$patfile.'s.L'.$len if !$opts{'pa'} and $len>0;


die "pat=pa" if $patfile eq $pafile;
$sep=0 if (!$sep);

die "Wrong sep" if ($sep !=0 and $sep!=1 and $sep!=2);

#��֤
die "patfile=$patfile not exists!" if !(-e $patfile);
my @cols;
if (!$tcols) {
    @cols=(0,1,2);
} else {
    @cols=split(/:/,$tcols);
    die "cols=@cols not 3 cols (l=0)\n" if $#cols!=2 and $len==0;
    die "cols=@cols not 4 cols (l>0)\n" if $#cols!=3 and $len>0;
}
my ($chrcol,$strandcol,$poscol)=($cols[0],$cols[1],$cols[2]);
my $lencol=-1;
if ($#cols==3) {
  $lencol=$cols[3];
}

#���sep=1/2,��֤�Ƿ����һ��=pair/psudo/alone
if ($sep>0) {
  open (PAT,"<$patfile");
  my $line=<PAT>;
  $line=trim($line);
  my @items=split(/\t/,$line);
  if ($items[$#items] ne 'Ipair' and $items[$#items] ne 'Gpair' and $items[$#items] ne 'psudo' and $items[$#items] ne 'alone' ) {
	die "Sep=1 or 2, but $patfile LAST column not TYPE: Gpair/Ipair/psudo/alone!";
  }
  close(PAT);
}

#��¼��Ҫ��������������
my @patfiles=($patfile);
my @pafiles=($pafile);


if (!$sep and $len>0) {
  print "filter PAT (minLen=$len)\n";
  open (PAT,"<$patfile");
  open (TMPPAT,">$patfile.tmpflt") or die "cannot write $patfile.tmpflt";
  my ($line,@items);
  my ($npass,$ndiscard)=(0,0);
  while ($line=<PAT>) {
	my @items=split(/\t/,$line);
	if ($items[$lencol]>=$len) {
	  print TMPPAT "$line\n";
	  $npass++;
	} else {
	  $ndiscard++;
	}  
  }
  close(PAT);
  close(TMPPAT);
  print ">>>>> $npass pass; $ndiscard discard (minLen=$len)\n";
  @patfiles=("$patfile.tmpflt");
  @pafiles=($pafile);
} elsif ($sep==1) {#���sep,���Ƚ�PAT��Ϊpair��unpair
  my $opair=$patfile.'.pair';
  my $ounpair=$patfile.'.unpair';
  my ($np,$nu)=(0,0);
  print "seperate PAT to .pair and .unpair (minLen=$len)\n";
  open (PAT,"<$patfile");
  open (PAIR,">$opair");
  open(UNPAIR,">$ounpair");
  my ($line,@items);
  while ($line=<PAT>) {
    $line=trim($line);
	if ($line=~/pair$/) {
	  if (!$len) {
	    print PAIR "$line\n";
	    $np++;
	  } else { #2013-12-24 -lѡ�� �����>=l�����pair
        my @items=split(/\t/,$line);
		if ($items[$lencol]>=$len) {
	      print PAIR "$line\n";
	      $np++;
		} else {
	      print UNPAIR "$line\n";
	      $nu++;
		}
	  }
	} else {
	  print UNPAIR "$line\n";
	  $nu++;
	}
  }#while
  close(PAT);
  close(PAIR);
  close(UNPAIR);
  print ">>>>> $np pair; $nu unpair\n";
  @patfiles=($opair,$ounpair);
  @pafiles=($pafile.'.pair',$pafile.'.unpair');

} elsif ($sep==2) { #T/A/unpair
  my ($na,$nn,$nu)=(0,0,0);
  my $oa=$patfile.'.A';
  my $on=$patfile.'.N';
  my $ou=$patfile.'.unpair';
  print "seperate PAT to .T, .N and .unpair (minLen=$len)\n";
  open (PAT,"<$patfile");
  open (A,">$oa");
  open (N,">$on");
  open(UNPAIR,">$ou");
  my $line;
  while ($line=<PAT>) {
	$line=trim($line);
   if (!$len) {
		if ($line=~/N\t[A-Z]pair$/) {
		  print N "$line\n";
		  $nn++;
		} elsif ($line=~/A\t[A-Z]pair$/) {
		  print A "$line\n";
		  $na++;
		} else {
		  print UNPAIR "$line\n";
		  $nu++;
		}
   } else { # l>0
        my @items=split(/\t/,$line);
		if ($items[$lencol]<$len) {
		  print UNPAIR "$line\n";
		  $nu++;	
        } elsif ($line=~/N\t[A-Z]pair$/) {
		  print N "$line\n";
		  $nn++;
		} elsif ($line=~/A\t[A-Z]pair$/) {
		  print A "$line\n";
		  $na++;
		}else {
		  print UNPAIR "$line\n";
		  $nu++;
		}
   } # if l
  } #while
  close(PAT);
  close(A);
  close(N);
  close(UNPAIR);
  print ">>>>> $na pairA; $nn pairN; $nu unpair\n";
  @patfiles=($oa,$on,$ou);
  @pafiles=($pafile.'.A',$pafile.'.N',$pafile.'.unpair');
}


if ($len) {
  pop(@cols);
}

for my $ff(0..$#patfiles) {

my $patff=$patfiles[$ff];
my $paff=$pafiles[$ff];

#��chr/strand�ָ��ļ�
#print "Split $patff by chr+strand\n";
my $files=splitFileByCols($patff,join(':',@cols[0..1]),'',1);
my $totFn=$#$files+1;
print "  $totFn parts\n";

#��ÿ���ļ���ϣ����,�����$paff��
print "PAT2PA by Hash...\n";
my %hash;
my ($cnt1,$cnt2,$tot1,$tot2)=(0,0);
my ($k, $v);
open (OO,">$paff");
for my $i(0..$#$files) {
  my $f=$files->[$i][0];
  my $desc=$files->[$i][1];
  $cnt1=$cnt2=0;
  print " part.$i: $desc\n";
  open(PART,"<$f") or die "cannot read $f ($desc)\n";
  my ($line,@items);
  while ($line=<PART>) {
	$line=trim($line);
	@items=split(/\t/,$line);
    $hash{join("\t",@items[@cols])}++; #ע����@items
	$cnt1++;
  }
  close(PART);
  while (($k, $v) = each(%hash)) {
    print OO "$k\t$v\n";
	$cnt2++;
  }
  print "  $cnt1 PAT --> $cnt2 PA\n";
  unlink($f);
  %hash=();
  $tot1+=$cnt1;
  $tot2+=$cnt2;
}
close(OO);
print ">>>>> total $tot1 PAT to $tot2 PA\n";

}

if (scalar(@patfiles)>1) {
 foreach my $p (@patfiles) {
  unlink($p);
 }
}

if ($patfiles[0] eq "$patfile.tmpflt") {
  unlink($patfiles[0]);
}

if ($zip) {
 foreach my $p (@pafiles) {
   system "gzip $p";
 }
}


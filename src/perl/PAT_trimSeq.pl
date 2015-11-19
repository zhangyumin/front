#!/usr/bin/perl -w
#\B0\B4polyA_site_in_genome\BA\CDstrand\BD\D8ȡλ\B5\E3\D0\F2\C1\D0
#\CA\E4\C8\EB\B1\ED: test \BB\F2 test chromosome
#\CA\E4\B3\F6\CEļ\FE: intron_tagsnum2_400nts \BB\F2 _C_intron_400nt
##\D0\F2\C1б\EA\CC\E2:\D7ֶ\CE\C3\FB[\D7ֶ\CEֵ];
##1) \C9\E8\D6\C3Ҫȡ\B5\C4uPA\B5\C4\CC\F5\BC\FE:
##   1)by=grp_ftr,\D4\F2\B6\C1ȡ4\D6\D6grp_ftr, \D4\CA\D0\ED\C9\E8\D6\C3utag,grp_ftr
##   2)by=class, \D4\F2\B6\C1ȡ4\D6\D6class,\D4\CA\D0\ED\C9\E8\D6\C3class,grp_ftr
##   \B6\D4\D3\DAÿ\CC\F5chr
##2) \C8\F4strand=+ ȡ\C9\CF\D3\CE300,1,\CF\C2\D3\CE99, 
##	 \C8\F4strand=- ȡ\C9\CF\D3\CE99,1,\CF\C2\D3\CE300,\D4ٷ\B4ת\BB\A5\B2\B9
##3) \C8\F4\CEļ\FE\D2Ѵ\E6\D4\DA\D4\F2׷\BC\D3.

#(\D7\EE\BA\F3λ\B5\E3\D4ڵ\DA301)

#  \D0޸\C4\C0\FAʷ
#  20091128: \CEļ\FE\C3\FB\D6\D0\CC\ED\BC\D3_xxs\B1\EDʾ\D0\F2\C1\D0\CA\FD,\C8\E7test_painfos_20_grp_three_prime_UTR_tagsnum2_400nt_154s
#  2009/12/28: \D4\F6\BC\D3by all\B5\C4ѡ\CF\EE,\BF\C9\D2Բ\BB\B7\D6grp_ftr\BB\F2class\CA\E4\B3\F6ȫ\B2\BF\D0\F2\C1\D0

#2010/6/4
#\D4\F6\BC\D3-from/-toѡ\CF\EE,ʹ\BF\C9\D2Խ\D8ȡ\C8\CE\D2ⷶΧ. \C8\E7 1~100\BD\D8ȡPA\CF\C2\D3\CE1~100nt,\B2\BB\BA\ACPA. -100~-1,\BD\D8ȡ\C9\CF\D3\CE. Ĭ\C8\CF-300~99,\BD\D8ȡ\C9\CF\D3\CE300,\CF\C2\D3\CE99,\D4ٰ\FC\BA\ACPA

#2011/3/24
#\B4\F3\D5\FB\B8ģ\AC\D3\C3byfld,byval,condȡ\B4\FAԭ\C0\B4\B5\C4ѡ\CF\EE
#\D4\CA\D0\ED\B6\E0\B8\F6\D7ֶ\CE\D7\E9\BA\CF\CA\E4\B3\F6\A3\AC\C8\E7-byfld grp_ftr:class:strand
#\D0޸\C4\CA\E4\B3\F6\D0\F2\C1е\C4\CEļ\FE\C3\FB\BC\B0\B1\EA\CC⣬ֻ\D3\C3>chr,strand,polyA_pos\B1\EDʾ,\C8\E7 >Chr1;-;12453928

## ԭpl\B3\CC\D0\F2
#0) PAT_trimseq_org.pl -tbl test -by grp_ftr  -conf dbconf_arabpaper.xml
#1) PAT_trimseq_org.pl -tbl test -by grp_ftr -ftr 3UTR -n 2  -conf dbconf_arabpaper.xml
#2) PAT_trimseq_org.pl -tbl test -by class  -conf dbconf_arabpaper.xml
#3) PAT_trimseq_org.pl -tbl test -by class_ftr -class M -ftr 3UTR  -conf dbconf_arabpaper.xml
#4) PAT_trimseq_org.pl -tbl test -by class_ftr -class M  -conf dbconf_arabpaper.xml
#5) PAT_trimseq_org.pl -tbl test -by class_ftr -ftr 3UTR  -conf dbconf_arabpaper.xml
#6) PAT_trimseq_org.pl -tbl test -by class_ftr -ftr intergenic  -conf dbconf_arabpaper.xml
#7) PAT_trimseq_org.pl -tbl test -by  all -conf dbconf_arabpaper.xml
#8) PAT_trimseq_org.pl -tbl test -by class -class M -from 1 -to 100 -conf dbconf_arabpaper.xml

## \B6\D4Ӧ\B5\C4\D0\C2pl\B3\CC\D0\F2
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
#\D4\F6\BC\D3flds:chr:strand:coordѡ\CF\EE

#2012-08-28
#\D4\F6\BC\D3sufѡ\CF\D4\CA\D0\ED\BC\D3һ\B8\F6\BA\F3׺\B1\EAʶ

#2012-09-11 Mtr\D0޸\C4 -- \D0\E8Ҫlongchr

#2013-12-22
#\D4\F6\BC\D3opathѡ\CFĬ\C8\CFΪgetTmpPath)

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
  if ($#byvals<$#byflds) { #\C8\F4 -byvals 3utr::\A3\AC\D4\F2\D7Զ\AF\B2\B9\C9\CF\C1\BD\B8\F1\BFյ\C4
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

#\D7ֶ\CE\C3\FB,\D3\C3\D3\DA\CA\E4\B3\F6>title
#$sth = $dbh->prepare("desc $intbl") or die $dbh->errstr;
#$sth->execute or die "can't execute the query: $sth->errstr\n";
#my($flds)= $sth->fetchall_arrayref();

#ȷ\B6\A8\CA\E4\B3\F6\CEļ\FE\D0ź\C5:filesInfo[suffix,seqnum]
my ($suffix);
my $fldEnum; #\B6\FEά\BE\D8\D5\F3\A3\ACÿ\D0д\E6\B7\C5һ\B8\F6fld\B5\C4ö\BE\D9ֵ
my $nf=1; #\CA\E4\B3\F6\CEļ\FE\BC\C6\CA\FD
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

my $filesInfo; #\B4\E6\B7\C5\CEļ\FE\C3\FB\A3\ACSQL\CC\F5\BC\FE\A3\AC\D0\F2\C1\D0\CC\F5\CA\FD
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
  my $permute=getPermute($fldEnum); #\B5õ\BD\C8\E7 ([3UTR,C],[3UTR,M]..)
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

#\B0\B4ÿchr \B1\E9\C0\FApolyA_site_in_genome,trim400nt
#\B4\A6\C0\EDlongchr
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
    #\B6\C1ȡȾɫ\CC\E5seq
	print "$chr: ";
	my $sql="select seq from $chrtbl where title=\'$chr\'";
	my ($fastbl,$rv)=execSql($dbh,$sql);
	#print "$sql; $fastbl->[0][1]\n";
    next if $#{$fastbl}<0;
	$chrfas=$fastbl->[0][0];
	$fastbl=[];
	$curFileIdx=0;
	#\B6\C1ȡ\C2\FA\D7\E3\CC\F5\BC\FE\B5\C4uPA
	for my $i (0..$#$filesInfo) {
	  $curFileIdx=$i;
      $sql="select * from $intbl where $chrfld=\'$chr\' $filesInfo->[$i][1]";
	  #print "\n$sql\n";
	  my ($tbl,$rv)=execSql($dbh,$sql);
	  $rv=trimAll($tbl); #\B1\E9\C0\FA\D5\FB\B8\F6\B1\ED\C0\B4\BD\D8ȡ\D0\F2\C1\D0
	  $tbl=[];
	  print "$rv+";
	}
	print "\n";
} #foreach

#\C8\E7\B9\FB\D3ж\CCchr
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
	  #\B6\D4ÿ\B8\F6chr\B1\E9\C0\FA
	  print "  $filesInfo->[$i][1]: ";
	  my $idxsChr=getIntervals($tbl,$chrFld,1);
	  #print "\n".mtx2str($idxsChr)."\n";
	  for my $j(0..$#$idxsChr) {
		$chrfas=$hashfas{$idxsChr->[$j][2]};
        $num+=trimAll($tbl,$idxsChr->[$j][0],$idxsChr->[$j][1]); #\B1\E9\C0\FA\D5\FB\B8\F6\B1\ED\C0\B4\BD\D8ȡ\D0\F2\C1\D0
	  }
	  print "$num\n";	
	}
} #long

#\D0޸\C4\CEļ\FE\C3\FB,\D4\F6\BC\D3\D0\D0\CA\FD _xxxs
my ($newf);
print "Output FILES in $opath: \n";
for my $i (0..$#{$filesInfo}) {
  $newf="$intbl$filesInfo->[$i][0]";
  rename("$opath$intbl"."$filesInfo->[$i][0]","$opath$newf");
  print "  $newf\n";
}

$dbh->disconnect();

#############################################################################
#  trimAll(): \B1\E9\C0\FA\D5\FB\B8\F6\B1\ED\C0\B4\BD\D8ȡ\D0\F2\C1\D0
#  useage: trimAll();    
#  ˵\C3\F7: ʹ\D3ú\CD\D0޸\C4ȫ\BEֲ\CE\CA\FD (chrfas,curFileIdx,num...)
#############################################################################
sub trimAll {
	my ($tbl,$is,$ie)=@_;
	$is=0 if !defined($is) or $is<0;
	$ie=$#$tbl if !defined($ie) or $ie>$#$tbl;
	my $rv=$ie-$is+1;
	return(0) if ($rv<=0 or $chrfas eq '');
	#\BC\C7¼\D0\F2\C1\D0\CA\FD
	$filesInfo->[$curFileIdx][2]+=$rv;

	#\B6\D4ÿ\B8\F6polyA_site,\BD\D8ȡ\D0\F2\C1\D0
	my @seqs=();
    for my $i ( $is .. $ie ) {
      my $p=$tbl->[$i][$posFld];
      my $s=$tbl->[$i][$strandFld];
      
	  #\D0\F2\C1б\EA\CC\E2\D0\D0
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
    
	#׷\BC\D3\CA\E4\B3\F6\B5\BD\CEļ\FE 
    my $suffix=$filesInfo->[$curFileIdx][0];
    my $ofile="$opath$intbl"."$suffix";
	open(FH, ">>$ofile");	
	for my $i ( 0 .. $#seqs ) {
      print FH "@seqs[$i]";
	  print FH "\n";	
	} 
	close(FH);  
	@seqs=();
	return($rv); #\B7\B5\BB\D8\D0\D0\CA\FD
}



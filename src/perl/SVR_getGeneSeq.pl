#!/usr/bin/perl -w
#修改至 UTIL_getGeneSeq.pl

#2015/9/30
#用于server，为防止不同gff的基因的ftr不一定是gene，这里先通过 $rv=geneFromGff($dbh,'t_gff9','t_gff9_codon_genes',1)得到gene table
#再截取序列
#增加short chrs的支持


use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;

use lib '/var/www/front/src/perl';
require ('funclib.pl');

##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","gff=s","ogtbl:s","ofile:s","chrtbl:s","conf=s");
my $USAGE=<< "_USAGE_";
Usage: 
  SVR_getGeneSeq.pl -gff t_arab_gff -ogtbl t_arab_gff_genes -ofile F:/t_arab_gff_genes.fa -chrtbl arab_common.t_chr9 -conf dbconf_dbserver.xml

-h=help
-gff=gff table (small table) (HAS columns: chr,strand,ftr,gene,ftr_start,ftr_end)
-ogtbl=output gene table (HAS columns: chr,strand,ftr,gene,ftr_start,ftr_end)
-ofile=output gene sequence .fa
-chrtbl=chromosome table (if not provided, will check conf file)
-conf=db config
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'gff'}||!$opts{'conf'};
my $ogtbl=$opts{'ogtbl'};
my $gfftbl=$opts{'gff'};
my $ofile=$opts{'ofile'};
my $chrtbl=$opts{'chrtbl'};

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my $conf=$opts{'conf'};
my $dbh;
if (!$chrtbl) {
  ($dbh,$chrtbl)=connectDB($conf,1,'chromosome');
} else {
  ($dbh)=connectDB($conf,1);
}
die "chrtbl or chromosome not in xml when ofile" if !$chrtbl and $ofile;

die "at least ofile or ogtbl" if !$ogtbl and !$ofile;


#############################################################################
#  ANTI                                                
#############################################################################
if (!$ogtbl) {
  $ogtbl='xx_getGeneSeq_tmp'
}

print "get gene coordinate from gfftbl\n";
my $rv=geneFromGff($dbh,$gfftbl,$ogtbl,0);

if (!$ofile) {
  exit;
}

print "read chr to hash\n";
#直接将chr读入hash
my $sql="select title,seq from $chrtbl order by title";
my ($chrseq,$rv)=execSql($dbh,$sql);
print "$rv\tchrs\n";
my %hashChr=();
for my $i(0..$#$chrseq) {
  $hashChr{$chrseq->[$i][0]}=$chrseq->[$i][1];
  $chrseq->[$i][1]='';
}
$chrseq=[];

print "output gene seq.fa\n";
#遍历
open(OO,">$ofile") or die "cannot write $ofile\n";
$sql="select gene,chr,strand,ftr_start,ftr_end from $ogtbl order by chr,strand";
my ($genes,$rv)=execSql($dbh,$sql);
print "$rv\tgenes\n";
for my $i(0..$#$genes) {
  my $len=$genes->[$i][4]-$genes->[$i][3]+1;
  my $seq=substr($hashChr{$genes->[$i][1]},$genes->[$i][3]-1,$len);
  my $title='>'.$genes->[$i][0];
  if ($genes->[$i][2] eq '-') {
     $seq=reverseAndComplement($seq,1,1);   
  }
  print OO "$title\n$seq\n";
}

if ($ogtbl eq 'xx_getGeneSeq_tmp') {
  $dbh->do("drop table if exists $ogtbl") or die;
} else {
  $dbh->do("create index idx_gene on $ogtbl(gene)");
}

close(OO);


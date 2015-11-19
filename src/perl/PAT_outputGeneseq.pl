#!/usr/bin/perl -w
use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;

use lib '/var/www/front/src/perl';
require ('funclib.pl');

#2009/12/22
#���gene seq, ��-���,ȡ������

#2010/12/18
#����ѡ�� gntbl,gfftbl �����ṩgene����gff������seq
#ԭѡ�� genetbl��Ϊgstbl

#2011/3/16
#����ѡ�� -ftr �������gene�ڸ���ftr�����У�ƴ�Ӻ�
#��������磺 >AT1G01020;Chr1-;all_CDS

#2011/3/22
#���Ӷ� gfftbl���жϣ��������ftr�У������� ftr='gene'������

#2011/3/22
#�ڱ���������  start-end
#�� UTIL_countIP��ȡgene����ĩ

#2013-01-25
#��ԭ����start/end��Ϊftr_start/end
#������ ���� intron... ���еĹ���

#�����ṩanno��condѡ�����anno�����ftr����ֹ���������
#gntbl��annotblֻ��дһ�������
#���gntbl ��ԭ���ķ�ʽ��� (cond���ڹ���gntbl)
#���annotbl������� annotbl, cond ��ȡ����Ҫ���е��У���ÿ��һ���������

#2013-05-25
#���� -CDS ѡ��������CDS�ĳ��ȣ�����seq��

##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","gntbl:s","gstbl:s","gfftbl:s","ftr:s","annotbl:s","cond:s","ofile:s","seqlen:s","conf=s");
my $USAGE=<< "_USAGE_";
File:    PAT_outputGeneseq.pl
Require: XML.chromosome
Usage: 
  # output geneseq already in a gstbl
  1) PAT_outputGeneseq.pl -gstbl t_gene_seq -ofile e:/t_gene_seq  -conf dbconf_arabpat_raw.xml
  # output gene seq by referring a gff table in a gntbl (here gene is unit: t_gff9_org_all_genes)
  2) PAT_outputGeneseq.pl -gntbl genes -gfftbl arab_common.t_gff9_org_all_genes -ofile e:/t_gene_seq  -conf dbconf_arabpaper.xml
  # output join-ftr gene seq by referring a gfftbl (here ftr is unit: t_gff9_org)
  3) PAT_outputGeneseq.pl -gntbl genes -gfftbl arab_common.t_gff9_org -ofile e:/t_gene_seq -ftr CDS -conf dbconf_arabpaper.xml
  # gffall table (PAT_outputGeneseq.pl will filter gene for U)
  4) PAT_outputGeneseq.pl -gntbl genes -gfftbl arab_common.t_gff9_org_all -ofile e:/t_gene_seq  -conf dbconf_arabpaper.xml
  # output intron sequence, one line one intron (ע�������conf��arabtair10�Ա�ȡchr��������mtr_newpac��)
   PAT_outputGeneseq.pl -annotbl mtr_newpac.orth_pmpac_ath_mtr_introncons -cond "ftr='intron' and consNum>0 and pac_from='at'" -ofile e:/t_intron_seq  -conf dbconf_arabtair10.xml
  
  # ���gene��CDS���ȣ�MTR���£� ( -seqlen T )
  PAT_outputGeneseq.pl -seqlen T -ftr CDS -gntbl mtr_newpac.t_pubwt_pac -gfftbl arab_tair10.t_gff10_ae120pm2k -cond "ftr='CDS'" -ofile "e:/t_pubwt_pac.CDS.len"  -conf dbconf_arabtair10.xml

  # output intergenic seq
  PAT_outputGeneseq.pl -annotbl t_gff7_ae_all -cond "ftr like 'inter%'" -ofile f:/t_gff7_ae_all.igt.fa  -conf dbconf_ricegff7.xml

-h=help
-gstbl=t_gene_seq for output! (HAS columns: gene,chr,strand,gene_type,ftr_start,ftr_end,seq)
-gntbl=gene tbl <gene,...> -- ����distinct���������Զ������cond��ɸѡdistinct gene
-gfftbl=gene_gff table, set with -gntbl (gene,chr,strand,gene_type,ftr_start,ftr_end)+ftr/(chr,strand,gene,ftr,ftr_start,ftr_end) when [ftr]
-ofile=output file. donot use ''!
-ftr=CDS/intron/3UTR.. (ftr in gff table to output seqeunce of a gene, if multiple CDS..intron, then join together first)
-annotbl=ftr tbl <chr,strand,ftr_start,ftr_end>, one row one ftr (������PAC��.pl���Զ����� distinct(chr,strand,ftr_start,ftr_end)ȡΨһ��
* XOR with gntbl
-seqlen=T/F(default) if T, output length of seq (���� ftr �ṩʱ��Ч)
-cond=SQL to filter gntbl or annotbl
-conf=db conf xml
_USAGE_

#############################################################################
#  invoke the program                               
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'conf'}||!$opts{'ofile'};

my($ofile)=$opts{'ofile'};
my($otbl)=$opts{'otbl'};
my($gstbl)=$opts{'gstbl'};
my($gntbl)=$opts{'gntbl'};
my($gfftbl)=$opts{'gfftbl'};
my($useftr)=$opts{'ftr'};
my($annotbl)=$opts{'annotbl'};
my($cond)=$opts{'cond'};
my($seqlen)=0;
$seqlen=1 if $opts{'seqlen'} eq 'T';

die "seqlen=1 but ftr not provided" if ($seqlen==1 and $useftr eq '');

die "gntbl must with gfftbl" if ($gntbl and !$gfftbl);
die "gstbl or gntbl" if ($gstbl and $gntbl);
die "ftr and gntbl" if (!$gntbl and $useftr);

die "gntbl XOR annotbl" if ($gntbl and $annotbl);
die "annotbl not with (gfftbl,gstbl,ftr)" if ($annotbl and ($gfftbl or $gstbl or $useftr));
die "annotbl must with ofile" if ($annotbl and !$ofile);

my $TYPE='ANNO';
if ($gstbl) {
  $TYPE='GS';
} elsif ($gntbl and !$useftr) {
  $TYPE='GENE';
} elsif ($gntbl and $useftr) {
  $TYPE='FTR';
}

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my $conf=$opts{'conf'};
my($dbh,$chrtbl)=connectDB($conf,1,'chromosome');
die "chromosome not in xml" if !$chrtbl;

#############################################################################
#  DO DB                                                 
#############################################################################
my ($chrFld,$strandFld,$gFld,$sFld,$eFld)=(0,1,2,3,4); #type=ftr
my ($tbl,$fas, $rv);

if ($TYPE eq 'GS' or $TYPE eq 'GENE') {
	my($sql,$sth,$rv,$i,$row,$gseqtbl);

	if ($gstbl) {
	  $gseqtbl=$gstbl;
	} else { #ȡ��gseq (gfftbl+seq)
	print "create gene seq table...\n";
    #����ʱ��gene��ȡ��distinct(gene)
	my $tmpgene="t_tmp_gene_xxx";
    $dbh->do("drop table if exists $tmpgene") or die;
	my $sql="create table $tmpgene select distinct(gene) from $gntbl";
	if ($cond) {
      $sql="create table $tmpgene select distinct(gene) from $gntbl where $cond";
	}
    $dbh->do($sql) or die;
    $dbh->do("create index idx_gene on $tmpgene(gene)") or die;

	$gseqtbl=$gntbl.'_seq_tmpX';
	$dbh->do("drop table if exists $gseqtbl") or die;
    $sql="create table $gseqtbl ";
	$sql.="select a.*,substr(d.seq,a.ftr_start,a.ftr_end-a.ftr_start+1) seq ";
    $sql.="from $tmpgene b,$gfftbl a,$chrtbl d ";
    if (getFldsIdx($dbh,$gfftbl,'ftr')!=-1) {	#ֻ���˳�gff����ftr=gene����
	  $sql.="where a.ftr=\'gene\' and b.gene=a.gene and a.chr=d.title ";
	} else {
      $sql.="where b.gene=a.gene and a.chr=d.title ";
    }
	$sql.="order by gene"; 
	$dbh->do($sql) or die;
    $dbh->do("drop table if exists $tmpgene") or die;
	}

	#ȡ����������
	print "Select gene seq...";
	$sql="select * from $gseqtbl";
	($tbl,$rv)=execSql($dbh,$sql);
	print "$rv rows.\n";

	#����ֶε��ֶ��±�
	my ($chrFld,$strandFld,$gFld,$sFld,$eFld,$gtFld,$seqFld,$all)=getFldsIdx($dbh,$gseqtbl,('chr','strand','gene','ftr_start','ftr_end','gene_type','seq'));
	die "chr,strand,gene,ftr_start,ftr_end,gene_type,seq not in $gseqtbl" if $all==-1;

	my($gseq);
	print "Output gene seq...\n";
	open (OF,">$ofile") || die "Cannot open $ofile";
	#����ÿ������
	for $i(0..$#$tbl) {
	  $row=$tbl->[$i];
	  print OF '>'."$row->[$gFld];$row->[$chrFld]$row->[$strandFld];$row->[$gtFld];$row->[$sFld]~$row->[$eFld]\n";
	  $gseq=$row->[$seqFld];
	  #-strand:��seqȡ������
	  if ($row->[$strandFld] eq '-') {
	   $gseq=reverseAndComplement($gseq,1,1);   
	  }
	  print OF "$gseq\n"
	}
	close(OF);

	if ($gntbl) {
	  $dbh->do("drop table if exists $gseqtbl") or die;
	}
} # type=GS/GENE

elsif ($TYPE eq 'FTR') {
  open (OF,">$ofile") || die "Cannot open $ofile";
  my ($sql,$sth,$rv,$fastbl);
  $dbh->do("create index idx_gene on $gfftbl(gene)") or print "idx_gene already in $gfftbl\n";
  $dbh->do("create index idx_gene on $gntbl(gene)") or print "idx_gene already in $gntbl\n";
  my (@chrs);
  if (getFldsIdx($dbh,$gntbl,'chr')==-1) { 
	@chrs=getFldValues($dbh,"SELECT distinct(title) chr FROM $chrtbl order by title",0);
  } else { #���gntbl����chr�У��������������chr����Ŀ
    if ($cond) {
	  (@chrs)=getFldValues($dbh,"SELECT distinct(chr) chr FROM $gntbl where ($cond) order by chr",0);
    } else {
	  (@chrs)=getFldValues($dbh,"SELECT distinct(chr) chr FROM $gntbl order by chr",0);
	}
  }
  foreach  my $chr (@chrs) {
    #��ȡȾɫ��seq
	my ($fastbl,$rv)=execSql($dbh,"select seq from $chrtbl where title=\'$chr\'");


    next if $#{$fastbl}<0;
	$fas=$fastbl->[0][0];

	#��ȡ����gff
    my $where='';
	if ($cond) {
	  $where=" ($cond) and ";
	}
	my $sql="select a.chr,a.strand,a.gene,a.ftr_start,a.ftr_end from $gfftbl a where a.chr=\'$chr\' and a.ftr=\'$useftr\' and exists (select * from $gntbl b where $where a.gene=b.gene) order by a.ftr_start";
	#print "\n\n$sql\n\n";
	($tbl,$rv)=execSql($dbh,$sql);

    print "$chr $rv rows\n";
	next if $#$tbl<0;
    #��ÿ��gene�ڱ���ftr
	my($lastIdx,$curG,$startIdx,$endIdx);
	$lastIdx=$#{$tbl};
	next if ($#{$tbl}<0);
	$curG=$tbl->[0][$gFld];
	$endIdx=$startIdx=0;
	if ($lastIdx==0) {
	  doThisFragment($startIdx,$lastIdx);
	} else {
	  for my $i (1..$lastIdx) {
		if ($tbl->[$i][$gFld] eq $curG) {    
			$endIdx++;  			
		}  
		else {	  
		  doThisFragment($startIdx,$endIdx);	  
		  $startIdx=$endIdx+1;
		  $endIdx++; 
		  $curG=$tbl->[$i][$gFld];	
		}
		if ($i==$lastIdx) {
		  doThisFragment($startIdx,$lastIdx);
		  last;
		}
	  }	#for $i	  
	}
  } #chr
  close(OF);

} #type=FTR

elsif ($TYPE eq 'ANNO') {
    #����ʱ��ȡ��distinct(chr,strand,ftr_start,ftr_end)
	print "annotbl=$annotbl\ncond=$cond\n";
	my $tmpgene="t_tmp_gene_xxx";
    $dbh->do("drop table if exists $tmpgene") or die;
	my $sql="create table $tmpgene select chr,strand,ftr_start,ftr_end from $annotbl group by chr,strand,ftr_start,ftr_end";
	if ($cond) {
      $sql="create table $tmpgene select chr,strand,ftr_start,ftr_end from $annotbl where $cond group by chr,strand,ftr_start,ftr_end";
	}
    my $rv=$dbh->do($sql) or die;
	print "$rv\tregions\n";

	my $gseqtbl=$annotbl.'_seq_XXX';
	$dbh->do("drop table if exists $gseqtbl") or die;
    $sql="create table $gseqtbl ";
	$sql.="select b.*,substr(d.seq,b.ftr_start,b.ftr_end-b.ftr_start+1) seq ";
    $sql.="from $tmpgene b, $chrtbl d where b.chr=d.title ";
	$rv=$dbh->do($sql) or die;
	print "$rv\tseqs\n";
    $dbh->do("drop table if exists $tmpgene") or die;

	#ȡ����������
	$sql="select * from $gseqtbl";
	($tbl,$rv)=execSql($dbh,$sql);

	#����ֶε��ֶ��±�
	my ($chrFld,$strandFld,$sFld,$eFld,$seqFld)=getFldsIdx($dbh,$gseqtbl,('chr','strand','ftr_start','ftr_end','seq'));

	my($gseq);
	open (OF,">$ofile") || die "Cannot open $ofile";
	#����ÿ������
	for my $i(0..$#$tbl) {
	  my $row=$tbl->[$i];
	  print OF '>'."$row->[$chrFld]$row->[$strandFld];$row->[$sFld]~$row->[$eFld]|\n"; #ĩβ����|��������IMEter�����
	  my $gseq=$row->[$seqFld];
	  #-strand:��seqȡ������
	  if ($row->[$strandFld] eq '-') {
	   $gseq=reverseAndComplement($gseq,1,1);   
	  }
	  print OF "$gseq\n"
	}
	close(OF);

	$dbh->do("drop table if exists $gseqtbl") or die;
} # type=ANNO


$dbh->disconnect();

#############################################################################
#  doThisFragment($startIdx,$endIdx);
#  use global vars:  
#############################################################################
sub doThisFragment {
  my ($si,$ei)=@_;
  #�������ftr
  my ($row,$gseq); 
  $row=$tbl->[$si];
  if (!$seqlen) { #2013-05-25
	  $gseq='';
  print OF '>'."$row->[$gFld];$row->[$chrFld]$row->[$strandFld];all_$useftr;$row->[$sFld]~$row->[$eFld]\n";
		for my $i($si..$ei) {
		  $row=$tbl->[$i];
		  $gseq.=substr($fas,$row->[$sFld]-1,$row->[$eFld]-$row->[$sFld]+1);
		}
	  #-strand:��seqȡ������
	  if ($row->[$strandFld] eq '-') {
		 $gseq=reverseAndComplement($gseq,1,1);   
	  }
	  print OF "$gseq\n"  
  } else { #��� seq len
  print OF "$row->[$gFld]\t";
	  $gseq=0;
		for my $i($si..$ei) {
		  $row=$tbl->[$i];
		  $gseq+=($row->[$eFld]-$row->[$sFld]+1);
		}
	  print OF "$gseq\n"  
  }		

}


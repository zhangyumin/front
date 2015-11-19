#!/usr/bin/perl -w
use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;

use lib '/var/www/front/src/perl';
require ('funclib.pl');

#2009/12/22
#输出gene seq, 对-向的,取反互补

#2010/12/18
#增加选项 gntbl,gfftbl 允许提供gene名及gff表，导出seq
#原选项 genetbl改为gstbl

#2011/3/16
#增加选项 -ftr 允许输出gene内给定ftr的序列（拼接后）
#输出标题如： >AT1G01020;Chr1-;all_CDS

#2011/3/22
#增加对 gfftbl的判断，如果含有ftr列，则增加 ftr='gene'的条件

#2011/3/22
#在标题中增加  start-end
#供 UTIL_countIP读取gene的首末

#2013-01-25
#把原来的start/end改为ftr_start/end
#添加输出 单个 intron... 序列的功能

#允许提供anno表，cond选项，根据anno表里的ftr的起止，输出序列
#gntbl和annotbl只能写一个，如果
#如果gntbl 则按原来的方式输出 (cond用于过滤gntbl)
#如果annotbl，则根据 annotbl, cond 来取得需要序列的行，再每行一条序列输出

#2013-05-25
#增加 -CDS 选项，允许输出CDS的长度（而非seq）

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
  # output intron sequence, one line one intron (注意这里的conf是arabtair10以便取chr；表是在mtr_newpac中)
   PAT_outputGeneseq.pl -annotbl mtr_newpac.orth_pmpac_ath_mtr_introncons -cond "ftr='intron' and consNum>0 and pac_from='at'" -ofile e:/t_intron_seq  -conf dbconf_arabtair10.xml
  
  # 输出gene的CDS长度（MTR文章） ( -seqlen T )
  PAT_outputGeneseq.pl -seqlen T -ftr CDS -gntbl mtr_newpac.t_pubwt_pac -gfftbl arab_tair10.t_gff10_ae120pm2k -cond "ftr='CDS'" -ofile "e:/t_pubwt_pac.CDS.len"  -conf dbconf_arabtair10.xml

  # output intergenic seq
  PAT_outputGeneseq.pl -annotbl t_gff7_ae_all -cond "ftr like 'inter%'" -ofile f:/t_gff7_ae_all.igt.fa  -conf dbconf_ricegff7.xml

-h=help
-gstbl=t_gene_seq for output! (HAS columns: gene,chr,strand,gene_type,ftr_start,ftr_end,seq)
-gntbl=gene tbl <gene,...> -- 不用distinct，程序中自动会根据cond来筛选distinct gene
-gfftbl=gene_gff table, set with -gntbl (gene,chr,strand,gene_type,ftr_start,ftr_end)+ftr/(chr,strand,gene,ftr,ftr_start,ftr_end) when [ftr]
-ofile=output file. donot use ''!
-ftr=CDS/intron/3UTR.. (ftr in gff table to output seqeunce of a gene, if multiple CDS..intron, then join together first)
-annotbl=ftr tbl <chr,strand,ftr_start,ftr_end>, one row one ftr (可以是PAC表，.pl中自动会用 distinct(chr,strand,ftr_start,ftr_end)取唯一）
* XOR with gntbl
-seqlen=T/F(default) if T, output length of seq (仅对 ftr 提供时有效)
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
	} else { #取得gseq (gfftbl+seq)
	print "create gene seq table...\n";
    #建临时的gene表取得distinct(gene)
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
    if (getFldsIdx($dbh,$gfftbl,'ftr')!=-1) {	#只过滤出gff表中ftr=gene的行
	  $sql.="where a.ftr=\'gene\' and b.gene=a.gene and a.chr=d.title ";
	} else {
      $sql.="where b.gene=a.gene and a.chr=d.title ";
    }
	$sql.="order by gene"; 
	$dbh->do($sql) or die;
    $dbh->do("drop table if exists $tmpgene") or die;
	}

	#取得所有数据
	print "Select gene seq...";
	$sql="select * from $gseqtbl";
	($tbl,$rv)=execSql($dbh,$sql);
	print "$rv rows.\n";

	#相关字段的字段下标
	my ($chrFld,$strandFld,$gFld,$sFld,$eFld,$gtFld,$seqFld,$all)=getFldsIdx($dbh,$gseqtbl,('chr','strand','gene','ftr_start','ftr_end','gene_type','seq'));
	die "chr,strand,gene,ftr_start,ftr_end,gene_type,seq not in $gseqtbl" if $all==-1;

	my($gseq);
	print "Output gene seq...\n";
	open (OF,">$ofile") || die "Cannot open $ofile";
	#处理每个基因
	for $i(0..$#$tbl) {
	  $row=$tbl->[$i];
	  print OF '>'."$row->[$gFld];$row->[$chrFld]$row->[$strandFld];$row->[$gtFld];$row->[$sFld]~$row->[$eFld]\n";
	  $gseq=$row->[$seqFld];
	  #-strand:将seq取反互补
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
  } else { #如果gntbl含有chr列，则按这个表来减少chr的数目
    if ($cond) {
	  (@chrs)=getFldValues($dbh,"SELECT distinct(chr) chr FROM $gntbl where ($cond) order by chr",0);
    } else {
	  (@chrs)=getFldValues($dbh,"SELECT distinct(chr) chr FROM $gntbl order by chr",0);
	}
  }
  foreach  my $chr (@chrs) {
    #读取染色体seq
	my ($fastbl,$rv)=execSql($dbh,"select seq from $chrtbl where title=\'$chr\'");


    next if $#{$fastbl}<0;
	$fas=$fastbl->[0][0];

	#读取基因gff
    my $where='';
	if ($cond) {
	  $where=" ($cond) and ";
	}
	my $sql="select a.chr,a.strand,a.gene,a.ftr_start,a.ftr_end from $gfftbl a where a.chr=\'$chr\' and a.ftr=\'$useftr\' and exists (select * from $gntbl b where $where a.gene=b.gene) order by a.ftr_start";
	#print "\n\n$sql\n\n";
	($tbl,$rv)=execSql($dbh,$sql);

    print "$chr $rv rows\n";
	next if $#$tbl<0;
    #在每个gene内遍历ftr
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
    #建临时表取得distinct(chr,strand,ftr_start,ftr_end)
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

	#取得所有数据
	$sql="select * from $gseqtbl";
	($tbl,$rv)=execSql($dbh,$sql);

	#相关字段的字段下标
	my ($chrFld,$strandFld,$sFld,$eFld,$seqFld)=getFldsIdx($dbh,$gseqtbl,('chr','strand','ftr_start','ftr_end','seq'));

	my($gseq);
	open (OF,">$ofile") || die "Cannot open $ofile";
	#处理每个基因
	for my $i(0..$#$tbl) {
	  my $row=$tbl->[$i];
	  print OF '>'."$row->[$chrFld]$row->[$strandFld];$row->[$sFld]~$row->[$eFld]|\n"; #末尾加上|用于区分IMEter的输出
	  my $gseq=$row->[$seqFld];
	  #-strand:将seq取反互补
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
  #输出所有ftr
  my ($row,$gseq); 
  $row=$tbl->[$si];
  if (!$seqlen) { #2013-05-25
	  $gseq='';
  print OF '>'."$row->[$gFld];$row->[$chrFld]$row->[$strandFld];all_$useftr;$row->[$sFld]~$row->[$eFld]\n";
		for my $i($si..$ei) {
		  $row=$tbl->[$i];
		  $gseq.=substr($fas,$row->[$sFld]-1,$row->[$eFld]-$row->[$sFld]+1);
		}
	  #-strand:将seq取反互补
	  if ($row->[$strandFld] eq '-') {
		 $gseq=reverseAndComplement($gseq,1,1);   
	  }
	  print OF "$gseq\n"  
  } else { #输出 seq len
  print OF "$row->[$gFld]\t";
	  $gseq=0;
		for my $i($si..$ei) {
		  $row=$tbl->[$i];
		  $gseq+=($row->[$eFld]-$row->[$sFld]+1);
		}
	  print OF "$gseq\n"  
  }		

}


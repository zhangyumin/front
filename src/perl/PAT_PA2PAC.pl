#!/usr/bin/perl -w
#2011/5/4
#����masterPA��,�ϲ�tot_tagnum��seed:wt֮���������
#�൱��֮ǰPAT_groupPA.pl

#2011/5/18
#����antiѡ��
#���� PAT_mapGff.pl -anti T -gff t_gff9_ae120 -pa xxPAC -posfld coord -out yy_anti -ogflds gff_id:strand:ftr:ftr_start:ftr_end:transcript:gene:gene_type -ogpre anti_ -conf dbconf_xx.xml

#����: masterPA����gfftbl(���ڹ���ע��)
#���: 
  # GFF����: gff_id, chr, strand, ftr, ftr_start, ftr_end, transcript, gene, gene_type, ftrs, trspt_cnt, ambID,
  # PAC����: coord, tot_tagnum, (s3_rep1...s4_rep4 ȡ����smpsѡ��), UPA_start, UPA_end, tot_PAnum, tot_ftrs (��AMB,3UTR), 
  # ����PAC����: ref_coord, ref_tagnum
  # antisense����: anti_gff_id,anti_strand,anti_ftr,anti_ftr_start,anti_ftr_end,anti_transcript,anti_gene,anti_gene_type

# �������PAC��PATһ���࣬��ȡ��ǰ1��

#2012-09-10 Mtr�޸� -- ����Ҫlongchr
#PAT_PA2PAC.pl -d 24 -mtbl wt_pas_master_pnas -gfftbl arab_tair9.t_gf9_ae120 -anti T -otbl wt_pas_pac -smps wt:oxt -conf dbconf_test.xml
#PAT_PA2PAC_pnas.pl -d 24 -mtbl wt_pas_master_pnas -gfftbl arab_tair9.t_gf9_ae120 -anti T -otbl wt_pas_pac_pnas -smps wt:oxt -conf dbconf_test.xml
#select * from wt_pas_pac order by chr,strand,coord into outfile 'c:/wt_pas_pac';
#select *  from wt_pas_pac_pnas order by chr,strand,coord into outfile 'c:/wt_pas_pac_pnas';

#2013-02-18 ����remapѡ��
#����ʹ����gff_id��master��
#��master������gff_id�������½�master����gff���������ٽ���PA2PAC
#�����ṩ remap=T/J(defualt)ѡ�����gff_id��remap=T����Ҳ���½�master����gff������������PA����PAC������gfftbl��ͬ�������
#��remap=F������gff_id������ԭ���ķ�ʽ(join id=id)

#2013-02-20 ���ڴ����ǳ����PA����out of memory�����
#���remap=G�����ʾmtbl�к���gene�У���ͨ�����Ѿ�����mapgff.pl�������Լ�С�ڴ棩
#��ֱ������join/mapgff�Ĳ���������groupPos

#2013-02-20 ���ڴ����ǳ����PA����out of memory�����
#���mtbl���г���X�����ݣ���50�򣩣����Ⱦɫ�崦��

#2013-08-29 ���� norms ѡ������ṩ��׼�����ӣ��Ӷ���tag#�йص��ж��Ǳ�׼���Ժ�� [round(tot_tagnum��smps��ref_tagnum)]
#��Ҫ�������������ʱ���ò����������ôӴ���г�ȡ���У�����������
#�±��� ����ֶα��ĳ��� decimal���͵ģ�������ֵ�ϻ������͵ģ�Ӧ�ò�Ӱ��ʹ��

#2013-08-29 ���� roundsѡ��
#��Ϊ 1:1:0:1 ��ʾ��3��smp��floor()����Ҫ���ǵ� MV�����е�mvoxt�е��̬�����
#��PA���ͽ���norm���پۺϳ�PAC

#2014-03-19 bug����
#��round֮������һ������, ��ԭ����ֵ��PAC��roundû�ˣ����´���tag=0��PAC������ȥ��

#use lib '/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi/';
use lib '/var/www/front/src/perl';
#use lib '/home/zym/soft/cpan/lib/perl5/';

require ('funclib.pl');
use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;
use POSIX qw(ceil floor);



##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","d=i","mtbl=s","gfftbl:s","otbl=s","smps=s","norms:s","rounds:s","anti:s","remap:s","conf=s");
my $USAGE=<< "_USAGE_";
Usage: 
  tot fld:
  1) PAT_PA2PAC.pl -d 24 -mtbl masterPA -gfftbl gff_ae120 -otbl masterPAC -smps tot_tagnum -conf dbconf_arabtest.xml
  seperate flds:
  2) PAT_PA2PAC.pl -d 24 -mtbl masterPA -gfftbl gff_ae120 -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  only 1 sample:
  3) PAT_PA2PAC.pl -d 24 -mtbl masterPA -gfftbl gff_ae120 -otbl leafPAC -smps leaf -conf dbconf_arabtest.xml
  add antipart
  4) PAT_PA2PAC.pl -anti T -d 24 -mtbl masterPA -gfftbl gff_ae120 -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  
  5) ���¹���mtbl��gff��: remap=T
  ** ����mtbl�Ƿ���gff_id�����¹���gff��
  PAT_PA2PAC.pl -anti T -d 24 -mtbl masterPA -remap T -gfftbl gff_ae120 -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  ** no gff_id in masterPA
  PAT_PA2PAC.pl -anti T -d 24 -mtbl masterPA -remap T -gfftbl gff_ae120 -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  
  6) mtbl��gene���� �� remap=G �� δ�ṩgfftbl��ֱ�ӽ���groupPos�������ṩgfftbl
  ���anti=F������Ҫ�ṩgfftbl
  PAT_PA2PAC.pl -anti F -d 24 -mtbl masterPA -remap G -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  ���anti=T������Ҫ�ṩgfftbl
  PAT_PA2PAC.pl -anti T -gfftbl gff_ae120 -d 24 -mtbl masterPA -remap G -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  
  7) ��׼��	 -norms
  PAT_PA2PAC.pl -d 24 -mtbl arab_mypa.t_gff10ae120pm2k_MVCtrl -gfftbl arab_tair10.t_gff10_ae120pm2k -anti T -otbl T_MVCtrl_PAC -smps wt:oxt:mvwt:mvoxt -norms "0.5715476:0.9879794:1:0.5874561" -conf dbconf_arabmv.xml

  8) �������� -rounds
  PAT_PA2PAC.pl -d 24 -smps wt:oxt:mvwt:mvoxt  -rounds "1:1:1:0" -norms "0.5715476:0.9879794:1:0.5874561" -mtbl arab_mypa.t_gff10ae120pm2k_MVCtrl -gfftbl arab_tair10.t_gff10_ae120pm2k -anti T -otbl T_MVCtrl_PACN2 -conf dbconf_arabmv.xml

-h=help
-d=dist  (>0)
-mtbl=masterPA table, <chr,strand,coord,tot_tagnum>+<smps..> ++ <gff_id [remap=J] ++ <gene, ftr [remap=G]>
-gfftbl=gff table <gff_id, chr, strand, ftr, ftr_start, ftr_end, transcript, gene, gene_type, ftrs, trspt_cnt, ambID>
-otbl=output PAC table, <gff info>+<coord,UPA_start,UPA_end,tot_tagnum,tot_PAnum,tot_ftrs>+<ref_coord,ref_tagnum>+<WT,Seed..>
-smps=flds to group, like tot_tagnum, or seed:leaf:wt, cannot be tot_tagnum:seed
-norms=��smps�ı�׼�����ӣ�Ĭ�� 1:1...:1 =round��������round֮����group��tot�ģ�����tot=�������ͣ��������͵ģ�
-rounds=��smps��floor����round ����Ҫ���ǵ�mvoxt�ı�̬�У���Ĭ�� 1:1:..:1
-anti=T/F(default), if T, then output antisense part: <anti_gff_id,anti_strand,anti_ftr,anti_ftr_start,anti_ftr_end,anti_transcript,anti_gene,anti_gene_type>
-remap=T/J(default)/G
  T, ���ʾ����mtbl�Ƿ���id��gene�У�ֱ��������mapgff.pl����mtbl��gfftbl
  J, ���ʾmtbl��gff_id�У�ֱ����join�ķ�ʽ
  G�����ʾmtbl��gene�У�ֱ����gene����groupPos
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||($opts{'d'}<=0)||!$opts{'mtbl'}||!$opts{'otbl'}||!$opts{'smps'}||!$opts{'conf'};
my $dist=$opts{'d'};
my $intbl=$opts{'mtbl'};
my $otbl=$opts{'otbl'};
my $gfftbl=$opts{'gfftbl'};
my $s=$opts{'smps'};   
my $norm=$opts{'norms'};
$norm='' if !defined($norm);
my $round=$opts{'rounds'};
$round='' if !defined($round);
my $anti=0;
$anti=1 if $opts{'anti'} eq 'T';
my $remap='J';
$remap='T' if $opts{'remap'} eq 'T';
$remap='G' if $opts{'remap'} eq 'G';

die "anti=T or remap=T but no gfftbl" if ( ($anti or ($remap eq 'T')) and !$gfftbl );
die "anti=F and remap=G but provide gfftbl" if (!$anti and ($remap eq 'G') and $gfftbl);

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my $conf=$opts{'conf'};
my($dbh)=connectDB($conf,1);

#############################################################################
#  Group PA                                             
#############################################################################
#��֤
die "mtbl=$intbl not exists!" if (!tblExists($dbh,$intbl));

my $haveID=1;
my $id=getFldsIdx($dbh,$intbl,'gff_id');
$haveID=0 if $id==-1;
die "remap=J but no gffID in mtbl" if ($remap eq 'J' and !$haveID);

my (@xx)=getFldsIdx($dbh,$intbl,('gff_id','ftr','ftr_start','ftr_end','transcript','gene','gene_type','ftrs','trspt_cnt'));
die "remap=G but no gene/ftr/... in mtbl" if  ($remap eq 'G' and $xx[$#xx]==-1);

my $MAPGFF;
if ($anti or ($remap eq 'T')) {
	$MAPGFF='PAT_mapGff.pl';
	$MAPGFF='/var/www/front/src/perl/PAT_mapGff.pl' if !(-e 'PAT_mapGff.pl');
	die "PAT_mapGff.pl not exists when (anti=T or gff_id not in mtbl or remap=T)" if !(-e $MAPGFF);
}

my @smps=split(/:/,$s);	
#2013-08-29 ��׼������
my @norms=@smps;
if (!$norm) {
  for my $i(0..$#norms) {
	$norms[$i]=1;
  } 
} else {
 @norms=split(/:/,$norm);	  
 die "error norm=$norm, not match smps=$s" if $#smps!=$#norms;
}

my @rounds=@smps;
if ($round eq '') {
  for my $i(0..$#rounds) {
	$rounds[$i]=1;
  } 
} else {
 @rounds=split(/:/,$round);	  
 die "error round=$round, not match smps=$s" if $#smps!=$#rounds;
}

my @tmp=getFldsIdx($dbh,$intbl,@smps);
die "smps=$s not in $intbl" if ($tmp[$#tmp]==-1);
die "error smps=$s, tot_tagnum must be alone!" if scalar(@smps)>1 and $s=~/tot_tagnum/;
my $useTot=0;
$useTot=1 if $s=~/tot_tagnum/;

@tmp=('chr','strand','coord','tot_tagnum');
my @tmpId=getFldsIdx($dbh,$intbl,@tmp);
die "@tmp not in $intbl" if $tmpId[$#tmpId]==-1;

print "mtbl=$intbl\ngfftbl=$gfftbl\nremap=$remap\nanti=$anti\nsmps=$s\nnorms=$norm\nrounds=$round\n";

#����gff��intbl
my $tmpPA=$intbl.'xxPAxx';;

#�������г��ϱ�׼������ ����ɸ����У�
my $totstr='';	# a.A*0.5+a.B*0.4 tot_tagnum, 
my $smpstr='';	# a.A*0.5 A, a.B*0.4 B
my $smpsum='';	# a.A*0.5+a.B*0.4
for my $i(0..$#smps) {
  $totstr.="a.$smps[$i]*$norms[$i]+";
  $smpstr.="a.$smps[$i]*$norms[$i] $smps[$i],";
}
$smpsum=substr($totstr,0,length($totstr)-1);
$totstr=substr($totstr,0,length($totstr)-1)." tot_tagnum, ";
$smpstr=substr($smpstr,0, length($smpstr)-1);

if ($remap eq 'J') { #���remap=J (mtbl��gff_id)������join��ʽ

  print "remap=J: join $intbl and $gfftbl \n";
  
  my $sela;
  $sela="a.coord,a.tot_tagnum*$norms[0] tot_tagnum" if $useTot;
  #$sela="a.coord,".join('+',@smps)." tot_tagnum,".join(',a.',@smps) if !$useTot;
  $sela="a.coord,$totstr$smpstr" if !$useTot;
  print "----\nCreate idx_id on $gfftbl and $intbl\n";
  $dbh->do("create index idx_id on $intbl(gff_id)");
  $dbh->do("create index idx_id on $gfftbl(gff_id)");
  print "----\n";
  $dbh->do("drop table if exists $tmpPA") or die;
  my $sql="create TEMPORARY table $tmpPA select b.*,$sela from $intbl a, $gfftbl b where a.gff_id=b.gff_id and ".$smpsum.">0" if !$useTot;
  $sql="create TEMPORARY table $tmpPA select b.*,$sela from $intbl a, $gfftbl b where a.gff_id=b.gff_id and tot_tagnum*$norms[0]>0" if $useTot; #
  my $rv=$dbh->do($sql) or die;
  die "no rows after join" if $rv==0;
  print "After join total PA; $rv rows\n";

} elsif ($remap eq 'T') {#��� remap=T (�����Ƿ���gff_id)����������mapGFF
  print "remap=T: MAPGFF $intbl + $gfftbl \n";
  my $sela;
  $sela="a.chr,a.strand,a.coord,a.tot_tagnum*$norms[0] tot_tagnum" if $useTot;
  #$sela="a.chr,a.strand,a.coord,".join('+',@smps)." tot_tagnum,".join(',a.',@smps) if !$useTot;
  $sela="a.chr,a.strand,a.coord,".$totstr.$smpstr if !$useTot;
  my $filterTbl=$intbl."_xxflt";
  $dbh->do("drop table if exists $filterTbl") or die;
  my $sql="create table $filterTbl select $sela from $intbl a where ".$smpsum.">0" if !$useTot;
  $sql="create table $filterTbl select $sela from $intbl a where tot_tagnum*$norms[0]>0" if $useTot; #
  my $rv=$dbh->do($sql) or die;
  #exit 0;
  my $cmd="$MAPGFF -gff $gfftbl -pa $filterTbl -posfld coord -out $tmpPA -conf $conf";
  print "---- Map mtbl with gfftbl (remap=$remap OR haveID=$haveID)----\n";
  system $cmd;
  print "---------------------------------------------------\n";
  $dbh->do("drop table if exists $filterTbl") or die;

} elsif ($remap eq 'G') { #remap=G ��mtbl������gene�У�
  print "remap=G: use intbl \n";
  my $sela;
  $sela="gff_id,chr,strand,coord,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,tot_tagnum*$norms[0] tot_tagnum" if $useTot;
  #$sela="gff_id,chr,strand,coord,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,".join('+',@smps)." tot_tagnum,".join(',',@smps) if !$useTot;
  $sela="gff_id,chr,strand,coord,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,".$totstr.$smpstr if !$useTot;
  $dbh->do("drop table if exists $tmpPA") or die;
  my $sql="create TEMPORARY table $tmpPA select $sela from $intbl a where ".$smpsum.">0" if !$useTot;
  $sql="create TEMPORARY table $tmpPA select $sela from $intbl a where "." tot_tagnum*$norms[0]>0" if $useTot; 
  #print "$sql\n\n";
  my $rv=$dbh->do($sql) or die;
  print "PA; $rv rows\n";
}

## ֱ��tmpPA��ŵ�����Ҫ��PA����

#������ֶε��±�
my $oo;
my(@fldnames)=('gene','ftr','coord','tot_tagnum');
my ($gFld,$ftrFld,$posFld,$totFld,$all)=getFldsIdx($dbh,$tmpPA,@fldnames);
die "@fldnames not in tmpPA table" if $all==-1;
my @smpIds;
if (!$useTot) {
  @smpIds=getFldsIdx($dbh,$tmpPA,@smps);
  die "@smps not in $intbl" if ($smpIds[$#smpIds]==-1);
  pop(@smpIds) if $#smpIds>0;
}
#��ͳ��������ı��ֶ� $tpmPA+<UPA_start,UPA_end,tot_PAnum,tot_ftrs>+<ref_coord,ref_tagnum>
my $t=scalar(getTblFlds($dbh,$tmpPA));
my($usFld,$ueFld,$tpaFld,$tftrFld,$rcFld,$rtFld)=($t,$t+1,$t+2,$t+3,$t+4,$t+5);
my $lastPAIdx=$t-1;
my $lastPACIdx=$t+5;
my @blankRow=();
for my $i(0..$lastPACIdx) {
  push(@blankRow,0);
}

#��������
my @ftrNames=getFldValues($dbh,"select distinct(ftr) from $tmpPA order by ftr",0);
my %ftrCnts;
for my $i(0..$#ftrNames) {
  $ftrCnts{$ftrNames[$i]}=0;
}

#����
my($startIdx,$endIdx,$idxs,$idxs2,$tbl,$cntPAC,$rv);

#����tmpPA���������������50�����Ⱦɫ�崦��
my @counts=getFldValues($dbh,"select count(*) from $tmpPA",0);
my $ROWMAX=500000;
my $split=0;
if ($counts[0]>$ROWMAX) {
  $split=1;
  print "Total $counts[0] PAs > $ROWMAX, split...\n";
}
my @chrs=('xx'); #��ʱ��chr,���ڷ�split�����
@chrs=getFldValues($dbh,"SELECT distinct(chr) FROM $tmpPA order by chr",0) if $split;

my $opath=getTmpPath(1);
my $ofile=$opath."${intbl}_PA2PAC.tmp";
unlink $ofile if -e $ofile;

foreach  my $chr (@chrs) {
    
	if ($split) { 
	  my $sql="select * from $tmpPA where chr=\'$chr\'";
	  ($tbl,$rv)=execSql($dbh,$sql);
      next if $#{$tbl}<0;
	  print "$rv PA ($chr)\n";
	} else {
	  my $sql="select * from $tmpPA order by gene,coord";
	  ($tbl,$rv)=execSql($dbh,$sql);
	  if ($#{$tbl}<0) {
	    print "0 PAs in $tmpPA";
	    if ($tmpPA ne $intbl) {
		  $dbh->do("drop table if exists $tmpPA") or die;
	    }  
	    exit 0;
	  } 
	  print "$rv PA\n";
	}
	
	$idxs=getIntervals($tbl,$gFld);
	for my $i(0..$#$idxs) {
	  $startIdx=$idxs->[$i][0];
	  $endIdx=$idxs->[$i][1];
	  doThisFragment($startIdx,$endIdx);
	}	
	saveMtx2File($oo,$ofile,1);
	print "$cntPAC PAC\n";
	$oo=[];
	$tbl=[];
}


##�������ݿ���
$dbh->do("drop table if exists $otbl") or die;
my $sql="create table $otbl select * from $tmpPA where 1<>1";
$dbh->do($sql) or die;
$sql="alter table $otbl add column UPA_start int, add column UPA_end int, add column tot_PAnum int,add column  tot_ftrs varchar(100),add column ref_coord int,add column ref_tagnum int";
$dbh->do($sql) or die;
if ($tmpPA ne $intbl) {
  $dbh->do("drop table if exists $tmpPA") or die;
}

$rv=loadFile2Tbl($dbh,$otbl,$ofile,0);

#2014-03-19 ȥ������tot_tagnum=0����
my $rv2=$dbh->do("delete from $otbl where tot_tagnum=0") or die;
print "$rv2\ttot_tagnum=0\n";
my $xx=($rv-$rv2);
print "$xx\t$otbl\n";
unlink($ofile) if (-e $ofile);

if ($anti) {
  my $tmp=$otbl.'_xxAtxx';
  my $cmd="$MAPGFF -anti T -gff $gfftbl -pa $otbl -posfld coord -out $tmp -ogflds gff_id:strand:ftr:ftr_start:ftr_end:transcript:gene:gene_type -ogpre anti_ -conf $conf";
  print "---------------- anti=T, add anti_xx to $otbl ------------------\n";
  system $cmd;
  print "----------------------------------------------------------------\n";
  $dbh->do("drop table if exists $otbl") or die;
  $dbh->do("alter table $tmp rename $otbl") or die;
}

$dbh->disconnect();

if ($split) {
 #system "net stop mysql";
 #system "net start mysql";
}


#############################################################################
#  doThisFragment($startIdx,$endIdx);
#  use global vars: $tbl
#############################################################################
sub doThisFragment {
	my ($si,$ei)=@_;
	#print "$si~$ei $tbl->[$si][$gFld]\n";
	my(@coord,@grp,$i,$startIdx,$endIdx);

	#1)tag��dist����-->@grp    
	for $i ($si..$ei) {
	  push(@coord,$tbl->[$i][$posFld]);
	}
    
     @grp=grpByPos(\@coord,$dist);	 

	 $idxs2=getIntervals(\@grp);
	 for my $i(0..$#$idxs2) {
		$startIdx=$si+$idxs2->[$i][0];
		$endIdx=$si+$idxs2->[$i][1];
		doThisGrp($startIdx,$endIdx);
	 }
}

#############################################################################
#  doThisGrp($startIdx,$endIdx,$PApos):lastPApos;
#  use global vars: $tbl,%ftrCnts,$dist,-- $chr,$strands[$s],$curMG
#############################################################################
#��ͳ��������ı��ֶ� $tpmPA+<UPA_start,UPA_end,tot_PAnum,tot_ftrs>+<ref_coord,ref_tagnum>
#my $t=getTblFlds($dbh,$tpmPA);
#my($usFld,$ueFld,$tpaFld,$tftrFld,$rcFld,$rtFld)=($t,$t+1,$t+2,$t+3,$t+4,$t+5);

sub doThisGrp {
	my ($si,$ei)=@_;
	$cntPAC++;
	my($grpPACnt)=0;
	#�ܵ�utag_num
	for my $i ($si..$ei) {
		$ftrCnts{$tbl->[$i][$ftrFld]}+=$tbl->[$i][$totFld];
        $ftrCnts{"all"}+=$tbl->[$i][$totFld];
		$grpPACnt++;
	}

    #@smpIds��tagnum
	my @smpTn=();
	if ($#smpIds!=-1) {
	  for my $j(0..$#smpIds) {
		push(@smpTn,0);
	  }
	  for my $i ($si..$ei) {
	   for my $j(0..$#smpIds) {
		 $smpTn[$j]+=$tbl->[$i][$smpIds[$j]];
	   }
	  }
	}

    #�������ֵ����ֹ
	my $maxIdx=-1;
	my $maxTn=0;
	my ($PAstart,$PAend);
	$PAstart=$PAend=$tbl->[$si][$posFld];
	for my $i ($si..$ei) {
		if ($tbl->[$i][$totFld]>$maxTn) {
		  $maxTn=$tbl->[$i][$totFld];
		  $maxIdx=$i;
	    }
		if ($tbl->[$i][$posFld]>$PAend) {
          $PAend=$tbl->[$i][$posFld];
		} 
		if ($tbl->[$i][$posFld]<$PAstart) {
          $PAstart=$tbl->[$i][$posFld];
		} 
	}	
  
   my $totFtrs=getGrpFtr();

   push(@$oo,[@blankRow]);
   #print "\n $lastPAIdx~$lastPACIdx smpIDs=$#smpIds\n";
   #print "tbl[si]: @{$tbl->[$si]}\n";
   #print "PAstart: ($PAstart,$PAend,$grpPACnt,$totFtrs,$tbl->[$maxIdx][$posFld],$maxTn)\n";

   #���ͻ�
   my $tottag=0;
   for my $i(0..$#smpTn) {
	 if ($rounds[$i]) {
	   $smpTn[$i]=round($smpTn[$i]);
	 } else {
	   $smpTn[$i]=floor($smpTn[$i]);
	 }
	 $tottag+=$smpTn[$i];
   }
   $maxTn=round($maxTn);				   
   @{$oo->[$#$oo]}[0..$lastPAIdx]=@{$tbl->[$maxIdx]};
   $oo->[$#$oo][$totFld]=$tottag if $#smpIds!=-1; 
   if ($#smpIds==-1 and $rounds[0]==1) {
	 $oo->[$#$oo][$totFld]=round($ftrCnts{"all"});
   } elsif ($#smpIds==-1 and $rounds[0]==0) {
	 $oo->[$#$oo][$totFld]=floor($ftrCnts{"all"});
   }
   @{$oo->[$#$oo]}[@smpIds]=@smpTn if $#smpIds!=-1;   
   @{$oo->[$#$oo]}[($lastPAIdx+1)..$lastPACIdx]=($PAstart,$PAend,$grpPACnt,$totFtrs,$tbl->[$maxIdx][$posFld],$maxTn);

   foreach my $i (keys(%ftrCnts)) {
     $ftrCnts{$i}=0;
   } 
}


#############################################################################
#  getGrpFtr():$grp_ftr
#  use global vars: %ftrCnts,@ftrNames;
#############################################################################
sub getGrpFtr {
	my ($cnt,$str);
	foreach my $ftr (@ftrNames) {
		$cnt=$ftrCnts{$ftr};
		if ($cnt>0) {
		  $str.=($ftr.',');
		}
	} 
	$str=substr($str,0,length($str)-1);
    return $str;
}

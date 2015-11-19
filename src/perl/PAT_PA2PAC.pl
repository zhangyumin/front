#!/usr/bin/perl -w
#2011/5/4
#基于masterPA表,合并tot_tagnum或seed:wt之类的样本列
#相当于之前PAT_groupPA.pl

#2011/5/18
#增加anti选项
#调用 PAT_mapGff.pl -anti T -gff t_gff9_ae120 -pa xxPAC -posfld coord -out yy_anti -ogflds gff_id:strand:ftr:ftr_start:ftr_end:transcript:gene:gene_type -ogpre anti_ -conf dbconf_xx.xml

#输入: masterPA表及gfftbl(用于关联注释)
#输出: 
  # GFF部分: gff_id, chr, strand, ftr, ftr_start, ftr_end, transcript, gene, gene_type, ftrs, trspt_cnt, ambID,
  # PAC部分: coord, tot_tagnum, (s3_rep1...s4_rep4 取决于smps选项), UPA_start, UPA_end, tot_PAnum, tot_ftrs (如AMB,3UTR), 
  # 主导PAC部分: ref_coord, ref_tagnum
  # antisense部分: anti_gff_id,anti_strand,anti_ftr,anti_ftr_start,anti_ftr_end,anti_transcript,anti_gene,anti_gene_type

# 如果整组PAC的PAT一样多，则取最前1个

#2012-09-10 Mtr修改 -- 不需要longchr
#PAT_PA2PAC.pl -d 24 -mtbl wt_pas_master_pnas -gfftbl arab_tair9.t_gf9_ae120 -anti T -otbl wt_pas_pac -smps wt:oxt -conf dbconf_test.xml
#PAT_PA2PAC_pnas.pl -d 24 -mtbl wt_pas_master_pnas -gfftbl arab_tair9.t_gf9_ae120 -anti T -otbl wt_pas_pac_pnas -smps wt:oxt -conf dbconf_test.xml
#select * from wt_pas_pac order by chr,strand,coord into outfile 'c:/wt_pas_pac';
#select *  from wt_pas_pac_pnas order by chr,strand,coord into outfile 'c:/wt_pas_pac_pnas';

#2013-02-18 增加remap选项
#允许使用无gff_id的master表
#若master表中无gff_id，则重新将master表与gff表关联，再进行PA2PAC
#另外提供 remap=T/J(defualt)选项，若有gff_id但remap=T，则也重新将master表与gff表关联（用于PA表与PAC表所用gfftbl不同的情况）
#若remap=F，且有gff_id，则按照原来的方式(join id=id)

#2013-02-20 用于处理非常大的PA表，out of memory的情况
#如果remap=G，则表示mtbl中含有gene列，（通常是已经经过mapgff.pl操作，以减小内存）
#则直接跳过join/mapgff的操作，进行groupPos

#2013-02-20 用于处理非常大的PA表，out of memory的情况
#如果mtbl含有超过X的数据（如50万），则分染色体处理

#2013-08-29 增加 norms 选项，允许提供标准化因子，从而与tag#有关的列都是标准化以后的 [round(tot_tagnum，smps，ref_tagnum)]
#主要用于两样本组合时更好操作，而不用从大表中抽取两列，这样误差更大
#新表的 相关字段被改成了 decimal类型的，不过数值上还是整型的，应该不影响使用

#2013-08-29 增加 rounds选项
#若为 1:1:0:1 表示第3个smp用floor()。主要考虑到 MV数据中的mvoxt有点变态而设的
#对PA表就进行norm，再聚合成PAC

#2014-03-19 bug修正
#有round之后会存在一个现象, 把原来有值的PAC给round没了，导致存在tag=0的PAC。必须去除

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
  
  5) 重新关联mtbl与gff表: remap=T
  ** 不管mtbl是否有gff_id，重新关联gff表
  PAT_PA2PAC.pl -anti T -d 24 -mtbl masterPA -remap T -gfftbl gff_ae120 -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  ** no gff_id in masterPA
  PAT_PA2PAC.pl -anti T -d 24 -mtbl masterPA -remap T -gfftbl gff_ae120 -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  
  6) mtbl含gene等列 且 remap=G 且 未提供gfftbl，直接进行groupPos，不用提供gfftbl
  如果anti=F，则不需要提供gfftbl
  PAT_PA2PAC.pl -anti F -d 24 -mtbl masterPA -remap G -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  如果anti=T，还是要提供gfftbl
  PAT_PA2PAC.pl -anti T -gfftbl gff_ae120 -d 24 -mtbl masterPA -remap G -otbl leafseedPAC -smps leaf:seed -conf dbconf_arabtest.xml
  
  7) 标准化	 -norms
  PAT_PA2PAC.pl -d 24 -mtbl arab_mypa.t_gff10ae120pm2k_MVCtrl -gfftbl arab_tair10.t_gff10_ae120pm2k -anti T -otbl T_MVCtrl_PAC -smps wt:oxt:mvwt:mvoxt -norms "0.5715476:0.9879794:1:0.5874561" -conf dbconf_arabmv.xml

  8) 四舍五入 -rounds
  PAT_PA2PAC.pl -d 24 -smps wt:oxt:mvwt:mvoxt  -rounds "1:1:1:0" -norms "0.5715476:0.9879794:1:0.5874561" -mtbl arab_mypa.t_gff10ae120pm2k_MVCtrl -gfftbl arab_tair10.t_gff10_ae120pm2k -anti T -otbl T_MVCtrl_PACN2 -conf dbconf_arabmv.xml

-h=help
-d=dist  (>0)
-mtbl=masterPA table, <chr,strand,coord,tot_tagnum>+<smps..> ++ <gff_id [remap=J] ++ <gene, ftr [remap=G]>
-gfftbl=gff table <gff_id, chr, strand, ftr, ftr_start, ftr_end, transcript, gene, gene_type, ftrs, trspt_cnt, ambID>
-otbl=output PAC table, <gff info>+<coord,UPA_start,UPA_end,tot_tagnum,tot_PAnum,tot_ftrs>+<ref_coord,ref_tagnum>+<WT,Seed..>
-smps=flds to group, like tot_tagnum, or seed:leaf:wt, cannot be tot_tagnum:seed
-norms=给smps的标准化因子，默认 1:1...:1 =round（当样本round之后，再group成tot的，所以tot=各样本和，都是整型的）
-rounds=给smps是floor还是round （主要考虑到mvoxt的变态列），默认 1:1:..:1
-anti=T/F(default), if T, then output antisense part: <anti_gff_id,anti_strand,anti_ftr,anti_ftr_start,anti_ftr_end,anti_transcript,anti_gene,anti_gene_type>
-remap=T/J(default)/G
  T, 则表示不论mtbl是否有id或gene列，直接重新用mapgff.pl关联mtbl和gfftbl
  J, 则表示mtbl有gff_id列，直接用join的方式
  G，则表示mtbl有gene列，直接用gene进行groupPos
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
#验证
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
#2013-08-29 标准化因子
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

#关联gff和intbl
my $tmpPA=$intbl.'xxPAxx';;

#将样本列乘上标准化因子 （变成浮点列）
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

if ($remap eq 'J') { #如果remap=J (mtbl有gff_id)，则用join方式

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

} elsif ($remap eq 'T') {#如果 remap=T (不管是否有gff_id)，则重新用mapGFF
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

} elsif ($remap eq 'G') { #remap=G （mtbl必须有gene列）
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

## 直此tmpPA存放的是需要的PA数据

#输出表字段的下标
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
#由统计再输出的表字段 $tpmPA+<UPA_start,UPA_end,tot_PAnum,tot_ftrs>+<ref_coord,ref_tagnum>
my $t=scalar(getTblFlds($dbh,$tmpPA));
my($usFld,$ueFld,$tpaFld,$tftrFld,$rcFld,$rtFld)=($t,$t+1,$t+2,$t+3,$t+4,$t+5);
my $lastPAIdx=$t-1;
my $lastPACIdx=$t+5;
my @blankRow=();
for my $i(0..$lastPACIdx) {
  push(@blankRow,0);
}

#特征计数
my @ftrNames=getFldValues($dbh,"select distinct(ftr) from $tmpPA order by ftr",0);
my %ftrCnts;
for my $i(0..$#ftrNames) {
  $ftrCnts{$ftrNames[$i]}=0;
}

#遍历
my($startIdx,$endIdx,$idxs,$idxs2,$tbl,$cntPAC,$rv);

#计算tmpPA的行数，如果超过50万，则分染色体处理
my @counts=getFldValues($dbh,"select count(*) from $tmpPA",0);
my $ROWMAX=500000;
my $split=0;
if ($counts[0]>$ROWMAX) {
  $split=1;
  print "Total $counts[0] PAs > $ROWMAX, split...\n";
}
my @chrs=('xx'); #临时的chr,用于非split的情况
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


##导入数据库中
$dbh->do("drop table if exists $otbl") or die;
my $sql="create table $otbl select * from $tmpPA where 1<>1";
$dbh->do($sql) or die;
$sql="alter table $otbl add column UPA_start int, add column UPA_end int, add column tot_PAnum int,add column  tot_ftrs varchar(100),add column ref_coord int,add column ref_tagnum int";
$dbh->do($sql) or die;
if ($tmpPA ne $intbl) {
  $dbh->do("drop table if exists $tmpPA") or die;
}

$rv=loadFile2Tbl($dbh,$otbl,$ofile,0);

#2014-03-19 去除可能tot_tagnum=0的列
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

	#1)tag按dist分组-->@grp    
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
#由统计再输出的表字段 $tpmPA+<UPA_start,UPA_end,tot_PAnum,tot_ftrs>+<ref_coord,ref_tagnum>
#my $t=getTblFlds($dbh,$tpmPA);
#my($usFld,$ueFld,$tpaFld,$tftrFld,$rcFld,$rtFld)=($t,$t+1,$t+2,$t+3,$t+4,$t+5);

sub doThisGrp {
	my ($si,$ei)=@_;
	$cntPAC++;
	my($grpPACnt)=0;
	#总的utag_num
	for my $i ($si..$ei) {
		$ftrCnts{$tbl->[$i][$ftrFld]}+=$tbl->[$i][$totFld];
        $ftrCnts{"all"}+=$tbl->[$i][$totFld];
		$grpPACnt++;
	}

    #@smpIds的tagnum
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

    #整组最大值及起止
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

   #整型化
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


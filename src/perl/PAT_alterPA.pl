#!/usr/bin/perl -w
#2011/5/4
#目的: 得到或修改masterPA; masterPA表是一个规范的PA表,不同样本各占1列,可以添加,删除或修改已有的行和列
#PA表:  至少含 chr,strand,coord,tagnum 或加 gff_id (不保留完整注释)
#masterPA表: 必须含<chr,strand,coord,tot_tagnum,gff_id>

#如果输入的PA表不含gff_id,则对其关联gff_id后再加入masterPA表
#比较时,只比较 chr,strand,coord,其它列均以master表为准

#只提供完全的重写:ow 或 更新:ap(相同的行不累加tagnum,只更新,2012-09-10没明白当初这么做的原因！！！) 不提供累加的追加.

#2011/5/7
#提供format=table(默认)或file选项,允许以file形式提供PA文件
#若format=file,则必须只含4列为chr:strand:coord:tagnum

#2011/5/18
#修改gff_id列为float(10,1)

#2011/9/28
#BUG修正：对有重复含的输入表或输入文件（每行一个PA）的情况，PAT会错误计数
#案例：将几个Oxt6混合后，会有同一coord，不同行的情况。

#2011/11/7
#添加usage帮助：允许设置来自PAtbl（owtbl或aptbl）的列，即从fromsmp列取得PA数据，添加至master表的owsmp或apsmp列

#2012-09-09
#mtr修正，允许long/short （如果需要用到MapGff.pl，则需要dbconf.longchr）
#2012-09-10 测试Mtr
#use test;
#create table oxt_pas select chr,strand,coord,oxt from arab_aghoxt.t_oxt_pac;
#create table wt_pas select chr,strand,coord,wt from arab_aghoxt.t_wt_pac;
#PAT_alterPA.pl -master wt_pas_master -owtbl wt_pas -flds chr:strand:coord:wt -owsmp wt -gfftbl arab_tair9.t_gf9_ae120 -conf dbconf_test.xml
#PAT_alterPA_pnas.pl -master wt_pas_master_pnas -owtbl wt_pas -flds chr:strand:coord:wt -owsmp wt -gfftbl arab_tair9.t_gf9_ae120 -conf dbconf_test.xml
#PAT_alterPA.pl -master wt_pas_master -owtbl oxt_pas -flds chr:strand:coord:oxt -owsmp oxt -gfftbl arab_tair9.t_gf9_ae120 -conf dbconf_test.xml
#PAT_alterPA_pnas.pl -master wt_pas_master_pnas -owtbl oxt_pas -flds chr:strand:coord:oxt -owsmp oxt -gfftbl arab_tair9.t_gf9_ae120 -conf dbconf_test.xml
#select * from wt_pas_master order by chr,strand,coord into outfile 'c:/1.txt';
#select * from wt_pas_master_pnas order by chr,strand,coord into outfile 'c:/2.txt';

#2012-09-10
#新增选项 apadd＝0(default)/1，＝1时，在apsmp/aptbl的情况下，将tag#累加到原列（而非之前的只将tag#覆盖原列）

#2013-02-18
#允许不使用gff表，来创建master表（即master表无gffid）
#如果 master表存在gffid 且 gfftbl 有提供，则按原来的方式
#如果 master表不存在，若有提供gfftbl，则按原来的方式
#如果 master表不存在（或无gffid)，未提供gfftbl，则新的master表（或累加后的master表）不含gffid

#2013-08-15 bug修正： alterPA后出现tot_tagnum=0的情况
#如果小PA表中存在多于1列的PA，而只用其中1列合并到master时，会把PA的所有行都抽出，导致一部分行为0

#2013-12-16 添加apadd=2选项
#允许将PA表，以不添加新行的方式高值覆盖到master表中,若master.fld的tag#大于aptbl的tag#，则也不更新
#主要用于raw data mapping时，对PAT的修正
#比如用L30N1map得到基准表（master），保证PA的位置是不变的
#再用L20N1map得到的表去修正原来的PA的tag#，但不添加新的PAT#，使最终map得到的PAT#可以增加！
#即使master表中有wt和oxt列，但只更新oxt列，用apadd=2也是有效的（程序中会自动判断master.oxt是否>0，若>0才更新）

#use lib '/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi/';
use lib '/var/www/front/src/perl';
#use lib '/home/zym/soft/cpan/lib/perl5/';

require ('funclib.pl');

use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;
use List::MoreUtils qw/ uniq /; 


##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","master=s","owtbl:s","owsmp:s","aptbl:s","apsmp:s","delsmp:s","flds:s","gfftbl:s","omtbl:s","format:s","apadd:i","conf=s");
my $USAGE=<< "_USAGE_";
Require: PAT_mapGff.pl if to relate gff_id
Usage: 
(i) already gff_id in PA table
1) create masterPA table
PAT_alterPA.pl -master masterPA -owtbl wtgf -owsmp wt -conf dbconf_arabtest.xml
2) Add new sample
PAT_alterPA.pl -master masterPA -owtbl seedgf -owsmp seed -conf dbconf_arabtest.xml
3) update existing sample (!apadd=0/1)
PAT_alterPA.pl -master masterPA -aptbl wt2gf -apsmp wt -conf dbconf_arabtest.xml
4) user defined fields
PAT_alterPA.pl -master masterPA -owtbl wt3gf -owsmp wt3 -flds chr:strand:polyA_pos:tagnum:gff_id -conf dbconf_arabtest.xml
5) 允许用PA表中规定的sample列，更新master列(设置flds中的tagnum列，如这里的ol_s1）
PAT_alterPA.pl -master t_org_pa -owtbl t_org_oxt_pa -owsmp ol_s1 -flds chr:strand:coord:ol_s1:gff_id -conf dbconf_arabwtoxt.xml

(ii) no gff_id in PA table, option: [-gff gff_ae120]
PAT_alterPA.pl -master masterPA2 -owtbl wt -owsmp wt -gff gff_ae120 -conf dbconf_arabtest.xml
PAT_alterPA.pl -master masterPA2 -owtbl seed -owsmp seed -gff gff_ae120 -conf dbconf_arabtest.xml
PAT_alterPA.pl -master masterPA2 -aptbl wt2 -apsmp wt -gff gff_ae120 -conf dbconf_arabtest.xml
PAT_alterPA.pl -master masterPA2 -owtbl wt3 -owsmp wt3 -gff gff_ae120 -flds chr:strand:polyA_pos:tagnum -conf dbconf_arabtest.xml

(iii) Update existing PA table (NOT master table)
PAT_alterPA.pl -master xx -owtbl t_pa_leaf -owsmp leaf -gff gff_ae120 -conf dbconf_arabtest.xml #format to master PA
PAT_alterPA.pl -master xx -aptbl t_pa_seed -apsmp leaf -gff gff_ae120 -conf dbconf_arabtest.xml #append new dataset to master PA
alter table xx rename t_pa_leaf;... #change master to original table

(IV) format=file
PAT_alterPA.pl -master masterPA -owtbl c:/wt.PA -owsmp wt -gff gff_ae120 -format file -conf dbconf_arabtest.xml

(IVI) apadd=1
PAT_alterPA.pl -master masterPA -aptbl wt2gf -apsmp wt -apadd 1 -conf dbconf_arabtest.xml

(XX) !! 无gff_id的master表：master表不存在（或无gffid)，不提供gfftbl，则新的master表（或累加后的master表）不含gffid
PAT_alterPA.pl -master masterPA -owtbl c:/wt.PA -owsmp wt -format file -conf dbconf_arabtest.xml

(IVI) apadd=2 （用于raw mapping时的PAT修正）
在原master表中，高值覆盖原行，但不添加新行！
PAT_alterPA.pl -master masterPA -aptbl c:/wtL20N1.PA -apsmp wt -apadd 2 -format file -conf dbconf_arabtest.xml

-h=help
-master=master PA table, at least <chr,strand,coord,tot_tagnum,gff_id,...different samples> 前面几列必须是这样，后面才是样本列！
-owtbl/owsmp=PA table to overwrite, owsmp must be a field of master table
*-aptbl/apsmp=PA table to append, apsmp must be a field of master table
*ap is insert new line and update (Overwrite! NOT add) the existing line
** apadd=0时，aptbl,apsmp 是直接更新原列中的相应的行值（但没有将tagnum加到原列，而是覆盖原列！！！！）
** 比如wt=10，新的wt=20，则最终master的WT还是为20,而不是30！！！
-gfftbl=gff table to get gff_id (if no gff_id in OW or AP table)
-delsmp=sample to delete, delsmp one in master table
-flds=fldnames, default is (chr:strand:coord:tagnum:gff_id) or (chr:strand:coord:tagnum) -- 允许用PA表（非master表）中规定的sample列，如WT
*PA table: table with flds: <chr,strand,coord,tagnum>+<gff_id>
-omtbl=output master table, if '' or same as master then overwrite
*-format=input table(default) or file
*if format=file, the file only has 4 columns, and is chr:strand:coord:tagnum
-apadd=0(default)/1 （仅在apsmp和aptbl时有效）
 0时，添加新行，但用新的tag#去覆盖原来的tag#
 1时，添加新行，且可将tag#累加到原列（而非apadd=0只更新原列）
 2时，不添加新行，但将tag#高值覆盖原行
-conf=db conf
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'master'}||!$opts{'conf'};
my ($OW,$AP,$DEL)=(1,2,3);
my ($TABLE,$FILE)=(1,2);
my $mtbl=$opts{'master'};
my $owtbl=$opts{'owtbl'};
my $owsmp=$opts{'owsmp'};
my $aptbl=$opts{'aptbl'};
my $apsmp=$opts{'apsmp'};
my $delsmp=$opts{'delsmp'};
my $gfftbl=$opts{'gfftbl'};
my $omtbl=$opts{'omtbl'};
my $flds=$opts{'flds'};
my $apadd=$opts{'apadd'};
$apadd=0 if !defined($apadd);
my $format=$TABLE;
$format=$FILE if $opts{'format'} and lc($opts{'format'}) eq 'file';

die "owtbl not with owsmp" if ($owtbl and !$owsmp) or ($owsmp and !$owtbl);
die "aptbl not with apsmp" if ($aptbl and !$apsmp) or ($apsmp and !$aptbl);
die "owsmp,apsmp,delsmp cannot togeter" if ($owsmp and $apsmp) or ($apsmp and $delsmp) or ($owsmp and $delsmp);
die "format=$FILE but delsmp" if ($format eq $FILE) and $delsmp;
die "format=$FILE but flds" if ($format eq $FILE) and $flds;
die "apadd not with apsmp" if ($apadd and !$apsmp);

my $mode=$OW if $owtbl;
$mode=$AP if $aptbl;
$mode=$DEL if $delsmp;

if ($apadd==2) {
  print "apadd=$apadd (raw PAT number correcting mode...)\n";
}

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my $conf=$opts{'conf'};
my($dbh)=connectDB($conf,1);
my($sql,$rv);

#如果mtbl不存在,则先建立
if (!tblExists($dbh,$mtbl)) {
  die "mtbl=$mtbl not exists, but delsmp=$delsmp" if $delsmp;
  die "mtbl=$mtbl not exists, but omtbl=$omtbl" if $omtbl;
  my $s=$apsmp if $apsmp;
  $s=$owsmp if $owsmp;
  if ($gfftbl) {
    $sql="create table $mtbl (chr varchar(20),strand char(1),coord int,tot_tagnum int,gff_id float(10,1),$s int)";
  } else { #如果未提供gfftbl，则不要gff_id
    $sql="create table $mtbl (chr varchar(20),strand char(1),coord int,tot_tagnum int,$s int)";
  }
  print "$sql\n";
  $dbh->do($sql) or die;
}

#验证字段是否存在
my @tmp=('chr','strand','coord','tot_tagnum');
my @tmpId=getFldsIdx($dbh,$mtbl,@tmp);
die "@tmp not in master=$mtbl" if $tmpId[$#tmpId]==-1;
my $haveID=1;
my $id=getFldsIdx($dbh,$mtbl,'gff_id');
$haveID=0 if $id==-1;

#如果master表中不存在gff_id，但提供了gff表，也算错
if (!$haveID and $gfftbl) {
  die "No gff_id in master=$mtbl, but provide gfftbl=$gfftbl!";
}

#如果FILE,则先读入文件到临时表
my $TMPTBL=$mtbl.'_PAtmpxx';
if ($format eq $FILE) {
  if ($mode==$OW) {
    my $owfile=$owtbl;
	$owtbl=$TMPTBL;
    createPAtbl($dbh,$owtbl,0);
    $rv=loadFile2Tbl($dbh,$owtbl,$owfile,0);
	print "$rv PAs from $owfile\n";
  } elsif ($mode==$AP) {
    my $apfile=$aptbl;
	$aptbl=$TMPTBL;
    createPAtbl($dbh,$aptbl,0);
    $rv=loadFile2Tbl($dbh,$aptbl,$apfile,0);
	print "$rv PAs from $apfile\n";
  }
}

#验证: 如果PA小表是4列,不含gff_id,（但master表有gff_id），则必须有gfftbl及PAT_mapGff.pl存在
my $MAPGFF='PAT_mapGff.pl';
if ($mode==$OW or $mode==$AP) { 
 my $tmptbl=$owtbl if $mode==$OW;
 $tmptbl=$aptbl if $mode==$AP;
 die "$tmptbl not exists!" if !tblExists($dbh,$tmptbl);
 my @tmp=getPAFlds($tmptbl);
 if (gffIDfldType(@tmp)==2 and $haveID==1) {
   die "no gff_id in smallPAtbl, but mastertbl have gff_id, and not provide -gfftbl" if !$gfftbl;
   $MAPGFF='E:/sys/code/PAT/PAT_mapGff.pl' if !(-e 'PAT_mapGff.pl');
   if (!(-e $MAPGFF)) {
	  die "PAT_mapGff.pl not exists!" ;
   }  
 } 
}

if ($mode==$DEL) {  #删除
  print "******* Delete: $delsmp  ******* \n";
  delSmp($delsmp);
  print "********************************\n";
} 

elsif ($mode==$OW) { #重写: 先删除,再append
  print "******* OverWrite: delete $owsmp  ******* \n";
  delSmp($owsmp);
  print "\n******* OverWrite: append $owsmp  ******* \n";
  appendSmp($owtbl,$owsmp);
  print "****************************************\n";
}

elsif ($mode==$AP) { #更新
  print "******* Append: $apsmp  **************** \n";
  appendSmp($aptbl,$apsmp);
  print "****************************************\n";
}

#清理临时
if ($format eq $FILE) {
  $dbh->do("drop table if exists $TMPTBL") or die;
}

$dbh->disconnect();

#################################################
# SUB FUNCS
#################################################

#################################################
# delSmp(asmp) 删除样本
#################################################
sub delSmp {
  my $dsmp=shift;
  my $delId=getFldsIdx($dbh,$mtbl,$dsmp);
  print "delSmp: $dsmp not in $mtbl, return\n" if $delId==-1;
  return if $delId==-1;
  $sql="update $mtbl set tot_tagnum=tot_tagnum-$dsmp";
  $rv=$dbh->do($sql) or die;
  print "Update $mtbl (tot_tagnum-$dsmp): $rv rows\n";
  $sql="alter table $mtbl drop column $dsmp";
  $dbh->do($sql) or die;
  print "Update $mtbl (drop column $dsmp)\n";
  $sql="delete from $mtbl where tot_tagnum=0";
  $rv=$dbh->do($sql) or die;
  print "Update $mtbl (delete tot_tagnum=0); $rv rows\n";
}

#################################################
# appendSmp(patbl,asmp) 更新样本
# 若不存在,则添加;已存在,则更新;若qry无,master有,则master原有的不变
#################################################
sub appendSmp {
    my ($atbl,$asmp)=@_;
    my @pflds=getPAFlds($atbl);
    my $qrytbl=$atbl; #PA表
    my $sbjtbl=$mtbl; #master表
    #若atbl无gff_id(且master表含gff_id),先用PAT_mapGff.pl关联
    if (gffIDfldType(@pflds)==2 and $haveID) {
	  my $posfld=$pflds[2];
	  my $apGfftbl=$atbl.'_gff_id_xx';	  
	  my $opflds=join(':',@pflds);
	  my $cmd="$MAPGFF -gff $gfftbl -pa $atbl -posfld $posfld -out $apGfftbl -ogflds gff_id -opflds $opflds -conf $conf";
      print "---- PAT_mapGff.pl to relate $atbl with gff_id ----\n";
	  #print "  $cmd\n";
	  system $cmd;
	  print "---------------------------------------------------\n";
	  $qrytbl=$apGfftbl;
	  push(@pflds,'gff_id');
    }

    #master中的asmp对应的字段下标
    #如果master表不含asmp,则添加至master表的最后,否则更新
    my $asmpId=getFldsIdx($dbh,$mtbl,$asmp);
    my $addFld=0;
    if ($asmpId==-1) {
      my @t=getTblFlds($dbh,$mtbl);
	  $asmpId=$#t+1;
	  $addFld=1;
    }
    my $totId=getFldsIdx($dbh,$mtbl,'tot_tagnum');
	#遍历master和PA表,添加或修改master表
	my $qchrfld=$pflds[0];
	my $qstrandfld=$pflds[1];
	my $qposfld=$pflds[2];
	my ($qry,$sbj); 
	my @chrs=getFldValues($dbh,"SELECT distinct(lower(chr)) chr FROM $qrytbl order by chr",0);
	push(@chrs,getFldValues($dbh,"SELECT distinct(lower(chr)) chr FROM $sbjtbl order by chr",0));
    @chrs = uniq @chrs;

    my @strands=('+','-');

    my $ofile=getTmpPath(1)."alterMasterPA.tmp";
	unlink $ofile if -e $ofile;
    my $qryflds=join(',',@pflds);
    # 2011/9/28 用group by去除重PA的行 (chr,strand,coord,tagnum,<gffid>) 第4列是tagnum列，但名称不一定是tagnum
	my ($grpflds,$selflds,$having)=('','','');
	for my $p (0..$#pflds) {
	  if ($p!=3) {
		$grpflds.="$pflds[$p],";
		$selflds.="$pflds[$p],";
	  } else {
		$selflds.="sum($pflds[$p]),";
		$having="having sum($pflds[$p])>0";
	  }
	}
    $grpflds=substr($grpflds,0,length($grpflds)-1);
    $selflds=substr($selflds,0,length($selflds)-1);
	my ($qsCol)=2;
	my ($ssCol)=getFldsIdx($dbh,$mtbl,'coord');

	my @blankRow=getTblFlds($dbh,$mtbl);
	for my $i(0..$#blankRow) {
	  $blankRow[$i]=0;
	}
	push(@blankRow,0) if $addFld;	
	  my ($n1,$n2)=(0,0);
	  $qry=$sbj=[];
	  #数据: qry <chr,strand,coord,tagnum,gff_id>
	  #$sql="select $selflds from $qrytbl group by $qchrfld,$qstrandfld,$grpflds order by $qchrfld,$qstrandfld,$qposfld";
	  #2013-08-15 bug修正： alterPA后出现tot_tagnum=0的情况
	  $sql="select $selflds from $qrytbl group by $grpflds $having order by $qchrfld,$qstrandfld,$qposfld";
	  #print "\n\n$sql\n\n";
	  ($qry,$rv)=execSql($dbh,$sql);
	  print "$rv PAs; ";

	  if (0) { #$rv>500000
		$dbh->disconnect();
	    system "net stop mysql";
	    system "net start mysql";
	    ($dbh)=connectDB($conf,1); #一定要用一个($dbh) 不能只是 $dbh？？
	  }

	  my $idxsQry=getIntervals($qry,'0:1',1); #取得chr,strand对应的下标范围(start,end,(chr1,+))

	  $sql="select * from $sbjtbl order by chr,strand,coord";
	  ($sbj,$rv)=execSql($dbh,$sql);
	  print "$rv Masters\n";
	  my $idxsSbj=getIntervals($sbj,'0:1',1);

	  if ($addFld) {
		for my $i(0..$#$sbj) {
		  $sbj->[$i][$asmpId]=0;
		}
	  }

      ##取得qry+sbj的unique chr/strand
	  my @uniqCS=();
      for my $i(0..$#$idxsQry) {
		push(@uniqCS,$idxsQry->[$i][2]);
      }
      for my $i(0..$#$idxsSbj) {
		push(@uniqCS,$idxsSbj->[$i][2]);
      }
	  @uniqCS=uniq @uniqCS;

	  ##建hashQry和Sbj，方便找uniqCS
	  my (%hashQry,%hashSbj);
      for my $i(0..$#$idxsQry) {
		$hashQry{$idxsQry->[$i][2]}=($idxsQry->[$i][0].','.$idxsQry->[$i][1]);
      }
      for my $i(0..$#$idxsSbj) {
		$hashSbj{$idxsSbj->[$i][2]}=($idxsSbj->[$i][0].','.$idxsSbj->[$i][1]);
      }
	  $idxsQry=$idxsSbj=[];

	  ##每次取一条chr,strand
	for my $uq(@uniqCS) {       
	   ##找到qry和sbj在uniqCS中的对应区间
	   my ($qis,$qie,$sis,$sie)=(-1,-1,-1,-1);
	   if ($hashQry{$uq}) {
         my @tmps=split(/,/,$hashQry{$uq});
         ($qis,$qie)=($tmps[0],$tmps[1]);
	   }
	   if ($hashSbj{$uq}) {
         my @tmps=split(/,/,$hashSbj{$uq});
         ($sis,$sie)=($tmps[0],$tmps[1]);
	   }

	  if ($qis==-1 or $qie==-1) { #无qry,则master表不变
		saveMtx2File($sbj,$ofile,1,0,$sis,$sie);
		$n1=0;
	  } elsif ($sis==-1 or $sie==-1) { #无master,在apadd!=2时，则添加qry行 <chr,strand,coord,tot_tagnum,gff_id...>
	    if ($apadd!=2) {#2013-12-16
			my $tmp=[];
			for my $i($qis..$qie) {
			  push(@{$tmp},[@blankRow]);
			  @{$tmp->[$#$tmp]}[0..$#pflds]=@{$qry->[$i]}; #master表中铁定的前5列
			  $tmp->[$#$tmp][$asmpId]=$qry->[$i][3];  #样本列
			}
			saveMtx2File($tmp,$ofile,1,0);
			$n1=$qie-$qis+1;
        }
	  } else {   #都有,则添加新行,更新旧行      
		  #findOverlaps($qry,$sbj,$qsCol,$qeCol,$ssCol,$seCol,$leftMargin,$rightMargin,$select,$drop,$type,$minOverlap,$outputType):$mtxIdx
		  #qry与master点对点,不drop,每个qry都对应一行
		  my ($aqry,$asbj)=[];
		  for my $i($qis..$qie) {
			push(@{$aqry},[@{$qry->[$i]}]);
		  }
		  for my $i($sis..$sie) {
			push(@{$asbj},[@{$sbj->[$i]}]);
		  }  
		  
		  my $idxs=findOverlaps($aqry,$asbj,$qsCol,$qsCol,$ssCol,$ssCol,0,0,'first',0,'equal',1,0);
		  my ($qi,$si);
		  for my $i(0..$#$idxs) {
			$qi=$idxs->[$i][0];
			#print "aqry $qi: @{$aqry->[$qi]}\n";
			if (!defined($idxs->[$i][1]) and $apadd!=2) { #添加新行 (apadd=2时不添加新行)
			  push(@{$asbj},[@blankRow]);
			  @{$asbj->[$#$asbj]}[0..$#pflds]=@{$aqry->[$qi]};
			  $asbj->[$#$asbj][$asmpId]=$aqry->[$qi][3];
			  $n1++;
			} else { #更新旧行
			  $si=$idxs->[$i][1];
			  #print "asbj $si: @{$asbj->[$si]}\n";
			  if ($apadd==0) {
			    $asbj->[$si][$totId]-=$asbj->[$si][$asmpId];
			    $asbj->[$si][$asmpId]=$aqry->[$qi][3]; #样本列				  
			    $asbj->[$si][$totId]+=$aqry->[$qi][3]; #tot_tagnum
			    $n2++;
			  } elsif ($apadd==1) { #2013-12-16 #累加到原值
			    $asbj->[$si][$totId]-=$asbj->[$si][$asmpId];
			    $asbj->[$si][$asmpId]+=$aqry->[$qi][3]; #样本列累加				  
			    $asbj->[$si][$totId]+=$asbj->[$si][$asmpId]; #tot_tagnum
				$n2++;
			  }elsif ($apadd==2 and $asbj->[$si][$asmpId]>0 and $asbj->[$si][$asmpId]<$aqry->[$qi][3]) { #2013-12-16 #累加到原值 apadd=2 #如果旧行tag#=0或tag#大于新行，则不更新旧行，否则覆盖原值
			    $asbj->[$si][$totId]-=$asbj->[$si][$asmpId];
			    $asbj->[$si][$asmpId]=$aqry->[$qi][3]; #样本列高值时覆盖				  
			    $asbj->[$si][$totId]+=$asbj->[$si][$asmpId]; #tot_tagnum
				$n2++;
			  }
			}
		  }
		  $idxs=[];
		  saveMtx2File($asbj,$ofile,1,0);
		  $aqry=$asbj=[];
	  }
	  #print "Add $n1; Update $n2\n";
	 }#uq

   $qry=$sbj=[];
   %hashQry=%hashSbj=();
   print ">>>>Total Add $n1; Update $n2\n";
   $dbh->do("drop table if exists ${atbl}_gff_id_xx") or die;

   #导入数据库 
   my $om=$omtbl;
   my $over=0;
   if (!$omtbl or ($omtbl eq $mtbl)) {
     $om="${mtbl}_newxx";
	 $over=1;
   }
   print "Fill $mtbl; "; 
   $dbh->do("drop table if exists $om") or die;
   if ($addFld) {
	 $dbh->do("create table $om select *,tot_tagnum $asmp from $mtbl where 1<>1") or die;
   } else {
     $dbh->do("create table $om select * from $mtbl where 1<>1") or die;
   }    
   $rv=loadFile2Tbl($dbh,$om,$ofile,0);
   print "$rv rows\n";
   unlink $ofile if -e $ofile;
   cloneTblIndex($dbh,$mtbl,$om);
   if ($over) {
	 $dbh->do("drop table if exists $mtbl") or die;
     $dbh->do("alter table $om rename $mtbl") or die;
   }
}

#################################################
# getPAFlds(tbl)
# 根据opt及PA表取得有效的字段名 (如果返回长为5,则最后1列是gff_id)
# 带die判断
#################################################
sub getPAFlds { 
  my $tbl=shift;
  my (@pflds,@tmpId);
  if (!$flds) {
    @pflds=('chr','strand','coord','tagnum','gff_id');
    @tmpId=getFldsIdx($dbh,$tbl,@pflds);
	if ($tmpId[$#tmpId]==-1) {
	  @pflds=('chr','strand','coord','tagnum');
      @tmpId=getFldsIdx($dbh,$tbl,@pflds);
      if ($tmpId[$#tmpId]==-1) {
		die "NO flds option, and chr,strand,coord,tagnum not in $tbl\n";
	  }
	}
  } else {
    @pflds=split(/:/,$flds);
	die "$flds not contain 4 or 5 flds" if !gffIDfldType(@pflds);
	@tmpId=getFldsIdx($dbh,$tbl,@pflds);
    die "flds=@pflds not in $tbl; ID=@tmpId\n" if $tmpId[$#tmpId]==-1;
  }
  return @pflds;
}

#################################################
# gffIDfldType(@pflds);
# 判断字段的类型:0是错误,1含gff_id,2不含
#################################################
sub gffIDfldType { 
	my @f=@_;
	return 1 if scalar(@f)==5;
	return 2 if scalar(@f)==4;
	return 0;
}


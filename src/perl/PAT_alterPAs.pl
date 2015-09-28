#!/usr/bin/perl -w
#2015/4/9
#修改至 alterPA.pl
#方便PAs2PAC.pl的调用，能够一次处理一个master表的多个样本，而不像alterPA只能一次一个样本

#目的: 得到或修改masterPA; masterPA表是一个规范的PA表,不同样本各占1列,可以添加,删除或修改已有的行和列
#PA表:  至少含 chr,strand,coord,tagnum 或加 gff_id (不保留完整注释)
#masterPA表: 必须含<chr,strand,coord,tot_tagnum,gff_id>

#与alterPA的不同：
#多样本处理，允许一次处理一个表/文件的多个列，而不是一次只能处理一个列
#原来owsmp之类的，添加 xx:yy 表示一次处理2个列

#且把 alterPA.pl的 mapgff的选项去掉，所有master表均不提供gff_id形式的关联
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
use List::Util qw/max min/;


##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","master=s","owtbl:s","owsmp:s","aptbl:s","apsmp:s","delsmp:s","flds:s","omtbl:s","format:s","apadd:i","uq:s","verbose:s","conf=s");
my $USAGE=<< "_USAGE_";
Require: PAT_mapGff.pl if to relate gff_id
Usage: 

0) !若原PA文件可能含有相同的coord行，要加 -uq F 选项
PAT_alterPAs.pl -master t_test -owtbl F:/test.PA -owsmp mp1 -format file -conf dbconf_ricepa.xml
PAT_alterPAs.pl -master t_test  -delsmp mp1  -conf dbconf_ricepa.xml
PAT_alterPAs.pl -uq F -master t_test -owtbl F:/test.PA -owsmp mp1 -format file -conf dbconf_ricepa.xml

1) create masterPA table
PAT_alterPAs.pl -master t_test -owtbl F:/test.PA -owsmp mp1 -format file -conf dbconf_ricepa.xml

2) Add new sample
PAT_alterPAs.pl -master t_test -owtbl F:/test.PA -owsmp mp11 -format file -conf dbconf_ricepa.xml

3) update existing sample (!!!! apadd=0/1)
tag累加到原列
PAT_alterPAs.pl -master t_test -aptbl F:/test.PA -apsmp mp11 -apadd 1 -format file -conf dbconf_ricepa.xml
tag覆盖原列
PAT_alterPAs.pl -master t_test -aptbl F:/test.PA -apsmp mp11 -apadd 0 -format file -conf dbconf_ricepa.xml

4) user defined fields
PAT_alterPAs.pl -flds 0:1:2:4 -master t_test -aptbl F:/test.PA -apsmp mp2 -format file -conf dbconf_ricepa.xml

5) 多个列一起添加
PAT_alterPAs.pl -master t_test -aptbl F:/test.PA -apsmp mp3:mp4 -format file -conf dbconf_ricepa.xml
PAT_alterPAs.pl -flds 0:1:2:4:5 -master t_test -aptbl F:/test.PA -apsmp mp5:mp6 -format file -conf dbconf_ricepa.xml
PAT_alterPAs.pl -flds 0:1:2:3:4:5 -master t_test -aptbl F:/test.PA -apsmp mp1:mp2:mp3 -apadd 1 -format file -conf dbconf_ricepa.xml

5) 允许用PA表中规定的sample列，更新master列 （把t_test表中的mp4~6，累加到原表的mp1~3）
PAT_alterPAs.pl  -flds chr:strand:coord:mp4:mp5:mp6 -apadd 1 -master t_test -aptbl t_test -apsmp mp1:mp2:mp3 -format table -conf dbconf_ricepa.xml

(iii) Update existing PA table (NOT master table)
PAT_alterPAs.pl -master xx -owtbl t_pa_leaf -owsmp leaf -conf dbconf_arabtest.xml #format to master PA
PAT_alterPAs.pl -master xx -aptbl t_pa_seed -apsmp leaf -conf dbconf_arabtest.xml #append new dataset to master PA
alter table xx rename t_pa_leaf;... #change master to original table

-h=help
-master=master PA table, at least <chr,strand,coord,tot_tagnum,...different samples> 前面几列必须是这样，后面才是样本列！
-owtbl/owsmp=PA table to overwrite, owsmp must be a field of master table 
*-aptbl/apsmp=PA table to append, apsmp must be a field of master table (若uq=T，则aptbl的行坐标要是unique的，不能存在两行同坐标的情况)
*ap is insert new line and update (Overwrite! NOT add) the existing line
** apadd=0时，aptbl,apsmp 是直接更新原列中的相应的行值（但没有将tagnum加到原列，而是覆盖原列！！！！）
** 比如wt=10，新的wt=20，则最终master的WT还是为20,而不是30！！！
-delsmp=sample to delete, delsmp one in master table
-flds=PA子表中对应owsmp/apsmp的列名或列号
  --如果format=file，则默认是 0:1:2:(+3:4.. 与smps同列数)
  --如果format=table，则默认是 (chr:strand:coord)+smps
*PA table: table with flds: <chr,strand,coord,tagA,tagB...>
-omtbl=output master table, if '' or same as master then overwrite
*-format=input table(default) or file
*if format=file, the file only has 4 columns, and is chr:strand:coord:tagnum
-apadd=0(default)/1 （仅在apsmp和aptbl时有效）
 0时，添加新行，但用新的tag#去覆盖原来的tag#
 1时，添加新行，且可将tag#累加到原列（而非apadd=0只更新原列）
 2时，不添加新行，但将tag#高值覆盖原行
-uq=T(default)/F
-verbose=T(default)/F
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
my $omtbl=$opts{'omtbl'};
my $flds=$opts{'flds'}; 
my $apadd=$opts{'apadd'};
my $format=$TABLE;
$format=$FILE if $opts{'format'} and lc($opts{'format'}) eq 'file';
my $nogrp=1;
$nogrp=0 if $opts{'uq'} and  $opts{'uq'} eq 'F'; 
my $ver=1;
$ver=0 if $opts{'verbose'} and  $opts{'verbose'} eq 'F'; 

my @owsmps=split(/:/,$owsmp);
my @apsmps=split(/:/,$apsmp);
my @desmps=split(/:/,$delsmp);

die "owtbl not with owsmp" if ($owtbl and !$owsmp) or ($owsmp and !$owtbl);
die "aptbl not with apsmp" if ($aptbl and !$apsmp) or ($apsmp and !$aptbl);
die "owsmp,apsmp,delsmp cannot togeter" if ($owsmp and $apsmp) or ($apsmp and $delsmp) or ($owsmp and $delsmp);
die "format=$FILE but delsmp" if ($format eq $FILE) and $delsmp;
die "apadd not with apsmp" if ($apadd and !$apsmp);

my $mode=$OW if $owtbl;
$mode=$AP if $aptbl;
$mode=$DEL if $delsmp;

if ($apadd==2) {
  print "apadd=$apadd (raw PAT number correcting mode...)\n" if $ver;
}

#默认flds设定
if ($flds and $mode==$DEL) {
  die "mode=del but with flds=$flds";
}

if (!$flds) {
  if ($format==$FILE) {
     $flds="0:1:2";
	 if ($mode==$OW) {
	   for my $i(0..$#owsmps) {
		 $flds=$flds.":".($i+3);
	   }
	 }
	 if ($mode==$AP) {
	   for my $i(0..$#apsmps) {
		 $flds=$flds.":".($i+3);
	   }
	 }
  }
  if ($format==$TABLE) {
     $flds="chr:strand:coord";
	 if ($mode==$OW) {
	   for my $smp(0..$#owsmps) {
		 $flds=$flds.":$smp";
	   }
	 }
	 if ($mode==$AP) {
	   for my $smp(0..$#apsmps) {
		 $flds=$flds.":$smp";
	   }
	 }
  }
} #flds

my @pflds=(); #存放PA表的列名 chr,strand,coord+ owsmps/apsmps; 如果是FILE，则导入表后，重新变为列名
if ($mode!=$DEL and $flds) {
  @pflds=split(/:/,$flds);
  die "flds=$flds (less than 4 cols: chr,strand,coord,tagA,tagB..)" if scalar(@pflds)<4;
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

  my $txt='';
  if ($mode==$AP) {
	  for my $i(0..$#apsmps) {
		$txt.="$apsmps[$i] int,";
	  }  
  }
  if ($mode==$OW) {
	  for my $i(0..$#owsmps) {
		$txt.="$owsmps[$i] int,";
	  }  
  }
  $txt=substr($txt,0,length($txt)-1);
  $sql="create table $mtbl (chr varchar(20),strand char(1),coord int,tot_tagnum int,$txt)";
  print "$sql\n"  if $ver;
  $dbh->do($sql) or die;
}

#验证字段是否存在
my @tmp=('chr','strand','coord','tot_tagnum');
my @tmpId=getFldsIdx($dbh,$mtbl,@tmp);
die "@tmp not in master=$mtbl" if $tmpId[$#tmpId]==-1;

#如果FILE,则先读入文件到临时表
my $TMPTBL=$mtbl.'_PAtmpxx';
if ($format eq $FILE) {
	my $maxID=max @pflds;
  if ($mode==$OW) {
    my $owfile=$owtbl;
	$owtbl=$TMPTBL;
    createPAtbl($dbh,$owtbl,0,$owsmp);
	my $ncol=ncolFile($owfile); #chr strand coord XX YY
	die "max(@pflds)> last col in $owfile" if $maxID>$ncol-1;
	if ($ncol>scalar(@owsmps)+3) { #若提供的owfile的列数多于owsmps，则必须通过flds选项，取得相应的列 chr:strand:coord:xx:yy
       my $tmpfile=$owfile.".$ncol".'cols';
	   extractCols($owfile,$tmpfile,@pflds);
      $rv=loadFile2Tbl($dbh,$owtbl,$tmpfile,0);
	  unlink($tmpfile);
	} elsif ($ncol<scalar(@owsmps)+3) {
	  die "less columns in $owfile for @owsmps\n";
	} else {
      $rv=loadFile2Tbl($dbh,$owtbl,$owfile,0);
	}
	print "$rv PAs from $owfile\n" if $ver;
	@pflds=('chr','strand','coord',@owsmps);
  } elsif ($mode==$AP) {
    my $apfile=$aptbl;
	$aptbl=$TMPTBL;
    createPAtbl($dbh,$aptbl,0,$apsmp);
	my $ncol=ncolFile($apfile); #chr strand coord XX YY
	die "max(@pflds)> last col in $apfile" if $maxID>$ncol-1;
	if ($ncol>scalar(@apsmps)+3) {
       my $tmpfile=$apfile.".$ncol".'cols';
	   extractCols($apfile,$tmpfile,@pflds);
      $rv=loadFile2Tbl($dbh,$aptbl,$tmpfile,0);
	  unlink($tmpfile);
	} elsif ($ncol<scalar(@apsmps)+3) {
	  die "less columns in $apfile for @apsmps\n";
	} else {
      $rv=loadFile2Tbl($dbh,$aptbl,$apfile,0);
	}
	print "$rv PAs from $apfile\n" if $ver;
	@pflds=('chr','strand','coord',@apsmps);
  }
}

if ($mode==$DEL) {  #删除
  print "******* Delete: $delsmp  ******* \n" if $ver;
  delSmp(@desmps);
  print "********************************\n" if $ver;
} 

elsif ($mode==$OW) { #重写: 先删除,再append
  print "******* OverWrite: delete $owsmp  ******* \n" if $ver;
  delSmp($owsmp);
  print "\n******* OverWrite: append $owsmp  ******* \n" if $ver;
  appendSmp($owtbl,@owsmps);
  print "****************************************\n" if $ver;
}

elsif ($mode==$AP) { #更新
  print "******* Append: $apsmp  **************** \n" if $ver;
  appendSmp($aptbl,@apsmps);
  print "****************************************\n" if $ver;
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
  my @dsmps=@_;
  for my $dsmp(@dsmps) {
	  my $delId=getFldsIdx($dbh,$mtbl,$dsmp);
	  print "delSmp: $dsmp not in $mtbl\n" if $delId==-1 and $ver;
	  next if $delId==-1;
	  $sql="update $mtbl set tot_tagnum=tot_tagnum-$dsmp";
	  $rv=$dbh->do($sql) or die;
	  print "Update $mtbl (tot_tagnum-($dsmp)): $rv rows\n" if $ver;
	  $sql="alter table $mtbl drop column $dsmp";
	  $dbh->do($sql) or die;
	  print "Update $mtbl (drop column $dsmp)\n" if $ver;
  }  
  $sql="delete from $mtbl where tot_tagnum=0";
  $rv=$dbh->do($sql) or die;
  print "Update $mtbl (delete tot_tagnum=0); $rv rows\n" if $rv>0 and $ver;

}

#################################################
# appendSmp(patbl,asmp) 更新样本
# 若不存在,则添加;已存在,则更新;若qry无,master有,则master原有的不变
#################################################
sub appendSmp {
    my ($atbl,@asmps)=@_;

    my $qrytbl=$atbl; #PA表
    my $sbjtbl=$mtbl; #master表

	#@asmpIDs: master中的asmp对应的字段下标
    #如果master表不含asmp,则添加至master表的最后,否则更新
	#如果多个样本，如AA:BB，则asmpIDs的顺序，要和qry表中的样本列顺序一致
    my @asmpIDs=getFldsIdx($dbh,$mtbl,@asmps);
	pop(@asmpIDs) if $#asmpIDs>0;
	my @addFlds=();
	my $j=0;
	for my $i(0..$#asmpIDs) {
	  $addFlds[$i]=0;
		if ($asmpIDs[$i]==-1) {
		  $j++;
		  my @t=getTblFlds($dbh,$mtbl);
		  $asmpIDs[$i]=$#t+$j;
		  $addFlds[$i]=1;
		}
	}

    my $totId=getFldsIdx($dbh,$mtbl,'tot_tagnum');
	my $ncolQry=3+scalar(@asmpIDs); #PA表的列数

    my(@tmpID)=getFldsIdx($dbh,$atbl,@pflds);
	die "@pflds not all in $atbl" if ($tmpID[$#tmpID]==-1);

	#遍历master和PA表,添加或修改master表
	#@pflds: chr,strand,coord,AA,BB
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


    # 2011/9/28 用group by去除重PA的行 (chr,strand,coord,XX,YY) 第4列是tagnum列，但名称不一定是tagnum
	if (!$nogrp) { #2015/4/8 判断是否要用group by
		my ($selflds,$having)=('','','');
		my $having="having sum(".join('+',@pflds[3..$#pflds]).')>0'; # having sum(AA+BB)>0
		for my $p (3..$#pflds) {
		  $selflds.="sum($pflds[$p]),";
		}
		$selflds=substr($selflds,0,length($selflds)-1);
      $sql="select $qchrfld,$qstrandfld,$qposfld,$selflds from $qrytbl group by $qchrfld,$qstrandfld,$qposfld $having order by $qchrfld,$qstrandfld,$qposfld";
	} else {
      $sql="select ".join(',',@pflds)." from $qrytbl where ".join('+',@pflds[3..$#pflds]).'>0'." order by $qchrfld,$qstrandfld,$qposfld";
	}
	#print "\n\n$sql\n";
	my ($qsCol)=2;
	my (@tmpID)=getFldsIdx($dbh,$mtbl,('chr','strand','coord'));
	die "chr,strand,coord not 0:1:2 in $mtbl" if ($tmpID[0]!=0 or $tmpID[1]!=1 or $tmpID[2]!=2);

	my $ssCol=2;

	my @blankRow=getTblFlds($dbh,$mtbl);
	for my $i(0..$#blankRow) {
	  $blankRow[$i]=0;
	}
	for my $addFld(@addFlds) {
	  push(@blankRow,0) if $addFld;	
	}

	  my $n2=0;

	  $qry=$sbj=[];
	  #数据: qry <chr,strand,coord,AA,BB>
	  ($qry,$rv)=execSql($dbh,$sql);
	  print "$rv PAs; " if $ver;

	  $sql="select * from $sbjtbl order by chr,strand,coord";
	  ($sbj,$rv)=execSql($dbh,$sql);
	  print "$rv Masters\n" if $ver;

	  if ($rv>1000000) { #2015/4/8 如果master表很大，则每次添加一个query，就stop/start一下
		$dbh->disconnect();
	    system "net stop mysql";
	    system "net start mysql";
	    ($dbh)=connectDB($conf,1); #一定要用一个($dbh) 不能只是 $dbh？？
	  }

      #如果添加新的样本，则master加全0列
      for my $i(0..$#addFlds) {
		  if ($addFlds[$i]) {
			for my $j(0..$#$sbj) {
			  $sbj->[$j][$asmpIDs[$i]]=0;
			}
		  }
      }

	  my $idxsQry=getIntervals($qry,'0:1',1); #取得chr,strand对应的下标范围(start,end,(chr1,+))
	  my $idxsSbj=getIntervals($sbj,'0:1',1);

	  ##以下是对 $sbj和 $qry 作处理，所以只要控制给它的sbj和qry是什么，后面就处理多少数据
	  ##比如只给sbj和qry一个chr，则后面处理的也是一个chr的东西
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

	  #print currTime()."\n"; #hash这一步基本上不花时间

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
	  } elsif ($sis==-1 or $sie==-1) { #无master,在apadd!=2时，则添加qry行 <chr,strand,coord,AA,BB...>
	    if ($apadd!=2) {#2013-12-16
			my $tmp=[];
			for my $i($qis..$qie) {
			  push(@{$tmp},[@blankRow]);
			  @{$tmp->[$#$tmp]}[0..2]=@{$qry->[$i]}[0..2]; #master表中的前3列
			  @{$tmp->[$#$tmp]}[@asmpIDs]=@{$qry->[$i]}[3..($ncolQry-1)];  #样本列
              for my $j(3..($ncolQry-1)) {
    			$tmp->[$#$tmp][$totId]+=$qry->[$i][$j]; #tot_tagnum
              }
			}
			saveMtx2File($tmp,$ofile,1,0);
			$n2+=$qie-$qis+1;
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
			if (!defined($idxs->[$i][1]) and $apadd!=2) { #添加新行 (apadd=2时不添加新行)
			  push(@{$asbj},[@blankRow]);
			  @{$asbj->[$#$asbj]}[0..2]=@{$aqry->[$qi]}[0..2]; #master表中的前3列
			  @{$asbj->[$#$asbj]}[@asmpIDs]=@{$aqry->[$qi]}[3..($ncolQry-1)];  #样本列
              for my $j(3..($ncolQry-1)) {
    			$asbj->[$#$asbj][$totId]+=$aqry->[$qi][$j]; #tot_tagnum
              }
			  $n2++;
			} else { #更新旧行
			  $si=$idxs->[$i][1];
			  #print "asbj $si: @{$asbj->[$si]}\n";
			  if ($apadd==0) { #覆盖
               for my $j(0..$#asmpIDs) {
				$asbj->[$si][$totId]-=$asbj->[$si][$asmpIDs[$j]];
				$asbj->[$si][$asmpIDs[$j]]=$aqry->[$qi][$j+3]; #样本列				  
				$asbj->[$si][$totId]+=$aqry->[$qi][$j+3]; #tot_tagnum
	           } 
			    $n2++;
			  } elsif ($apadd==1) { #2013-12-16 #累加到原值
               for my $j(0..$#asmpIDs) {
				$asbj->[$si][$asmpIDs[$j]]+=$aqry->[$qi][$j+3]; #样本列				  
				$asbj->[$si][$totId]+=$aqry->[$qi][$j+3]; #tot_tagnum
	           } 
				$n2++;
			  }elsif ($apadd==2 ) { #2013-12-16 #累加到原值 apadd=2 #如果旧行tag#=0或tag#大于新行，则不更新旧行，否则覆盖原值
               for my $j(0..$#asmpIDs) {
				if ($asbj->[$si][$asmpIDs[$j]]>0 and $asbj->[$si][$asmpIDs[$j]]<$aqry->[$qi][3+$j]) {
					$asbj->[$si][$totId]-=$asbj->[$si][$asmpIDs[$j]];
					$asbj->[$si][$asmpIDs[$j]]=$aqry->[$qi][$j+3]; #样本列高值时覆盖				  
					$asbj->[$si][$totId]+=$aqry->[$qi][$j+3]; #tot_tagnum
				}
	           } 			    
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
   print ">>>>Add/Update $n2\n" if $ver;

   #导入数据库   
   my $om=$omtbl;
   my $over=0;
   if (!$omtbl or ($omtbl eq $mtbl)) {
     $om="${mtbl}_newxx";
	 $over=1;
   }
   print "Fill $mtbl; " if $ver;
   $dbh->do("drop table if exists $om") or die;

   my $txt='';
   for my $i(0..$#addFlds) {
	  if ($addFlds[$i]) {
		$txt.="tot_tagnum $asmps[$i],";
	  }    
   }
   if ($txt ne '') {
	 $txt=substr($txt,0,length($txt)-1);
	 $dbh->do("create table $om select *,$txt from $mtbl where 1<>1") or die;
   } else {
     $dbh->do("create table $om select * from $mtbl where 1<>1") or die;
   }   
   $rv=loadFile2Tbl($dbh,$om,$ofile,0);
   print "$rv rows\n" if $ver;
   unlink $ofile if -e $ofile;
   cloneTblIndex($dbh,$mtbl,$om);
   if ($over) {
	 $dbh->do("drop table if exists $mtbl") or die;
     $dbh->do("alter table $om rename $mtbl") or die;
   }
} #sub

#从一个文件中提取指定列，输出另一个文件
sub extractCols {
       my ($infile,$tofile,@pflds)=@_;
	   open (IN,"<$infile") or die "cannot open $infile";
	   open (OUT,">$tofile") or die "cannot write $tofile";
	   while (my $line=<IN>) {
		 $line=trim($line);
		 my @items=split(/\t/,$line);
		 @items=@items[@pflds];
		 print OUT join("\t",@items)."\n";
	   }
	   close(IN);
	   close(OUT);
}


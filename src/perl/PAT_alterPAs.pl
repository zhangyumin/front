#!/usr/bin/perl -w
#2015/4/9
#�޸��� alterPA.pl
#����PAs2PAC.pl�ĵ��ã��ܹ�һ�δ���һ��master��Ķ��������������alterPAֻ��һ��һ������

#Ŀ��: �õ����޸�masterPA; masterPA����һ���淶��PA��,��ͬ������ռ1��,�������,ɾ�����޸����е��к���
#PA��:  ���ٺ� chr,strand,coord,tagnum ��� gff_id (����������ע��)
#masterPA��: ���뺬<chr,strand,coord,tot_tagnum,gff_id>

#��alterPA�Ĳ�ͬ��
#��������������һ�δ���һ����/�ļ��Ķ���У�������һ��ֻ�ܴ���һ����
#ԭ��owsmp֮��ģ���� xx:yy ��ʾһ�δ���2����

#�Ұ� alterPA.pl�� mapgff��ѡ��ȥ��������master������ṩgff_id��ʽ�Ĺ���
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

0) !��ԭPA�ļ����ܺ�����ͬ��coord�У�Ҫ�� -uq F ѡ��
PAT_alterPAs.pl -master t_test -owtbl F:/test.PA -owsmp mp1 -format file -conf dbconf_ricepa.xml
PAT_alterPAs.pl -master t_test  -delsmp mp1  -conf dbconf_ricepa.xml
PAT_alterPAs.pl -uq F -master t_test -owtbl F:/test.PA -owsmp mp1 -format file -conf dbconf_ricepa.xml

1) create masterPA table
PAT_alterPAs.pl -master t_test -owtbl F:/test.PA -owsmp mp1 -format file -conf dbconf_ricepa.xml

2) Add new sample
PAT_alterPAs.pl -master t_test -owtbl F:/test.PA -owsmp mp11 -format file -conf dbconf_ricepa.xml

3) update existing sample (!!!! apadd=0/1)
tag�ۼӵ�ԭ��
PAT_alterPAs.pl -master t_test -aptbl F:/test.PA -apsmp mp11 -apadd 1 -format file -conf dbconf_ricepa.xml
tag����ԭ��
PAT_alterPAs.pl -master t_test -aptbl F:/test.PA -apsmp mp11 -apadd 0 -format file -conf dbconf_ricepa.xml

4) user defined fields
PAT_alterPAs.pl -flds 0:1:2:4 -master t_test -aptbl F:/test.PA -apsmp mp2 -format file -conf dbconf_ricepa.xml

5) �����һ�����
PAT_alterPAs.pl -master t_test -aptbl F:/test.PA -apsmp mp3:mp4 -format file -conf dbconf_ricepa.xml
PAT_alterPAs.pl -flds 0:1:2:4:5 -master t_test -aptbl F:/test.PA -apsmp mp5:mp6 -format file -conf dbconf_ricepa.xml
PAT_alterPAs.pl -flds 0:1:2:3:4:5 -master t_test -aptbl F:/test.PA -apsmp mp1:mp2:mp3 -apadd 1 -format file -conf dbconf_ricepa.xml

5) ������PA���й涨��sample�У�����master�� ����t_test���е�mp4~6���ۼӵ�ԭ���mp1~3��
PAT_alterPAs.pl  -flds chr:strand:coord:mp4:mp5:mp6 -apadd 1 -master t_test -aptbl t_test -apsmp mp1:mp2:mp3 -format table -conf dbconf_ricepa.xml

(iii) Update existing PA table (NOT master table)
PAT_alterPAs.pl -master xx -owtbl t_pa_leaf -owsmp leaf -conf dbconf_arabtest.xml #format to master PA
PAT_alterPAs.pl -master xx -aptbl t_pa_seed -apsmp leaf -conf dbconf_arabtest.xml #append new dataset to master PA
alter table xx rename t_pa_leaf;... #change master to original table

-h=help
-master=master PA table, at least <chr,strand,coord,tot_tagnum,...different samples> ǰ�漸�б�����������������������У�
-owtbl/owsmp=PA table to overwrite, owsmp must be a field of master table 
*-aptbl/apsmp=PA table to append, apsmp must be a field of master table (��uq=T����aptbl��������Ҫ��unique�ģ����ܴ�������ͬ��������)
*ap is insert new line and update (Overwrite! NOT add) the existing line
** apadd=0ʱ��aptbl,apsmp ��ֱ�Ӹ���ԭ���е���Ӧ����ֵ����û�н�tagnum�ӵ�ԭ�У����Ǹ���ԭ�У���������
** ����wt=10���µ�wt=20��������master��WT����Ϊ20,������30������
-delsmp=sample to delete, delsmp one in master table
-flds=PA�ӱ��ж�Ӧowsmp/apsmp���������к�
  --���format=file����Ĭ���� 0:1:2:(+3:4.. ��smpsͬ����)
  --���format=table����Ĭ���� (chr:strand:coord)+smps
*PA table: table with flds: <chr,strand,coord,tagA,tagB...>
-omtbl=output master table, if '' or same as master then overwrite
*-format=input table(default) or file
*if format=file, the file only has 4 columns, and is chr:strand:coord:tagnum
-apadd=0(default)/1 ������apsmp��aptblʱ��Ч��
 0ʱ��������У������µ�tag#ȥ����ԭ����tag#
 1ʱ��������У��ҿɽ�tag#�ۼӵ�ԭ�У�����apadd=0ֻ����ԭ�У�
 2ʱ����������У�����tag#��ֵ����ԭ��
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

#Ĭ��flds�趨
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

my @pflds=(); #���PA������� chr,strand,coord+ owsmps/apsmps; �����FILE�����������±�Ϊ����
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

#���mtbl������,���Ƚ���
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

#��֤�ֶ��Ƿ����
my @tmp=('chr','strand','coord','tot_tagnum');
my @tmpId=getFldsIdx($dbh,$mtbl,@tmp);
die "@tmp not in master=$mtbl" if $tmpId[$#tmpId]==-1;

#���FILE,���ȶ����ļ�����ʱ��
my $TMPTBL=$mtbl.'_PAtmpxx';
if ($format eq $FILE) {
	my $maxID=max @pflds;
  if ($mode==$OW) {
    my $owfile=$owtbl;
	$owtbl=$TMPTBL;
    createPAtbl($dbh,$owtbl,0,$owsmp);
	my $ncol=ncolFile($owfile); #chr strand coord XX YY
	die "max(@pflds)> last col in $owfile" if $maxID>$ncol-1;
	if ($ncol>scalar(@owsmps)+3) { #���ṩ��owfile����������owsmps�������ͨ��fldsѡ�ȡ����Ӧ���� chr:strand:coord:xx:yy
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

if ($mode==$DEL) {  #ɾ��
  print "******* Delete: $delsmp  ******* \n" if $ver;
  delSmp(@desmps);
  print "********************************\n" if $ver;
} 

elsif ($mode==$OW) { #��д: ��ɾ��,��append
  print "******* OverWrite: delete $owsmp  ******* \n" if $ver;
  delSmp($owsmp);
  print "\n******* OverWrite: append $owsmp  ******* \n" if $ver;
  appendSmp($owtbl,@owsmps);
  print "****************************************\n" if $ver;
}

elsif ($mode==$AP) { #����
  print "******* Append: $apsmp  **************** \n" if $ver;
  appendSmp($aptbl,@apsmps);
  print "****************************************\n" if $ver;
}

#������ʱ
if ($format eq $FILE) {
  $dbh->do("drop table if exists $TMPTBL") or die;
}

$dbh->disconnect();

#################################################
# SUB FUNCS
#################################################

#################################################
# delSmp(asmp) ɾ������
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
# appendSmp(patbl,asmp) ��������
# ��������,�����;�Ѵ���,�����;��qry��,master��,��masterԭ�еĲ���
#################################################
sub appendSmp {
    my ($atbl,@asmps)=@_;

    my $qrytbl=$atbl; #PA��
    my $sbjtbl=$mtbl; #master��

	#@asmpIDs: master�е�asmp��Ӧ���ֶ��±�
    #���master����asmp,�������master������,�������
	#��������������AA:BB����asmpIDs��˳��Ҫ��qry���е�������˳��һ��
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
	my $ncolQry=3+scalar(@asmpIDs); #PA�������

    my(@tmpID)=getFldsIdx($dbh,$atbl,@pflds);
	die "@pflds not all in $atbl" if ($tmpID[$#tmpID]==-1);

	#����master��PA��,��ӻ��޸�master��
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


    # 2011/9/28 ��group byȥ����PA���� (chr,strand,coord,XX,YY) ��4����tagnum�У������Ʋ�һ����tagnum
	if (!$nogrp) { #2015/4/8 �ж��Ƿ�Ҫ��group by
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
	  #����: qry <chr,strand,coord,AA,BB>
	  ($qry,$rv)=execSql($dbh,$sql);
	  print "$rv PAs; " if $ver;

	  $sql="select * from $sbjtbl order by chr,strand,coord";
	  ($sbj,$rv)=execSql($dbh,$sql);
	  print "$rv Masters\n" if $ver;

	  if ($rv>1000000) { #2015/4/8 ���master��ܴ���ÿ�����һ��query����stop/startһ��
		$dbh->disconnect();
	    system "net stop mysql";
	    system "net start mysql";
	    ($dbh)=connectDB($conf,1); #һ��Ҫ��һ��($dbh) ����ֻ�� $dbh����
	  }

      #�������µ���������master��ȫ0��
      for my $i(0..$#addFlds) {
		  if ($addFlds[$i]) {
			for my $j(0..$#$sbj) {
			  $sbj->[$j][$asmpIDs[$i]]=0;
			}
		  }
      }

	  my $idxsQry=getIntervals($qry,'0:1',1); #ȡ��chr,strand��Ӧ���±귶Χ(start,end,(chr1,+))
	  my $idxsSbj=getIntervals($sbj,'0:1',1);

	  ##�����Ƕ� $sbj�� $qry ����������ֻҪ���Ƹ�����sbj��qry��ʲô������ʹ����������
	  ##����ֻ��sbj��qryһ��chr������洦���Ҳ��һ��chr�Ķ���
      ##ȡ��qry+sbj��unique chr/strand
	  my @uniqCS=();
      for my $i(0..$#$idxsQry) {
		push(@uniqCS,$idxsQry->[$i][2]);
      }
      for my $i(0..$#$idxsSbj) {
		push(@uniqCS,$idxsSbj->[$i][2]);
      }
	  @uniqCS=uniq @uniqCS;

	  ##��hashQry��Sbj��������uniqCS
	  my (%hashQry,%hashSbj);
      for my $i(0..$#$idxsQry) {
		$hashQry{$idxsQry->[$i][2]}=($idxsQry->[$i][0].','.$idxsQry->[$i][1]);
      }
      for my $i(0..$#$idxsSbj) {
		$hashSbj{$idxsSbj->[$i][2]}=($idxsSbj->[$i][0].','.$idxsSbj->[$i][1]);
      }
	  $idxsQry=$idxsSbj=[];

	  #print currTime()."\n"; #hash��һ�������ϲ���ʱ��

	##ÿ��ȡһ��chr,strand
	for my $uq(@uniqCS) {       
	   ##�ҵ�qry��sbj��uniqCS�еĶ�Ӧ����
	   my ($qis,$qie,$sis,$sie)=(-1,-1,-1,-1);
	   if ($hashQry{$uq}) {
         my @tmps=split(/,/,$hashQry{$uq});
         ($qis,$qie)=($tmps[0],$tmps[1]);
	   }
	   if ($hashSbj{$uq}) {
         my @tmps=split(/,/,$hashSbj{$uq});
         ($sis,$sie)=($tmps[0],$tmps[1]);
	   }

	  if ($qis==-1 or $qie==-1) { #��qry,��master����
		saveMtx2File($sbj,$ofile,1,0,$sis,$sie);
	  } elsif ($sis==-1 or $sie==-1) { #��master,��apadd!=2ʱ�������qry�� <chr,strand,coord,AA,BB...>
	    if ($apadd!=2) {#2013-12-16
			my $tmp=[];
			for my $i($qis..$qie) {
			  push(@{$tmp},[@blankRow]);
			  @{$tmp->[$#$tmp]}[0..2]=@{$qry->[$i]}[0..2]; #master���е�ǰ3��
			  @{$tmp->[$#$tmp]}[@asmpIDs]=@{$qry->[$i]}[3..($ncolQry-1)];  #������
              for my $j(3..($ncolQry-1)) {
    			$tmp->[$#$tmp][$totId]+=$qry->[$i][$j]; #tot_tagnum
              }
			}
			saveMtx2File($tmp,$ofile,1,0);
			$n2+=$qie-$qis+1;
        }
	  } else {   #����,���������,���¾���      
		  #findOverlaps($qry,$sbj,$qsCol,$qeCol,$ssCol,$seCol,$leftMargin,$rightMargin,$select,$drop,$type,$minOverlap,$outputType):$mtxIdx
		  #qry��master��Ե�,��drop,ÿ��qry����Ӧһ��
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
			if (!defined($idxs->[$i][1]) and $apadd!=2) { #������� (apadd=2ʱ���������)
			  push(@{$asbj},[@blankRow]);
			  @{$asbj->[$#$asbj]}[0..2]=@{$aqry->[$qi]}[0..2]; #master���е�ǰ3��
			  @{$asbj->[$#$asbj]}[@asmpIDs]=@{$aqry->[$qi]}[3..($ncolQry-1)];  #������
              for my $j(3..($ncolQry-1)) {
    			$asbj->[$#$asbj][$totId]+=$aqry->[$qi][$j]; #tot_tagnum
              }
			  $n2++;
			} else { #���¾���
			  $si=$idxs->[$i][1];
			  #print "asbj $si: @{$asbj->[$si]}\n";
			  if ($apadd==0) { #����
               for my $j(0..$#asmpIDs) {
				$asbj->[$si][$totId]-=$asbj->[$si][$asmpIDs[$j]];
				$asbj->[$si][$asmpIDs[$j]]=$aqry->[$qi][$j+3]; #������				  
				$asbj->[$si][$totId]+=$aqry->[$qi][$j+3]; #tot_tagnum
	           } 
			    $n2++;
			  } elsif ($apadd==1) { #2013-12-16 #�ۼӵ�ԭֵ
               for my $j(0..$#asmpIDs) {
				$asbj->[$si][$asmpIDs[$j]]+=$aqry->[$qi][$j+3]; #������				  
				$asbj->[$si][$totId]+=$aqry->[$qi][$j+3]; #tot_tagnum
	           } 
				$n2++;
			  }elsif ($apadd==2 ) { #2013-12-16 #�ۼӵ�ԭֵ apadd=2 #�������tag#=0��tag#�������У��򲻸��¾��У����򸲸�ԭֵ
               for my $j(0..$#asmpIDs) {
				if ($asbj->[$si][$asmpIDs[$j]]>0 and $asbj->[$si][$asmpIDs[$j]]<$aqry->[$qi][3+$j]) {
					$asbj->[$si][$totId]-=$asbj->[$si][$asmpIDs[$j]];
					$asbj->[$si][$asmpIDs[$j]]=$aqry->[$qi][$j+3]; #�����и�ֵʱ����				  
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

   #�������ݿ�   
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

#��һ���ļ�����ȡָ���У������һ���ļ�
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


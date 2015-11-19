#!/usr/bin/perl -w
#2011/5/4
#Ŀ��: �õ����޸�masterPA; masterPA����һ���淶��PA��,��ͬ������ռ1��,�������,ɾ�����޸����е��к���
#PA��:  ���ٺ� chr,strand,coord,tagnum ��� gff_id (����������ע��)
#masterPA��: ���뺬<chr,strand,coord,tot_tagnum,gff_id>

#��������PA����gff_id,��������gff_id���ټ���masterPA��
#�Ƚ�ʱ,ֻ�Ƚ� chr,strand,coord,�����о���master��Ϊ׼

#ֻ�ṩ��ȫ����д:ow �� ����:ap(��ͬ���в��ۼ�tagnum,ֻ����,2012-09-10û���׵�����ô����ԭ�򣡣���) ���ṩ�ۼӵ�׷��.

#2011/5/7
#�ṩformat=table(Ĭ��)��fileѡ��,������file��ʽ�ṩPA�ļ�
#��format=file,�����ֻ��4��Ϊchr:strand:coord:tagnum

#2011/5/18
#�޸�gff_id��Ϊfloat(10,1)

#2011/9/28
#BUG�����������ظ����������������ļ���ÿ��һ��PA���������PAT��������
#������������Oxt6��Ϻ󣬻���ͬһcoord����ͬ�е������

#2011/11/7
#���usage������������������PAtbl��owtbl��aptbl�����У�����fromsmp��ȡ��PA���ݣ������master���owsmp��apsmp��

#2012-09-09
#mtr����������long/short �������Ҫ�õ�MapGff.pl������Ҫdbconf.longchr��
#2012-09-10 ����Mtr
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
#����ѡ�� apadd��0(default)/1����1ʱ����apsmp/aptbl������£���tag#�ۼӵ�ԭ�У�����֮ǰ��ֻ��tag#����ԭ�У�

#2013-02-18
#����ʹ��gff��������master����master����gffid��
#��� master�����gffid �� gfftbl ���ṩ����ԭ���ķ�ʽ
#��� master�����ڣ������ṩgfftbl����ԭ���ķ�ʽ
#��� master�����ڣ�����gffid)��δ�ṩgfftbl�����µ�master�����ۼӺ��master������gffid

#2013-08-15 bug������ alterPA�����tot_tagnum=0�����
#���СPA���д��ڶ���1�е�PA����ֻ������1�кϲ���masterʱ�����PA�������ж����������һ������Ϊ0

#2013-12-16 ���apadd=2ѡ��
#����PA���Բ�������еķ�ʽ��ֵ���ǵ�master����,��master.fld��tag#����aptbl��tag#����Ҳ������
#��Ҫ����raw data mappingʱ����PAT������
#������L30N1map�õ���׼��master������֤PA��λ���ǲ����
#����L20N1map�õ��ı�ȥ����ԭ����PA��tag#����������µ�PAT#��ʹ����map�õ���PAT#�������ӣ�
#��ʹmaster������wt��oxt�У���ֻ����oxt�У���apadd=2Ҳ����Ч�ģ������л��Զ��ж�master.oxt�Ƿ�>0����>0�Ÿ��£�

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
5) ������PA���й涨��sample�У�����master��(����flds�е�tagnum�У��������ol_s1��
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

(XX) !! ��gff_id��master��master�����ڣ�����gffid)�����ṩgfftbl�����µ�master�����ۼӺ��master������gffid
PAT_alterPA.pl -master masterPA -owtbl c:/wt.PA -owsmp wt -format file -conf dbconf_arabtest.xml

(IVI) apadd=2 ������raw mappingʱ��PAT������
��ԭmaster���У���ֵ����ԭ�У�����������У�
PAT_alterPA.pl -master masterPA -aptbl c:/wtL20N1.PA -apsmp wt -apadd 2 -format file -conf dbconf_arabtest.xml

-h=help
-master=master PA table, at least <chr,strand,coord,tot_tagnum,gff_id,...different samples> ǰ�漸�б�����������������������У�
-owtbl/owsmp=PA table to overwrite, owsmp must be a field of master table
*-aptbl/apsmp=PA table to append, apsmp must be a field of master table
*ap is insert new line and update (Overwrite! NOT add) the existing line
** apadd=0ʱ��aptbl,apsmp ��ֱ�Ӹ���ԭ���е���Ӧ����ֵ����û�н�tagnum�ӵ�ԭ�У����Ǹ���ԭ�У���������
** ����wt=10���µ�wt=20��������master��WT����Ϊ20,������30������
-gfftbl=gff table to get gff_id (if no gff_id in OW or AP table)
-delsmp=sample to delete, delsmp one in master table
-flds=fldnames, default is (chr:strand:coord:tagnum:gff_id) or (chr:strand:coord:tagnum) -- ������PA����master���й涨��sample�У���WT
*PA table: table with flds: <chr,strand,coord,tagnum>+<gff_id>
-omtbl=output master table, if '' or same as master then overwrite
*-format=input table(default) or file
*if format=file, the file only has 4 columns, and is chr:strand:coord:tagnum
-apadd=0(default)/1 ������apsmp��aptblʱ��Ч��
 0ʱ��������У������µ�tag#ȥ����ԭ����tag#
 1ʱ��������У��ҿɽ�tag#�ۼӵ�ԭ�У�����apadd=0ֻ����ԭ�У�
 2ʱ����������У�����tag#��ֵ����ԭ��
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

#���mtbl������,���Ƚ���
if (!tblExists($dbh,$mtbl)) {
  die "mtbl=$mtbl not exists, but delsmp=$delsmp" if $delsmp;
  die "mtbl=$mtbl not exists, but omtbl=$omtbl" if $omtbl;
  my $s=$apsmp if $apsmp;
  $s=$owsmp if $owsmp;
  if ($gfftbl) {
    $sql="create table $mtbl (chr varchar(20),strand char(1),coord int,tot_tagnum int,gff_id float(10,1),$s int)";
  } else { #���δ�ṩgfftbl����Ҫgff_id
    $sql="create table $mtbl (chr varchar(20),strand char(1),coord int,tot_tagnum int,$s int)";
  }
  print "$sql\n";
  $dbh->do($sql) or die;
}

#��֤�ֶ��Ƿ����
my @tmp=('chr','strand','coord','tot_tagnum');
my @tmpId=getFldsIdx($dbh,$mtbl,@tmp);
die "@tmp not in master=$mtbl" if $tmpId[$#tmpId]==-1;
my $haveID=1;
my $id=getFldsIdx($dbh,$mtbl,'gff_id');
$haveID=0 if $id==-1;

#���master���в�����gff_id�����ṩ��gff��Ҳ���
if (!$haveID and $gfftbl) {
  die "No gff_id in master=$mtbl, but provide gfftbl=$gfftbl!";
}

#���FILE,���ȶ����ļ�����ʱ��
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

#��֤: ���PAС����4��,����gff_id,����master����gff_id�����������gfftbl��PAT_mapGff.pl����
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

if ($mode==$DEL) {  #ɾ��
  print "******* Delete: $delsmp  ******* \n";
  delSmp($delsmp);
  print "********************************\n";
} 

elsif ($mode==$OW) { #��д: ��ɾ��,��append
  print "******* OverWrite: delete $owsmp  ******* \n";
  delSmp($owsmp);
  print "\n******* OverWrite: append $owsmp  ******* \n";
  appendSmp($owtbl,$owsmp);
  print "****************************************\n";
}

elsif ($mode==$AP) { #����
  print "******* Append: $apsmp  **************** \n";
  appendSmp($aptbl,$apsmp);
  print "****************************************\n";
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
# appendSmp(patbl,asmp) ��������
# ��������,�����;�Ѵ���,�����;��qry��,master��,��masterԭ�еĲ���
#################################################
sub appendSmp {
    my ($atbl,$asmp)=@_;
    my @pflds=getPAFlds($atbl);
    my $qrytbl=$atbl; #PA��
    my $sbjtbl=$mtbl; #master��
    #��atbl��gff_id(��master��gff_id),����PAT_mapGff.pl����
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

    #master�е�asmp��Ӧ���ֶ��±�
    #���master����asmp,�������master������,�������
    my $asmpId=getFldsIdx($dbh,$mtbl,$asmp);
    my $addFld=0;
    if ($asmpId==-1) {
      my @t=getTblFlds($dbh,$mtbl);
	  $asmpId=$#t+1;
	  $addFld=1;
    }
    my $totId=getFldsIdx($dbh,$mtbl,'tot_tagnum');
	#����master��PA��,��ӻ��޸�master��
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
    # 2011/9/28 ��group byȥ����PA���� (chr,strand,coord,tagnum,<gffid>) ��4����tagnum�У������Ʋ�һ����tagnum
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
	  #����: qry <chr,strand,coord,tagnum,gff_id>
	  #$sql="select $selflds from $qrytbl group by $qchrfld,$qstrandfld,$grpflds order by $qchrfld,$qstrandfld,$qposfld";
	  #2013-08-15 bug������ alterPA�����tot_tagnum=0�����
	  $sql="select $selflds from $qrytbl group by $grpflds $having order by $qchrfld,$qstrandfld,$qposfld";
	  #print "\n\n$sql\n\n";
	  ($qry,$rv)=execSql($dbh,$sql);
	  print "$rv PAs; ";

	  if (0) { #$rv>500000
		$dbh->disconnect();
	    system "net stop mysql";
	    system "net start mysql";
	    ($dbh)=connectDB($conf,1); #һ��Ҫ��һ��($dbh) ����ֻ�� $dbh����
	  }

	  my $idxsQry=getIntervals($qry,'0:1',1); #ȡ��chr,strand��Ӧ���±귶Χ(start,end,(chr1,+))

	  $sql="select * from $sbjtbl order by chr,strand,coord";
	  ($sbj,$rv)=execSql($dbh,$sql);
	  print "$rv Masters\n";
	  my $idxsSbj=getIntervals($sbj,'0:1',1);

	  if ($addFld) {
		for my $i(0..$#$sbj) {
		  $sbj->[$i][$asmpId]=0;
		}
	  }

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
		$n1=0;
	  } elsif ($sis==-1 or $sie==-1) { #��master,��apadd!=2ʱ�������qry�� <chr,strand,coord,tot_tagnum,gff_id...>
	    if ($apadd!=2) {#2013-12-16
			my $tmp=[];
			for my $i($qis..$qie) {
			  push(@{$tmp},[@blankRow]);
			  @{$tmp->[$#$tmp]}[0..$#pflds]=@{$qry->[$i]}; #master����������ǰ5��
			  $tmp->[$#$tmp][$asmpId]=$qry->[$i][3];  #������
			}
			saveMtx2File($tmp,$ofile,1,0);
			$n1=$qie-$qis+1;
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
			#print "aqry $qi: @{$aqry->[$qi]}\n";
			if (!defined($idxs->[$i][1]) and $apadd!=2) { #������� (apadd=2ʱ���������)
			  push(@{$asbj},[@blankRow]);
			  @{$asbj->[$#$asbj]}[0..$#pflds]=@{$aqry->[$qi]};
			  $asbj->[$#$asbj][$asmpId]=$aqry->[$qi][3];
			  $n1++;
			} else { #���¾���
			  $si=$idxs->[$i][1];
			  #print "asbj $si: @{$asbj->[$si]}\n";
			  if ($apadd==0) {
			    $asbj->[$si][$totId]-=$asbj->[$si][$asmpId];
			    $asbj->[$si][$asmpId]=$aqry->[$qi][3]; #������				  
			    $asbj->[$si][$totId]+=$aqry->[$qi][3]; #tot_tagnum
			    $n2++;
			  } elsif ($apadd==1) { #2013-12-16 #�ۼӵ�ԭֵ
			    $asbj->[$si][$totId]-=$asbj->[$si][$asmpId];
			    $asbj->[$si][$asmpId]+=$aqry->[$qi][3]; #�������ۼ�				  
			    $asbj->[$si][$totId]+=$asbj->[$si][$asmpId]; #tot_tagnum
				$n2++;
			  }elsif ($apadd==2 and $asbj->[$si][$asmpId]>0 and $asbj->[$si][$asmpId]<$aqry->[$qi][3]) { #2013-12-16 #�ۼӵ�ԭֵ apadd=2 #�������tag#=0��tag#�������У��򲻸��¾��У����򸲸�ԭֵ
			    $asbj->[$si][$totId]-=$asbj->[$si][$asmpId];
			    $asbj->[$si][$asmpId]=$aqry->[$qi][3]; #�����и�ֵʱ����				  
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

   #�������ݿ� 
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
# ����opt��PA��ȡ����Ч���ֶ��� (������س�Ϊ5,�����1����gff_id)
# ��die�ж�
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
# �ж��ֶε�����:0�Ǵ���,1��gff_id,2����
#################################################
sub gffIDfldType { 
	my @f=@_;
	return 1 if scalar(@f)==5;
	return 2 if scalar(@f)==4;
	return 0;
}


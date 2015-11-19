#!/usr/bin/perl -w
#2009/12/09
#��polyA_site_in_genome�ж��Ƿ�ÿ�е�tagΪIP
#�����: $in(��chr,strand,polyA_site_in_genome),chromosome��
#�����:$in_IP (P) [$in�����+isIP��]
##1) ���ձ�P,��������isIP��,�������
##2) ÿ��ȡchrx��strandy�ļ�¼ fatchall_arrayref()-һ�ζ����ڴ�
##   @tbl=select * from $in where chr=x and strand=y order bypolyA_site_in_genome
##	 select seq from chromosome where chr=\'$chr\'
##   д��@IPInfo,���$tbl��@IPInfo���ļ�
##3) load data����P
##4) ͳ�ƽ��: ��IP����IP��tag�� (isIP=1��internal priming)

#2010/6/20
#���Ӷ�pattbl��֧��: 
#ԭʼ�������: chr/strand/polyA_site_in_genome or chrsm_name/chrsm_match_strand/polyA_site_in_genome

#2010/9/14
#����-delѡ��, ���ΪT,��ֱ�ӽ�is_IP�м��뵽ԭ����.
#�����ֵ��\N����: ��Ϊ��ʱ�п�ֵ,�ڵ��뵼��ʱ�����.

#2011/5/7 (���޸�)
#���ѡ�� flds:s ��������chr,strand,coord��
#���ѡ��iptbl: ������,��iptbl�е���ΪisIP=1����,��otblΪisIP=0��;����ֱ��ɾ��isIP=0��
#����format=table/fileѡ��,ͬʱflds��Ϊ1:2:3���б�

#2011/8/18 ��BUG������
#�� �����ˣ������if����fas���գ�ֻ��ÿ�ζ���ȡfas!!

#2012-09-07 ��Mtr.���޸� -- ��Ҫdbconf.longchr��
#�����Mtr��short/longchr����ʽ
#���format=FILE��Ҳ�ǵ������ݿ��У��ٽ���format=TABLE�ƵĲ���
#�µĴ���ʽ��
#��dbconf�е�<longchr>����е�������������Щû�д���chr��genome����arab��human��
#�Է�longchr�һ�δ����ݿ��ж�ȡ����for����
#���dbconf����<longchr>���ȫ����������

## ����
#create table test select chr,strand,polya_site_in_genome,tag_num from t_chlamy_pas_ip1;
#insert into  test select * from t_chlamy_pas;
#PAT_setIP.pl -format table -itbl test -otbl test_real1 -iptbl test_ip1 -flds chr:strand:polyA_site_in_genome -conf dbconf_chlamy.xml
#PAT_setIP_pnas.pl -format table -itbl test -otbl test_real -iptbl test_ip -flds chr:strand:polyA_site_in_genome -conf dbconf_chlamy.xml

#push(@INC,"/home/zym/Myperl");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/x86_64-linux-gnu-thread-multi/");
#push(@INC,"/home/zym/soft/cpan/lib/perl5/");

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
GetOptions(\%opts,"h","itbl=s","otbl=s","iptbl:s","flds:s","format:s","conf=s");
my $USAGE=<< "_USAGE_";
Require: <chromosome(title,seq)>
Purpose: map gff with patag
Usage: 
  seperate IP and nonIP
  1) PAT_setIP.pl -itbl PAT_or_PA -otbl PAT_or_PA -iptbl af_PAT_or_PA -conf dbconf_xx.xml
  delete IP
  2) PAT_setIP.pl -itbl PAT_or_PA -otbl PAT_or_PA -conf dbconf_xx.xml
  user define flds
  3) PAT_setIP.pl -itbl PAT_or_PA -otbl PAT_or_PA -iptbl af_PAT_or_PA -flds chrsm:strand:polyA_pos -conf dbconf_xx.xml
  file format
  4) PAT_setIP.pl -itbl c:/PAT_or_PA.txt -otbl c:/PAT_or_PA.txt -iptbl af_PAT_or_PA.txt -flds 0:1:2 -format file -conf dbconf_arabcommon.xml

from .TANi
(1) format=table
FILE_PAT2PA.pl -pat c:/tan.file -pa c:/PA.file -tcols 1:2:6
PAT_alterPA.pl -master tan -owtbl c:/PA.file -owsmp tan -gff gff_ae120 -format file -conf dbconf_arabtest.xml
PAT_setIP.pl -itbl tan -otbl tan_real -iptbl tan_ip -conf dbconf_arabtest.xml
(2) format=file
PAT_setIP.pl -itbl c:/PA.file -otbl c:/tan.real -iptbl c:/tan.ip -flds 0:1:2 -format file -conf dbconf_arabcommon.xml

-h=help
-itbl=input table (HAS columns: chr,strand,coord) or flds
*-otbl=<input>_IP table (columns+isIP=1(internal priming)/0);
*if otbl=itbl, then overwrite itbl
-iptbl=table for IP rows
-flds=chr:strand:coord(default) or 0:1:2(default for format=file)
*-format=table(default) or file
*if format=file, xxtbl is xxfile, the flds is the cols index (first 0), like 0:1:2
_USAGE_


#############################################################################
#  invoke the program                             
#############################################################################
die $USAGE if $opts{'h'}||!$opts{'conf'}||!$opts{'itbl'}||!$opts{'otbl'};
my ($TABLE,$FILE)=(1,2);
my $itbl=$opts{'itbl'};
my $otbl=$opts{'otbl'};
my $iptbl=$opts{'iptbl'};
my $flds=$opts{'flds'};
my $format=$TABLE;
$format=$FILE if $opts{'format'} and lc($opts{'format'}) eq 'file';

$flds='chr:strand:coord' if (!$flds and $format eq $TABLE);
$flds='0:1:2' if (!$flds and $format eq $FILE);

my $ow=0; #��������
if ($otbl eq $itbl) {
  $otbl=$itbl.'xxIPtmpxx';
  $ow=1;
}

#��֤
my $conf=$opts{'conf'};
my($dbh,$chrtbl,$longchr)=connectDB($conf,1,('chromosome','longchr'));
die "chromosome not in xml" if !$chrtbl;
die "$chrtbl not exists!" if (!tblExists($dbh,$chrtbl));

my($chrFld,$strandFld,$posFld);
my($chrname,$strandname,$posname);

my $ofileReal=getTmpPath(1)."setIP.real.xxx";
my $ofileIP=getTmpPath(1)."setIP.ips.xxx";
unlink $ofileReal if -e $ofileReal;
unlink $ofileIP if -e $ofileIP;

# �Ȱ�PA�ļ�������ʱ����ͳһ�����
my ($itblOrg,$otblOrg,$iptblOrg)=($itbl,$otbl,$iptbl);
if ($format eq $FILE) {
  unlink $iptbl if ($iptbl and -e $iptbl);
  unlink $otbl if -e $otbl;
  ($itbl,$otbl)=('itbl_xxx','otbl_xxx'); #��ʱ��
  $iptbl='iptbl_xxx' if $iptbl;
  open(FF,"<$itblOrg") or die "cannot open $itblOrg\n";
  my $line=<FF>;
  close(FF);
  my @items=split(/\t/,$line);
  my @cols=split(/:/,$flds);
  ($chrFld,$strandFld,$posFld)=@cols;
  die "$flds not 3 cols" if scalar(@cols)!=3;
  my $sqltxt='';

  for my $i(0..$#items) {
	if ($i==$chrFld) {
	  $chrname="item$i";
	  $sqltxt.="item$i varchar(50),";
	} elsif ($i==$strandFld) {
	  $strandname="item$i";
	  $sqltxt.="item$i char(1),";
	} elsif ($i==$posFld) {
	  $posname="item$i";
	  $sqltxt.="item$i int,";
	} else {
      $sqltxt.="item$i varchar(200),";
	}
  }
  $sqltxt=substr($sqltxt,0,length($sqltxt)-1);
  $dbh->do("drop table if exists $itbl") or die;
  $dbh->do("create table $itbl ($sqltxt)") or die "cannot create $itbl!";
  my $rv=loadFile2Tbl($dbh,$itbl,$itblOrg);
  print "$rv in $itblOrg\n";
} #format

die "itbl=$itbl not exists!" if (!tblExists($dbh,$itbl));

if ($format eq $TABLE) {
	my @fldnames=split(/:/,$flds);
	die "$flds not 3 flds" if scalar(@fldnames)!=3;
	my @Flds=getFldsIdx($dbh, $itbl,(@fldnames));
	die "$flds not in $itbl; Idx=@Flds" if $Flds[$#Flds]==-1;
	pop(@Flds) if $#Flds>0;

	($chrFld,$strandFld,$posFld)=@Flds;
	($chrname,$strandname,$posname)=@fldnames;
}

#�����
$dbh->do("drop table if exists $otbl") or die;
$dbh->do("create table $otbl select * from $itbl where 1<>1") or die;
if ($iptbl) {
  $dbh->do("drop table if exists $iptbl") or die;
  $dbh->do("create table $iptbl select * from $itbl where 1<>1") or die;
}

#����longchr
my (@chrs,$sqlchr);
if ($longchr) {
  $sqlchr=dotStr2sqlStr($longchr);
  @chrs=getFldValues($dbh,"SELECT distinct($chrname) FROM $itbl where $chrname in ($sqlchr) order by $chrname",0);
} else {
  @chrs=getFldValues($dbh,"SELECT distinct($chrname) FROM $itbl order by $chrname",0);
}

my @strands=('+','-');

# ȫ�ֱ��� 
my ($fas,$s);
my ($tot1,$tot2)=(0,0);

foreach  my $chr (@chrs) {
    my $sql="select seq from $chrtbl where title=\'$chr\'";
	my ($fastbl,$rv)=execSql($dbh,$sql);
    next if $#{$fastbl}<0;
	$fas=$fastbl->[0][0];

	for my $si (0..$#strands) {
	  $s=$strands[$si];
	  print "$chr $s; ";
	  my $sql="select * from $itbl where $chrname=\'$chr\' and $strandname=\'$s\' order by  $posname";
	  my ($tbl,$rv)=execSql($dbh,$sql);
      print "$rv PA; ";
	  print "\n" if $#{$tbl}<0;
      my($ipcnt,$realcnt)=output($tbl,0,$#$tbl);#($s,$tbl,$posFld,$fas);
	  $tbl=[];
	  print "$ipcnt ips; $realcnt reals\n";
  } #for strand
} #for chr

#����ж�chr
if ($longchr) { #Mtr
    $fas=''; 
    my $sql="select seq,title from $chrtbl where title not in ($sqlchr) order by title";
	my ($fastbls,$rv1)=execSql($dbh,$sql);
	my %hashfas=();
	for my $i(0..$#$fastbls) {
	  $hashfas{$fastbls->[$i][1]}=$fastbls->[$i][0];
	}
    $fastbls=[];
	$sql="select * from $itbl where $chrname not in ($sqlchr) order by $chrname,$strandname,$posname";
	my ($tbls,$rv2)=execSql($dbh,$sql);	    
	
	#2012-09-08 ȡPA���chr�����PA�ٵĻ���ֻ��Ҫ����������chr -- ����Mtr��6���chr��˵���ٶȿ�ǳ�֮�࣡������
	my @validChrs=getFldValues($dbh,"select distinct($chrname) from $itbl where $chrname not in ($sqlchr) order by $chrname",0);

	if (scalar(keys(%hashfas))>0 and $#$tbls>=0) {
	  print "********************\n";
	  print scalar(@validChrs)." short chrs; $rv2 PAs\n";
      my ($icnt,$rcnt)=(0,0);
	  my $p=0;
	  for my $i(0..$#validChrs) {
		$fas=$hashfas{$validChrs[$i]};
		my $title=$validChrs[$i];
		for my $si (0..$#strands) {
		  $s=$strands[$si];
		  my ($i1,$i2)=(-1,-1);
		  for my $j($p..$#$tbls) {
			if ($tbls->[$j][$chrFld] eq $title and $tbls->[$j][$strandFld] eq $s) {
			  $i1=$i2=$j;
			  $p++;
			  for my $k($j+1..$#$tbls) {
				if ($tbls->[$k][$chrFld] eq $title and $tbls->[$k][$strandFld] eq $s) {
				  $i2=$k;
				  $p++;
				} else {
			      last;
				}
			  }
			  last;
			} 
		  } #for j
          if ($i1!=-1 and $i2!=-1) {
		    #print "$title,$s, $i1~$i2 ".($i2-$i1+1)."PAs;";
		    my($ipcnt,$realcnt)=output($tbls,$i1,$i2);#($s,$tbl,$posFld,$fas);
		    #print " $ipcnt ips; $realcnt reals\n";
		    ($icnt,$rcnt)=($icnt+$ipcnt,$rcnt+$realcnt);
          }
		} #s
	  } #i
	  print "$icnt IPs; $rcnt reals\n";
	} else {
      print "$rv1 short chrs; $rv2 $itbl.\n";
	}
   $fastbls=$tbls=[];
}#mtr

my ($l1,$l2)=(0,0);
if ($iptbl and -e $ofileIP) {
  $l1=loadFile2Tbl($dbh,$iptbl,$ofileIP,0);
}
  $l2=loadFile2Tbl($dbh,$otbl,$ofileReal,0);
  print "********************\n";
  print "Total $tot1/$l1 ips; $tot2/$l2 reals\n";

#������ԭ���ı�
if ($ow and $format eq $TABLE) {
  print "delete IP rows from $itbl\n";
  cloneTblIndex($dbh,$itbl,$otbl);
  $dbh->do("drop table if exists $itbl") or die;
  $dbh->do("alter table $otbl rename $itbl");
}

# ������ļ�������
if ($format eq $FILE) {
  unlink($otblOrg) if -e($otblOrg);
  unlink($iptblOrg) if -e($iptblOrg);
  #print "select * from $otbl into outfile \'$otblOrg\'\n";
  $dbh->do("select * from $otbl into outfile \'$otblOrg\'") or die;
  if ($iptbl) {
    $dbh->do("select * from $iptbl into outfile \'$iptblOrg\'") or die;
  }
  if ($ow) {
    print "overwrite org file/table\n";
	rename "$otblOrg","$itblOrg";
  }
  $dbh->do("drop table if exists $itbl") or die;
  if ($iptbl) {
    $dbh->do("drop table if exists $iptbl") or die;
  }
  $dbh->do("drop table if exists $otbl") or die;
}

unlink $ofileReal if -e $ofileReal;
unlink $ofileIP if -e $ofileIP;

$dbh->disconnect();


#################################################
# SUB FUNCS
#################################################

#$IPInfo=getIPinfo($s,$tbl,$i1,$i2,$posFld,$fas)
sub getIPinfo {
  my ($s,$tbl,$i1,$i2,$posFld,$fas)=@_;
  my @ret=();
  my ($nt,$nts);
  if ($s eq '+') {
	  $nt='A';
  } else {
	  $nt='T';
  }
  $nts=($nt x 6);

  return if $i1<0 or $i2<0;
  my $papos=$tbl->[$i1][$posFld];
  $ret[0]=isIP($papos,$nt,$nts,\$fas);
  my $ri=1;
  for my $i (($i1+1)..$i2) {
	if ($tbl->[$i][$posFld]==$papos) {
		$ret[$ri]=$ret[$ri-1];
		$ri++;
		next;
	} else {
		$papos=$tbl->[$i][$posFld];
		$ret[$ri]=isIP($papos,$nt,$nts,\$fas);	
		$ri++;
	}
  }	#for $i	  
  return @ret;
}

## �������Ƭ�� ##
sub output {
    my ($atbl,$i1,$i2)=@_;
    my($ipcnt,$realcnt)=(0,0);
	my @IPInfo=getIPinfo($s,$atbl,$i1,$i2,$posFld,$fas);

	if ($iptbl) {
	  my $ips=[];
	  for my $i($i1..$i2) {
		if ($IPInfo[$i-$i1]==1) {
	       push(@{$ips},[@{$atbl->[$i]}]);
		}
	  }
	  if ($#$ips!=-1) {
		  saveMtx2File($ips,$ofileIP,1,'\N');
		  $ipcnt=$#$ips+1;
		  $tot1+=$ipcnt;
	  } else {
		  $ipcnt=0;
	  }
	  $ips=[];
	} else {
	  $ipcnt=-1;
	}

	my $oo=[];
	for my $i($i1..$i2) {
	  if ($IPInfo[$i-$i1]==0) {
	     push(@{$oo},[@{$atbl->[$i]}]);
	  }	  
	}
	if ($#$oo!=-1) {
		saveMtx2File($oo,$ofileReal,1,'\N');
		$realcnt=$#$oo+1;
		$tot2+=$realcnt;
	} else {
		$realcnt=0;
	}
	$oo=[];
    @IPInfo=();
	return($ipcnt,$realcnt);
}


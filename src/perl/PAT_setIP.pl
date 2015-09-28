#!/usr/bin/perl -w
#2009/12/09
#按polyA_site_in_genome判断是否每行的tag为IP
#输入表: $in(含chr,strand,polyA_site_in_genome),chromosome表
#输出表:$in_IP (P) [$in表的列+isIP列]
##1) 建空表P,若不存在isIP列,则添加列
##2) 每次取chrx和strandy的记录 fatchall_arrayref()-一次读入内存
##   @tbl=select * from $in where chr=x and strand=y order bypolyA_site_in_genome
##	 select seq from chromosome where chr=\'$chr\'
##   写入@IPInfo,输出$tbl和@IPInfo到文件
##3) load data到表P
##4) 统计结果: 有IP和无IP的tag数 (isIP=1是internal priming)

#2010/6/20
#增加对pattbl的支持: 
#原始表可以是: chr/strand/polyA_site_in_genome or chrsm_name/chrsm_match_strand/polyA_site_in_genome

#2010/9/14
#增加-del选项, 如果为T,则直接将is_IP列加入到原表中.
#输出空值用\N代替: 因为有时有空值,在导入导出时会出错.

#2011/5/7 (大修改)
#添加选项 flds:s 允许设置chr,strand,coord列
#添加选项iptbl: 若设置,则iptbl中的行为isIP=1的行,而otbl为isIP=0的;否则直接删除isIP=0的
#增加format=table/file选项,同时flds变为1:2:3的列标

#2011/8/18 （BUG修正）
#见 见鬼了，如果用if，则fas会变空，只能每次都再取fas!!

#2012-09-07 （Mtr.大修改 -- 需要dbconf.longchr）
#整理成Mtr的short/longchr的形式
#如果format=FILE，也是导入数据库中，再进行format=TABLE似的操作
#新的处理方式：
#对dbconf中的<longchr>项，进行单独处理（方便那些没有大量chr的genome，如arab或human）
#对非longchr项，一次从数据库中读取，再for处理
#如果dbconf中无<longchr>项，则全部单独处理

## 测试
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

my $ow=0; #覆盖输入
if ($otbl eq $itbl) {
  $otbl=$itbl.'xxIPtmpxx';
  $ow=1;
}

#验证
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

# 先把PA文件导入临时表，再统一处理吧
my ($itblOrg,$otblOrg,$iptblOrg)=($itbl,$otbl,$iptbl);
if ($format eq $FILE) {
  unlink $iptbl if ($iptbl and -e $iptbl);
  unlink $otbl if -e $otbl;
  ($itbl,$otbl)=('itbl_xxx','otbl_xxx'); #临时表
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

#输出表
$dbh->do("drop table if exists $otbl") or die;
$dbh->do("create table $otbl select * from $itbl where 1<>1") or die;
if ($iptbl) {
  $dbh->do("drop table if exists $iptbl") or die;
  $dbh->do("create table $iptbl select * from $itbl where 1<>1") or die;
}

#处理longchr
my (@chrs,$sqlchr);
if ($longchr) {
  $sqlchr=dotStr2sqlStr($longchr);
  @chrs=getFldValues($dbh,"SELECT distinct($chrname) FROM $itbl where $chrname in ($sqlchr) order by $chrname",0);
} else {
  @chrs=getFldValues($dbh,"SELECT distinct($chrname) FROM $itbl order by $chrname",0);
}

my @strands=('+','-');

# 全局变量 
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

#如果有短chr
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
	
	#2012-09-08 取PA表的chr，如果PA少的话，只需要遍历少量的chr -- 对于Mtr的6万多chr来说，速度快非常之多！！！！
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

#重命名原来的表
if ($ow and $format eq $TABLE) {
  print "delete IP rows from $itbl\n";
  cloneTblIndex($dbh,$itbl,$otbl);
  $dbh->do("drop table if exists $itbl") or die;
  $dbh->do("alter table $otbl rename $itbl");
}

# 输出到文件及清理
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

## 输出程序片段 ##
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


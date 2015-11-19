#!/usr/bin/perl -w
use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;

require ('funclib.pl');

#############################################################################
#  将fasta文件存入数据表arabpa.chromosome(title,seq,len),若表不存在则新建 
#  出现: "DBD::mysql::st execute failed: MySQL server has gone away at seq2db.pl"错误,则修改my.ini
#[mysqld]
#max_allowed_packet=150M
#wait_timeout=288000000
#innodb_lock_wait_timeout=288000000
#table_lock_wait_timeout=288000000
#  show VARIABLES like '%max_allowed_packet%';  set @@max_allowed_packet=150000000
#############################################################################

##########################################################################################
#  parameters & usage
##########################################################################################
my %opts=();
GetOptions(\%opts,"h","input=s","tbl:s","conf=s");
my $USAGE=<< "_USAGE_";
Purpose: import fas to chromosome table
Usage: 1) UTIL_seq2db.pl -input TAIR9_chr_all.fas -conf dbconf_arabpat_raw.xml
	   2) UTIL_seq2db.pl -input xx.fas -tbl xx -conf dbconf_arabpat_raw.xml
-h=help
-tbl=chr table, default 'chromosome' (title,seq,len)
_USAGE_

#############################################################################
#  invoke the program                             
#############################################################################
my ($tbl);
die $USAGE if $opts{'h'}||!$opts{'input'}||!$opts{'conf'};
if ($opts{'tbl'}) {
  $tbl=$opts{'tbl'};
} else {
  $tbl='chromosome';
}

#############################################################################
#  Establish DB Connection                                                  
#############################################################################
my($dbh)=connectDB($opts{'conf'},1);

#############################################################################
#  Seq to DB                                               
#############################################################################
my($sth,$rv,$line,$title,$seq);

if (!tblExists($dbh,$tbl)) {
  print "Create table $tbl.\n";
  $dbh->do("create table $tbl (title varchar(100), seq longtext,len bigint(20))") or die "$dbh->errstr\n"; 
}

open (INPUT,"<$opts{'input'}") ||  die "Cannot open $opts{'input'}!\n";

$title="";
$seq="";
while($line=<INPUT>)
{
	chop($line);
	$line=trim($line);
	if($line=~/^>/)
	{
		storeIntoDB($dbh,$title,$seq,$tbl);
        $title=substr($line,1,length($line)-1);
		$seq="";
	}
	else
	{
		$seq=$seq.$line;
	}
}

storeIntoDB($dbh,$title,$seq,$tbl);


#############################################################################
#  function storeIntoDB store the seqs into database                  
#  useage: storeIntoDB($db,$transcript_id,$len,$seq);    
#############################################################################
sub storeIntoDB()
{
	my ($db,$title,$seq,$tbl)=@_;
	my $len=length($seq);
	if(length($title)==0 || $len==0)
	{
		return;			
	}
	if (length($title)>100) {
	  $title=substr($title,0,100);
	}
	
	my $query = "INSERT INTO $tbl SET title=".$db->quote($title).",seq=".$db->quote($seq).",len=".length($seq);
	#print $query."\n";
	my $sth=$db->prepare($query);
    $sth->execute(); 
}



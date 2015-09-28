#2010/8/23
#测试perl是否安装成功

use strict;
use DBI;
use Getopt::Long;
use XML::Parser;
use XML::Simple;

require ('funclib.pl');

my $path=getTmpPath(1);
print "$path\n";

my $conf='dbconf_arabtest.xml';
my $xml     = new XML::Simple;
my $data    = eval {$xml->XMLin($conf , ForceArray => 0)};
my $db_host= $data->{dbhost};
my $db_name= $data->{dbname};
my $db_user_name= $data->{user};
my $db_password= $data->{password};
print "host=$db_host\ndbname=$db_name\nuser=$db_user_name\npwd=$db_password\n";

print "Perl test success!\n";

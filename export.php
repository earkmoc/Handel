<?php

require('dbconnectt.inc');

$tmpfname='/usr/mysql/data/Handel/'.date('Y.m.d_H.i.s_').(1*microtime()).'.txt';
$file=fopen($tmpfname,"w");
if (!$file) {
    echo "<p>Nie mo¿na otworzyæ pliku do zapisu.\n";
    exit;
}

$w=mysql_query("Select * from tabelee");
while ($r=mysql_fetch_row($w)) {
	for ($i=0;$i<count($r);$i++) {
//		fputs($file,str_replace("\r\n","<br>",$r[$i])."\t");
		fputs($file,str_replace("\r\n","<br>",$r[$i])."\t");
	}
	fputs($file,"\n");
}
fclose($file);

require('dbdisconnect.inc');

require('dbconnecta.inc');

mysql_query("drop table tabele");
mysql_query("CREATE TABLE `tabele` (
`ID` int( 11 ) NOT NULL AUTO_INCREMENT ,
`NAZWA` varchar( 10 ) NOT NULL default '',
`OPIS` varchar( 99 ) NOT NULL default '',
`STRUKTURA` text NOT NULL ,
`TABELA` text NOT NULL ,
`FORMULARZ` text NOT NULL ,
`FUNKCJE` text NOT NULL ,
`FUNKCJEF` text NOT NULL ,
`MAXROWS` smallint( 6 ) NOT NULL default '0',
`ID_GRUPY` int( 11 ) NOT NULL default '2',
`PARAMSF` text NOT NULL ,
`PHPFORMEND` varchar( 20 ) NOT NULL default '',
PRIMARY KEY ( `ID` ) ,
KEY `NAZWA` ( `NAZWA` ) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");

mysql_query("LOAD DATA INFILE '$tmpfname' INTO TABLE tabele");
mysql_query("update tabele set NAZWA=replace(NAZWA,'<br>','\r\n')");
mysql_query("update tabele set OPIS=replace(OPIS,'<br>','\r\n')");
mysql_query("update tabele set STRUKTURA=replace(STRUKTURA,'<br>','\r\n')");
mysql_query("update tabele set TABELA=replace(TABELA,'<br>','\r\n')");
mysql_query("update tabele set FORMULARZ=replace(FORMULARZ,'<br>','\r\n')");
mysql_query("update tabele set FUNKCJE=replace(FUNKCJE,'<br>','\r\n')");
mysql_query("update tabele set FUNKCJEF=replace(FUNKCJEF,'<br>','\r\n')");
mysql_query("update tabele set PARAMSF=replace(PARAMSF,'<br>','\r\n')");

require('dbdisconnect.inc');

?>

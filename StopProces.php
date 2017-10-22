<?php

session_start();
$punkt=$_SESSION['osoba_pu'];

require('dbconnectr.inc');
$ido=0;
$z="select ID_OSOBYUPR from analizabb order by ID desc limit 1";
if ($w=mysql_query($z)) {$r=mysql_fetch_row($w); $ido=$r[0];}
if ($ido==0) {	//tabela 'analizabb' mo¿e byæ ju¿ pusta po naturalnym ukoñczeniu procesu
	$z="select ID_OSOBYUPR from analizabp where AKCJA='SIO' limit 1";
	$w=mysql_query($z); $r=mysql_fetch_row($w); $ido=$r[0];
}
mysql_query("update analizabp set AKCJA='STOP' where ID_OSOBYUPR=$ido limit 1");
require('dbdisconnect.inc');

	$file=fopen("C:/Arrakis/Wydruki$punkt/Stop","w");
	if (!$file) {
	    echo "<p>Nie mo¿na otworzyæ pliku do zapisu.\n";
	    exit;
	}
	fputs($file,"\n");
	fclose($file);

?>
<html>
<head>
<title>Stop procesu<?php for($i=0;$i<80;$i++) {echo '&nbsp;';}?></title>
</head>
<body onload="window.close();">
</body>
</html>
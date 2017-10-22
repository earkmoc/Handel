<?php
require('dbconnect.inc');
echo '<a href="Konserwuj.php">Esc=powrót</a><br><br>';
$w=mysql_query("select NAZWA from tabele"); 
while ($r=mysql_fetch_row($w)) {
	$rr=$r[0];
	$ww=mysql_query("CHECK TABLE $rr"); 
	while ($rr=mysql_fetch_row($ww)) {
		if (substr($rr[3],-13,13)<>"doesn't exist") {
			for($i=0;$i<count($rr);$i++) {
				echo $rr[$i];echo ' ';
			}
			echo '<br>';
		}
	}
}
require('dbdisconnect.inc');
?>

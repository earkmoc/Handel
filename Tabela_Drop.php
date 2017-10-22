<?php
$r=$_GET['r'];
$c=$_GET['c'];
$str=$_GET['str'];
$tabela=$_GET['tabela'];
$id=$_GET['ID'];

@ $db=mysql_connect('','root','danuta6brukowa341');
if (!$db){
	echo 'Błąd: Połączenie z bazą danych nie powiodło się';
	exit;
}
mysql_select_db('Handel');

$z="select * from tabele where ID='";
$z.=$id;
$z.="'";
$w=mysql_query($z);
if (!$w) {
	echo $z;
	echo 'Błąd: zapytanie nie powiodło się';
	exit;
}
$w=mysql_fetch_array($w);
$z=$w['NAZWA'];
$z="drop table $z";
$w=mysql_query($z);
if ($w) {
	echo '   OK   ';
}
else {
	echo $z;
	echo "<br>";
	echo "<br>";
	echo 'Błąd: zapytanie nie powiodło się';
	exit;
}
if ($w) {
	echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
	echo "<title>Zapis udany</title></head><body bgcolor=white ";
	echo "onload='";
	echo 'location.href="Tabela.php?tabela='.$tabela.'&r='.$r.'&c='.$c.'&str='.$str.'"';
	echo '\'></body></html>';
}
if (!$w) echo "$z<br  /><br  />niestety nie wyszło !!!";

require('dbdisconnect.inc');

?>
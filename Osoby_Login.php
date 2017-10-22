<?php

session_start();

$_SESSION['osoba_upr']='';
$_SESSION['osoba_id']='';

//$r=$_GET['r'];
//$c=$_GET['c'];
//$str=$_GET['str'];

$tabela=$_GET['tabela'];

$id=$_GET['ID'];

require('dbconnect.inc');

$z="insert into logi (ID_OSOBY,CZAS) values (";
$z.=$id;
$z.=",'";
$z.=date('Y-m-d H:i:s');
$z.="');";
$w=mysql_query($z);
$id=mysql_insert_id();
if ($w) {
	echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
	echo "<title>Zapis udany</title></head><body bgcolor=white ";
	echo "onload='";
	echo 'location.href="Tabela_Formularz.php?ID='.$id.'&tabela=logi&tabelaa=tabele&tabelab=osoby&op=L';
//	echo '&r='.$r.'&c='.$c.'&str='.$str;
	echo '"\'>';
//	echo $z;
	echo '</body></html>';
}
if (!$w) echo "$z<br  />$id<br  />niestety nie wyszło !!!";
//mysql_free_result($w);
require('dbdisconnect.inc');
?>
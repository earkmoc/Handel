<?php

//A|Abonent-Zapisz|PlikPHP('AbonentUwagi.php','Zapisać zmiany w uwagach i telefonach abonenta ?')
//require('skladuj_zmienne.php');

require('dbconnect.inc');

$z="update abonenci set UWAGIAB='".AddSlashes($_POST['UWAGIAB']);
$z.="', TELEFON1='".AddSlashes($_POST['TELEFON1']);
$z.="', TELEFON2='".AddSlashes($_POST['TELEFON2']);
$z.="', UWAGI='".AddSlashes($_POST['UWAGI']);
$z.="', EMAIL='".AddSlashes($_POST['EMAIL']);
$z.="', EMAIL2='".AddSlashes($_POST['EMAIL2']);
$z.="', EMAIL3='".AddSlashes($_POST['EMAIL3']);
$z.="' where ID=".$_POST['(abonenci_ID)'];
$z.=" limit 1";
$w=mysql_query($z);

echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
echo "<title>Wykonanie SQL</title></head><body bgcolor='#0F4F9F' ";
if ($w) {
	echo "onload='";
	echo 'location.href="Tabela.php?tabela='.$_GET['tabela'].'"';
	echo '\'>';
}
else {
	echo '>';
	echo "$z<br><br>niestety nie wyszło !!!";
	echo '<a href="Tabela.php?tabela='.$_GET['tabela'].'">powrót</a>';
}
echo '</body></html>';
require('dbdisconnect.inc');
?>
<?php
session_start();

//@ $db=mysql_connect('','root','danuta6brukowa341');
@ $db=mysql_connect('','root','223');
if (!$db){
        echo 'Bณฑd: Poณฑczenie z bazฑ danych nie powiodณo si๊';
        exit;
}
if ($_SESSION['osoba_se']) {
	if (!mysql_select_db('Handel'.$_SESSION['osoba_se'])) {
		echo "Nie wyszlo: mysql_select_db('Handel".$_SESSION['osoba_se']."')";
		exit;
	}
}
else {
	if (!mysql_select_db('Handel')) {
		echo "Nie wyszlo: mysql_select_db('Handel')";
		exit;
	}
}

$charset="latin2";
//$charset="utf8";
mysql_query("SET CHARACTER SET $charset");
mysql_query("SET character_set_client=$charset", $db);
mysql_query("SET character_set_connection=$charset", $db);
mysql_query("SET character_set_database=$charset", $db);
mysql_query("SET character_set_results=$charset", $db);
mysql_query("SET character_set_server=$charset", $db);

$collation="utf8mb4_unicode_ci";
$collation="utf8_general_ci";
mysql_query("SET collation_connection = $collation");
mysql_query("SET collation_database = $collation");
mysql_query("SET collation_server = $collation");

?>
<?php
session_start();

//@ $db=mysql_connect('','root','danuta6brukowa341');
@ $db=mysql_connect('','root','');
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
mysql_query('SET CHARACTER SET latin1');
mysql_query('SET character_set_connection=latin1', $db);

//mysql_query('SET character_set_client=latin2', $db);
//mysql_query('SET character_set_results=latin2', $db);
//mysql_query('SET collation_connection = latin2_general_ci');

?>
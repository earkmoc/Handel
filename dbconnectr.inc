<?php
//@ $db=mysql_connect('','root','danuta6brukowa341');
@ $db=mysql_connect('','root','');
if (!$db){
        echo 'Bณฑd: Poณฑczenie z bazฑ danych nie powiodณo si๊';
        exit;
}
if (!mysql_select_db('Handel')) {
	echo "Nie wyszlo: mysql_select_db('Handel')";
	exit;
}
mysql_query('SET CHARACTER SET latin2');
mysql_query('SET character_set_client=latin2', $db);
mysql_query('SET character_set_connection=latin2', $db);
mysql_query('SET character_set_database=latin2', $db);
mysql_query('SET character_set_results=latin2', $db);
mysql_query('SET character_set_server=latin2', $db);
mysql_query('SET collation_connection = utf8_polish_ci');
mysql_query('SET collation_database = utf8_polish_ci');
mysql_query('SET collation_server = utf8_polish_ci');
?>
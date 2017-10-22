<?php
session_start();

//$db=mysql_connect('mysql9.webd.pl','parrot_user','sZLVTFPCD3yM');
//$db=mysql_connect('parrotsc.seo-linuxpl.com','parrot_user','sZLVTFPCD3yM');
$db=mysql_connect('s7.seo-linuxpl.com','parrotsc','bomo6jemi');
if (!$db){
	echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Parrot - Internet connect</title></head><body>';
   echo '<h1>Błąd: Połączenie z serwerem bazy danych internetowego sklepu Parrot nie powiodło się</h1>';
   die;
}
//if (!mysql_select_db('parrot_baza')) {
if (!mysql_select_db('parrotsc_baza')) {
	echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Parrot - Internet connect</title></head><body>';
	echo "<h1>Nie wyszło: mysql_select_db('parrotsc_baza')</h1>";
	die;
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
<?php
$z='Select IDULICY from ULICE where ID=';
$z.=$_POST['sutabmid'];	// warto¶æ do zapisu: ID z WYKAZYODBW
$w=mysql_query($z);
$w=mysql_fetch_row($w);

$z='Update ';							// wiêc wype³niamy pole ID_ODBIO
$z.=$_POST['batab'];	 	// tabela do zapisu: WYKAZY
$z.=" set NAZWISKO='";
$z.=$w[0];
$z.="' where ID=";
$z.=$ipole;								// ID pola na którym dzia³a formularz
$w=mysql_query($z);					// zapis
?>
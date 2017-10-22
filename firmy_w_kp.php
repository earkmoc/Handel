<?php
$z='Select * from firmy where ID=';
$z.=$_POST['sutabmid'];	// warto¶æ do zapisu: ID z WYKAZYODBW
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$z='Update ';							// wiêc wype³niamy pole ID_ODBIO
$z.=$_POST['batab'];	 	// tabela do zapisu: bloki
$z.=" set PSKONT='";
$z.=$w['INDEKS'];
$z.="', NRKONT='";
$z.=$w['ID'];
$z.="', NIP='";
$z.=$w['NIP'];
$z.="', NAZWA='";
$z.=$w['NAZWA'];
$z.="', ADRES='";
$z.=$w['KOD'].' '.$w['MIASTO'].', ul. '.$w['ADRES'];
$z.="' where ID=";
$z.=$ipole;								// ID pola na którym dzia³a formularz
$w=mysql_query($z);					// zapis
?>
<?php
$z='Select * from firmy where ID=';
$z.=$_POST['sutabmid'];	// warto�� do zapisu: ID z WYKAZYODBW
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$z='Update ';							// wi�c wype�niamy pole ID_ODBIO
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
$z.=$ipole;								// ID pola na kt�rym dzia�a formularz
$w=mysql_query($z);					// zapis
?>
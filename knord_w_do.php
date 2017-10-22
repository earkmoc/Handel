<?php
$z='Select * from knordpol where ID=';
$z.=$HTTP_POST_VARS['sutabmid'];	// warto¶æ do zapisu: ID z WYKAZYODBW
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$z='Update ';							// wiêc wype³niamy pole ID_ODBIO
$z.=$HTTP_POST_VARS['batab'];	 	// tabela do zapisu: bloki
$z.=" set NRKONT='";
$z.=$w['NUMER'];
$z.="', NIP='";
$z.=$w['NIP'];
$z.="', PSKONT='";
$z.=$w['PSEUDO'];
$z.="', NAZWA='";
$z.=$w['NAZWA'];
$z.="', ADRES='";
$z.=$w['KOD_POCZT'].' '.trim($w['MIASTO']).' '.trim($w['ULICA']);
$z.="', ID_KNORDPOL=";
$z.=$w['ID'];
$z.=" where ID=";
$z.=$ipole;								// ID pola na którym dzia³a formularz
$w=mysql_query($z);					// zapis
?>
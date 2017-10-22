<?php
$z='Select ID from towary where ID=';
$z.=$_POST['sutabmid'];	// warto¶æ do zapisu: ID z WYKAZYODBW
$w=mysql_query($z);
$w=mysql_fetch_row($w);

$z='Update ';
$z.=$_POST['batab'];	 	// tabela do zapisu
$z.=" set ID_TOWARY='";
$z.=$w[0];
$z.="' where ID=";
$z.=$ipole;								// ID pola na którym dzia³a formularz
$w=mysql_query($z);					// zapis
?>
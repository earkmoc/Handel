<?php
$z='Select * from firmy where ID=';
$z.=$_POST['sutabmid'];	// warto¶æ do zapisu: ID z WYKAZYODBW
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$z='Update ';							// wiêc wype³niamy pole ID_ODBIO
$z.=$_POST['batab'];	 	// tabela do zapisu: bloki
$z.=" set INDEKS_F='";
$z.=$w['INDEKS'];
$z.="', NIP='";
$z.=$w['NIP'];
$z.="', TYP_F='";
$z.=$w['TYP'];
$z.="', NAZWA='";
$z.=$w['NAZWA'];
$z.="', KOD='";
$z.=$w['KOD'];
$z.="', MIASTO='";
$z.=$w['MIASTO'];
$z.="', ADRES='";
$z.=$w['ADRES'];
$z.="', TOWCENNIK='";
$z.=$w['CENNIK'];
$z.="', TOWRABAT='";
$z.=$w['RABAT'];
$z.="', DNIZWLOKI='";
$z.=$w['TERMIN'];
$z.="', ODEBRAL='";
$z.=$w['OSOBA'];
$z.="', DATAT=Date_Add(CurDate(),interval ";
$z.=$w['TERMIN'];
$z.=" day)";
$z.=" where ID=";
$z.=$ipole;								// ID pola na którym dzia³a formularz
$w=mysql_query($z);					// zapis
?>
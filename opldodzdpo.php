<?php

$raport='';						//planowane odciêcie

$z="Select ID from tabele where NAZWA='abonenci'";		//zmiana grupy tylko z tabeli "abonenci"
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytego

$z="update opldodg set IDABONENTA=$w, TYPOPER='x', ID_OSOBYUPR=$ido, CZAS=Now() where ID=$ipole limit 1";
$w=mysql_query($z);

$z="Select * from opldodg where ID=$ipole";
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$raport.=$z;

$z='insert into `opldod` set ';
$z.="`IDABONENTA`='".$w['IDABONENTA']."',";
$z.="`TYPOPER`='".$w['TYPOPER']."',";
$z.="`DATAOPER`='".$w['DATAOPER']."'";
$w=mysql_query($z);

$raport.=$z;

$w=mysql_insert_id();

$z="update opldodg set ID_OPLDOD=$w where ID=$ipole limit 1";
$w=mysql_query($z);

$tabelaa='opldodA';	// tu l¹duje po akcji

//echo $raport;
?>
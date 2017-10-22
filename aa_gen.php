<?php

//require('skladuj_zmienne.php');

$ipole=($ipole<0)?(-$ipole):$ipole;
$idt=$_POST['idtab'];

//********************************************************************
// zapamiêtaj stan tabeli dla zalogowanej osoby
$w=mysql_query("select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido"); $w=mysql_fetch_row($w);
if ($w[0]>0) 	{$w=mysql_query(     "update tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c where ID_TABELE=$idt and ID_OSOBY=$ido limit 1");}
else 		{$w=mysql_query("Insert into tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c,ID_TABELE=$idt,ID_OSOBY=$ido");}
// zapamiêtaj stan tabeli dla zalogowanej osoby
//********************************************************************

$z="select ID_F, ID_T from analizaa where ID=$ipole limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w); $idf=$w[0]; $idt=$w[1];

$z="update analiza3p set ID_FIRMY=$idf, ID_TOWARY=$idt, DATA2=CurDate() where ID_OSOBYUPR=$ido";
$w=mysql_query($z);

$natab='analiza3';
require('analiza3.php');
?>

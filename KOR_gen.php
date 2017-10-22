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

$z="select NABYWCA from dokum where ID=$ipole limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="update analizabp set ID_FIRMY=$w where ID_OSOBYUPR=$ido";
$w=mysql_query($z);

$natab='Tabela_Formularz.php?idtab=-1&natab=analizabp';
?>

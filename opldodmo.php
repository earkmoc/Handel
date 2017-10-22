<?php

session_start();					//inicjacja formularza dodatkowej op³aty comiesiêcznej

$ido=$_SESSION['osoba_id'];

$z="Select ID from tabele where NAZWA='typoplatod'";		//typ wybrany z tabeli
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID typu op³.
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytej

$wynik[0]=$w;

$z="Select NAZWAPELNA from typoplat where ID=$w";				//jest NAZWA typu
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];			//ostatnio u¿ytej

$wynik[1]=$w;

$wynik[2]=date('Y.m.d');
$wynik[3]=date('Y.m.d');

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo '$natabb="opldodA";'; echo "\n";		// l¹dowanie po Esc w formularzu
echo '-->'; echo "\n";
echo '</script>'; echo "\n";
?>
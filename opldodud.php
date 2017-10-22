<?php

session_start();				//inicjacja formularza umowy dodatkowej

$ido=$_SESSION['osoba_id'];

$z="Select ID from tabele where NAZWA='typoplatud'";		//typ wybrany z tabeli
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID typu op³.
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytej

$wynik[0]=$w;

$z="Select NAZWAPELNA from typoplat where ID=$w";				//jest NAZWA typu
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];			//ostatnio u¿ytej

$wynik[1]=$w;

$z="Select ID from tabele where NAZWA='typyumowud'";		//parametr typu wybrany z tabeli
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID typu op³.
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytej

$z="Select concat(TYPUMOWY, WALUTA), CENA from typyumow where ID=$w";		//jest reszta
$w=mysql_query($z); $w=mysql_fetch_row($w);											//te¿

$wynik[2]=$w[0];
$wynik[3]=$w[1];
$wynik[4]=date('Y.m.d');

//opldodg
//C1|Typ op³aty|7|
//DATAOPERR|Nazwa typu op³aty|50|style="color:blue"|
//INFO|Typ i waluta|10|
//DATAOPER|Od dnia|+10|
//from opldodg
//where ID=

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo '$natabb="opldodA";'; echo "\n";		// l¹dowanie po Esc w formularzu
echo '-->'; echo "\n";
echo '</script>'; echo "\n";
?>
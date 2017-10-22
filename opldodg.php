<?php

session_start();		//inicjacja formularza zmiany grupy

$ido=$_SESSION['osoba_id'];

$z="Select ID from tabele where NAZWA='grupyopldo'";		//grupa wybrana z tabeli "grupyopldo"
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID grupy
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytej

$wynik[0]=$w;

$z="Select NAZWAGR from grupy where ID=$w";						//jest NAZWA grupy
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];			//ostatnio u¿ytej

$wynik[1]=$w;

$wynik[2]=date('Y.m.d');

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo '$natabb="opldodA";'; echo "\n";		// l¹dowanie po Esc w formularzu
echo '-->'; echo "\n";
echo '</script>'; echo "\n";
?>
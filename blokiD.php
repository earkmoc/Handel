<?php
session_start();

$z="Select ID from tabele where NAZWA='bloki' limit 1";
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$w=$w[0];

$z='Select ID_POZYCJI from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE='.$w;
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$w=$w[0];

$z="Select NUMER from bloki where IDOSIEDLA=$w order by ID desc limit 1";
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$wynik[1]=$w[0]+1;
?>
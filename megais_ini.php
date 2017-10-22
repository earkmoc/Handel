<?php

$w=mysql_query("select ID from tabele where NAZWA='TOWARY'");
$w=mysql_fetch_row($w);
$w=$w[0];

$w=mysql_query("select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido");
$w=mysql_fetch_row($w);
$w=$w[0];

$wynik[1]=$w;

$w=mysql_query("select INDEKS from towary where ID=$w");
$w=mysql_fetch_row($w);
$w=$w[0];

$wynik[2]=$w;
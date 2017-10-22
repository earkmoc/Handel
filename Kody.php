<?php

0x80040200 dla DllRegisterServer

char(50620)|1
ord(substr(towary.NAZWA,1,1))|1
ord(substr(towary.NAZWA,2,1))|2
ord(substr(towary.NAZWA,3,1))|3
ord(substr(towary.NAZWA,4,1))|4
ord(substr(towary.NAZWA,5,1))|5
ord(substr(towary.NAZWA,6,1))|6
ord(substr(towary.NAZWA,7,1))|7
ord(substr(towary.NAZWA,8,1))|8
ord(substr(towary.NAZWA,9,1))|9
ord(substr(towary.NAZWA,10,1))|10
ord(substr(towary.NAZWA,11,1))|11
ord(substr(towary.NAZWA,12,1))|12
ord(substr(towary.NAZWA,13,1))|13
ord(substr(towary.NAZWA,14,1))|14
ord(substr(towary.NAZWA,15,1))|15
ord(substr(towary.NAZWA,16,1))|16
ord(substr(towary.NAZWA,17,1))|17
ord(substr(towary.NAZWA,18,1))|18
ord(substr(towary.NAZWA,19,1))|19
ord(substr(towary.NAZWA,20,1))|20
ord(substr(towary.NAZWA,21,1))|21
ord(substr(towary.NAZWA,22,1))|22
ord(substr(towary.NAZWA,23,1))|23
ord(substr(towary.NAZWA,24,1))|24
ord(substr(towary.NAZWA,25,1))|25
ord(substr(towary.NAZWA,26,1))|26
ord(substr(towary.NAZWA,27,1))|27
ord(substr(towary.NAZWA,28,1))|28
ord(substr(towary.NAZWA,29,1))|29
ord(substr(towary.NAZWA,30,1))|30

¹=177
ê=234
³=179
œ=182

//¥=50308
//Æ=50310
//Ê=50328
//£=50561
//Ñ=50563
//Ó=50067
//Œ=50586
//=50617
//¯=50619

//¹=50309
//æ=50311
//ê=50329
//³=50562
//ñ=50564
//ó=50099
//œ=50587
//Ÿ=50618
//¿=50620

//ord(substr(towary.NAZWA,1,1))|1

require('dbconnect.php');

$z="update analiza3p set ID_FIRMY=$idf, ID_TOWARY=$idt, DATA2=CurDate() where ID_OSOBYUPR=$ido";
$w=mysql_query($z);

require('dbdisconnect.php');
?>

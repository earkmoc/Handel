<?php
$z='Select NUMER from WYKAZYSU order by NUMER desc limit 1';
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$wynik[0]=$w[0]+1;
$wynik[1]=date('Y.m.d');
?>
<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analiza5p'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analiza5p where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analiza5b");
mysql_query("insert into analiza5b select 0, $ido, dokum.NABYWCA, dokum.WARTOSC from dokum where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");

mysql_query("delete from analiza5 where ID_OSOBYUPR=$ido");
mysql_query("insert into analiza5 select 0, $ido, ID_F, sum(WARTOSC) from analiza5b group by ID_F");

mysql_query("update analiza5p SET CZAS=Now() where ID=$ipole");

$tabelaa='analiza5';	// tu l¹duje po akcji

//echo $raport;
?>

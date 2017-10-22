<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analiza6p'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analiza6p where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("delete from analiza6 where ID_OSOBYUPR=$ido");
if ($w['ID_FIRMY']>0) {
mysql_query("insert into analiza6 select 0, $ido, dokum.ID from dokum where dokum.NABYWCA=".($w['ID_FIRMY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");
}
else {
mysql_query("insert into analiza6 select 0, $ido, dokum.ID from dokum where                                        dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");
}
mysql_query("update analiza6p SET CZAS=Now() where ID=$ipole");

$tabelaa='analiza6';	// tu l¹duje po akcji

//echo $raport;
?>

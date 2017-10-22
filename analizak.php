<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analizakp'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analizakp where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analizakb");

$z="insert into analizakb select 0, $ido, dokum.NABYWCA, dokum.DATAS from dokum where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0";
if ($w['CZY']=='T') {$z.=" and upper(dokum.TYP_F)='P' and dokum.NABYWCA<>1";}	//gdy tylko podmagazyny, MZ=1
mysql_query($z);

mysql_query("truncate analizakc");
mysql_query("insert into analizakc select 0, $ido, ID_F, DATA from analizakb order by DATA desc");

mysql_query("truncate analizakb");
mysql_query("insert into analizakb select 0, $ido, ID_F, DATA from analizakc group by ID_F");

$z=-$w['ID_TOWARY'];
mysql_query("delete from analizakb where DATA>=Date_Add(CurDate(), interval $z day)");

mysql_query("delete from analizak where ID_OSOBYUPR=$ido");
mysql_query("insert into analizak select 0, $ido, ID_F, DATA from analizakb");

mysql_query("update analizakp SET CZAS=Now() where ID=$ipole");

$tabelaa='analizak';	// tu l¹duje po akcji

//echo $raport;
?>

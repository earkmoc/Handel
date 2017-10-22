<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analiza4p'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analiza4p where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analiza4b");
if ($w['ID_FIRMY']<=0) {
mysql_query("insert into analiza4b select 0, $ido, spec.ID_T, spec.CENA, spec.ILOSC, spec.CENA*spec.ILOSC from spec left join dokum on dokum.ID=spec.ID_D where                                        dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");
}
else {
mysql_query("insert into analiza4b select 0, $ido, spec.ID_T, spec.CENA, spec.ILOSC, spec.CENA*spec.ILOSC from spec left join dokum on dokum.ID=spec.ID_D where dokum.NABYWCA=".($w['ID_FIRMY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");
}

mysql_query("delete from analiza4 where ID_OSOBYUPR=$ido");
if ($w['CZY']=='T') {	//œrednia cena
	mysql_query("insert into analiza4 select 0, $ido, ID_T, CENA, sum(ILOSC), sum(WARTOSC) from analiza4b group by analiza4b.ID_T");
	mysql_query("update analiza4 set CENA=round(WARTOSC/ILOSC,2) where ID_OSOBYUPR=$ido");
}
else {
	mysql_query("insert into analiza4 select 0, $ido, ID_T, CENA, sum(ILOSC), sum(WARTOSC) from analiza4b group by analiza4b.ID_T, analiza4b.CENA");
}
mysql_query("update analiza4p SET CZAS=Now() where ID=$ipole");

$tabelaa='analiza4';	// tu l¹duje po akcji

//echo $raport;
?>

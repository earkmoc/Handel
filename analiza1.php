<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analiza1p'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analiza1p where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analiza1b");

//stany
$z="insert into analiza1b select 0, $ido, ID_X, ILOSC, 0, 0, 0, CurDate(), '', 0, 0, ID_T from magazyny left join firmy on firmy.ID=magazyny.ID_X where ID_T=".($w['ID_TOWARY'])." and ID_X<>1";
if ($w['CZY_TMP']=='T') {$z.=" and upper(firmy.TYP)='P'";}	//gdy tylko podmagazyny, MZ=1
mysql_query($z);

//g³ówne
$z="insert into analiza1b select 0, $ido, dokum.NABYWCA, 0, spec.ILOSC, 0, 1, dokum.DATAS, dokum.DATAS, spec.ILOSC*spec.CENA, 0, ID_T from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0";
if ($w['CZY_TMP']=='T') {$z.=" and upper(dokum.TYP_F)='P' and dokum.NABYWCA<>1";}	//gdy tylko podmagazyny, MZ=1
mysql_query($z);

//zwrotowe
$z="insert into analiza1b select 0, $ido, dokum.NABYWCA, 0,          0, 0, 1, dokum.DATAS, dokum.DATAS,           0, spec.ILOSC, ID_T from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKR'])."','.',','))>0";
if ($w['CZY_TMP']=='T') {$z.=" and upper(dokum.TYP_F)='P' and dokum.NABYWCA<>1";}	//gdy tylko podmagazyny, MZ=1
mysql_query($z);

mysql_query("truncate magazynyb");
mysql_query("insert into magazynyb select 0, ID_X, ID_T, sum(ILOSC), max(CENA_Z), max(DATA_Z) from magazyny group by ID_X, ID_T");
mysql_query("update analiza1b left join magazynyb on magazynyb.ID_X=analiza1b.ID_F and magazynyb.ID_T=".($w['ID_TOWARY'])." SET analiza1b.STAN=magazynyb.ILOSC");

if ($w['CZY_MIN']=='T') {	//œrednia z minimalnego okresu wystêpowania faktur
	$z =" (12*substring(max(analiza1b.DATAK),1,4)+1*substring(max(analiza1b.DATAK),6,2))";
	$z.="-(12*substring(min(analiza1b.DATAP),1,4)+1*substring(min(analiza1b.DATAP),6,2))+1";
}
else {				//œrednia z ca³ego podanego okresu
	$z =" (12*substring('".($w['DATA2'])."',1,4)+1*substring('".($w['DATA2'])."',6,2))";
	$z.="-(12*substring('".($w['DATA1'])."',1,4)+1*substring('".($w['DATA1'])."',6,2))+1";
}
mysql_query("delete from analiza1 where ID_OSOBYUPR=$ido");
mysql_query("insert into analiza1 select 0, $ido, ID_F, STAN, sum(analiza1b.SPRZEDAZ), round(sum(analiza1b.SPRZEDAZ)/($z),1), sum(analiza1b.ILEDOK), min(analiza1b.DATAP), max(analiza1b.DATAK), sum(analiza1b.SPRZEDAZW), sum(analiza1b.ZWROTY), ID_T from analiza1b group by analiza1b.ID_F");

mysql_query("update analiza1p SET CZAS=Now() where ID=$ipole");

$tabelaa='analiza1';	// tu l¹duje po akcji

//echo $raport;
?>

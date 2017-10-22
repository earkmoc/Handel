<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analizaap'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analizaap where ID=$ipole"); $w=mysql_fetch_array($w);
$idf=$w['ID_FIRMY'];

mysql_query("truncate analizaab");

$z="insert into analizaab select 0, $ido, $idf, spec.ID_T, 0, 0, spec.ILOSC, 0, 0, 0, dokum.DATAS, dokum.DATAS from spec left join dokum on dokum.ID=spec.ID_D where dokum.NABYWCA=".($w['ID_FIRMY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and FIND_IN_SET(dokum.TYP,'".($w['TYPYDOKP'])."')>0";
mysql_query($z);

//$z="insert into analizaab select 0, $ido, $idf, spec.ID_T, 0, 0, 0, spec.ILOSC, 0, 0, '".($w['DATA2'])."', '".($w['DATA1'])."' from spec left join dokum on dokum.ID=spec.ID_D where dokum.NABYWCA=".($w['ID_FIRMY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,".($w['TYPYDOKR'])."')>0";
//$z="insert into analizaab select 0, $ido, $idf, spec.ID_T, 0, 0, 0, spec.ILOSC, 0, 0, dokum.DATAS, dokum.DATAS from spec left join dokum on dokum.ID=spec.ID_D where dokum.NABYWCA=".($w['ID_FIRMY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,'".($w['TYPYDOKR'])."')>0";
$z="insert into analizaab select 0, $ido, $idf, spec.ID_T, 0, 0, 0, spec.ILOSC, 0, 0, '".($w['DATA2'])."', '' from spec left join dokum on dokum.ID=spec.ID_D where dokum.NABYWCA=".($w['ID_FIRMY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,'".($w['TYPYDOKR'])."')>0";
mysql_query($z);

mysql_query("truncate magazynyb");
mysql_query("insert into magazynyb select 0, ID_X, ID_T, sum(ILOSC), max(CENA_Z), max(DATA_Z) from magazyny group by ID_X, ID_T");

$z="insert into analizaab select 0, $ido, $idf, magazynyb.ID_T, 0, magazynyb.ILOSC, 0, 0, 0, 0, '".($w['DATA2'])."', '' from magazynyb left join analizaab on analizaab.ID_T=magazynyb.ID_T where magazynyb.ILOSC<>0 and magazynyb.ID_X=$idf and isnull(analizaab.ID)";mysql_query($z);

mysql_query("update analizaab left join magazynyb on magazynyb.ID_T=analizaab.ID_T and magazynyb.ID_X=".($w['ID_FIRMY'])." SET analizaab.STAN=magazynyb.ILOSC");
mysql_query("update analizaab left join magazynyb on magazynyb.ID_T=analizaab.ID_T and magazynyb.ID_X=2 SET analizaab.STANMG=magazynyb.ILOSC");

if ($w['CZY']=='T') {	//œrednia z minimalnego okresu wystêpowania faktur
	$z =" (12*substring(max(analizaab.DATAK),1,4)+1*substring(max(analizaab.DATAK),6,2))";
	$z.="-(12*substring(min(analizaab.DATAP),1,4)+1*substring(min(analizaab.DATAP),6,2))+1";
}
else {				//œrednia z ca³ego podanego okresu
	$z =" (12*substring('".($w['DATA2'])."',1,4)+1*substring('".($w['DATA2'])."',6,2))";
	$z.="-(12*substring('".($w['DATA1'])."',1,4)+1*substring('".($w['DATA1'])."',6,2))+1";
}
mysql_query("delete from analizaa where ID_OSOBYUPR=$ido");
mysql_query("insert into analizaa select 0, $ido, $idf, ID_T, STANMG, STAN, sum(analizaab.SPRZEDAZ), sum(analizaab.ZWROTY), round(sum(analizaab.SPRZEDAZ)/($z),1), count(analizaab.ILEDOK), min(analizaab.DATAP), max(analizaab.DATAK) from analizaab group by analizaab.ID_T");

mysql_query("update analizaap SET CZAS=Now() where ID=$ipole");

$tabelaa='analizaa';	// tu l¹duje po akcji

//echo $raport;
?>

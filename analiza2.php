<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analiza2p'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analiza2p where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analiza2b");

$z="insert into analiza2b select 0, $ido, dokum.NABYWCA, 0, spec.ILOSC,";
$z.="if(dokum.DATAS<=Date_Add('".($w[DATA1])."',interval 1 month),spec.ILOSC,0),";
$z.="if((dokum.DATAS>Date_Add('".($w[DATA1])."',interval 1 month) and dokum.DATAS<=Date_Add('".($w[DATA1])."',interval 2 month)),spec.ILOSC,0),";
$z.="if((dokum.DATAS>Date_Add('".($w[DATA1])."',interval 2 month) and dokum.DATAS<=Date_Add('".($w[DATA1])."',interval 3 month)),spec.ILOSC,0),";
$z.="if((dokum.DATAS>Date_Add('".($w[DATA1])."',interval 3 month) and dokum.DATAS<=Date_Add('".($w[DATA1])."',interval 4 month)),spec.ILOSC,0),";
$z.="if((dokum.DATAS>Date_Add('".($w[DATA1])."',interval 4 month) and dokum.DATAS<=Date_Add('".($w[DATA1])."',interval 5 month)),spec.ILOSC,0),";
$z.="if((dokum.DATAS>Date_Add('".($w[DATA1])."',interval 5 month) and dokum.DATAS<=Date_Add('".($w[DATA1])."',interval 6 month)),spec.ILOSC,0) ";
$z.=" from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0";
if ($w['CZY_TMP']=='T') {$z.=" and upper(dokum.TYP_F)='P' and dokum.NABYWCA<>1";}	//gdy tylko podmagazyny, MZ=1
mysql_query($z);

mysql_query("truncate magazynyb");
mysql_query("insert into magazynyb select 0, ID_X, ID_T, sum(ILOSC), max(CENA_Z), max(DATA_Z) from magazyny group by ID_X, ID_T");
mysql_query("update analiza2b left join magazynyb on magazynyb.ID_X=analiza2b.ID_F and magazynyb.ID_T=".($w['ID_TOWARY'])." SET analiza2b.STAN=magazynyb.ILOSC");

mysql_query("delete from analiza2 where ID_OSOBYUPR=$ido");
mysql_query("insert into analiza2 select 0, $ido, ID_F, STAN, sum(analiza2b.SPRZEDAZ), sum(analiza2b.SPRZEDAZ1), sum(analiza2b.SPRZEDAZ2), sum(analiza2b.SPRZEDAZ3), sum(analiza2b.SPRZEDAZ4), sum(analiza2b.SPRZEDAZ5), sum(analiza2b.SPRZEDAZ6) from analiza2b group by analiza2b.ID_F");

if ($w['CZY_MIN']=='N') {		//bez magazynu zbiorczego
	mysql_query("delete from analiza2 where ID_F=1 and ID_OSOBYUPR=$ido");
}
mysql_query("update analiza2p SET CZAS=Now() where ID=$ipole");

$tabelaa='analiza2';	// tu l¹duje po akcji

//echo $raport;
?>

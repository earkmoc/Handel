<?php

$raport='';						//dodatkowe miesiêczne op³aty

$z="Select ID from tabele where NAZWA='abonenci'";		//zmiana grupy tylko z tabeli "abonenci"
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $ida=$w[0];							//ostatnio u¿ytego

$z="update opldodg set IDABONENTA=$ida, TYPOPER='o', ID_OSOBYUPR=$ido, CZAS=Now() where ID=$ipole limit 1";
$w=mysql_query($z);

//$z="select ZABLOK from abonenci where ID=$ida limit 1";
//$w=mysql_query($z); $w=mysql_fetch_row($w); $nik=$w[0];							//Niskie kody ?

$z="Select * from opldodg where ID=$ipole";
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$raport.=$z;

//if ((!($nik==='T')&&($w['INFO']*1)>999) {
if (($w['INFO']*1)>999) {
	$w['INFO']=$w['INFO']-1000;
}

if ($w['DATAOPER']<date('Y-m-d')) {
	$w['DATAOPER']=date('Y-m-d');
}

$dtstart=$w['DATAOPER'];
$wi=chr($w['INFO']*1).'pocz';

$z='insert into `opldod` set ';
$z.="`IDABONENTA`='".$w['IDABONENTA']."',";
$z.="`TYPOPER`='".$w['TYPOPER']."',";
$z.="`INFO`='$wi',";
$z.="`DATAOPER`='".$w['DATAOPER']."',";
$z.="`C1`='".ord(substr($wi,0,1))."',";
$z.="`C2`='".ord(substr($wi,1,1))."',";
$z.="`C3`='".ord(substr($wi,2,1))."',";
$z.="`C4`='".ord(substr($wi,3,1))."',";
$z.="`C5`='".ord(substr($wi,4,1))."' ";
$ww=mysql_query($z);

$raport.=$z;

$ww=mysql_insert_id();

$z="update opldodg set ID_OPLDOD=$ww where ID=$ipole limit 1";
$ww=mysql_query($z);

$wi=chr($w['INFO']*1).'zako';

$z='insert into `opldod` set ';
$z.="`IDABONENTA`='".$w['IDABONENTA']."',";
$z.="`TYPOPER`='".$w['TYPOPER']."',";
$z.="`INFO`='$wi',";
$z.="`DATAOPER`='".$w['DATAOPERR']."',";
$z.="`C1`='".ord(substr($wi,0,1))."',";
$z.="`C2`='".ord(substr($wi,1,1))."',";
$z.="`C3`='".ord(substr($wi,2,1))."',";
$z.="`C4`='".ord(substr($wi,3,1))."',";
$z.="`C5`='".ord(substr($wi,4,1))."' ";
$ww=mysql_query($z);

$raport.=$z;

$tabelaa='opldodA';	// tu l¹duje po akcji

include('oplatyzakaw.php');

//echo $raport;
?>
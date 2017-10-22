<?php

$sql='';

$z="Select * from typydok where KOD='FA' limit 1";
$w=mysql_fetch_array(mysql_query($z));		// mamy dane o ostatnim dokumencie z "typydok"
$sql.=$z; $sql.="<br>";

$dane['DATAWPLATY']=$wart[10];

$dane['NUMERFV']=trim($w['NUMER']);
$dane['MASKAFV']=trim($w['MASKA']);

$z="Select * from typydok where KOD='FA".$punkt."' limit 1";
$w=mysql_query($z);						// mamy dane ?
if (!$w||(mysql_num_rows($w)==0)) {	//nie
	$z="insert into typydok values (0,'FA".$punkt."','".$dane['NUMERFV']."',0,'','',NULL,'')";
	$sql.=$z; $sql.="<br>";
	$w=mysql_query($z);
	$z="Select * from typydok where KOD='FA".$punkt."' limit 1";
	$sql.=$z; $sql.="<br>";
	$w=mysql_query($z);				// mamy dane
}
$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok"

$dane['NUMERMFV']=trim($w['NUMERM']);

$dane['NUMERFV']=($dane['NUMERFV']+1);
$dane['NUMERMFV']=($dane['NUMERMFV']+1);
if (substr($dane['DATAWPLATY'],0,4)<>substr($w['DATA'],0,4)||substr($dane['DATAWPLATY'],5,2)<>substr($w['DATA'],5,2)) {
	$dane['NUMERMFV']=1;
}

$sql.="$dane[DATAWPLATY]=".$dane['DATAWPLATY']; $sql.="<br>";
$sql.="$w[DATA]=".$w['DATA']; $sql.="<br>";

$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer FA
$z.=$dane['NUMERFV'];
$z.="', NUMERM='";
$z.=$dane['NUMERMFV'];
$z.="', DATA='";
$z.=$dane['DATAWPLATY'];
$z.="' where KOD='FA' limit 1";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer FA
$z.=$dane['NUMERFV'];
$z.="', NUMERM='";
$z.=$dane['NUMERMFV'];
$z.="', DATA='";
$z.=$dane['DATAWPLATY'];
$z.="' where KOD='FA".$punkt."' limit 1";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

$dane['NUMERFV']=sprintf("%'06d",$dane['NUMERFV']);

$z="update faktury set ";						// zapis danych do "faktury"
$z.="NRFAKTURY='".$dane['NUMERFV']."',";
$z.="ROKDOKVAT='".substr($dane['DATAWPLATY'],0,4)."',";
$z.="MSCDOKVAT='".sprintf("%'02d",substr($dane['DATAWPLATY'],5,2))."',";
$z.="NRDOKVAT='".$dane['NUMERMFV']."/".$punkt."'";
$z.=" where ID=$ipole limit 1";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

$sql.='zaznaczone='.$zaznaczone; $sql.="<br>";

$x=count(explode(',',$zaznaczone));
if ($zaznaczone) {
	$x=explode(',',$zaznaczone);
	for($i=0;$i<count($x);$i++) {
		$zz="Select Z_TABELI, ID_WTABELI from specoplf where ID=$x[$i] limit 1";
		$ww=mysql_query($zz);
		$ww=mysql_fetch_row($ww);

		$z="update $ww[0] set ";						// zapis danych do tabel
		$z.="NRFAKTURY='".$dane['NUMERFV']."'";
		$z.=" where ID=$ww[1] limit 1";
		$w=mysql_query($z);
		$sql.=$z; $sql.="<br>";
	}
}
if ($wart[21]==='T') {$tabelaa='WydrukWzor.php?natab=abonenci&wzor=FA&ipole='.$ipole;}
else {$tabelaa='abonenci';}	// tu l¹duje po akcji 

//echo $sql;
?>

<?php

$sql='';

$z="Select * from typydok where KOD='FA' limit 1";
$w=mysql_fetch_array(mysql_query($z));		// mamy dane o ostatnim dokumencie z "typydok"
$sql.=$z; $sql.="<br>";

$dane['DATAWPLATY']=$wart[10];

$dane['NUMERFV']=trim($w['NUMER']);			//narastaj¹co z faktur

$z="Select * from typydok where KOD='FK".$punkt."' limit 1";
$sql.=$z; $sql.="<br>";
$w=mysql_query($z);				// mamy dane ?
if (!$w||(mysql_num_rows($w)==0)) {
	$z="insert into typydok values (0,'FK".$punkt."','".$dane['NUMERFV']."',0,'','',NULL,'')";
	$sql.=$z; $sql.="<br>";
	$w=mysql_query($z);
	$z="Select * from typydok where KOD='FK".$punkt."' limit 1";
	$sql.=$z; $sql.="<br>";
	$w=mysql_query($z);				// mamy dane
}
$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok"
$sql.=$z; $sql.="<br>";

$dane['NUMERMFV']=trim($w['NUMERM']);		//miesiêczny
$dane['MASKAFV']=trim($w['MASKA']);			//i maska z korekt

$dane['NUMERFV']=($dane['NUMERFV']+1);
$dane['NUMERMFV']=($dane['NUMERMFV']+1);
if (substr($dane['DATAWPLATY'],0,4)<>substr($w['DATA'],0,4)||substr($dane['DATAWPLATY'],5,2)<>substr($w['DATA'],5,2)) {
	$dane['NUMERMFV']=1;
}

$sql.="$dane[DATAWPLATY]=".$dane['DATAWPLATY']; $sql.="<br>";
$sql.="$w[DATA]=".$w['DATA']; $sql.="<br>";

$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer FV
$z.=$dane['NUMERFV'];
$z.="' where KOD='FA' limit 1";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer FK
$z.=$dane['NUMERFV'];
$z.="', NUMERM='";
$z.=$dane['NUMERMFV'];
$z.="', DATA='";
$z.=$dane['DATAWPLATY'];
$z.="' where KOD='FK".$punkt."' limit 1";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

$dane['NUMERFV']=sprintf("%'06d",$dane['NUMERFV']);

$z="update faktury set ";						// zapis danych FK koryguj¹cej do "faktury"
$z.="NRFAKTURY='".$dane['NUMERFV']."',";
$z.="ROKDOKVAT='".substr($dane['DATAWPLATY'],0,4)."',";
$z.="MSCDOKVAT='".sprintf("%'02d",substr($dane['DATAWPLATY'],5,2))."',";
$z.="NRDOKVAT='".$dane['NUMERMFV']."/".$punkt."'";
$z.=" where ID=$ipole limit 1";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

$z="select IDFAKTANUL, IDABONENTA from faktury";		// numer faktury korygowanej
$z.=" where ID=$ipole limit 1";
$w=mysql_fetch_row(mysql_query($z));
$sql.=$z; $sql.="<br>";

$ida=$w[1];

$z="update faktury set ";						// zapis danych FA korygowanej do "faktury"
$z.="IDFAKTANUL='".$dane['NUMERFV']."'";
$z.=" where NRFAKTURY='".$w[0]."' and IDABONENTA=$ida limit 1";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

$z="insert into anulpoz select NULL, '".$dane['NUMERFV']."', left(specbufk.PKWIU,1), specbufk.TYPTYTULU, specbufk.ZTYTULU, round(if(specbufk.PKWIU='By³o',-1,1)*specbufk.ILOSC*specbufk.CENABRUTTO,2), specbufk.DODNIA, specbufk.ZAMIESIAC, specbufk.NRRATY, $ida from specbufk where specbufk.ID_OSOBYUPR=$ido";
$w=mysql_query($z);
$sql.=$z; $sql.="<br>";

if ($wart[21]==='T') {$tabelaa='WydrukWzor.php?natab=fakturyA&wzor=FK&ipole='.$ipole;}
else {$tabelaa='fakturyA';}	// tu l¹duje po akcji 

//echo $sql;
?>

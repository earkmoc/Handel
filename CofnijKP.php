<?php

session_start();

$punkt=$_SESSION['osoba_pu'];

if (!$_SESSION['osoba_upr']) {
        echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
        echo "<title>OK</title></head><body bgcolor='#BFD2FF' ";
        echo "onload='";
        echo 'location.href="Tabela_End.php"';
        echo "'\'>";
        echo '</body></html>';
        exit;
};

//include('skladuj_zmienne.php');

require('dbconnect.inc');

//$sql.=($z='Select * from '.$_POST['batab'].' where ID='.$_POST['ipole'].' limit 1');
//if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};
//$w=mysql_fetch_array($w);
//$sql.=($z='Select * from dokwplat where ID='.$w['ID_OPLATY'].' limit 1');

$sql.=($z='Select * from dokwplat where ID='.$_POST['ipole'].' limit 1');
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};
$w=mysql_fetch_row($w);

$x=($dane['KPLUBBANK']=$w[1]);
$dane['IDABONENTA']=$w[8];
$dane['KWOTA']=trim($w[9]);
$dane['DATAWPLATY']=date('Y-m-d');
$dane['OSOBA_ID']=$_SESSION['osoba_id'];

//'.$_POST['batab'].'

if ($x==='1' || $x==='2') {	//cofanie banku nie tworzy nowego zapisu w dokwplat, tylko zmienia bieżšcy
	$id=$w[0];
}
else {
	$z='Insert into dokwplat values (0';
	for($i=1;$i<count($w);$i++) {$z.=",'".$w[$i]."'";};
	$sql.=($z.=")");
	if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (ID='.($id=mysql_insert_id()).')<br>';};
}

if ($x==='1' || $x==='2') {	//bank
	$dane['MASKA']='';
	$dane['NUMER']=$w[2];
	$dane['DATAWPLATY']=$w[3];
	$dane['OSOBA_ID']=$w[4];
	$dane['KWOTA']=0;
}
elseif ($x==='0') {		//KP
						$z="Select * from typydok where LITERA='".$dane['KPLUBBANK']."' limit 1";
						$w=mysql_fetch_array(mysql_query($z));			// mamy ostatni NUMER KP z "typydok"
						$sql.=$z; $sql.="<br>";

						$dane['NUMERKP']=trim($w['NUMER']);
						$dane['MASKAKP']=trim($w['MASKA']);

						$z="Select * from typydok where KOD='KP".$punkt."' limit 1";
						$w=mysql_query($z);						// mamy dane ?
						if (!$w||(mysql_num_rows($w)==0)) {	//nie
							$z="insert into typydok values (0,'KP".$punkt."','".$dane['NUMERKP']."',0,'','',NULL,'')";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);
							$z="Select * from typydok where KOD='KP".$punkt."' limit 1";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);				// mamy dane
						}
						$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok"

						$dane['NUMERKP']=trim($w['NUMER']);		//2006-05-30
						$dane['NUMERMKP']=trim($w['NUMERM']);	//renio 6000/m-c => 6000/1/12/2006
						$dane['MASKAKP']='/'.$punkt.'/'.substr($dane['DATAWPLATY'],5,2);	//6000/1/12

						$dane['NUMERKP']=($dane['NUMERKP']+1);
						$dane['NUMERMKP']=($dane['NUMERMKP']+1);
						if (substr($dane['DATAWPLATY'],0,7)<>substr($w['DATA'],0,7)) {
							$dane['NUMERMKP']=1;
						}

						$z="update typydok set NUMER='";				// zapisz że zwiększono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='KP' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";
	
						$z="update typydok set NUMER='";				// zapisz że zwiększono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='KP".$punkt."' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";

						$dane['NUMERKP']=$dane['NUMERMKP'];		//2006-05-30
						$dane['NUMER']=$dane['NUMERKP'];
						$dane['MASKA']=$dane['MASKAKP'];
}
elseif ($x==='.') {		//UM
						$z="Select * from typydok where LITERA='.' limit 1";
						$w=mysql_fetch_array(mysql_query($z));			// mamy ostatni NUMER KP z "typydok"
						$sql.=$z; $sql.="<br>";

						$dane['NUMERKP']=trim($w['NUMER']);
						$dane['MASKAKP']='/'.$punkt.trim($w['MASKA']);

						$z="Select * from typydok where KOD='UM".$punkt."' limit 1";
						$w=mysql_query($z);						// mamy dane ?
						if (!$w||(mysql_num_rows($w)==0)) {	//nie
							$z="insert into typydok values (0,'UM".$punkt."','".$dane['NUMERKP']."',0,'','',NULL,'')";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);
							$z="Select * from typydok where KOD='UM".$punkt."' limit 1";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);				// mamy dane
						}
						$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok"

						$dane['NUMERKP']=trim($w['NUMER']);		//2006-05-30
						$dane['NUMERKP']=($dane['NUMERKP']+1);
						$dane['NUMERMKP']=0;

						$z="update typydok set NUMER='";				// zapisz że zwiększono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='UM' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";
	
						$z="update typydok set NUMER='";				// zapisz że zwiększono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='UM".$punkt."' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";

						$dane['NUMER']=$dane['NUMERKP'];
						$dane['MASKA']=$dane['MASKAKP'];
}
else {
	$sql.=($z="Select * from typydok where LITERA='$x' limit 1");
	if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};
	$w=mysql_fetch_array($w);

	$dane['NUMER']=trim($w['NUMER']);
	$dane['MASKA']=trim($w['MASKA']);

	$dane['NUMER']=($dane['NUMER']+1);

	$z="update typydok set NUMER='";				// zapisz że zwiększono numer RN
	$z.=$dane['NUMER'];
	$z.="', DATA='";
	$z.=$dane['DATAWPLATY'];
	$z.="' where LITERA='";
	$z.=$dane['KPLUBBANK'];
	$sql.=($z.="' limit 1");
	if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (OK)<br>';};
}

$z="update dokwplat";				// zapisz że zwiększono numer
$z.=" set NRDOKUM='";
$z.=$dane['NUMER'].$dane['MASKA'];
$z.="', DATAPRZYJ='";
$z.=$dane['DATAWPLATY'];
$z.="', IDOPERATOR='";
$z.=$dane['OSOBA_ID'];				// inny operator
$z.="', KWOTA='";
$z.=(-$dane['KWOTA']);				// przeciwna kwota
$z.="', CZAS=Now()";
$z.=" where ID=".$id." limit 1";
$sql.=($z);
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (OK)<br>';};

$sql.=($z='Select NRDOKUM, DATAPRZYJ, IDOPERATOR, KPLUBBANK, IDABONENTA from dokwplat where ID='.$_POST['ipole']);
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};
$w=mysql_fetch_row($w);

$z="Select * from wplaty where (wplaty.NRDOKUM='[0]' and wplaty.DATAPRZYJ='[1]' and wplaty.IDOPERATOR=[2] and wplaty.KPLUBBANK='[3]'[4])";
$z=str_replace('[0]',$w[0],$z);
$z=str_replace('[1]',$w[1],$z);
$z=str_replace('[2]',$w[2],$z);
$z=str_replace('[3]',$w[3],$z);
if ($x==='1' || $x==='2') {	//bank cofa tylko jednego abonenta z wycišgu
	$z=str_replace('[4]',' and IDABONENTA='.$w[4],$z);
}
else {
	$z=str_replace('[4]','',$z);
}
$sql.=($z);
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};

while ($r=mysql_fetch_array($w)) {	// przywracanie kwot w "oplaty"
	$idr=$r['ID'];				// ID rekordu we "wplaty"
	if ($r['ID_OPLATY']) {	// nowy styl, gdzie nie usuwa opłat (nawet zerowych) i można robić częciowe wpłaty i cofki
		$sql.=($z='Select KWOTA from oplaty where ID='.$r['ID_OPLATY']);
		if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($q).')<br>';};
		$q=mysql_fetch_array($q);

		$sql.=($z='Update oplaty set KWOTA='.($q['KWOTA']+$r['WYSWPL']).' where ID='.$r['ID_OPLATY']);
		if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (OK)<br>';};
	}
	else {	// stary styl, brak zerowych opłat, cofki tylko całkowite, a nie częciowe
		$z="Insert into oplaty values (0,'";
		$z.=$r['IDABONENTA'];
		$z.="','";
		$z.=$r['TYPTYTULU'];
		$z.="','";
		$z.=$r['ZTYTULU'];
		$z.="','";
		$z.=$r['DODNIA'];
		$z.="','";
		$z.=$r['WYSWPL'];
		$z.="','";
		$z.=$r['NRFAKTURY'];
		$z.="','";
		$z.=$r['NRPOZYCJI'];
		$z.="','";
		$z.=$r['ZAMIESIAC'];
		$z.="')";
		$sql.=($z);
		if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (ID='.(mysql_insert_id()).')<br>';};
	}
	$sql.=($z="Select * from wplaty where ID=$idr limit 1");
	if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($q).')<br>';};
	$q=mysql_fetch_row($q);

	if ($x==='1' || $x==='2') {		//bank nie zmienia dat
		$dane['DATAWPLATY']=$q[10];
		$dane['DATAPRZYJ']=$q[11];
	}
	else {
		$dane['DATAWPLATY']=date('Y.m.d');
		$dane['DATAPRZYJ']=date('Y.m.d');
	}

	$q[7]=-$q[7];									// WYSWPL przeciwna kwota 
	$q[8]=$dane['NUMER'].$dane['MASKA'];	// NRDOKUM nowy
	$q[10]=$dane['DATAWPLATY'];				// DATAWPLATY dzi
	$q[11]=$dane['DATAPRZYJ'];					// DATAPRZYJ dzi
	$q[13]=$dane['OSOBA_ID'];					// IDOPERATOR nowy
//	$q[14]='';										// NRFAKTURY pusty = NIE !!! trzeba zmniejszyć specyfikację FAKTURY
//	$q[15]='';										// NRPOZYCJI pusty

	$z='Insert into wplaty values (0';		// kopia ze zmianami
	for($i=1;$i<count($q);$i++) {$z.=",'".$q[$i]."'";};
	$sql.=($z.=")");
	if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (ID='.(mysql_insert_id()).')<br>';};
};

$sql.=($z='Select NRDOKUM, DATAPRZYJ, IDOPERATOR, KPLUBBANK, IDABONENTA from dokwplat where ID='.$_POST['ipole']);
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};
$w=mysql_fetch_row($w);

$z="Select * from splaty WHERE (splaty.NRDOKUM='[0]' and splaty.DATAPRZYJ='[1]' and splaty.IDOPERATOR=[2] and splaty.KPLUBBANK='[3]'[4])";
$z=str_replace('[0]',$w[0],$z);
$z=str_replace('[1]',$w[1],$z);
$z=str_replace('[2]',$w[2],$z);
$z=str_replace('[3]',$w[3],$z);
if ($x==='1' || $x==='2') {	//bank cofa tylko jednego abonenta z wycišgu
	$z=str_replace('[4]',' and IDABONENTA='.$w[4],$z);
}
else {
	$z=str_replace('[4]','',$z);
}
$sql.=($z);
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};

while ($r=mysql_fetch_array($w)) {	// przywracanie kwot w "dlugi"
	$idr=$r['ID'];				// ID rekordu we "splaty"
	if ($r['ID_DLUGI']) {
		$sql.=($z='Select KWOTA from dlugi where ID='.$r['ID_DLUGI']);
		if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($q).')<br>';};
		$q=mysql_fetch_array($q);

		$sql.=($z='Update dlugi set KWOTA='.($q['KWOTA']+$r['WYSWPL']).' where ID='.$r['ID_DLUGI']);
		if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (OK)<br>';};
	}
	else {	// stary styl, brak zerowych dlugów, cofki tylko całkowite, a nie częciowe
		$z="Insert into dlugi values (0,'";
		$z.=$r['IDABONENTA'];
		$z.="','";
		$z.=$r['TYPTYTULU'];
		$z.="','";
		$z.=$r['ZTYTULU'];
		$z.="','";
		$z.=$r['DODNIA'];
		$z.="','";
		$z.=$r['WYSWPL'];
		$z.="','";
		$z.=$r['NRFAKTURY'];
		$z.="','";
		$z.=$r['NRPOZYCJI'];
		$z.="','";
		$z.=$r['WALUTA'];
		$z.="','";
		$z.=$r['NRRATY'];
		$z.="')";
		$sql.=($z);
		if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (ID='.(mysql_insert_id()).')<br>';};
	}
	$sql.=($z="Select * from splaty where ID=$idr limit 1");
	if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($q).')<br>';};
	$q=mysql_fetch_row($q);

	if ($x==='1' || $x==='2') {		//bank nie zmienia dat
		$dane['DATAWPLATY']=$q[10];
		$dane['DATAPRZYJ']=$q[11];
	}
	else {
		$dane['DATAWPLATY']=date('Y.m.d');
		$dane['DATAPRZYJ']=date('Y.m.d');
	}

	$q[7]=-$q[7];									// WYSWPL przeciwna kwota 
	$q[8]=$dane['NUMER'].$dane['MASKA'];	// NRDOKUM nowy
	$q[10]=$dane['DATAWPLATY'];				// DATAWPLATY dzi
	$q[11]=$dane['DATAPRZYJ'];					// DATAPRZYJ dzi
	$q[13]=$dane['OSOBA_ID'];					// IDOPERATOR nowy

//	$q[14]='';										// NRFAKTURY pusty
//	$q[15]='';										// NRPOZYCJI pusty

	$z='Insert into splaty values (0';		// kopia ze zmianami
	for($i=1;$i<count($q);$i++) {$z.=",'".$q[$i]."'";};
	$sql.=($z.=")");
	if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (ID='.(mysql_insert_id()).')<br>';};
};

$sql.=($z='Select NRDOKUM, DATAPRZYJ, IDOPERATOR, KPLUBBANK, IDABONENTA from dokwplat where ID='.$_POST['ipole']);
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};
$w=mysql_fetch_row($w);

$z="Select * from nadplaty WHERE (nadplaty.NRDOKUM='[0]' and nadplaty.DATAPRZYJ='[1]' and nadplaty.IDOPERATOR=[2] and nadplaty.KPLUBBANK='[3]'[4])";
$z=str_replace('[0]',$w[0],$z);
$z=str_replace('[1]',$w[1],$z);
$z=str_replace('[2]',$w[2],$z);
$z=str_replace('[3]',$w[3],$z);
if ($x==='1' || $x==='2') {	//bank cofa tylko jednego abonenta z wycišgu
	$z=str_replace('[4]',' and IDABONENTA='.$w[4],$z);
}
else {
	$z=str_replace('[4]','',$z);
}
$sql.=($z);
if (!$w=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($w).')<br>';};

while ($r=mysql_fetch_array($w)) {	// przywracanie kwot w "nadplaty"
	$idr=$r['ID'];
	$sql.=($z="Select * from nadplaty where ID=$idr limit 1");
	if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (afected rows : '.mysql_num_rows($q).')<br>';};
	$q=mysql_fetch_row($q);

	if ($x==='1' || $x==='2') {		//bank nie zmienia dat
		$dane['DATAWPLATY']=$q[10];
		$dane['DATAPRZYJ']=$q[11];
	}
	else {
		$dane['DATAWPLATY']=date('Y.m.d');
		$dane['DATAPRZYJ']=date('Y.m.d');
	}

	$q[7]=-$q[7];									// WYSWPL przeciwna kwota 
	$q[8]=$dane['NUMER'].$dane['MASKA'];	// NRDOKUM nowy
	$q[10]=$dane['DATAWPLATY'];				// DATAWPLATY dzi
	$q[11]=$dane['DATAPRZYJ'];					// DATAPRZYJ dzi
	$q[13]=$dane['OSOBA_ID'];					// IDOPERATOR nowy

//	$q[14]='';										// NRFAKTURY pusty
//	$q[15]='';										// NRPOZYCJI pusty

	$z='Insert into nadplaty values (0';		// kopia ze zmianami
	for($i=1;$i<count($q);$i++) {$z.=",'".$q[$i]."'";};
	$sql.=($z.=")");
	if (!$q=mysql_query($z)) {$sql.=' (nie poszło)<br>';} else {$sql.=' (ID='.(mysql_insert_id()).')<br>';};
};

$zz="Select sum(nadplaty.WYSWPL) from nadplaty where IDABONENTA=".$dane['IDABONENTA'];
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);

$zz="update abonenci set NADPLATA=".$ww[0]." where ID=".$dane['IDABONENTA']." limit 1";
$ww=mysql_query($zz);

require('dbdisconnect.inc');
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<title>"Handel"
<?php
if ($_SESSION['osoba_upr']) {
   echo ': ';
   echo $_SESSION['osoba_upr'];
	echo ' (operator Nr ';
	echo $_SESSION['osoba_id'];
	echo ')';
}
?>
</title>
</head>
<?php
echo "<body bgcolor='#BFD2FF' onload=";
echo '"location.href=';
echo "'Tabela.php?tabela=";
echo $_POST['natab'];
echo "'\">";
echo "\n";
//echo $sql;
echo '<hr><font style="font-size:100">';
echo 'Dokument cofnięty';
echo "</font>";
echo "<br><hr><a href='Tabela.php?tabela=";
echo $_POST['natab'];
echo "'>Powrót</a>";
?>
</body>
</html>
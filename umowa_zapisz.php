<?php
session_start();
require('dbconnect.inc');

$natab='wzoryumow2';

$z="Select ID from tabele where NAZWA='$natab' limit 1";		//ID tabeli
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$w=$w[0];

$z='Select ID_POZYCJI from tabeles where ID_OSOBY=';			//ID w tabeli
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE='.$w;
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$id=$w[0];

$natab='abonencisz';

$z="Select ID from tabele where NAZWA='$natab' limit 1";		//ID tabeli
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$w=$w[0];

$z='Select ID_POZYCJI from tabeles where ID_OSOBY=';			//ID w tabeli
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE='.$w;
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$ida=$w[0];

$ido=$_SESSION['osoba_id'];
$idp=$_SESSION['osoba_pu'];

//CREATE TABLE umowy (
//ID int(11) NOT NULL auto_increment,
//IDABONENTA int(11) NOT NULL default 0,
//IDOPERATORA int(11) NOT NULL default 0,
//PUNKT int(11) NOT NULL default 0,
//CZAS DATETIME not null,
//IDUMOWY int(11) NOT NULL default 0,
//ZMIENNA char(30) NOT NULL default '',
//WARTOSC char(50) NOT NULL default '',

foreach($_POST as $zmienna => $wartosc) {
	$z="insert into umowy values ( 0, $ida, $ido, $idp, Now(), $id, '$zmienna', '$wartosc' )";
	$w=mysql_query($z);
}
foreach($_POST as $zmienna => $wartosc) {

	$idztyt=0;
	$idzmgrupy=0;

	if ($zmienna=='na_pakiet') {
		$idzmgrupy=$wartosc;		//zmiana grupy
	}
	if (($zmienna=='MONTA¯_FILTRA') && $wartosc) {	//tu jest tylko X, ale ile to kosztuje ???
		$idztyt=33;
		$z="Select CENA from typyumow where ZTYTULRAT=$idztyt order by ID desc limit 1";
		$w=mysql_query($z); $w=mysql_fetch_row($w); 
		$wartosc=$w[0];
	}
	if ($zmienna=='wpisowe') {
		$idztyt=27;					//WPISOWE STK: ID=27
	}
	if ($zmienna=='op³ata_jednorazowa') {
		$idztyt=6;					//op³ata aktywacyjna Internet: ID=6
	}
	if ($idztyt) {
		$z="Select ID from tabele where NAZWA='abonenci'";		//przygotowania do "opldodzdoj.php"
		$w=mysql_query($z); $w=mysql_fetch_row($w); $idt=$w[0];

		$z="Select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido";	//ile zapisów ?
		$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

		if ($w) {	//jest
			$z="update tabeles SET ID_POZYCJI=$ida where ID_TABELE=$idt and ID_OSOBY=$ido";	//ID abonenta
			$w=mysql_query($z);
		}
		else {	//nie ma
			$z="insert into tabeles SET ID_POZYCJI=$ida, ID_TABELE=$idt, ID_OSOBY=$ido, NR_STR=1, NR_ROW=1, NR_COL=1";	//ID abonenta
			$w=mysql_query($z);
		}

		$z="Select ID from tabele where NAZWA='typoplatoj'";		//typ wybrany z tabeli
		$w=mysql_query($z); $w=mysql_fetch_row($w); $idt=$w[0];

		$z="Select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido";	//ile zapisów ?
		$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

		if ($w) {	//jest
			$z="update tabeles SET ID_POZYCJI=$idztyt where ID_TABELE=$idt and ID_OSOBY=$ido";	//ID abonenta
			$w=mysql_query($z);
		}
		else {	//nie ma
			$z="insert into tabeles SET ID_POZYCJI=$idztyt, ID_TABELE=$idt, ID_OSOBY=$ido, NR_STR=1, NR_ROW=1, NR_COL=1";	//ID abonenta
			$w=mysql_query($z);
		}


//tylko który typ umowy: A, B, C, D, E, F ???


		$z="Select ID, TYPUMOWY from typyumow where ZTYTULRAT=$idztyt and CENA=$wartosc order by ID desc limit 1";		//typ wybrany z tabeli
		$w=mysql_query($z); $w=mysql_fetch_row($w); $id27=$w[0]; $typum=$w[1];

		$z="Select ID from tabele where NAZWA='typyumowoj'";		//typ wybrany z tabeli
		$w=mysql_query($z); $w=mysql_fetch_row($w); $idt=$w[0];

		$z="Select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido";	//ile zapisów ?
		$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

		if ($w) {	//jest
			$z="update tabeles SET ID_POZYCJI=$id27 where ID_TABELE=$idt and ID_OSOBY=$ido";	//ID abonenta
			$w=mysql_query($z);
		}
		else {	//nie ma
			$z="insert into tabeles SET ID_POZYCJI=$id27, ID_TABELE=$idt, ID_OSOBY=$ido, NR_STR=1, NR_ROW=1, NR_COL=1";	//ID abonenta
			$w=mysql_query($z);
		}



		$z="insert into opldodg set IDABONENTA=$ida, TYPOPER='v', DATAOPER=Now(), INFO='1".$typum."', C1='$idztyt'";	//WPISOWE STK
		$w=mysql_query($z);
	   $ipole=mysql_insert_id();			// identyfikator nowego wiersza w tabeli


		include('opldodzdoj.php');			// naniesienie op³at abonentowi, a raczej d³ugów

	}	//if ($idztyt)

	if ($idzmgrupy) {
		$z="Select ID from tabele where NAZWA='abonenci'";		//przygotowania do "opldodzd.php"
		$w=mysql_query($z); $w=mysql_fetch_row($w); $idt=$w[0];

		$z="Select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido";	//ile zapisów ?
		$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

		if ($w) {	//jest
			$z="update tabeles SET ID_POZYCJI=$ida where ID_TABELE=$idt and ID_OSOBY=$ido";	//ID abonenta
			$w=mysql_query($z);
		}
		else {	//nie ma
			$z="insert into tabeles SET ID_POZYCJI=$ida, ID_TABELE=$idt, ID_OSOBY=$ido, NR_STR=1, NR_ROW=1, NR_COL=1";	//ID abonenta
			$w=mysql_query($z);
		}

		$z="Select ID from tabele where NAZWA='grupyopldo'";		//grupa wybrana z tabeli
		$w=mysql_query($z); $w=mysql_fetch_row($w); $idt=$w[0];

		$z="Select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido";	//ile zapisów ?
		$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

		if ($w) {	//jest
			$z="update tabeles SET ID_POZYCJI=$idztyt where ID_TABELE=$idt and ID_OSOBY=$ido";	//ID abonenta
			$w=mysql_query($z);
		}
		else {	//nie ma
			$z="insert into tabeles SET ID_POZYCJI=$idztyt, ID_TABELE=$idt, ID_OSOBY=$ido, NR_STR=1, NR_ROW=1, NR_COL=1";	//ID abonenta
			$w=mysql_query($z);
		}

		$z="insert into opldodg set IDABONENTA=$ida, TYPOPER='p', DATAOPER=Now(), INFO=replace('$idzmgrupy','-', ''), C1='$idztyt'";	//WPISOWE STK
		$w=mysql_query($z);
	   $ipole=mysql_insert_id();			// identyfikator nowego wiersza w tabeli


		include('opldodzd.php');			// naniesienie op³at abonentowi

	}	//if ($idzmgrupy)
}
require('dbdisconnect.inc');
?>
<html>
<head>
<title>Zapis udany</title>
</head>
<?php
echo '<body bgcolor="#0F4F9F" onload="';
echo "location.href='";
echo 'Tabela.php?tabela=wzoryumow2'."'";
echo '">';
echo '</body>';
echo "\n";
echo '</html>';
?>
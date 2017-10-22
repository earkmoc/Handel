<?php

session_start();

//require('skladuj_zmienne.php');

$tabela=$_POST['tabela'];          // zapisz tu
$tabelaa=$_POST['tabelaa'];        // i id¿ tu
$szukane=$_POST['szukane'];        // zawartosc pola w ktorym stal gdy wcisnal POMOC

$idtab=$_POST['idtab'];                        // ID tabeli gdzie dzia³a formularz
$id=$_POST['ID'];                                        // ID pozycji w tabeli j.w.
$op=$_POST['opole'];
$c=$_POST['c'];

require('dbconnect.inc');

$z="select * from tabele where NAZWA='";        // pobierz definicjê formularza
$z.=$tabela;
$z.="'";
$z.=' limit 1';

$baza='';
$dodany='';
$numerycznie='';
$w=mysql_query($z);
if ($w){
   $w=mysql_fetch_array($w);

   $sql=StripSlashes($w['TABELA']);
   $z=explode("\n",$sql);		// na linie
   $z=trim($z[0]);
   $z=explode(",",$z);
   $baza=trim($z[3]);
   if (!$baza) {
      $baza=trim($z[0]);
   }
   
   if (!$sql) {
      exit;
   } else {
		$w=explode("\n",$sql);		// na linie
		$l=explode("|",$w[$c]);		// liniê numer "$c" na pola
		$pola[0]=trim($l[4]);
		if (!$pola[0]) {$pola[0]=$l[0];}
      $wart[0]=$_POST[str_replace(".","krooopka",trim($pola[0]))];	// wartoœæ pola
		$dodany=$_POST['dodany'];	// dodany ?
		$numerycznie=$_POST['numerycznie'];	// numerycznie ?
   }
}
$z="Select ID from tabeles where ID_OSOBY=".$_SESSION['osoba_id']." and ID_TABELE=$idtab limit 1";
$w=mysql_query($z);
if ($w) {                                                // jest namiar na konkretny stan
	$w=mysql_fetch_array($w);
	$idtabeles=$w[0];                                // ID konkretnego stanu
	if ($wart[0]==='bez') {
		$z="Update tabeles set SORTOWANIE='' where ID=$idtabeles limit 1";
		$w=mysql_query($z);
		$sqla=$z;
	} elseif ($dodany=='on') {

      $z="Select ID, SORTOWANIE from tabeles where ID=$idtabeles limit 1";
      $w=mysql_query($z);
      $w=mysql_fetch_array($w);
      $w=$w['SORTOWANIE'];
      
      $z="Update tabeles set NR_STR=1,NR_ROW=1,SORTOWANIE='";
		if ($w) {$z.="$w, ";}
      if ($numerycznie=="on") {$z.="1*";}
// jest kropka w nazwie pola, np. "grupy.NAZWAGR"
		if (count(explode(".",$pola[0]))>1) { $z.='';} else {$z.=$baza.'.';}
		$z.=strip_tags($pola[0]);
      $z.=' ';
      $z.=$wart[0];
      $z.="' where ID=$idtabeles limit 1";
      $w=mysql_query($z);
      $sqla=$z;
	} else {
      $z='Update tabeles';
      $z.=" set NR_STR=1,NR_ROW=1,SORTOWANIE='";
      if ($numerycznie=="on") {$z.="1*";}
      // jest kropka w nazwie pola, np. "grupy.NAZWAGR"
      if (count(explode(".",$pola[0]))>1) {$z.='';} else {$z.=$baza.'.';}
      $z.=strip_tags($pola[0]);
      $z.=' ';
      $z.=$wart[0];
      $z.="' where ID=$idtabeles limit 1";
      $w=mysql_query($z);
      $sqla=$z;
	}
}

//echo $sqla;exit;

$w=mysql_query($z);                // zmiana warunku

if ($w) {
	echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
	echo '<title>Zapis udany</title></head><body bgcolor="#0F4F9F" ';
	echo 'onload="';
	echo "location.href='Tabela.php?tabela=".$tabelaa."'";
	echo '">';
	echo '</body></html>';
}
if (!$w) {echo "$z<br  /><br  />niestety nie wysz³o !!!";}
//mysql_free_result($w);
require('dbdisconnect.inc');
?>

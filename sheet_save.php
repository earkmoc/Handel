<?php

session_start();
$ido=$_SESSION['osoba_id'];
$tabela='dokum';

//require('pokaz_zmienne.php');exit;

header('Location: Tabela.php?tabela=spec');
 
require_once('funkcje.php');
require('dbconnect.inc');

$w=mysql_query("select ID from tabele where NAZWA='$tabela'");
if ($r=mysql_fetch_row($w)) {
   $w=mysql_query("select ID_POZYCJI from tabeles where ID_TABELE=$r[0] and ID_OSOBY=$ido");
   if ($r=mysql_fetch_row($w)) {
      $idd=$r[0];
   }
}

if ($idd) {
   $w=mysql_query("delete from spec where ID_D=$idd");
}

$ipole=0;

foreach($_POST as $zmienna => $wartosc) {

   $tab=explode('_',$zmienna);      //towar_0_3  = 1000-0001 

   $r=$tab[1];
   $c=$tab[2];

   if ($c==3) {   //INDEKS
      $w=mysql_query("select ID, CENA_S, VAT from towary where INDEKS='$wartosc'");
      if ($r=mysql_fetch_row($w)) {
         $idt=$r[0];
         $cenabezr=$r[1];
         $rabat=0;
         $cena=$r[1];
         $vat=$r[2];
      }
   }
   if ($c==5&&$wartosc) {   //cena
      $cenabezr=$wartosc;
      $cena=$cenabezr;
   }
   if ($c==6&&$wartosc) {   //rabat
      $rabat=$wartosc;
      $cena=myRound($cenabezr-$cenabezr*$rabat*0.01);
   }
   if ($c==9&&$idd&&$idt) {   //ILOSC
      $ilosc=1*$wartosc;

      $w=mysql_query("select CENA_S3 from towary where ID=$idt");
      if ($r=mysql_fetch_row($w)) {
         if (($r[0]>0)&&($cena<$r[0])) {   //przekroczenie ceny netnet (je¶li jest okre¶lona)
             $cena=$r[0];
         }
      }

      $netto=myRound($ilosc*$cena);
      $kwotavat=myRound($netto*$vat*0.01);
      $brutto=$netto+$kwotavat;
      $cenabrutto=(($ilosc*1<>0)?myRound($brutto/$ilosc):99999);
      if ($ilosc*1<>0) {
         mysql_query("insert into spec (ID, ID_D, ID_T, CENA,  ILOSC,  RABAT,  CENABEZR,  NETTO,  KWOTAVAT,  BRUTTO,  CENABRUTTO,   STAWKAVAT) 
                                values (0, $idd, $idt, $cena, $ilosc, $rabat, $cenabezr, $netto, $kwotavat, $brutto, $cenabrutto, '$vat' )");
         $ipole=mysql_insert_id();
         require('spec_calc.php');
      }
      $idt=0;
   }
}

//$ipole=0;
$tabelaa='';
?>
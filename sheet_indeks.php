<?php 

session_start();
$ido=$_SESSION['osoba_id'];
$tabela='dokum';

require_once('funkcje.php');
require('dbconnect.inc');

$indeks=$_GET['indeks'];
$ilosc=($_GET['ilosc']?$_GET['ilosc']:1);
$rabat=$_GET['rabat'];
$cennik=0;

if (!$rabat) {
   $w=mysql_query("select ID from tabele where NAZWA='$tabela'");
   if ($r=mysql_fetch_row($w)) {
      $w=mysql_query("select ID_POZYCJI from tabeles where ID_TABELE=$r[0] and ID_OSOBY=$ido");
      if ($r=mysql_fetch_row($w)) {
         $idd=$r[0];
         $w=mysql_query("select TOWCENNIK, TOWRABAT from $tabela where ID=$idd");
         if ($r=mysql_fetch_row($w)) {
            $cennik=$r[0];
            $rabat=$r[1];
            $rabat=($rabat*1<>0?$rabat:'');
         }
      }
   }
}

$cennik=($cennik<=1?'':($cennik>5?5:$cennik));
$znak=ord(substr(trim($indeks),0,1));

$q="select INDEKS, NAZWA, CENA_S$cennik, STAN, JM, VAT, CENA_S3 from towary where STATUS='T' and ";
if (!(48<=$znak && $znak<=57)) { //litera
   $q.="NAZWA like '%$indeks%'";
} elseif (strlen($indeks)>9) {
   $q.="KODPAS like '%$indeks%'";
} else {
   if ((strlen($indeks)==8)&&(substr($indeks,4,1)<>'-')) {
      $indeks=substr($indeks,0,4).'-'.substr($indeks,4,4);
   }
   $q.="INDEKS like '%$indeks%'";
}
$w=mysql_query($q);
$r=mysql_fetch_row($w);
$r[3]=sprintf('%15.0f',$r[3]);      //."&nbsp;&nbsp;&nbsp;&nbsp;";   //stan

$cena=$r[2];
$cena=myRound($cena-$cena*$rabat*0.01);
$cena=sprintf('%5.2f',$cena);

if (($r[6]>0)&&($cena<$r[6])) {   //przekroczenie ceny netnet (je¶li jest okre¶lona)
    $cena=$r[6];
}

$vat=$r[5]*0.01;
$netto=myRound($ilosc*$cena);
$kwotavat=myRound($netto*$vat);
$brutto=$netto+$kwotavat;
$cenabrutto=(($ilosc*1<>0)?myRound($brutto/$ilosc):99999);

echo $r[0].'|'.$r[1].'|'.$r[2].'|'.$rabat.'|'.$cena.'|'.$r[3].'|'.$r[4].'|'.$r[5].'|';

if ($ilosc*1<>0) {
   echo sprintf('%15.2f',$netto).'|';
   echo sprintf('%15.2f',$kwotavat).'|';
   echo sprintf('%15.2f',$brutto).'|';
   echo sprintf('%15.2f',$cenabrutto).'|';
}
?>
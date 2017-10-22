<?php 

require_once('funkcje.php');

$ilosc=$_GET['ilosc'];
$cena=$_GET['cena'];
$vat=$_GET['vat']*0.01;

$netto=myRound($ilosc*$cena);
$kwotavat=myRound($netto*$vat);
$brutto=$netto+$kwotavat;

$cenabrutto=(($ilosc*1<>0)?myRound($brutto/$ilosc):99999);
if ($ilosc*1<>0) {
   echo sprintf('%15.2f',$netto).'|';
   echo sprintf('%15.2f',$kwotavat).'|';
   echo sprintf('%15.2f',$brutto).'|';
   echo sprintf('%15.2f',$cenabrutto).'|';
}
?>
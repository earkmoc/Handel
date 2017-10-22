<?php 

require_once('funkcje.php');

$cena=$_GET['cena'];
$rabat=$_GET['rabat'];
$ilosc=($_GET['ilosc']?$_GET['ilosc']:0);
$vat=$_GET['vat']*0.01;

$cena=myRound($cena-$cena*$rabat*0.01);
$netto=myRound($ilosc*$cena);
$kwotavat=myRound($netto*$vat);
$brutto=$netto+$kwotavat;
$cenabrutto=(($ilosc*1<>0)?myRound($brutto/$ilosc):99999);

if ($ilosc*1<>0) {
   echo sprintf('%15.2f',$cena).'|';
   echo sprintf('%15.2f',$netto).'|';
   echo sprintf('%15.2f',$kwotavat).'|';
   echo sprintf('%15.2f',$brutto).'|';
   echo sprintf('%15.2f',$cenabrutto).'|';
}
?>
<?php
//require('skladuj_echozmienne.php');

$zaznaczone=$_POST['zaznaczone'];
$ileZaznaczone=count(explode(',',$zaznaczone));
$ileSkasowano=0;
$ileProcent=0;

$w_megasp=mysql_query("
   select * from megasp where ID IN ($zaznaczone)
");
while ($r_megasp=mysql_fetch_array($w_megasp)) {
   if ($r_megasp['FLAGA']<>'*') {
      $w_dokum=mysql_query("
         select ID from dokum where NABYWCA='$r_megasp[DOSTAWCA]' and INDEKS='$r_megasp[NUMER_PZ]' and DATAW='$r_megasp[DATA_PZ]' limit 1
      ");
      if ($r_dokum=mysql_fetch_row($w_dokum)) {
         $w_magazyny=mysql_query("
            update magazyny set NO_SPZ='*' where NO_SPZ<>'*' and ID_F='$r_megasp[DOSTAWCA]' and ID_D='$r_dokum[0]' and ID_T='$r_megasp[ID_T]' limit 1
         ");
         if (mysql_affected_rows()>0) {
            mysql_query("
               delete from megasp where ID=$r_megasp[ID] limit 1
            ");
            $ileSkasowano++;
         }
      }
   }
}

if ($ileZaznaczone<>0) {
   $ileProcent=100*$ileSkasowano/$ileZaznaczone;
}
$komunikat.="Skasowano $ileProcent % ($ileSkasowano / $ileZaznaczone) pozycji z tego rozliczenia";

?>
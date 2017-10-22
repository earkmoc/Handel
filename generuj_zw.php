<?php

$ilezam=0;
$ww=mysql_query("select DOSTAWCA, NUMERFD, NUMER_PZ from megasp where ZAMAWIANE<>0 group by NUMERFD");
while ($rr=mysql_fetch_row($ww)) {

   $numerfd=$rr[1];
   
   $z=("select DATAO from dokum where TYP='PZ' and INDEKS='[2]' and NABYWCA=[0]");
   for ($i=0;$i<count($rr);$i++) {$z=str_replace("[$i]",$rr[$i],$z);}
   $w=mysql_query($z);
   if ($r=mysql_fetch_row($w)) {

      $datao=$r[0];

      $z=("select ID, INDEKS, NAZWA, KOD, MIASTO, ADRES, NIP, TELEFON from firmy where ID=[0]");
      for ($i=0;$i<count($rr);$i++) {$z=str_replace("[$i]",$rr[$i],$z);}
      $w=mysql_query($z);
      if ($r=mysql_fetch_row($w)) {
         $z=("insert into dokum set BLOKADA='O', TYP='ZW', NUMERFD='$numerfd', DATAO='$datao', NABYWCA=[0], INDEKS_F='[1]', NAZWA='[2]', KOD='[3]', MIASTO='[4]', ADRES='[5]', NIP='[6]', TYP_F='D', MAGAZYN=1, DATAW=CurDate(), DATAS=CurDate(), CZAS=Now()");
         for ($i=0;$i<count($r);$i++) {$z=str_replace("[$i]",$r[$i],$z);}
         $w=mysql_query($z);
         $insertef=mysql_insert_id();
         $ilezam++;
         
         mysql_query("insert into spec select 0, $insertef, ID_T, CENA_ZAKUP, ZAMAWIANE, 0, CENA_ZAKUP, ZAMAWIANE*CENA_ZAKUP, 0, ZAMAWIANE*CENA_ZAKUP, CENA_ZAKUP, '' from megasp where ZAMAWIANE<>0 and NUMERFD='$numerfd'");
         $w=mysql_query("select INDEKS from dokum where TYP='ZW' and INDEKS<>'' order by ID desc");
         if ($r=mysql_fetch_row($w)) {

            $z=("update dokum set INDEKS=right(concat('0000','[0]'*1+1,'-11'),7) where ID=$insertef limit 1"); 
            for ($i=0;$i<count($r);$i++) {$z=str_replace("[$i]",$r[$i],$z);}
            $w=mysql_query($z);
   
            $w=mysql_query("select sum(NETTO) from spec where ID_D=$insertef"); 
            if ($r=mysql_fetch_row($w)) {
               $z=("update dokum set WARTOSC=[0] where ID=$insertef limit 1");
               for ($i=0;$i<count($r);$i++) {$z=str_replace("[$i]",$r[$i],$z);}
               $w=mysql_query($z);
            }
         }
      }
   }
}

if ($ilezam) {
   $komunikat.="Powsta³o $ilezam dokumentów ZW";
}
?>

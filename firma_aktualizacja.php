<?php

$ww=mysql_query("
select NABYWCA 
      , sum(if(right(TYP,1)<>'K',WARTOSC,0))
      , sum(WPLACONO)
      , sum(if(UWAGI like '%korekta%',UWAGI*1,0))
      , sum(if(right(TYP,1)='K',WARTOSC,0)) 
   from dokum 
  where BLOKADA='' 
    and TYP<>'INW' 
    and TYP<>'ZAM' 
    and TYP<>'ZNI' 
    and TYP<>'ZW'
    and NABYWCA=$nab
");

if ($rr=mysql_fetch_row($ww)) {
   $idn=$rr[0];
   $nal=$rr[1]*1;
   $zal=$rr[2]*1;
   $kou=$rr[3]*1;
   $kor=$rr[4]*1;
   
   mysql_query("
   update firmy 
      set NALEZNOSCI=$nal-$kou
        , ZALICZKI=$zal
        , KOREKTY=$kor+$kou 
    where ID=$nab
   ");
}

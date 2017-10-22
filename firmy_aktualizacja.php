<?php

$w=mysql_query("
update firmy 
   set NALEZNOSCI=0
     , ZALICZKI=0
     , KOREKTY=0 
");

//$w=mysql_query("
//select NABYWCA 
//      , sum(if(right(TYP,1)<>'K',if((left(TYP,2)='PZ') and (BRUTTODOS<>0),BRUTTODOS,WARTOSC),0))
//      , sum(WPLACONO)
//      , sum(if(UWAGI like '%korekta%',UWAGI*1,0))
//      , sum(if(right(TYP,1)='K',if((left(TYP,2)='PZ') and (BRUTTODOS<>0),BRUTTODOS,WARTOSC),0)) 
//   from dokum 
//  where BLOKADA='' 
//    and TYP<>'INW' 
//    and TYP<>'ZAM' 
//    and TYP<>'ZNI' 
//    and TYP<>'ZW'
//group by NABYWCA
//");

$w=mysql_query("
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
group by NABYWCA
");

while ($r=mysql_fetch_row($w)) {
   $idn=$r[0];
   $nal=$r[1]*1;
   $zal=$r[2]*1;
   $kou=$r[3]*1;
   $kor=$r[4]*1;
   
   mysql_query("
   update firmy 
      set NALEZNOSCI=$nal-$kou
        , ZALICZKI=$zal
        , KOREKTY=$kor+$kou 
    where ID=$idn
   ");
}

?>

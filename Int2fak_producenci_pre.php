<?php

//---------------------------------------------------------------------------------
//teraz wszyscy producenci do sklepu

$z=("
    SELECT upper(WARTOSC)
      from parametry
     where NAZWA='Producenci'
  order by WARTOSC
");

$ww_pro=mysql_query($z);

?>

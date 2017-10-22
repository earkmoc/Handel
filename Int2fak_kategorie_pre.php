<?php

//---------------------------------------------------------------------------------
//teraz wszystkie kategorie do sklepu

$z=("
    SELECT *
      from parametry
     where NAZWA='Kategorie'
  order by LP, ID
");

$ww_kat=mysql_query($z);

?>

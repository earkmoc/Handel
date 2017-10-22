<?php

session_start();

$ido=$_SESSION['osoba_id'];

set_time_limit(6*60*60);	// 6h

require('dbconnect.inc');

echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Parrot - Naprawa magazynu od 2011-06-30 20:54:10</title></head><body>';
echo 'Naprawa magazynu od 2011-06-30 20:54:10: start='.$start=date('Y.m.d / H.i.s');
echo "<br>";
flush();

//---------------------------------------------------------------------------------

$www=mysql_query("
  select ID
    from dokum
   where CZAS='2011-11-06 17:07:14'
     and BLOKADA=''
order by CZAS
");
//limit 100

$licznik=0;
while ($r=mysql_fetch_row($www)) {
   $idd=$r[0];
   $licznik++;
//   if ($licznik++%10==0) {flush();}
   include("close_all_naprawa.php");

}

//---------------------------------------------------------------------------------

echo "<br>Start: ".$start;
echo "<br> Stop: ".date('Y.m.d / H.i.s');
echo "<br><br><a href='index.php'>Powr˘t</a>";
flush();
die;

?>
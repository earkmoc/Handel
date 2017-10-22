<?php

echo "<br>".$start_time=time();
sleep(4);
echo "<br>".$cur_time=time();
echo "<br>".($cur_time-$start_time);
echo "<br>".ini_get('max_execution_time');

echo "<br>".$s=date('Y.m.d / H.i.s');
echo "<br>".substr($s,3,4);
//echo substring($s,3,4);
die;

$plik='c:\ParrotLi\megakm.DBF';
$db=dbase_open($plik,0);
$fn=dbase_numfields($db);
$lastrec=dbase_numrecords($db);
$dbh=dbase_get_header_info($db);

echo "<table border=1 cellpadding=5 cellspacing=0>";
echo "<caption align=left><font size=5>$plik</font></caption>";

for($i=0;$i<$fn;$i++) {
   echo "<td>";
   echo '<b>';
   echo $dbh[$i]['name'];
   echo '</b><br>';
   echo '<font color="#CCCCCC">';
   echo $dbh[$i]['type'].','.$dbh[$i]['length'].','.$dbh[$i]['precision'];
   echo '</font>';
   echo "</td>";
}

for($rn=1;$rn<=$lastrec;$rn++) {
   echo "<tr>";
   $rec=dbase_get_record ($db,$rn);
   for($i=0;$i<$fn;$i++) {
      echo "<td nowrap align='".(substr($dbh[$i]['format'],0,2)=='%-'?'left':'right')."' >";
      if ($rec[$i]) {
         echo sprintf($dbh[$i]['format'],$rec[$i]);
      } else {
         echo "&nbsp;";
      }
      echo "</td>";
   }
   echo "</tr>";
   if ($rn>10) {
      exit;
   }
}
echo "</table>";

exit;

$s='towary
ID|ID|0|style="text-align:right"|
INDEKS|Indeks||style="text-align:left"|
NAZWA|Nazwa||style="text-align:left"|
STAN|Stan|@Z@z9|style="text-align:right"|
JM|Jm||
CENA_S|Cena zbytu|@Z|style="text-align: right"|
CENA_S2|Cena detal|@Z|style="text-align: right"|
CENA_S3|Cena spec|@Z|style="text-align: right"|
SWW|PKWiU||style="text-align:left"|
VAT|Stawka VAT|
KODPAS|Kod paskowy|
DOSTAWCA|Dostawca||style="text-align:right"|
PRODUCENT|Producent|||style="text-align:right"|
UWAGI|Uwagi||style="text-align:left"|
STAN_MIN|Stan minimalny|@Z@z%12.3f|style="text-align: right"|
STATUS|Status|
RABAT|Rabat|@Z|style="text-align: right"|
from towary
where STATUS<>"S"
order by NAZWA';

$w=strpos($s,'where');
if ($w) {
   echo $s=substr($s,$w);
   $w=strpos($s,"order");
   if ($w) {
      echo substr($s,0,$w);
   }
}
die;

require('dbconnect.inc');

$w=mysql_query("
   select NAZWA
     from dokum
    where ID=7
");

while ($r=mysql_fetch_row($w)) {
   $x=explode(chr(13).chr(10),$r[0]);
   for ($i=0;$i<count($x);$i++) {
      echo "[...]$x[$i][...]<br>";
   }
}

require('dbdisconnect.inc');

$s='Znajd¼';
echo substr($s,0,3);

$cenaold=15.54;
echo $cenaold_zl=sprintf("%' 7.2f z³",$cenaold);
echo '<br>';
echo substr(date('Y-m-d'),-2,2)*1;
echo '<br>';
echo substr('00000000001',-2,2);
echo '<br>';
echo substr('00000000001',-2,2)*1;
echo '<br>';
echo $dzis='2011-05-01';    //testowo
echo '<br>';
echo (substr($dzis,-2,2)*1==1);
echo '<br>';
echo (substr($dzis,-2,2));
echo '<br>';
echo (substr($dzis,-2,2)==1);
echo '<br>';
echo '.'.(substr($dzis,-2,2)==2).'.';

?>
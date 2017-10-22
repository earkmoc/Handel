<?php

session_start();
$filtr=$_SESSION['filtr'];

//require('skladuj_zmienne.php'); die;

require('dbconnect.inc');

$z=("
  update towary
     set STAN_POP=STAN
        ,STAN=0
   where STATUS='T'
");
if ($filtr) {
  $z.=" and $filtr";
}
if ($w=mysql_query($z)) {
  $ile=mysql_affected_rows();
  echo "Dla filtra '$filtr' wyzerowano stany $ile pozycji";
  echo'<br><br><br>';
  echo "<button onclick='location.href=\"Tabela.php?tabela=towary\"'> Powrót </button>";
  die;
}

?>
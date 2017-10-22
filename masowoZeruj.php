<?php
//require('skladuj_zmienne.php');

$w=mysql_query("
  select ID 
    from tabele 
   where NAZWA='$natab' 
");

if ($r=mysql_fetch_row($w)) {
  $w=mysql_query("
    select WARUNKI 
      from tabeles 
     where ID_TABELE='$r[0]'
       and ID_OSOBY=$ido 
  ");
  
  if ($r=mysql_fetch_row($w)) {
      $filtr=$r[0];
      $_SESSION['filtr']=$filtr;
      $z=("
        select count(*)
              ,sum(if(STAN=0,1,0))
              ,sum(if(STAN<0,1,0))
              ,sum(if(STAN>0,1,0))
          from $batab 
         where STATUS='T'
      ");
      if ($filtr) {
        $z.=" and $filtr";
      }
      $w=mysql_query($z);
      
      if ($r=mysql_fetch_row($w)) {
        echo "Dla filtra '$filtr' jest $r[0] pozycji do wyzerowania stanów, w tym:<br>";
        echo "zerowych : $r[1]<br>";
        echo "ujemnych : $r[2]<br>";
        echo "dodatnich: $r[3]<br>";
        echo "<br>Zerowaæ stany dla inwentaryzacji ca³oœciowej ?<br>";
        echo "<button onclick='location.href=\"masoweZerujTak.php\"'> TAK </button>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<button onclick='location.href=\"Tabela.php?tabela=$natab\"'> NIE </button> <br>";
        die;
      }
  }
}

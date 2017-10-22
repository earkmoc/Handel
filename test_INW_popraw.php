<?php

$problem=false;
$id=$_GET[ID];
$idd=$_GET[IDD];
$ile=$_GET[ile];

header("Location: test_INW.php?zaznaczone=$idd&dalej=1");

require('dbconnect.inc');

mysql_query("
   update spec
      set CENABRUTTO=$ile
         ,CENABEZR=$ile
    where ID=$id
");

echo '<br>Poprawione na '.$ile;

$w=mysql_query("
   select ID_D
         ,ID_T
     from spec
    where ID=$id
");

if ($r=mysql_fetch_row($w)) {

   $idd=$r[0];
   $idt=$r[1];

//jaki jest identyfikator ostatniej inwentaryzacji na tym towarze ?
   $w=mysql_query("
      select spec.ID_D
        from spec
   left join dokum 
          on (dokum.ID=abs(spec.ID_D))
       where spec.ID_T=$idt
         and dokum.TYP='INW'
    order by dokum.ID desc
       limit 1
   ");

   if (($r=mysql_fetch_row($w))&&(abs($r[0])==abs($idd))) {  //to jest w³a¶nie ostatnia inwentaryzacja tego towaru

      $w=mysql_query("
         select sum(CENABEZR)
           from spec
          where ID_D=$idd
            and ID_T=$idt
      ");
   
      if ($r=mysql_fetch_row($w)) {                //ustaw w magazynie nowy "stan poprzedni" zgodny z tym co jest teraz w specyfikacji INW 
         mysql_query("
            update towary
               set STAN_POP='$r[0]'
             where ID=$idt
         ");

         echo "<br>Stan poprzedni poprawiony na $r[0]";

      } else {
         $problem=true;
         echo "<br>Nie ustalono sumy analityk";
      }
   } else {
      $problem=true;
      echo "<br>To nie jest ostatnia inwentaryzacja: $r[0] <> $idd";
   }
}

//if (!$problem) {
if (false) {
   echo "\n";
   echo '<script type="text/javascript" language="JavaScript">';
   echo "\n<!--\n";
   echo 'window.opener.location.reload(true);';
   echo 'window.close();';
   echo "\n-->\n";
   echo "</script>";
   echo "\n";
}
//}
?>

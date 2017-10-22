<?php 
  $ida=$_GET['ida'];
  require('dbconnect.inc');
  $q="select NAZWISKO, IMIE from abonenci where ID=$ida";
  $w=mysql_query($q);
  $r=mysql_fetch_row($w);
  echo $r[0].'|'.$r[1].'|';
?>
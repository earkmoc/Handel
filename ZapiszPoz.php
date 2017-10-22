<?php
header('Location: Tabela.php?tabela='.$_POST['tabela']."&klawisz=F"); 
//require('skladuj_zmienna.php');
require('dbconnect.inc');

$w=mysql_query("
   select FORMULARZ
     from tabele 
    where NAZWA='".$_POST['tabela']."' 
    limit 1
");

$w=mysql_fetch_row($w);
$w=StripSlashes(trim($w[0]));

if (substr($w,0,1)=='#') {   //definicja jest gdzie indziej
  $w=substr($w,1);
  $_POST['tabela']=$w;
  $z="select FORMULARZ from tabele where NAZWA='$w' limit 1";
  $w=mysql_query($z);
  $w=mysql_fetch_row($w);
  $w=StripSlashes(trim($w[0]));
}
        
$w=explode("\n",$w);
$ww=Count($w);

$p=trim($_POST['opole']);
//echo $p; exit;
$p=explode("\n",$p);
$pp=Count($p);

$ppp='';

for($j=0;$j<$pp;$j++) {
   $r=explode(",",StripSlashes($p[$j]));		//nowe ustawienia z POST
   for($i=1;$i<$ww;$i++) {
      $l=explode("|",$w[$i]);	//z definicji formularza
      if ($l[0]==$r[0]) {
         if ($l[1]==$r[5]) {$r[5]='';}
//if (substr($r[0],0,1)=='(') {
//	skladuj($r[3]);
//}
         if (substr($l[3],0,strlen($r[3])-2)==substr($r[3],0,strlen($r[3])-2)) {$r[3]='';}
      }
   }
   $ppp.=$r[0].",".$r[1].",".$r[2].",".$r[3].",".$r[4].",".$r[5].",".$r[6].",".$r[7]."\n";
}

mysql_query("
   update tabele 
      set PARAMSF='".$ppp."' 
    where NAZWA='".$_POST['tabela']."' 
    limit 1
");
require('dbdisconnect.inc');
?>
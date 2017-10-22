<?php

//require('funkcje_sql.php');
function mysql_query1($z) {
	$w=mysql_query($z);
   return $w;
}
function mysql_queryy($z) {

   $na_ekran=false;
   $log=true;

   if ($log) {
   	$czas1=date('H')*3600+date('i')*60+date('s')+(1*microtime());
   }

	$w=mysql_query($z);

   if ($log) {
   	$czas2=date('H')*3600+date('i')*60+date('s')+(1*microtime());

      if (true||($czas2-$czas1>=0.01)||mysql_error()) {

//         for ($i=0;$i<30;$i++) {
//         	fputs($file,ord($z[$i]).',');
//         }
   
         $z=str_replace(chr(9),'',$z);
         $z=str_replace(chr(10),'',$z);
         $z=str_replace(chr(13),'',$z);

         while (strpos($z,'  ')) {  //koszenie wielokrotnych spacji
            $z=str_replace('  ',' ',$z);
         }
         $z=trim($z);   	
   
         if ($na_ekran) {
            echo date('Y.m.d_H.i.s')." = ".str_pad($czas2-$czas1,20)."(".str_pad(mysql_affected_rows(),10)."): $z\n<br>";
         	if (mysql_error()) {
               echo ("    Error: ".mysql_error()."\n<br>");
            }
         } else {
         	$file=fopen('mysql_query.log',"a");
            	fputs($file,date('Y.m.d_H.i.s')." = ".str_pad($czas2-$czas1,20)."(".str_pad(mysql_affected_rows(),10)."): $z\n");
            	if (mysql_error()) {
               	fputs($file,"    Error: ".mysql_error()."\n");
               }
         	fclose($file);
         }
      }
   }

	return $w;
}
function echoo($z) {

	$tmpfname='mysql_query.log';
	$file=fopen($tmpfname,"a");
   	fputs($file,"$z\n");
	fclose($file);
	
	return $w;
}
?>
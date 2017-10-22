<?php

$raport='';						//odciêcie

$z="Select ID from tabele where NAZWA='abonenci'";		//zmiana grupy tylko z tabeli "abonenci"
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $ida=$w[0];								//ostatnio u¿ytego

$z="update opldodg set IDABONENTA=$ida, TYPOPER='a', INFO='b', ID_OSOBYUPR=$ido, CZAS=Now() where ID=$ipole limit 1";
$w=mysql_query($z);

$z="Select * from opldodg where ID=$ipole";
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$raport.=$z;

$dtstart=$w['DATAOPER'];
$wi=$w['INFO'];

$z='insert into `opldod` set ';
$z.="`IDABONENTA`='".$w['IDABONENTA']."',";
$z.="`TYPOPER`='".$w['TYPOPER']."',";
$z.="`DATAOPER`='".$w['DATAOPER']."',";
$z.="`INFO`='$wi',";
$z.="`C1`='".ord(substr($wi,0,1))."',";
$z.="`C2`='".ord(substr($wi,1,1))."',";
$z.="`C3`='".ord(substr($wi,2,1))."',";
$z.="`C4`='".ord(substr($wi,3,1))."',";
$z.="`C5`='".ord(substr($wi,4,1))."' ";
$ww=mysql_query($z);

$raport.=$z;

$ww=mysql_insert_id();

$dt=$dtstart;
$rd=substr($dt,0,4)*1;		// '2006-04-11'  ->   '2006'*1 -> 2006
$md=substr($dt,5,2)*1;		// '2006-04-11'  ->   '04'*1   ->    4
$dd=1;							//pierwszy dzieñ
$md++;							//przysz³y miesi¹c: 4 -> 5
if ($md>12) {$md=1; $rd++;}//12.2006 -> 1.2007
$da=sprintf("%'04d",$rd);
$da.='-'.sprintf("%'02d",$md);
$da.='-'.sprintf("%'02d",$dd);	//'2006-05-01'

$z="delete from oplaty where IDABONENTA=$ida and DODNIA>='".$da."' and NRFAKTURY=''";
$w=mysql_query($z);

$z="update opldodg set ID_OPLDOD=$ww where ID=$ipole limit 1";
$w=mysql_query($z);

$z="update abonenci set STATUS='' where ID=$ida limit 1";
$w=mysql_query($z);

$tabelaa='opldodA';	// tu l¹duje po akcji

//echo $raport;
?>
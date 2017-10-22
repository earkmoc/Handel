<?php

//require('skladuj_zmienne.php');

$ipole=$_POST['ipole'];
$jeden_wiersz=false;

//if (!$rmc) {
//	$jeden_wiersz=true;
//	$w=mysql_query("
//		select ROK_MIES
//			 , K1
//		  from tab_obl
//		 where ID=$ipole
//	");
//	if ($r=mysql_fetch_row($w)) {
//		$rmc=$r[0];
//		$bo=$r[1];
//		$ddd=str_replace('.','-',$rmc);
//	}
//}

//if (!$jeden_wiersz) {
//	if ($rmc=='2011.07') {
//		$w=mysql_query("
//			select sum(CENA*ILOSC)
//			  from spec
//			 where ID_D=$idd_pop;
//		");
//	} else {
//		$w=mysql_query("
//			select sum(CENA*STAN)
//			  from magazyny
//		");
//	}
//	$r=mysql_fetch_row($w);
//	$bo=$r[0];
//}
	
$w=mysql_query("
	select sum(NETTOCZ)
	  from dokum
	 where $war
	   and BLOKADA=''
	   and TYP IN ('PZ','PZK')
");
$za=0;
if ($r=mysql_fetch_row($w)) {$za=$r[0];}

$w=mysql_query("
	select sum(NETTOCZ)
		 , sum(if(TOWCENNIK=0,NETTOCZ,0))
		 , sum(if(TOWCENNIK=1,NETTOCZ,0))
		 , sum(if(TOWCENNIK>1,NETTOCZ,0))
		 , sum(NETTODOS)
	  from dokum
	 where $war
	   and BLOKADA=''
	   and TYP IN ('FM','FMK','PM','PMK','FA','FAK','PA','PAK','FI','FIK','PI','PIK')
");
$sp=0;
if ($r=mysql_fetch_row($w)) {$sp=$r[0];}
$spz=$r[1];
$spb=$r[2];
$spd=$r[3];
//$spd=$r[4];

$w=mysql_query("
	select sum(NETTOCZ)
	  from dokum
	 where $war
	   and BLOKADA=''
	   and TYP IN ('WZ')
");
$wz=0;
if ($r=mysql_fetch_row($w)) {$wz=$r[0];}

$w=mysql_query("
	select sum(NETTOCZ)
	  from dokum
	 where $war
	   and BLOKADA=''
	   and TYP IN ('INW')
");
$inw=0;
if ($r=mysql_fetch_row($w)) {$inw=$r[0];}

$w=mysql_query("
	select sum(NETTOCZ)
	  from dokum
	 where $war
	   and BLOKADA=''
	   and TYP IN ('RE')
");
$re=0;
if ($r=mysql_fetch_row($w)) {$re=$r[0];}

$w=mysql_query("
	select sum(NETTOCZ)
	  from dokum
	 where $war
	   and BLOKADA=''
	   and TYP IN ('ZN')
");
$zn=0;
if ($r=mysql_fetch_row($w)) {$zn=$r[0];}

$bz=$bo+$za-$sp+$inw-$re-$zn-$wz;

if ($jeden_wiersz) {
	$z="
	  update tab_obl
	";
	$zz="
	   where ROK_MIES='$rmc'
	";
} else {
	$z="
	  insert 
	    into tab_obl
	";
	$zz="";
}
$raport="<br>".$z=("
		$z
	 set ROK_MIES='$rmc'
	 	,K1='$bo'
	 	,K2='$za'
	 	,K3='$sp'
	 	,K4='$spz'
	 	,K5='$spb'
	 	,K6='$spd'
	 	,K7='$wz'
	 	,K8='$inw'
	 	,K9='$re'
	 	,K10='$zn'
	 	,K11='$si'
	 	,K12='0'
	 	,K13='$bz'
		$zz
");

mysql_query($z);

if ($jeden_wiersz) {
	$id_last=0;
} else {
	$id_last=mysql_insert_id();
}

$raport.="<br>".$z=("
  select K12+K13
    from tab_obl
   where ID<$id_last
order by ID desc
   limit 1
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
$raport.="<br>"."od=$r[0]-$bo";
$raport.="<br><b>".($od=$r[0]-$bo)."</b>";

$raport.="<br>".$z=("
  update tab_obl
	  set K12='$od'
	 	  ,K13='$bo'
   where ID<$id_last
order by ID desc
   limit 1
	");

	mysql_query($z);

   if (abs($od)>=1.00) {
      echo $raport."<br>";
   }
}

?>
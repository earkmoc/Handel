<?php

session_start();

$ido=$_SESSION['osoba_id'];

set_time_limit(6*60*60);	// 6h

require('dbconnect.inc');

echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Parrot - Aktualizacja systemu od 2011-06-30 20:54:10</title></head><body>';
echo 'Aktualizacja systemu od 2011-06-30 20:54:10: start='.$start=date('Y.m.d / H.i.s');
echo "<br><hr>";
flush();

//---------------------------------------------------------------------------------
// Aktualizacja rozliczeń firm

$startTime=date('H')*60*60+date('i')*60+date('s');
require('firmy_aktualizacja.php');
$stopTime=date('H')*60*60+date('i')*60+date('s');
if ($deltaTime=$stopTime-$startTime) {
   echo "<hr>Aktualizacja stanu rozliczeń kontrahentów zajęła $deltaTime sek.<hr>";
}

//---------------------------------------------------------------------------------

mysql_query("ALTER TABLE towary CHANGE CZAS_OZ CZAS_OZ TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

$www=mysql_query("update towary set STAN=0, STAN_POP=0, STAN_PRZED=0, STAN_DELTA=0");

mysql_query("ALTER TABLE `tab_obl` CHANGE `ROK_MIES` `ROK_MIES` CHAR( 20 ) NULL DEFAULT NULL ");

//---------------------------------------------------------------------------------

//INW 0001-11: ID=105514
//	 and CZAS< '2011-11-06 17:07:14'

mysql_query("
   update dokum
      set DATAS='2011-12-14'
    where TYP='PZ'
      and INDEKS='1223-11'
    limit 1
");

mysql_query("
  delete 
	from tab_obl
   where ID>141
");

$www=mysql_query("
  select ID
  		,CZAS
  		,TYP
  		,INDEKS
  		,concat(left(concat(TYP,' '),3),INDEKS)
  		,DATAS
    from dokum
   where DATAS>='2011-06-30' and CZAS>='2011-06-30 20:54:10'
     and BLOKADA=''
order by DATAS, CZAS
");
//limit 100

$dokumentami=false;

$si=0;
$pop='';
$licznik=0;
while ($r=mysql_fetch_row($www)) {

	$idd=$r[0];
//	$ddd=substr($r[1],0,10).$r[4];			//kolejny dokument
//	$ddd=substr($r[1],0,10);				//data z CZAS
//	$ddd=$r[5];								//DATAS
if ($dokumentami) {
	$ddd=substr($r[5],0,10).$r[4];  //kolejny dokument
} else {
	$ddd=substr($r[5],0,7);         //kolejny m-c
}

	if ($licznik==0) {
		$idd_pop=$idd;
		mysql_query("
		  update magazyny
	  left join dokum
			   on dokum.ID=magazyny.ID_D
		     set magazyny.ID_T=-abs(magazyny.ID_T)
		   where dokum.CZAS>='2011-06-30 20:54:10'
		");
//		mysql_query("
//		  delete
//          from dokum
//		   where CZAS>'2011-06-30 20:54:10'
//		      or DATAW>='2011-07-01'
//		      or DATAS>='2011-07-01'
//		");
		mysql_query("
		  delete 
		    from magazyny
		   where ID_T<0
		");
		mysql_query("
		  update magazyny
		     set STAN=0
		");
//		mysql_query("
//		  update towary
//		     set STATUS='S'
//		   where STATUS='T'
//		");
		mysql_query("
		  update towary
		     set STAN=0
		       , STAN_POP=0
		");
		mysql_query("
		  update towary
		     set STATUS='T'
         where INDEKS='1300-0562'
         limit 1
		");
//		$w=mysql_query("
//			select sum(CENA*ILOSC)
//			  from spec
//			 where ID_D=$idd_pop;
//		");
//		$r=mysql_fetch_row($w);
//		$bo=$r[0];							//BO
//		$bo=807643.20;
		$bo=0;
	} else {
		if ($ddd<>$pop) {
			$w=mysql_query("
				select sum(CENA*STAN)
				  from magazyny
			");
			$r=mysql_fetch_row($w);
			$bo=$r[0];						//BO
		}
	}

   if ($dokumentami) {
   	$w=mysql_query("
   		select count(*)
   		  from spec
   		 where ID_D=-$idd
   	");
   	$r=mysql_fetch_row($w);
   	$si=$r[0];							//ile pozycji na dokumencie
   }

	include("close_all_naprawa.php");		//zmiana stanu magazynu i NETTOCZ dokumentu

	if (($licznik==0)||($ddd<>$pop)) {

//		$w=mysql_query("
//			select sum(CENA*STAN)
//			  from magazyny
//		");
//		$r=mysql_fetch_row($w);
//		$bz=$r[0];							//BZ

//		if (round($nettocz,2)<>round(abs($bo-$bz),2)) {
//			echo "<br>($nettocz<>($bo-$bz))<br>";
//		}

		if ($licznik==0) {
			$rmc=$ddd;							//substr($ddd,0,4).'.'.substr($ddd,5,2);
		} else {
			$rmc=$ddd;							//substr($ddd,0,4).'.'.substr($ddd,5,2);
		}
		$war="left(DATAS,7)=left('$rmc',7)";	//miesiącami
//		$war="left(CZAS,7)=left('$rmc',7)";		//miesiącami z czasu
//		$war="DATAS='$rmc'";					//dniami
//		$war="left(CZAS,10)=left('$rmc',10)";	//dniami
//		$war="left(CZAS,10)=left('$rmc',10) and TYP=substr('$rmc',11,3) and INDEKS=substr('$rmc',14,7)";	//dokumentami
      if ($dokumentami) {
   		$war="DATAS=left('$rmc',10) and TYP=substr('$rmc',11,3) and INDEKS=substr('$rmc',14,7)";	//dokumentami
      }
		require('magazyn_rozliczenie.php');	//rozliczenie po zmianach
	}
	$pop=$ddd;
	$licznik++;
}

//---------------------------------------------------------------------------------

mysql_query("ALTER TABLE towary CHANGE CZAS_OZ CZAS_OZ TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

echo "<hr><br>Start: ".$start;
echo "<br> Stop: ".date('Y.m.d / H.i.s');
echo "<br><br><a href='index.php'>Powr˘t</a>";
flush();
die;

?>
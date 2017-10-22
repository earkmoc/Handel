<?php

require('towary_kolumny.php');

//<!-- ROZPOCZ®CIE SKùADOWANIA ZMIENNYCH -->
//<!-- ZMIENNE GET -->
//<!-{ natab=towarywyb, projectKod=... } -->
//<!-- ZMIENNE POST -->
//<!-{ natab=spec, batab=spec, sutab=dokum, sutabpol=[0].[3].[4], sutabmid=115512, idtab=642, ipole=0, opole=, strpole=1, rpole=1, cpole=6, kpole=68, rrpole=1, rrrpole=10, phpini=, zaznaczone=, offsetX=0, offsetY=0 } -->
//<!-- ZMIENNE SESJI -->
//<!-{ osoba_pu=1, osoba_os=XP, idtab_master=, osoba_upr=Arkadiusz Moch (660-736-575), osoba_id=1, osoba_dos=T, osoba_gr=1, doktyp=INW, doktypnazwa=Inwentaryzacje, ntab_mast=dokum } -->

//require('skladuj_zmienne.php');exit;

$szukaj=true;
$update=false;
$ilosc=0;
$doktyp=$_SESSION['doktyp'];

$sutab=$_POST['sutab'];		//dokum
$idd=1*$_POST['sutabmid'];	//ID_D

$indeks=$_GET['projectKod'];
$ipole=$_POST['ipole'];
$znak=substr(trim($indeks),0,1);
$zkod=ord($znak);

if (!(48<=$zkod && $zkod<=57)) { 					//litera
	//   $_POST['NAZWA']=str_replace(' ','_',$indeks);
	$_POST['NAZWA']=$indeks;
	$c=$kolumna_nazwa;
} elseif ((strlen($indeks)>9)&&(!strpos($indeks,'.'))&&(!strpos($indeks,','))) {	//kod paskowy
	$indeks=substr($indeks,0,13);
	$_POST['KODPAS']=$indeks;
	$c=$kolumna_kodpas;
} elseif ((strlen($indeks)==8)||(strlen($indeks)==9)) {		//indeks z kreské lub bez kreski
	if (substr($indeks,4,1)<>'-') {							//indeks bez kreski
		$indeks=substr($indeks,0,4).'-'.substr($indeks,4,4);	//indeks teraz z kreské
	}
	$_POST['INDEKS']=$indeks;
	$c=$kolumna_indeks;
} elseif ($ipole&&($doktyp=='INW')&&(strlen($indeks)>9)&&((strpos($indeks,'.'))||(strpos($indeks,',')))) {	//indeks z iloúciπ

	if (strpos($indeks,'.')) {
		$znak='.';
	} else {
		$znak=',';
	}

	$buf=explode($znak,$indeks);
	$indeks=$buf[0];
	$ilosc=$buf[1]*1;

	$update=true;
	$szukaj=true;

	if (substr($indeks,4,1)<>'-') {							//indeks bez kreski
		$indeks=substr($indeks,0,4).'-'.substr($indeks,4,4);	//indeks teraz z kreské
	}

	$_POST['INDEKS']=$indeks;
	$c=$kolumna_indeks;

	$w=mysql_query("
		select ID
		  from towary
		 where INDEKS='$indeks'
		   and STATUS='T'
	");
	if ($r=mysql_fetch_row($w)) {
		$ipole=$r[0];
	}
} elseif ($ipole&&($doktyp=='INW')) {
	$ilosc=$indeks*1;
	$update=true;
	$szukaj=false;
	$c=$kolumna_indeks;
} else {
	$_POST['INDEKS']=$indeks;
	$c=$kolumna_indeks;
}

if ($update) {

	$w=mysql_query("
		select ID
		     , CENA_Z
		  from towary
		 where ID=$ipole
	");
	if ($r=mysql_fetch_row($w)) {

		$idt=$r[0];
		$cena=$r[1];

      if ($_SESSION['nieprzepisuj']) {
   		mysql_query("
   			update towary
   			   set STAN=$ilosc
   			 where ID=$ipole
   		");
      } else {
   		mysql_query("
   			update towary
   			   set STAN_POP=STAN
                 , STAN=$ilosc
   			 where ID=$ipole
   		");
      }

		$w=mysql_query("
			select ID
			  from spec
			 where ID_D=$idd
			   and ID_T=$idt
		");
		if (($r=mysql_fetch_row($w))&&($r[0])) {

			$ipole=$r[0];

			mysql_query("
				update spec
				   set ID_D=$idd
				     , ID_T=$idt
					 , ILOSC=$ilosc
					 , CENABEZR=$cena
				 where ID=$ipole
			");	

		} else {
			mysql_query("
				insert 
				  into spec
				   set ID_D=$idd
				     , ID_T=$idt
					 , ILOSC=$ilosc
					 , CENABEZR=$cena
			");	

			$ipole=mysql_insert_id();
				
		}
	}
}

$_POST['c']=$c;

//********************************************************************
// zapami©taj stan tabeli dla zalogowanej osoby

$r=$_POST['rpole'];
$c=$_POST['cpole'];
$str=$_POST['strpole'];

$idtab=$_POST['idtab'];
$w=mysql_query("select count(*) from tabeles where ID_TABELE=$idtab and ID_OSOBY=$ido");
$w=mysql_fetch_row($w);

if ($w[0]>0) {
	$w=mysql_query(     "update tabeles set ID_POZYCJI=$ipole, NR_STR=$str, NR_ROW=$r, NR_COL=$c where ID_TABELE=$idtab and ID_OSOBY=$ido limit 1");
} else {
	$w=mysql_query("Insert into tabeles set ID_POZYCJI=$ipole, NR_STR=$str, NR_ROW=$r, NR_COL=$c, ID_TABELE=$idtab, ID_OSOBY=$ido");
}

// zapami©taj stan tabeli dla zalogowanej osoby
//********************************************************************

if ($update) {
	//	require('spec_calc.php');
}

if (!$szukaj) {
	header("location:Tabela.php?tabela=$natab");
} else {

	//zmiana na "towarywyb"

	$w=mysql_query("
		select ID from tabele where NAZWA='$natab'
	");
	$r=mysql_fetch_row($w);
	$_POST['idtab']=$r[0];

	$_POST['opole']='S';
	$_POST['tabela']=$natab;
	$_POST['tabelaa']=$natab;

	require("Tabela_Szukaj_Zapisz.php");

}

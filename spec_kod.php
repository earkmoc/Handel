<?php

$indeks=$_GET['projectKod'];
$ilosc=1;
$rabat=0;
$cennik=0;
$doktyp='';

$ipole=$_POST['ipole'];
$sutab=$_POST['sutab'];
$idd=1*$_POST['sutabmid'];
$w=mysql_query("select TOWCENNIK, TOWRABAT, TYP from $sutab where ID=$idd");
if ($r=mysql_fetch_row($w)) {
	$cennik=$r[0];
	$rabat=$r[1];
	$rabat=($rabat*1<>0?$rabat:'');
	$doktyp=$r[2];
}

$cennik=($cennik<=1?'':($cennik>5?5:$cennik));
$znak=ord(substr(trim($indeks),0,1));

$update=false;
$update_stawki_VAT=false;

$odcen='Netto';
$ktoreceny=($odcen=='Netto'?'S':'B');

$w=mysql_query("select ODCEN, if(MAGAZYNP IN (1,3),1,0) from doktypy where TYP='$doktyp'");
if ($r=mysql_fetch_row($w)) {
	$odcen=$r[0];
	$ktoreceny=($odcen=='Netto'?'S':'B');
	if ($r[1]) {	//ceny zakupu
		$ktoreceny='Z';
	}
}

$q="select INDEKS, NAZWA, CENA_".$ktoreceny.$cennik.", STAN, JM, VAT, CENA_S3, ID from towary where STATUS='T' and ";

//if (!(48<=$znak && $znak<=57)) { //litera
//   $q.="NAZWA like '%$indeks%'";
//} else

if (strlen($indeks)>9) {	//kod paskowy
   $indeks=substr($indeks,0,13);
   $q.="KODPAS='$indeks'";
} elseif ((strlen($indeks)==8)||(strlen($indeks)==9)) {		//indeks z kreské lub bez kreski
   if (substr($indeks,4,1)<>'-') {							//indeks bez kreski
      $indeks=substr($indeks,0,4).'-'.substr($indeks,4,4);	//indeks teraz z kreské
   }
   $q.="INDEKS='$indeks'";
} elseif ((strtoupper($indeks)=='ZW.')
		||(strtoupper($indeks)=='ZW')
		||(	  (strlen($indeks)<=3)
			&&(substr($indeks,-1,1)=='%'))) { //zmiana stawki VAT
	$stawka=$indeks;
	$update_stawki_VAT=true;
} elseif ($ipole) {					//zmiana iloë>ci
	$ilosc=$indeks*1;
	$update=true;
}

$raport_sql='';

$ok=true;			//øeby nie wchodzi≥ do alert_red na koÒcu
if ($update) {
	mysql_query("
		update spec
		   set ILOSC=$ilosc
		 where ID=$ipole
	");
	$str=$_POST['strpole'];
	$r=$_POST['rpole'];
} elseif ($update_stawki_VAT) {
	mysql_query("
		update spec
		   set STAWKAVAT='$stawka'
		 where ID=$ipole
	");
	$str=$_POST['strpole'];
	$r=$_POST['rpole'];
} else {

$raport_sql.='1, ';

	$ok=false;
	$w=mysql_query($q);
	while (!$ok&&($r=mysql_fetch_row($w))) {

$raport_sql.='2, ';

		$towary_indeks=$r[0];
		$towary_nazwa=$r[1];
		if (1*$ilosc<=1*$r[3]) {			//stan kt¢ry pokrywa æ•dan• iloòÜ
			$ok=true;
		}
		$cenabezr=$r[2];
		$r[3]=sprintf('%15.0f',$r[3]);      //."&nbsp;&nbsp;&nbsp;&nbsp;";   //stan
		$idt=$r[7];
	}

	if (!$ok) {

$raport_sql.='3, ';

		if (strlen($indeks)>9) {		//kod paskowy
			$w=mysql_query("
                   select megais.INDEKS 
			               from megais 
			          left join towary 
			                 on towary.ID=megais.ID_TOWARY 
			              where INDEKSS='$indeks' 
			                and towary.STATUS='T'
			                and towary.STAN<>0
			");

$raport_sql.='4, ';

			if ($r=mysql_fetch_row($w)) {

$raport_sql.='5, ';

				$indeks=$r[0];
				$ok=true;
			} else {

$raport_sql.='6, ';

				$w=mysql_query("
                       select megais.INDEKS 
				                 from megais 
				            left join towary 
    			                 on towary.ID=megais.ID_TOWARY 
				                where INDEKSS='$indeks' 
				                  and towary.STATUS='T'
				");
				if ($r=mysql_fetch_row($w)) {

$raport_sql.='7, ';

					$indeks=$r[0];
					$ok=true;
				}   
			}
		}
		
		if ($ok) {

$raport_sql.='8, ';

			$ok=false;
			$q="select INDEKS, NAZWA, CENA_".$ktoreceny.$cennik.", STAN, JM, VAT, CENA_S3, ID from towary where STATUS='T' and ";
			$q.="INDEKS='$indeks'";

$raport_sql.='<br>'.$q.'<br>';

  		$w=mysql_query($q); 
			while (!$ok&&($r=mysql_fetch_row($w))) {

$raport_sql.='9, ';

				$towary_indeks=$r[0];
				$towary_nazwa=$r[1];
				if (1*$ilosc<=1*$r[3]) {			//stan kt¢ry pokrywa æ•dan• iloòÜ
					$ok=true;
				}
				$cenabezr=$r[2];
				$r[3]=sprintf('%15.0f',$r[3]);      //."&nbsp;&nbsp;&nbsp;&nbsp;";   //stan
				$idt=$r[7];
			}
		} 

		if (!$ok) {

$raport_sql.='10, ';

			$str=$_POST['strpole'];			//ta strona
			$r=$_POST['rpole'];				//ten wiersz
		}
	}
	
if ($ktoreceny=='Z'&&$towary_indeks) {	//nie alarmuj o stanie zerowym dla dokumentÛw od cen zakupu (INW, PZ)
	$ok=true;
	$ilosc=0;
}

	if ($ok) {
		mysql_query("
			insert 
			  into spec
			   set ID_D=$idd
			     , ID_T=$idt
				 , ILOSC=$ilosc
				 , CENABEZR=$cenabezr
				 , RABAT='$rabat'
		");	
		$ipole=mysql_insert_id();
		$str=999999;						//ostatnia strona
		$r=$_POST['rrrpole'];				//ostatni wiersz
	}
}

//********************************************************************
// zapami©taj stan tabeli dla zalogowanej osoby

$c=$_POST['cpole'];
$idtab=$_POST['idtab'];
$w=mysql_query("select count(*) from tabeles where ID_TABELE=$idtab and ID_OSOBY=$ido"); $w=mysql_fetch_row($w);

if ($update||$update_stawki_VAT) {
	if ($w[0]>0) {
	   $w=mysql_query(     "update tabeles set ID_POZYCJI=$ipole, NR_STR=$str, NR_ROW=$r, NR_COL=$c where ID_TABELE=$idtab and ID_OSOBY=$ido limit 1");
	} else {
	   $w=mysql_query("Insert into tabeles set ID_POZYCJI=$ipole, NR_STR=$str, NR_ROW=$r, NR_COL=$c, ID_TABELE=$idtab, ID_OSOBY=$ido");
	}
} else {
	if ($w[0]>0) {
	   $w=mysql_query(     "update tabeles set WARUNKI='', SORTOWANIE='', ID_POZYCJI=$ipole, NR_STR=$str, NR_ROW=$r, NR_COL=$c where ID_TABELE=$idtab and ID_OSOBY=$ido limit 1");
	} else {
	   $w=mysql_query("Insert into tabeles set WARUNKI='', SORTOWANIE='', ID_POZYCJI=$ipole, NR_STR=$str, NR_ROW=$r, NR_COL=$c, ID_TABELE=$idtab, ID_OSOBY=$ido");
	}
}
// zapami©taj stan tabeli dla zalogowanej osoby
//********************************************************************

if ($ok) {
	header("location:Tabela.php?tabela=$natab");
	require('spec_calc.php');
} else {
	if (!$towary_indeks) {
		$komunikat="Brak takiego towaru w magazynie:<br><br>KOD = $indeks";
	} else {
		$komunikat="Zerowy stan towaru w magazynie:<br><br>KOD = $indeks<br>Indeks = $towary_indeks<br>Nazwa = $towary_nazwa<br>Stan = 0";
	}
    $raport_sql='';
	require('alert_red.php');
}

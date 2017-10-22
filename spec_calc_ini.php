<?php

$z="Select ID from tabele where NAZWA='$dokum_ini'";				//dokum, dokum_FVK
$w=mysql_query($z); $w=mysql_fetch_row($w); 
$w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID dokum
$w=mysql_query($z); $w=mysql_fetch_row($w); 
$id_d=$w[0];

$z="Select TYP, NABYWCA, TOWCENNIK, TOWRABAT from dokum where ID=$id_d limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$doktyp=$w[0];
$idn=$w[1];
$cennik=$w[2]*1;
$rabat=$w[3];

$z="Select ID from tabele where NAZWA='$towarywyb'";
$w=mysql_query($z); $w=mysql_fetch_row($w); 
$w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID towary
$w=mysql_query($z); $w=mysql_fetch_row($w); 
$id_t=$w[0];

$z="Select if(TYP_F in ('M','D'),1,0), ODCEN from doktypy where TYP='$doktyp'";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$wgcz=($w[0]==1);
$odcen=$w[1];

//------------------------------------------------------------------

$z="Select * from towary where ID=$id_t limit 1";
$w=mysql_query($z); $w=mysql_fetch_array($w);

$wynik[5]=$id_d;
$wynik[6]=$id_t;

$wynik[7]=$w['INDEKS'];
$wynik[8]=$w['INDEKS2'];
$wynik[9]=$w['NAZWA'];
$wynik[10]=$w['KOD'];
$wynik[11]=$w['NAZWA2'];
$wynik[12]=$w['NAZWA3'];
$wynik[13]=$w['CENA_Z'];
$wynik[14]=$w['VAT'];
$wynik[15]=$w['MARZA'];
$wynik[16]=$w['CENA_S'];
$wynik[17]=$w['CENA_B'];
$wynik[18]=$w['MARZA2'];
$wynik[19]=$w['CENA_S2'];
$wynik[20]=$w['CENA_B2'];
$wynik[21]=$w['MARZA3'];
$wynik[22]=$w['CENA_S3'];
$wynik[23]=$w['CENA_B3'];
$wynik[24]=$w['MARZA4'];
$wynik[25]=$w['CENA_S4'];
$wynik[26]=$w['CENA_B4'];
$wynik[27]=$w['MARZA5'];
$wynik[28]=$w['CENA_S5'];
$wynik[29]=$w['CENA_B5'];
$wynik[30]=$w['JM'];
$wynik[31]=$w['SWW'];
$wynik[32]=$w['DOSTAWCA'];
$wynik[33]=$w['PRODUCENT'];
$wynik[34]=$w['KATEGORIA'];
$wynik[35]=$w['STAN'];
$wynik[36]=$w['STAN_MIN'];
$wynik[37]=$w['STATUS'];
$wynik[38]=$w['MASA'];
$wynik[39]=$w['W_PACZCE'];
$wynik[40]=$w['RABAT'];
$wynik[41]=$w['KODPAS'];
$wynik[42]=$w['UWAGI'];

//   $cenaost='';
//   if ($ktoreceny=='CENA_S') {
//   	if ($w=mysql_query("select CENA from magazyny where ID_T=$id_t limit 1")) {
//         $w=mysql_fetch_row($w);
//   	   $cenaost=$w[0];
//   	}
//   }
//$wynik[38]=($cenaost?$cenaost:$w["$ktoreceny"."$cennik"]);


if ($wgcz) {
	$cennik='';
	$ktoreceny='CENA_Z';
	if ($odcen=='Netto') {
		$cena=$w["$ktoreceny"."$cennik"];
	} else {
		require_once('funkcje.php');
		$cena=myRound($w["$ktoreceny"."$cennik"]*(100+1*$w['VAT'])*0.01);
	}
} else {
	$cennik=trim((($cennik<2)?'':$cennik));
	if ($odcen=='Netto') {
		$ktoreceny='CENA_S';
	} else {
		$ktoreceny='CENA_B';
	}
	$cena=$w["$ktoreceny"."$cennik"];
}

$wynik[44]=$cena;
$wynik[45]=$rabat;
$wynik[46]=0;           //ilo¶æ

$wynik[48]='';           //paczek
$wynik[49]='';           //po sztuk
$wynik[50]='';           //w cenie za paczk©

$posx=47;
	
//------------------------------------------------------------------

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo '$natabb="'.$towarywyb.'";'; echo "\n";		// l¹dowanie po Esc w formularzu
echo '-->'; echo "\n";
echo '</script>'; echo "\n";

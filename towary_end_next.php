<?php

require('funkcje.php');

$all=false;	//wszystko liczy† ? NIE
if ($ipole<0) {
	$ipole=-$ipole;		//przywr¢† prawidˆowy znak $ipole
	$all=true;		//wszystko liczy† ? TAK
}

if (!$ipole) {

   $z="Select ID from tabele where NAZWA='$natab'";
   $w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];
   
   $z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID dokum
   $w=mysql_query($z); $w=mysql_fetch_row($w); $ipole=$w[0];
}

$zz="select * from towary where ID=$ipole";
$ww=mysql_query($zz);
$ww=mysql_fetch_array($ww);

if (substr($ww['KATEGORIA'],0,1)=='_') {
   $ww['KATEGORIA']=substr($ww['KATEGORIA'],1);
}

$ww['MARZA' ]=myRound(($ww['CENA_Z']<>0&&$ww['CENA_S' ]<>0)?100*($ww['CENA_S' ]-$ww['CENA_Z'])/$ww['CENA_Z']:$ww['MARZA' ]);
$ww['MARZA2']=myRound(($ww['CENA_Z']<>0&&$ww['CENA_S2']<>0)?100*($ww['CENA_S2']-$ww['CENA_Z'])/$ww['CENA_Z']:$ww['MARZA2']);
$ww['MARZA3']=myRound(($ww['CENA_Z']<>0&&$ww['CENA_S3']<>0)?100*($ww['CENA_S3']-$ww['CENA_Z'])/$ww['CENA_Z']:$ww['MARZA3']);
$ww['MARZA4']=myRound(($ww['CENA_Z']<>0&&$ww['CENA_S4']<>0)?100*($ww['CENA_S4']-$ww['CENA_Z'])/$ww['CENA_Z']:$ww['MARZA4']);
$ww['MARZA5']=myRound(($ww['CENA_Z']<>0&&$ww['CENA_S5']<>0)?100*($ww['CENA_S5']-$ww['CENA_Z'])/$ww['CENA_Z']:$ww['MARZA5']);

$ww['CENAZB']=$ww['CENA_Z']*(1+$ww['VAT']*0.01);
$ww['MARZA' ]=myRound(($ww['CENAZB']<>0&&$ww['CENA_B' ]<>0)?100*($ww['CENA_B' ]-$ww['CENAZB'])/$ww['CENAZB']:$ww['MARZA' ]);
$ww['MARZA2']=myRound(($ww['CENAZB']<>0&&$ww['CENA_B2']<>0)?100*($ww['CENA_B2']-$ww['CENAZB'])/$ww['CENAZB']:$ww['MARZA2']);
$ww['MARZA3']=myRound(($ww['CENAZB']<>0&&$ww['CENA_B3']<>0)?100*($ww['CENA_B3']-$ww['CENAZB'])/$ww['CENAZB']:$ww['MARZA3']);
$ww['MARZA4']=myRound(($ww['CENAZB']<>0&&$ww['CENA_B4']<>0)?100*($ww['CENA_B4']-$ww['CENAZB'])/$ww['CENAZB']:$ww['MARZA4']);
$ww['MARZA5']=myRound(($ww['CENAZB']<>0&&$ww['CENA_B5']<>0)?100*($ww['CENA_B5']-$ww['CENAZB'])/$ww['CENAZB']:$ww['MARZA5']);

if (strlen($ww['INDEKS'])<9) {

	$indeks=$ww['INDEKS'];

	if ((strlen($indeks)>4)&&(substr($indeks,4,1)<>'-')) {
	    $indeks=substr($indeks,0,4).'-'.substr($indeks,4,4);
	}
	
	if (strlen($indeks)<=4) {
		$indeks=substr($indeks.'0000',0,4).'-0000';
	} elseif (strlen($indeks)>4) {
		$indeks=substr($indeks.'0000',0,9);
	}
	
	$max=0;
	do {
		$max++;
		$indeks=substr($indeks,0,4).'-'.substr('0000'.(substr($indeks,5,4)*1+1),-4,4);
		$q=("select count(*) from towary where STATUS<>'S' and INDEKS='$indeks'");
		$w=mysql_query($q);
		$r=mysql_fetch_row($w);
	} while ($r[0]<>0||$max>9999);

	$ww['INDEKS']=$indeks;
}

if (trim($ww[NAZWA_INT])=='') {
  $ww[NAZWA_INT]=$ww[NAZWA];
}

mysql_query("
   update towary
      set MARZA= '".$ww['MARZA' ]."'
        , MARZA2='".$ww['MARZA2']."'
        , MARZA3='".$ww['MARZA3']."'
        , MARZA4='".$ww['MARZA4']."'
        , MARZA5='".$ww['MARZA5']."'
        , KATEGORIA='".$ww['KATEGORIA']."'
        , INDEKS='".$ww['INDEKS']."'
        , NAZWA_INT='".$ww['NAZWA_INT']."'
    where ID=$ipole
");

if ($indeks) {
	require('towary_szukaj.php');
}
?>
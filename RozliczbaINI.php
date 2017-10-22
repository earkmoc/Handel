<?php

session_start();

$ido=$_SESSION['osoba_id'];

$zz="Select IDABONENTA from specopl where ID_OSOBYUPR=$ido limit 1";
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);

$ida=$ww[0];

$zz="Select count(*) from specrozba where ID_OSOBYUPR=$ido";
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
if ($ww[0]==0) {
	$zz="insert into specrozba (ID_OSOBYUPR) values ($ido)";
	$ww=mysql_query($zz);
}
$zz="Select ID from specrozba where ID_OSOBYUPR=$ido";
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$ipole=$ww[0];

//$zz="Select sum(nadplaty.WYSWPL) from nadplaty where IDABONENTA=$ida";
$zz="Select NADPLATA, ZABLOK from abonenci where ID=$ida";
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);

if ($ww[1]=='T' && $punkt==1) {$mybgcolor="red";}	//abonent z Pó³nocy a punkt 1
if ($ww[1]!='T' && $punkt==2) {$mybgcolor="red";}	//abonent z Zagronik a punkt 2
$zna=$ww[0];
$wna=$zna;

$suma=0;
if ($zaznaczone) {
	$x=explode(',',$zaznaczone);
	for($i=0;$i<count($x);$i++) {
		$zz="Select ID, KWOTA, ZTYTULU, ZAMIESIAC, NRRATY, DODNIA from specopl where ID=$x[$i] limit 1";
		$ww=mysql_query($zz);
		$ww=mysql_fetch_row($ww);
		$suma+=$ww[1];
//sprawdzamy, czy wczeœniejsze pozycje o tym "ZTYTULU" s¹ zaznaczone
		if ($mybgcolor<>"red") {
			$zz="Select ID from specopl where ID_OSOBYUPR=$ido and ZTYTULU=".$ww[2]." and DODNIA<'".$ww[5]."' order by DODNIA, ID";
//			$zz="Select ID from specopl where ID_OSOBYUPR=$ido and ZTYTULU=".$ww[2]." and (ZAMIESIAC<'".$ww[3]."' or NRRATY<'".$ww[4]."') order by ZAMIESIAC";
			$ww=mysql_query($zz);
			while ($ids=mysql_fetch_row($ww)) {
				$ids=$ids[0];
				$ok=false;
				for($j=0;$j<count($x);$j++) {
					if ($x[$j]==$ids) {
						$ok=true;
					}
				}
				if (!$ok) {$mybgcolor="red";}
			}
		}
	}
}
$kw=$suma-$zna;
if ($suma==0) {	// nic nie zaznaczone
	$zna=0;		// nie bie¿emy z nadp³at
	$kw=0;		// bêdzie jakaœ nadp³ata
}
//if ($kw<0) {	// w nadp³atach jest wiêcej ni¿ wybrane op³aty
//	$zna+=$kw;	// z nadp³at bie¿emy mniej
//	$kw=0;		// gotówki raczej nie bêdzie
//}
if ($suma>0) {	// normalnie
	$zna=0;		// z nadp³at nie bie¿emy bo to bank
	$kw=$suma;	// wiêc kwota bêdzie taka jak suma wybranych
}

if ($punkt==1) {
	$zz="Select NAZWABANKU, ID from banki where ID=1 limit 1";
}
else {
	$zz="Select NAZWABANKU, ID from banki where ID=2 limit 1";
}
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);

$ba=$ww[0];
$bn=$ww[1];

$zz="Select NUMER, DATA from typydok where LITERA='$bn' limit 1";
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);

$nz=$ww[0];
$dt=$ww[1];

$tak="T";
$nie="N";

if ($mybgcolor=="red") {$suma=0; $wna=0; $zna=0; $kw=0; $dt=''; $tak=''; $nie=''; $ba=''; $nz='';}

$zz='update `specrozba` set ';
$zz.="`IDABONENTA`='$ida',";
$zz.="`TYPDOK`='',";
$zz.="`SUMA`='$suma',";
$zz.="`W_NADPLATY`='$wna',";
$zz.="`Z_NADPLATY`='$zna',";
$zz.="`KWOTA`='$kw',";
$zz.="`DATAPRZYJ`='$dt',";
$zz.="`DRUK_KP`='$nie',";
$zz.="`GENE_FV`='$nie',";
$zz.="`DRUK_FV`='$nie',";
$zz.="`BANK`='$ba',";
$zz.="`NRZBIOR`='$nz'";
$zz.=" where `ID`='$ipole' limit 1";
$ww=mysql_query($zz);

$zz="Select ID from tabele where NAZWA='specrozba' limit 1"; $ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$zz="Select ID from tabeles where ID_TABELE=$ww[0] and ID_OSOBY=$ido limit 1"; $ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$zz="update tabeles set ID_POZYCJI=$ipole where ID=$ww[0] limit 1"; $ww=mysql_query($zz);

$iipole=$ipole;	// ustaw formularz na wy¿ej ustalonym rekordzie
//$posx=5;				// ustaw kursor w 4 polu formularza
?>
<?php

//automatyczne ksiêgowanie na podstawie schematu ksiêgowania

$ntab_master=$_SESSION['ntab_mast'];

$w=mysql_query("select ID from tabele where NAZWA='$ntab_master'"); $w=mysql_fetch_row($w); $w=$w[0];
$w=mysql_query("select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$osoba_id limit 1"); $w=mysql_fetch_row($w); 
$idd=$w[0];

$w=mysql_query("select PRZEDMIOT, SCHEMAT, WARTOSC, NETTOVAT, PODATEK_VAT, NRKONT, TYP, CLO_KWOTA, NETTOPD, NETTOVATT, NETTOVATM from dokumenty where ID=$idd"); $w=mysql_fetch_row($w); 
$prz=$w[0]; 
$sch=$w[1]; 
$bru=$w[2]; 
$net=$w[3]; 
$vat=$w[4]; 
$nrk=$w[5]; 
if     ($nrk*1>9999)	{$nrk=substr('00000'.$nrk,-5);} 
elseif ($nrk*1>999)	{$nrk=substr('0000'.$nrk,-4);} 
elseif ($nrk*1>99)	{$nrk=substr('000'.$nrk,-3);} 
else {			 $nrk=substr('0'.$nrk,-2);
}
$dtyp=$w[6]; 
$clo=$w[7];
$netPD=$w[8];
$netT=$w[9];
$netM=$w[10];

if ($sch=='') {$sch=strtolower(trim($dtyp));}

$w=mysql_query("select ID from schematy where TYP='$sch'"); $w=mysql_fetch_row($w); $idsch=$w[0];

if ($sch=='rk') {
	$w=mysql_query("select * from dokumentbk where ID_D=$idd");
	while ($r=mysql_fetch_array($w)) {
		$prz=$r['PRZEDMIOT']; $przych=$r['PRZYCHOD']; $rozch=$r['ROZCHOD']; $przeciw=$r['KONTOP']; $konto=$r['KONTOBK'];
		mysql_query("insert into dokumentk select 0, $idd, $osoba_id, Now(), if(OPIS='przedmiot','$prz',OPIS), if(KWOTAS='przychód',$przych,if(KWOTAS='rozchód',$rozch,KWOTAS*1)), 0, if(KONTOWN='konto','$konto',if(KONTOWN='przeciwstawne','$przeciw','KONTOWN')), if(KONTOMA='konto','$konto',if(KONTOMA='przeciwstawne','$przeciw','KONTOMA')) from schematys where schematys.ID_D=$idsch");
	}
} else {
	$ww=mysql_query("select ID, KWOTAS from schematys where schematys.ID_D=$idsch");
	while ($r=mysql_fetch_row($ww)) {
		$w=$r[1];
		$w=str_replace('brutto',$bru,$w);
		$w=str_replace('nettoPD',$netPD,$w);
		$w=str_replace('nettoT',$netT,$w);
		$w=str_replace('nettoM',$netM,$w);
		$w=str_replace('netto',$net,$w);
		$w=str_replace('vat',$vat,$w);
		$w=str_replace('clo',$clo,$w);
		$w=mysql_query("insert into dokumentk select 0, $idd, $osoba_id, Now(), if(OPIS='przedmiot','$prz',OPIS), $w, 0, replace(KONTOWN,'x','$nrk'), replace(KONTOMA,'x','$nrk') from schematys where schematys.ID_D=$idsch and schematys.ID=".$r[0]);
	}
}
$w=mysql_query("delete from dokumentk where WINIEN=0 and ID_D=$idd");

if (false) {
$w=mysql_query("select ID, KURSC, PRZEDMIOT, SCHEMAT from dokumenty where ID=$w"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select $w, sum(KWOTA)*[1], '[2]' from dokumentm where ID_D=$w and TYP='W'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("insert into dokumentk SET ID_D=$w, CZAS=Now(), KTO=$osoba_id, PRZEDMIOT='[2]', WINIEN='[1]', KONTOWN='203-27', MA='[1]', KONTOMA='700-03'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select ID_D from dokumentk where ID=id_inserted"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select ID, KURSC, KURSP, PRZEDMIOT from dokumenty where ID=$w"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select $w, sum(KWOTA)*[2]-sum(KWOTA)*[1], '[3]' from dokumentm where ID_D=$w and TYP='W'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("insert into dokumentk SET ID_D=$w, CZAS=Now(), KTO=$osoba_id, PRZEDMIOT='[2]', WINIEN='[1]', KONTOWN='203-27', MA='[1]', KONTOMA='700-05'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select ID_D from dokumentk where ID=id_inserted"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select ID, KURSC, KURSP, PRZEDMIOT from dokumenty where ID=$w"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select $w, sum(KWOTA)*[1], '[3]' from dokumentm where ID_D=$w and TYP='T'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("insert into dokumentk SET ID_D=$w, CZAS=Now(), KTO=$osoba_id, PRZEDMIOT='[2]', WINIEN='[1]', KONTOWN='203-27', MA='[1]', KONTOMA='730-03'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select ID_D from dokumentk where ID=id_inserted"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select ID, KURSC, KURSP, PRZEDMIOT from dokumenty where ID=$w"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select $w, sum(KWOTA)*[2]-sum(KWOTA)*[1], '[3]' from dokumentm where ID_D=$w and TYP='T'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("insert into dokumentk SET ID_D=$w, CZAS=Now(), KTO=$osoba_id, PRZEDMIOT='[2]', WINIEN='[1]', KONTOWN='203-27', MA='[1]', KONTOMA='730-05'"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("select ID_D from dokumentk where ID=id_inserted"); $w=mysql_fetch_row($w); $w=$w[0]; 
$w=mysql_query("delete from dokumentk where MA=0 and WINIEN=0"); $w=mysql_fetch_row($w); $w=$w[0];
}
?>
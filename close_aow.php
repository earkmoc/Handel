<?php

//require('skladuj_zmienne.php');

$ipole=($ipole<0)?(-$ipole):$ipole;
$idt=$_POST['idtab'];

//********************************************************************
// zapamiêtaj stan tabeli dla zalogowanej osoby
$w=mysql_query("select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido"); $w=mysql_fetch_row($w);
if ($w[0]>0) 	{$w=mysql_query(     "update tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c where ID_TABELE=$idt and ID_OSOBY=$ido limit 1");}
else 		{$w=mysql_query("Insert into tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c,ID_TABELE=$idt,ID_OSOBY=$ido");}
// zapamiêtaj stan tabeli dla zalogowanej osoby
//********************************************************************

$z="select BLOKADA, TYP, TYP_F, NABYWCA, MAGAZYN, INDEKS from dokum where ID=$ipole limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$blokada=$w[0];
$typ=$w[1];	//FV

$tyf=$w[2];	//P	//N
$nabb=$w[3];	//535
$mag=$w[4];	//0	//1

$nrdok=strtoupper(trim($w[5]));	//auto lub 1539/2006

$z="select TYP, ID from firmy where INDEKS='LITERA WD' limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$typfirmy=$w[0];
$tyf=$typfirmy;
$nab=$w[1];	//magazyn "W drodze"

$z="select MAGAZYNG, MAGAZYNP from doktypy where TYP='$typ' limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$mg=$w[0];	//2
$mp=$w[1];	//2

//wp³yw na stan MZ (syntetyka towarów)

if ($mg==0&&$mp==0) {$mnoznik=0;}		//nie wp³ywa, np. KOR
elseif ($mg==1&&$mp==2) {$mnoznik=0;}		//nie wp³ywa, np. MM, PWC, RWC
elseif ($mg==2&&$mp==1) {$mnoznik=0;}		//nie wp³ywa, np. MM, PWC, RWC
elseif ($mg==1) {$mnoznik=1;}			//przychód
elseif ($mg==2) {$mnoznik=-1;}			//rozchód
else {$mnoznik=0;}

//wp³yw na stan MZ (analityka towarów, firmy.ID=1)

$mnZ=$mnoznik;

//wp³yw na stan MG nr $mag (analityka towarów, firmy.ID=2, dokum.MAGAZYN=1 lub 0)

if ($mg==0) {$mnG=0;}			//nie wp³ywa, np. KOR
elseif ($mg==1) {$mnG=1;}		//przychód
elseif ($mg==2) {$mnG=-1;}		//rozchód
else {$mnG=0;}

//wp³yw na stan MP nr $nab (analityka towarów, firmy.ID=dokum.NABYWCA)

if ($mp==0) {$mnP=0;}			//nie wp³ywa, np. KOR
elseif ($mp==1) {$mnP=1;}		//przychód
elseif ($mp==2) {$mnP=-1;}		//rozchód
else {$mnP=0;}

//jeœli na dokumencie jest inny magazyn ni¿ zbiorczy i jest to faktura lub korekta i kontrahent na dokumencie jest 'Podmagazyn'
if (($mag<>1)&&(($typ=='FV')||($typ=='FVK'))&&(strtoupper($tyf)=='P')) {
	$mag=1;				//to musi to byæ z magazynu zbiorczego, nawet jeœli na dokumencie jest inaczej
	$z="update dokum set MAGAZYN=$mag where ID=$ipole limit 1";$w=mysql_query($z);
}
//jeœli kontrahent na dokumencie jest 'P' i jest to inwentaryzacja
if ((strtoupper($tyf)=='P')&&(($typ=='IN')||($typ=='INW'))) {$mag=$nab;}//to musi to byæ na podmagazynie, nawet jeœli na dokumencie jest inaczej

if (strtoupper($tyf)<>'P') 	{$mnP=0;}	//kontrahent na dokumencie nie jest 'P', to nie ma stanów i ruchu
if (strtoupper($typfirmy)<>'P') {$mnP=0;}	//kontrahent w kartotece nie jest 'P', to nie ma stanów i ruchu
if ($mag==1)	 		{$mnZ=0;}	//magazyn jest zbiorczy, to nie powtarzaj ruchu na MZ
if ($nab==1)	 		{$mnP=0;}	//nabywc¹ jest zbiorczy, to nie powtarzaj ruchu na MZ
if ($nab==$mag) 		{$mnP=0;}	//nabywc¹ jest magazyn, to nie powtarzaj ruchu na MP

if ($blokada=='O') {	//otwarty, wiêc ZAMYKAMY

	$z="update dokum set BLOKADA='.', CZAS=CurTime() where ID=$ipole limit 1";$w=mysql_query($z);	//w toku, ¿eby nikt nie ruszy³

	if     ($mnP==-1) {$mn=-1;$ma=$nab;}	//mirror z rozchodu na podstawie stanu podmagazynu
	elseif ($mnG==-1) {$mn=-1;$ma=$mag;}	//mirror z rozchodu na podstawie stanu magazynu g³ównego
	elseif ($mnZ==-1) {$mn=-1;$ma=1;}	//mirror z rozchodu na podstawie stanu magazynu zbiorczego
	else		  {$mn=1;}		//mirror z przychodu lub inwentaryzacji

	$z="delete from spec where ID_D=-$ipole";$w=mysql_query($z);	//mirror sio

	if ($mn==1) {$z="insert into spec select 0, -spe.ID_D, spe.ID_T, spe.CENA, spe.ILOSC, 0, 0,  0,0,0,0,'' from spec as spe where spe.ID_D=$ipole and spe.ILOSC<>0";$w=mysql_query($z);}
	else {
		$z="insert into spec select 0, -spe.ID_D, spe.ID_T, magazyny.CENA_Z, magazyny.ILOSC, 0, 0,  0,0,0,0,'' from spec as spe left join magazyny on (magazyny.ID_X=$ma and magazyny.ID_T=spe.ID_T) where spe.ID_D=$ipole and spe.ILOSC>=0 order by magazyny.DATA_Z, magazyny.ILOSC";$w=mysql_query($z);
		$z="select * from spec where ID_D=$ipole or ID_D=-$ipole order by ID_T, ID";$w=mysql_query($z);	//mirror do analizy
		$ids=-1;	//nawet nie zero
		$ile=0;
		while ($r=mysql_fetch_array($w)) {
			if (($r[ID_D]>0)&&($ids<>0)) {
				if (($ile<>0)&&($ids<>0)) {		//zaczyna nowego, a stary nieukoñczony !!!
					$zz="update spec set ILOSC=ILOSC+$ile where ID=$ids";$ww=mysql_query($zz);
				}
				$ile=$r[ILOSC];				//do rozchodu, np. 1600
				$ids=0;
			}
			elseif (($r[ID_D]>0)&&($ids==0)) {		//kolejny nowy, wiêc to pewnie korekta w stylu By³o/Jest
				$ile+=$r[ILOSC];			//jeszcze do rozchodu
			}
			elseif ($r[ILOSC]<0)	{			//minus, np.: -100
				$zz="update spec set ILOSC=".($r[ILOSC])." where ID=".$r[ID];$ww=mysql_query($zz);	//-100=0
				$ile-=$r[ILOSC];			//100-(-100)=200
				$ids=$r[ID];
			}
			elseif (($r[ILOSC]<=$ile)&&($ile<>0))	{	//mirror OK, np.: 200-300=>200 bo tyle jest
				$ile-=$r[ILOSC];
				$ids=$r[ID];
			}
			elseif (($r[ILOSC]>$ile)&&($ile<>0)) 	{	//mirror do zmniejszenia, np. 500>100
				$zz="update spec set ILOSC=$ile where ID=".$r[ID];$ww=mysql_query($zz);
				$ile=0;
				$ids=$r[ID];
			}
			elseif ($ile==0) { 				//mirror do skasowania
				$ids=$r[ID];
				$zz="update spec set ILOSC=0 where ID=$ids";$ww=mysql_query($zz);
			}
		}
		if (($ile<>0)&&($ids<>0)) {				//koniec specyfikacji, a ostatni nieukoñczony !!!
			$zz="update spec set ILOSC=ILOSC+$ile where ID=$ids";$ww=mysql_query($zz);
		}
		$zz="delete from spec where ILOSC=0 and ID_D=-$ipole";$ww=mysql_query($zz);
	}

//stany zbiorcze

	if ($mnoznik==3) 	{$z="update towary right join spec on spec.ID_T=towary.ID SET towary.STAN=spec.ILOSC where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);}
	elseif ($mnoznik<>0) 	{$z="update towary right join spec on spec.ID_T=towary.ID SET towary.STAN=(towary.STAN+($mnoznik*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);}

//stany indywidualne

	if ($mnZ<>0) {//miejsce w analityce dla nowych danych	//zmiana stanu w MZ=firmy.ID=1
		$z="insert into magazyny select 0, 1, ID_T, 0, CENA, Now(), 0 from spec where spec.ID_D=-$ipole and spec.ILOSC<>0 on duplicate key update ID=ID";$w=mysql_query($z);
		$z="update magazyny right join spec on (spec.ID_T=magazyny.ID_T and 1=magazyny.ID_X and spec.CENA=magazyny.CENA_Z) SET magazyny.ILOSC=(magazyny.ILOSC+($mnZ*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
	}
	if ($mnG<>0) {//miejsce w analityce dla nowych danych	//zmiana stanu w MG=$mag
		$z="insert into magazyny select 0, $mag, ID_T, 0, CENA, Now(), 0 from spec where spec.ID_D=-$ipole and spec.ILOSC<>0 on duplicate key update ID=ID";$w=mysql_query($z);
		$z="update magazyny right join spec on (spec.ID_T=magazyny.ID_T and $mag=magazyny.ID_X and spec.CENA=magazyny.CENA_Z) SET magazyny.ILOSC=(magazyny.ILOSC+($mnG*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
		$z="update towary right join spec on spec.ID_T=towary.ID SET towary.STAN_MG=(towary.STAN_MG+($mnG*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
	}
	if ($mnP<>0) {//miejsce w analityce dla nowych danych	//zmiana stanu w MP=$nab
		$z="insert into magazyny select 0, $nab, ID_T, 0, CENA, Now(), 0 from spec where spec.ID_D=-$ipole and spec.ILOSC<>0 on duplicate key update ID=ID";$w=mysql_query($z);
		$z="update magazyny right join spec on (spec.ID_T=magazyny.ID_T and $nab=magazyny.ID_X and spec.CENA=magazyny.CENA_Z) SET magazyny.ILOSC=(magazyny.ILOSC+($mnP*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
	}

//numer

   if ((substr($nrdok,-4,4)=='AUTO')||(substr($nrdok,-7,7)=='AUTOMAT')) {
		$z="select NUMER, MASKA from doktypy where TYP='$typ' limit 1";$w=mysql_query($z); $w=mysql_fetch_row($w);
		$nrdok=$w[0];
		$maska=$w[1];
		$z=1;
		while ($z>0) {
			$nrdok=$nrdok+1;
			$z="select count(*) from dokum where TYP='$typ' and INDEKS='$nrdok$maska'";
			$z=mysql_query($z);
			$z=mysql_fetch_row($z);
			$z=$z[0];
		}
		$z="update doktypy set NUMER=$nrdok where TYP='$typ' limit 1";$w=mysql_query($z);
		$z="update dokum set BLOKADA='W', CZAS=CurTime(), INDEKS='$nrdok$maska' where ID=$ipole limit 1";$w=mysql_query($z);
	}
	else {
		$z="update dokum set BLOKADA='W', CZAS=CurTime() where ID=$ipole limit 1";$w=mysql_query($z);	//finito
	}

//	$komunikat='Dokument zamkniêty';
}
elseif ($blokada=='W') {	//zamkniêty, wiêc OTWIERAMY

	$z="update dokum set BLOKADA=':', CZAS=CurTime() where ID=$ipole limit 1";$w=mysql_query($z);	//w toku, ¿eby nikt nie ruszy³

	if ($mnoznik==3) {;}//	$z="update towary right join spec on spec.ID_T=towary.ID SET towary.STAN=spec.ILOSC where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
	elseif ($mnoznik<>0) {	$z="update towary right join spec on spec.ID_T=towary.ID SET towary.STAN=(towary.STAN-($mnoznik*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);}

	$mnZ=(-$mnZ);
	if ($mnZ<>0) {//miejsce w analityce dla nowych danych	//zmiana stanu w MZ=firmy.ID=1
		$z="insert into magazyny select 0, 1, ID_T, 0, CENA, Now(), 0 from spec where spec.ID_D=-$ipole and spec.ILOSC<>0 on duplicate key update ID=ID";$w=mysql_query($z);
		$z="update magazyny right join spec on (spec.ID_T=magazyny.ID_T and 1=magazyny.ID_X and spec.CENA=magazyny.CENA_Z) SET magazyny.ILOSC=(magazyny.ILOSC+($mnZ*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
	}
	$mnG=(-$mnG);
	if ($mnG<>0) {//miejsce w analityce dla nowych danych	//zmiana stanu w MG=$mag
		$z="insert into magazyny select 0, $mag, ID_T, 0, CENA, Now(), 0 from spec where spec.ID_D=-$ipole and spec.ILOSC<>0 on duplicate key update ID=ID";$w=mysql_query($z);
		$z="update magazyny right join spec on (spec.ID_T=magazyny.ID_T and $mag=magazyny.ID_X and spec.CENA=magazyny.CENA_Z) SET magazyny.ILOSC=(magazyny.ILOSC+($mnG*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
		$z="update towary right join spec on spec.ID_T=towary.ID SET towary.STAN_MG=(towary.STAN_MG+($mnG*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
	}
	$mnP=(-$mnP);
	if ($mnP<>0) {//miejsce w analityce dla nowych danych	//zmiana stanu w MP=$nab
		$z="insert into magazyny select 0, $nab, ID_T, 0, CENA, Now(), 0 from spec where spec.ID_D=-$ipole and spec.ILOSC<>0 on duplicate key update ID=ID";$w=mysql_query($z);
		$z="update magazyny right join spec on (spec.ID_T=magazyny.ID_T and $nab=magazyny.ID_X and spec.CENA=magazyny.CENA_Z) SET magazyny.ILOSC=(magazyny.ILOSC+($mnP*spec.ILOSC)) where spec.ID_D=-$ipole and spec.ILOSC<>0";$w=mysql_query($z);
	}
	$z="delete from spec where ID_D=-$ipole";$w=mysql_query($z);					//specyfikacja mirror sio
	$z="update dokum set BLOKADA='O', CZAS=CurTime() where ID=$ipole limit 1";$w=mysql_query($z);	//finito
//	$komunikat='Dokument otwarty';
}

//nale¿noœci i korekty u kontrahenta

	$zz="select sum(WARTOSC-WPLACONO) from dokum where NABYWCA=$nabb";
	$zz.=' and WARTOSC<>WPLACONO and TYP="FV" and BLOKADA=""';
	$ww=mysql_query($zz);
	$ww=mysql_fetch_row($ww);
	$ww=$ww[0];

	if (!$ww) {$ww=0;}

	$naleznosci=$ww;

	$zz="select sum(WARTOSC-WPLACONO) from dokum where NABYWCA=$nabb";
	$zz.=' and WARTOSC<>WPLACONO and TYP="FVK" and BLOKADA=""';
	$ww=mysql_query($zz);
	$ww=mysql_fetch_row($ww);
	$ww=$ww[0];

	if (!$ww) {$ww=0;}

	$zz="update firmy SET NALEZNOSCI=$naleznosci, KOREKTY=-$ww where ID=$nabb limit 1";
	$ww=mysql_query($zz);
?>

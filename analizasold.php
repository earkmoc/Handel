<?php

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analizasp'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$w=mysql_query("select * from analizasp where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analizasb");
mysql_query("truncate analizasc");	//daty ostatnich inwentaryzacji poszczególnych towarów

if ($w['ID_FIRMY']<3) {			//MZ lub MG

//w Literaturze widaæ mirrory dopiero od daty 2002-08-23

if ($w['CZY_INW']=='T') {
$ww=mysql_query("select * from doktypy where MAGAZYNP=3");	//inwentaryzacje
while ($r=mysql_fetch_array($ww)) {
	$t=$r['TYP'];
if (($w['DATA1']<'2002-08-23')&&($w['CZY_RCE']=='T')) {
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, dokum.NABYWCA, spec.CENA, 0 from spec left join dokum on ";
	$z.="(dokum.ID=spec.ID_D)";	//nie ma mirrora
	$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '2002-08-22')";
	$z.=" and (dokum.NABYWCA=1 or dokum.NABYWCA=2)";	//.$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
	mysql_query($z);
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, dokum.NABYWCA, spec.CENA, 0 from spec left join dokum on ";
	$z.="(dokum.ID=-spec.ID_D)";	//z mirrora
	$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '2002-08-23' and '".($w['DATA2'])."') and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
	$z.=" order by spec.ID";	// chronologicznie
	mysql_query($z);
}
else {
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, dokum.NABYWCA, spec.CENA, 0 from spec left join dokum on ";
	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
	else			{$z.="(dokum.ID=spec.ID_D)";}
	$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
	$z.=" and (dokum.NABYWCA=1 or dokum.NABYWCA=2)";	//.$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
	$z.=" order by spec.ID";	// chronologicznie
	mysql_query($z);
}
}
mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb order by DATAK desc, ID desc");
mysql_query("truncate analizasb");
mysql_query("insert into analizasb select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasc group by ID_T");
mysql_query("truncate analizasc");
mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb");
}

$ww=mysql_query("select * from doktypy where MAGAZYNG=1");	//przychody
while ($r=mysql_fetch_array($ww)) {
	$t=$r['TYP'];
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
//	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
//	else			{
		$z.="(dokum.ID=spec.ID_D)";
//	}
	$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
	$z.=" and dokum.MAGAZYN=2";
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
//	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s¹ prywatnie i nie wp³ywaj¹ na stan
	mysql_query($z);
//echo $z."\n";
}
//exit;

$ww=mysql_query("select * from doktypy where MAGAZYNG=2");	//rozchody
while ($r=mysql_fetch_array($ww)) {
	$t=$r['TYP'];
if (($w['DATA1']<'2002-08-23')&&($w['CZY_RCE']=='T')) {		//nie ma mirrora
	$z="insert into analizasb select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
	$z.="(dokum.ID=spec.ID_D)";
	$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '2002-08-22')";
	$z.=" and dokum.MAGAZYN=2";
	$z.=" and dokum.TYP='$t'";
//	$z.=" or (left('$t',2)='FV' and dokum.TYP_F<>'P' and dokum.TYP_F<>'p'))"; // FV N'ki s¹ prywatnie i wp³ywaj¹ na stan MG
	mysql_query($z);

	$z="insert into analizasb select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
	else			{$z.="(dokum.ID=spec.ID_D)";}
	$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '2002-08-23' and '".($w['DATA2'])."')";
	$z.=" and dokum.MAGAZYN=2";
	$z.=" and dokum.TYP='$t'";
//	$z.=" or (left('$t',2)='FV' and dokum.TYP_F<>'P' and dokum.TYP_F<>'p'))"; // FV N'ki s¹ prywatnie i wp³ywaj¹ na stan MG
	mysql_query($z);
}
else {
	$z="insert into analizasb select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
	else			{$z.="(dokum.ID=spec.ID_D)";}
	$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
	$z.=" and dokum.MAGAZYN=2";
	$z.=" and dokum.TYP='$t'";
//	$z.=" or (left('$t',2)='FV' and dokum.TYP_F<>'P' and dokum.TYP_F<>'p'))"; // FV N'ki s¹ prywatnie i wp³ywaj¹ na stan MG
	mysql_query($z);

}	//if (($w['DATA1']<'2002-08-23')&&($w['CZY_RCE']=='T')) {		//nie ma mirrora
}	//while ($r=mysql_fetch_array($ww)) {
}	//if ($w['ID_FIRMY']<3) {		//MZ lub MG

else {

//w Literaturze widaæ mirrory dopiero od daty 2002-08-23

if ($w['CZY_INW']=='T') {
$ww=mysql_query("select * from doktypy where MAGAZYNP=3");	//inwentaryzacje
while ($r=mysql_fetch_array($ww)) {
	$t=$r['TYP'];
if (($w['DATA1']<'2002-08-23')&&($w['CZY_RCE']=='T')) {
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, dokum.NABYWCA, spec.CENA, 0 from spec left join dokum on ";
	$z.="(dokum.ID=spec.ID_D)";	//nie ma mirrora
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '2002-08-22')";
	$z.=" and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
	mysql_query($z);
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, dokum.NABYWCA, spec.CENA, 0 from spec left join dokum on ";
	$z.="(dokum.ID=-spec.ID_D)";	//z mirrora
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '2002-08-23' and '".($w['DATA2'])."') and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
	$z.=" order by spec.ID";	// chronologicznie
	mysql_query($z);
}
else {
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, dokum.NABYWCA, spec.CENA, 0 from spec left join dokum on ";
	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
	else			{$z.="(dokum.ID=spec.ID_D)";}
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
	$z.=" and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
	$z.=" order by spec.ID";	// chronologicznie
	mysql_query($z);
}
}
mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb order by DATAK desc, ID desc");
mysql_query("truncate analizasb");
mysql_query("insert into analizasb select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasc group by ID_T");
mysql_query("truncate analizasc");
mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb");
}

$ww=mysql_query("select * from doktypy where MAGAZYNP=1");	//przychody
while ($r=mysql_fetch_array($ww)) {
	$t=$r['TYP'];
	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
//	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
//	else			{
		$z.="(dokum.ID=spec.ID_D)";
//	}
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s¹ prywatnie i nie wp³ywaj¹ na stan
	mysql_query($z);
}

$ww=mysql_query("select * from doktypy where MAGAZYNP=2");	//rozchody
while ($r=mysql_fetch_array($ww)) {
	$t=$r['TYP'];
if (($w['DATA1']<'2002-08-23')&&($w['CZY_RCE']=='T')) {		//nie ma mirrora
	$z="insert into analizasb select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
	$z.="(dokum.ID=spec.ID_D)";
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '2002-08-22') and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s¹ prywatnie i nie wp³ywaj¹ na stan
	mysql_query($z);

	$z="insert into analizasb select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
	else			{$z.="(dokum.ID=spec.ID_D)";}
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '2002-08-23' and '".($w['DATA2'])."') and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s¹ prywatnie i nie wp³ywaj¹ na stan
	mysql_query($z);
}
else {
	$z="insert into analizasb select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//ró¿ne ceny, wiêc z mirrora
	else			{$z.="(dokum.ID=spec.ID_D)";}
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.NABYWCA=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s¹ prywatnie i nie wp³ywaj¹ na stan
	mysql_query($z);
}	//if (($w['DATA1']<'2002-08-23')&&($w['CZY_RCE']=='T')) {		//nie ma mirrora
}	//while ($r=mysql_fetch_array($ww)) {
}	//else 	//if ($w['ID_FIRMY']<3) {		//MZ lub MG

mysql_query("update analizasb set STAN=0 where DATAP<DATAK");		//dokumenty sprzed inwentaryzacji sio
mysql_query("update analizasb set STAN=0 where DATAP=DATAK and SREDNIO<>1");	//inwentaryzacjê z tego dnia zostaw

mysql_query("truncate analizasc");	//w DATAP jest data pierwszego ruchu na towarze, tj. przychodu (?)
$z="insert into analizasc select 0, $ido, ID_T, sum(STAN), sum(SPRZEDAZ), sum(SREDNIO), sum(ILEDOK), min(DATAP), max(DATAK), ID_F, CENA_Z, 0 from analizasb group by ID_T";
if ($w['CZY_RCE']=='T') {$z.=", CENA_Z";}	//ró¿ne ceny
mysql_query($z);

if ($w['CZY_UTN']=='N') {
	mysql_query("update analizasc left join towary on towary.ID=analizasc.ID_T set ILEDOK=1 where isnull(towary.ID) or towary.STATUS='S'");
	mysql_query("delete from analizasc where ILEDOK=1");
}
if ($w['CZY_RCE']=='N') {		//nie(ró¿ne ceny) => ceny zakupu z "towary"
	mysql_query("update analizasc left join towary on towary.ID=analizasc.ID_T set analizasc.CENA_Z=towary.CENA_Z");
}
mysql_query("truncate magazynyb");
if ($w['CZY_RCE']=='T') {		//ró¿ne ceny
	mysql_query("insert into magazynyb select 0, ID_X, ID_T, sum(ILOSC), max(CENA_Z), max(DATA_Z) from magazyny group by ID_X, ID_T, CENA_Z having magazyny.ID_X=".($w['ID_FIRMY']));
	mysql_query("update analizasc left join magazynyb on magazynyb.ID_T=analizasc.ID_T and magazynyb.CENA_Z=analizasc.CENA_Z SET analizasc.STANJEST=magazynyb.ILOSC");
}
else {
	mysql_query("insert into magazynyb select 0, ID_X, ID_T, sum(ILOSC), max(CENA_Z), max(DATA_Z) from magazyny group by ID_X, ID_T having magazyny.ID_X=".($w['ID_FIRMY']));
	mysql_query("update analizasc left join magazynyb on magazynyb.ID_T=analizasc.ID_T SET analizasc.STANJEST=magazynyb.ILOSC");
}
mysql_query("delete from analizas where ID_OSOBYUPR=$ido");
mysql_query("insert into analizas select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasc");

mysql_query("update analizasp SET CZAS=Now() where ID=$ipole");
mysql_query("update analizas SET ID_F=".$w['ID_FIRMY']);

$tabelaa='analizas';	// tu l¹duje po akcji

//echo $raport;
?>

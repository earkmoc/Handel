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
mysql_query("truncate analizasc");	//daty ostatnich inwentaryzacji poszczeg�lnych towar�w

//badamy magazyn zbiorczy
if ($w['ID_FIRMY']==1) { //<3||$w['ID_FIRMY']==3049) {			//MZ==1, MG==2 lub MW==3049 (w drodze)

	if ($w['CZY_INW']=='T') {
		$ww=mysql_query("select * from doktypy where MAGAZYNP=3");	//inwentaryzacje
		while ($r=mysql_fetch_array($ww)) {
			$t=$r['TYP'];
			$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, $w[ID_FIRMY], spec.CENA, 0 from spec left join dokum on ";
			if ($w['CZY_RCE']=='T') {
            $z.="(dokum.ID=-spec.ID_D)";  	//r�ne ceny, wi�c z mirrora
         } else {
            $z.="(dokum.ID=spec.ID_D)";
         }
			$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
			$z.=" and (dokum.NABYWCA=1)";	// or dokum.NABYWCA=2 or dokum.NABYWCA=3049     //.$w['ID_FIRMY'];
//				$z.=" and ".($w['ID_FIRMY']==3049?'dokum.NABYWCA=3049':'1=1');
			$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
			$z.=" order by spec.ID";	// chronologicznie
			mysql_query($z);
		}	//while ($r=mysql_fetch_array($ww))
		mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb order by DATAK desc, ID desc");
		mysql_query("truncate analizasb");
		mysql_query("insert into analizasb select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasc group by ID_T");
		mysql_query("truncate analizasc");
		mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb");
	}	//if ($w['CZY_INW']=='T')
	
	if (false) {	//$w['ID_FIRMY']==3049      w drodze
		$ww=mysql_query("select * from doktypy where MAGAZYNG=2");	//rozchody z MG to przychody do MW
		while ($r=mysql_fetch_array($ww)) {
			$t=$r['TYP'];
			$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
			$z.="(dokum.ID=spec.ID_D)";
			$z.=" where dokum.BLOKADA='W' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
			$z.=" and dokum.MAGAZYN=2";
			$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
			mysql_query($z);
		}
	} else {
		$ww=mysql_query("select * from doktypy where MAGAZYNG=1");	//przychody
		while ($r=mysql_fetch_array($ww)) {
			$t=$r['TYP'];
			$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, $w[ID_FIRMY], spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
		//	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//r�ne ceny, wi�c z mirrora
		//	else			{
				$z.="(dokum.ID=spec.ID_D)";
		//	}
			$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
//			$z.=" and dokum.MAGAZYN=2";
			$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
		//	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s� prywatnie i nie wp�ywaj� na stan
			mysql_query($z);
		//echo $z."\n";
		}
	}
	//exit;
	
	if (true) {	//$w['ID_FIRMY']<>3049    //nie w drodze
		$ww=mysql_query("select * from doktypy where MAGAZYNG=2");	//rozchody
		while ($r=mysql_fetch_array($ww)) {
			$t=$r['TYP'];
			$z="insert into analizasb select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
			if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//r�ne ceny, wi�c z mirrora
			else			{$z.="(dokum.ID=spec.ID_D)";}
			$z.=" where dokum.BLOKADA<>'O' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
			$z.=" and (dokum.MAGAZYN=2 or (dokum.MAGAZYN=1 and dokum.TYP_F='N'))";
			$z.=" and dokum.TYP='$t'";
			mysql_query($z);
		}	//while ($r=mysql_fetch_array($ww)) {
	}
}	//if ($w['ID_FIRMY']<3||$w['ID_FIRMY']==3049) {			//MZ==1, MG==2 lub MW==3049 (w drodze)

else {						//podmagazyny

if ($w['CZY_INW']=='T') {
	$ww=mysql_query("select * from doktypy where MAGAZYNP=3");	//inwentaryzacje
	while ($r=mysql_fetch_array($ww)) {
		$t=$r['TYP'];
		$z="insert into analizasb 
               select 0, $ido, spec.ID_T, spec.ILOSC, spec.ILOSC, 1, 0, dokum.DATAS, dokum.DATAS, $w[ID_FIRMY], spec.CENA, 0 
                 from spec 
            left join towary on towary.ID=spec.ID_T 
            left join dokum on ";
		if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//r�ne ceny, wi�c z mirrora
		else			{$z.="(dokum.ID=spec.ID_D)";}
		$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
		$z.=" and (dokum.NABYWCA=".$w[ID_FIRMY];
   		$z.=" or (dokum.NABYWCA=1 and towary.DOSTAWCA=$w[ID_FIRMY]))";
		$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0
		$z.=" order by spec.ID";	// chronologicznie
		mysql_query($z);
	}	//while ($r=mysql_fetch_array($ww))
	mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb order by DATAK desc, ID desc");
	mysql_query("truncate analizasb");
	mysql_query("insert into analizasb select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasc group by ID_T");
	mysql_query("truncate analizasc");
	mysql_query("insert into analizasc select 0, $ido, ID_T, STAN, SPRZEDAZ, SREDNIO, ILEDOK, DATAP, DATAK, ID_F, CENA_Z, STANJEST from analizasb");
}	//if ($w['CZY_INW']=='T')

$ww=mysql_query("select * from doktypy where MAGAZYNP=1");	//przychody
while ($r=mysql_fetch_array($ww)) {
	$z="insert into analizasb 
            select 0, $ido, spec.ID_T, spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, $w[ID_FIRMY], spec.CENA, 0 
              from spec 
         left join analizasc on (analizasc.ID_T=spec.ID_T) 
         left join dokum on (dokum.ID=spec.ID_D)
        where dokum.BLOKADA='' 
          and (dokum.DATAS between '$w[DATA1]' and '$w[DATA2]') 
          and dokum.NABYWCA=$w[ID_FIRMY]
	       and dokum.TYP='$r[TYP]'";
//	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s� prywatnie i nie wp�ywaj� na stan
	mysql_query($z);

//przych�d mo�e by� rozchodem

	$z="insert into analizasb 
            select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.MAGAZYN, spec.CENA, 0 
              from spec 
         left join analizasc on (spec.ID_T=analizasc.ID_T) 
         left join dokum on ";
	$z.="(dokum.ID=spec.ID_D)";
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.MAGAZYN=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
//	mysql_query($z);

}	//while ($r=mysql_fetch_array($ww))

$ww=mysql_query("select * from doktypy where MAGAZYNP=2");	//rozchody
while ($r=mysql_fetch_array($ww)) {
	$t=$r['TYP'];
	$z="insert into analizasb 
            select 0, $ido, spec.ID_T, -spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.NABYWCA, spec.CENA, 0 
              from spec 
         left join towary on (towary.ID=spec.ID_T) 
         left join analizasc on (spec.ID_T=analizasc.ID_T) 
         left join dokum on ";
	if ($w['CZY_RCE']=='T') {
      $z.="(dokum.ID=-spec.ID_D)";	//r�ne ceny, wi�c z mirrora
	} else {
      $z.="(dokum.ID=spec.ID_D)";
   }
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')";
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
	$z.=" and towary.DOSTAWCA=$w[ID_FIRMY]";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
//	$z.=" and (dokum.TYP_F='P' or dokum.TYP_F='p')";	// N'ki s� prywatnie i nie wp�ywaj� na stan
	mysql_query($z);

//rozch�d mo�e by� przychodem

	$z="insert into analizasb select 0, $ido, spec.ID_T, spec.ILOSC, 0, 0, 0, dokum.DATAS, analizasc.DATAK, dokum.MAGAZYN, spec.CENA, 0 from spec left join analizasc on (spec.ID_T=analizasc.ID_T) left join dokum on ";
	if ($w['CZY_RCE']=='T') {$z.="(dokum.ID=-spec.ID_D)";}	//r�ne ceny, wi�c z mirrora
	else			{$z.="(dokum.ID=spec.ID_D)";}
	$z.=" where dokum.BLOKADA='' and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.MAGAZYN=".$w['ID_FIRMY'];
	$z.=" and dokum.TYP='$t'";	// and spec.ILOSC<>0 and analizasc.DATAK<dokum.DATAS
//	mysql_query($z);

}	//while ($r=mysql_fetch_array($ww)) {
}	//else 	//if ($w['ID_FIRMY']<3) {		//MZ lub MG

mysql_query("update analizasb set STAN=0 where DATAP<DATAK");		//dokumenty sprzed inwentaryzacji sio
mysql_query("update analizasb set STAN=0 where DATAP=DATAK and SREDNIO<>1");	//inwentaryzacj� z tego dnia zostaw

mysql_query("truncate analizasc");	//w DATAP jest data pierwszego ruchu na towarze, tj. przychodu (?)
$z="insert into analizasc select 0, $ido, ID_T, sum(STAN), sum(SPRZEDAZ), sum(SREDNIO), sum(ILEDOK), min(DATAP), max(DATAK), ID_F, CENA_Z, 0 from analizasb group by ID_T";
if ($w['CZY_RCE']=='T') {$z.=", CENA_Z";}	//r�ne ceny
mysql_query($z);

if ($w['CZY_UTN']=='N') {
	mysql_query("update analizasc left join towary on towary.ID=analizasc.ID_T set ILEDOK=1 where isnull(towary.ID) or towary.STATUS='S'");
	mysql_query("delete from analizasc where ILEDOK=1");
}
if ($w['CZY_RCE']=='N') {		//nie(r�ne ceny) => ceny zakupu z "towary"
	mysql_query("update analizasc left join towary on towary.ID=analizasc.ID_T set analizasc.CENA_Z=towary.CENA_Z");
}
mysql_query("truncate magazynyb");
if ($w['CZY_RCE']=='T') {		//r�ne ceny
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
//mysql_query("update analizas SET ID_F=".$w['ID_FIRMY']);
mysql_query("update analizas left join towary on towary.ID=analizas.ID_T SET analizas.ID_F=towary.DOSTAWCA");

$tabelaa='analizas';	// tu l�duje po akcji

//echo $raport;
?>
<?php

//alter table analiza3 add STAN decimal(12,3) not null default '';

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analiza3p'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

//CREATE TABLE analiza3b (
//ID int(11) NOT NULL auto_increment,
//ID_OSOBYUPR  INT(11) not null default 0,
//ID_D int(11) not null default 0,
//PRZYCHOD decimal(12,3) not null default 0,
//ROZCHOD decimal(12,3) not null default 0,
//ILOSC_DOK decimal(12,3) not null default 0,
//CENA_DOK decimal(12,2) not null default 0,
//PRIMARY KEY (ID)
//) TYPE=MyISAM;
//GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON analiza3b TO 'guest' IDENTIFIED BY '123';

$w=mysql_query("select * from analiza3p where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analiza3b");
if ($w['ID_FIRMY']>2) {		//3,4...=> konkretnego kontrahenta
mysql_query("insert into analiza3b select 0, $ido, dokum.ID, if(dokum.BLOKADA='O' or dokum.TYP_F='N',0,spec.ILOSC),          0, spec.ILOSC, spec.CENA from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.NABYWCA=".($w['ID_FIRMY'])." and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");	// and dokum.BLOKADA=''
mysql_query("insert into analiza3b select 0, $ido, dokum.ID,          0, if(dokum.BLOKADA='O' or dokum.TYP_F='N',0,spec.ILOSC), spec.ILOSC, spec.CENA from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.NABYWCA=".($w['ID_FIRMY'])." and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKR'])."','.',','))>0");	// and dokum.BLOKADA=''
}
elseif ($w['ID_FIRMY']>0) { 	// 1, 2	=> z konkretnego magazynu
mysql_query("insert into analiza3b select 0, $ido, dokum.ID, if(dokum.BLOKADA='O' or dokum.TYP_F='N',0,spec.ILOSC),          0, spec.ILOSC, spec.CENA from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.MAGAZYN=".$w['ID_FIRMY']."   and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");	// and dokum.BLOKADA=''
mysql_query("insert into analiza3b select 0, $ido, dokum.ID,          0, if(dokum.BLOKADA='O' or dokum.TYP_F='N',0,spec.ILOSC), spec.ILOSC, spec.CENA from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and dokum.MAGAZYN=".$w['ID_FIRMY']."   and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKR'])."','.',','))>0");	// and dokum.BLOKADA=''
}
else {				// 0	=> wszystkie dokumenty
mysql_query("insert into analiza3b select 0, $ido, dokum.ID, if(dokum.BLOKADA='O' or dokum.TYP_F='N',0,spec.ILOSC),          0, spec.ILOSC, spec.CENA from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')                                        and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0");	// and dokum.BLOKADA=''
mysql_query("insert into analiza3b select 0, $ido, dokum.ID,          0, if(dokum.BLOKADA='O' or dokum.TYP_F='N',0,spec.ILOSC), spec.ILOSC, spec.CENA from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_T=".($w['ID_TOWARY'])." and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."')                                        and Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKR'])."','.',','))>0");	// and dokum.BLOKADA=''
}

mysql_query("delete from analiza3 where ID_OSOBYUPR=$ido");
//mysql_query("insert into analiza3 select 0, $ido, ID_D, sum(PRZYCHOD), sum(ROZCHOD), sum(ILOSC_DOK), CENA_DOK, 0, '' from analiza3b group by ID_D, CENA_DOK having sum(ILOSC_DOK)<>0");	//nie pokazuj kompensuj¹cych siê FVK
mysql_query("insert into analiza3 select 0, $ido, ID_D, sum(PRZYCHOD), sum(ROZCHOD), sum(ILOSC_DOK), CENA_DOK, 0, '' from analiza3b group by ID_D, CENA_DOK");

mysql_query("update      analiza3 left join dokum on dokum.ID=analiza3.ID_D set DATA=dokum.DATAS, PRZYCHOD=0, ROZCHOD=0, STAN=ILOSC_DOK where analiza3.ID_OSOBYUPR=$ido and dokum.TYP='INW'");
mysql_query("update      analiza3 left join dokum on dokum.ID=analiza3.ID_D                                          set STAN=-99999999 where analiza3.ID_OSOBYUPR=$ido and dokum.TYP='FVK' and analiza3.ILOSC_DOK=0");
mysql_query("delete from analiza3                                                                                                       where analiza3.ID_OSOBYUPR=$ido and analiza3.STAN=-99999999");
mysql_query("update      analiza3 left join dokum on dokum.ID=analiza3.ID_D set DATA=dokum.DATAS                       , STAN=-99999999 where analiza3.ID_OSOBYUPR=$ido and dokum.TYP<>'INW'");

$stan=0;
$ilosc=0;
$w=mysql_query("select ID, PRZYCHOD, ROZCHOD, STAN from analiza3 where analiza3.ID_OSOBYUPR=$ido order by DATA, ID");
while ($r=mysql_fetch_array($w)) {
	if ($r['STAN']<>-99999999) {
		$ilosc=$r['STAN']-$stan;
		$stan=$r['STAN'];
		mysql_query("update analiza3 set STAN=$stan, PRZYCHOD=if($ilosc>0,$ilosc,0), ROZCHOD=if($ilosc<0,-1*$ilosc,0) where ID=".$r['ID']);
	}
	else {
		$stan+=$r['PRZYCHOD']-$r['ROZCHOD'];
		mysql_query("update analiza3 set STAN=$stan where ID=".$r['ID']);
	}
}
mysql_query("update analiza3p SET CZAS=Now() where ID=$ipole");

$tabelaa='analiza3';	// tu l¹duje po akcji

//echo $raport;
?>
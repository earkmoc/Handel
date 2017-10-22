<?php

//Historia kontrahenta

//alter table analiza8 add STAN decimal(12,3) not null default '';

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analiza8p'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

//CREATE TABLE analiza8b (
//ID int(11) NOT NULL auto_increment,
//ID_OSOBYUPR  INT(11) not null default 0,
//ID_D int(11) not null default 0,
//PRZYCHOD decimal(12,3) not null default 0,
//ROZCHOD decimal(12,3) not null default 0,
//ILOSC_DOK decimal(12,3) not null default 0,
//CENA_DOK decimal(12,2) not null default 0,
//PRIMARY KEY (ID)
//) TYPE=MyISAM;
//GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON analiza8b TO 'guest' IDENTIFIED BY '123';

//echo ("select * from analiza8p where ID=$ipole");
$w=mysql_query("select * from analiza8p where ID=$ipole"); $w=mysql_fetch_array($w);

mysql_query("truncate analiza8b");
mysql_query("insert into analiza8b 
					select 0
						, $ido
						, dokum.ID
						, if(dokum.BLOKADA='O' or (Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))=0),0,dokum.WARTOSC)
						, if(dokum.BLOKADA='O' or (Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKR'])."','.',','))=0),0,dokum.WARTOSC)
						, 1
						, dokum.WARTOSC 
					 from dokum 
					where dokum.NABYWCA=".($w['ID_FIRMY'])."
					  and (   (dokum.DATAW between '".($w['DATA1'])."' and '".($w['DATA2'])."') 
					       or (dokum.CZAS  between '".($w['DATA1'])."' and '".($w['DATA2'])."')                                         
						  )                                        
					  and (   (Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKP'])."','.',','))>0)
					  		or(Find_In_Set(dokum.TYP,replace('".($w['TYPYDOKR'])."','.',','))>0)
						  )
            and not( dokum.TYP='PZK' and (dokum.DATAW between '2011-06-01' and '2011-06-30'))
				 order by dokum.CZAS, dokum.ID
");

mysql_query("insert into analiza8b 
					select 0
						, $ido
						, -ID
						, PRZYCHOD
						, ROZCHOD
						, 1
						, PRZYCHOD+ROZCHOD 
					 from dokumentbk 
					where dokumentbk.NRKONT=".($w['ID_FIRMY'])."
					  and (   (dokumentbk.DATA between '".($w['DATA1'])."' and '".($w['DATA2'])."') 
						  )                                        
				 order by dokumentbk.DATA, dokumentbk.ID
");

mysql_query("delete from analiza8 where ID_OSOBYUPR=$ido");
//mysql_query("insert into analiza8 select 0, $ido, ID_D, sum(PRZYCHOD), sum(ROZCHOD), sum(ILOSC_DOK), CENA_DOK, 0, '' from analiza8b group by ID_D, CENA_DOK having sum(ILOSC_DOK)<>0");	//nie pokazuj kompensuj¹cych siê FVK
//mysql_query("insert into analiza8 select 0, $ido, ID_D, sum(PRZYCHOD), sum(ROZCHOD), sum(ILOSC_DOK), CENA_DOK, 0, '' from analiza8b group by ID_D, CENA_DOK order by ID");
mysql_query("
		insert into analiza8 
		select 0, $ido, analiza8b.ID_D, analiza8b.PRZYCHOD, analiza8b.ROZCHOD, analiza8b.ILOSC_DOK, analiza8b.CENA_DOK, 0, '' 
		from analiza8b 
		left join dokum on dokum.ID=analiza8b.ID_D 
		left join dokumentbk on dokumentbk.ID=-analiza8b.ID_D 
		order by  if(IsNull(dokumentbk.ID),dokum.DATAW,dokumentbk.DATA) desc
				, if(IsNull(dokumentbk.ID),dokum.CZAS,dokumentbk.CZAS) desc
				, analiza8b.ID desc
");

//mysql_query("update      analiza8 left join dokum on dokum.ID=analiza8.ID_D set DATA=dokum.DATAW, PRZYCHOD=0, ROZCHOD=0, STAN=ILOSC_DOK where analiza8.ID_OSOBYUPR=$ido and dokum.TYP='INW'");
//mysql_query("update      analiza8 left join dokum on dokum.ID=analiza8.ID_D                                          set STAN=-99999999 where analiza8.ID_OSOBYUPR=$ido and right(dokum.TYP,1)='K' and analiza8.ILOSC_DOK=0");
//mysql_query("delete from analiza8                                                                                                       where analiza8.ID_OSOBYUPR=$ido and analiza8.STAN=-99999999");
mysql_query("
				   update analiza8 
					  set STAN=-99999999 
					where ID_OSOBYUPR=$ido
");

$stan=0;
$ilosc=0;
$w=mysql_query("select ID, PRZYCHOD, ROZCHOD, STAN from analiza8 where ID_OSOBYUPR=$ido order by ID desc");	//DATA, 
while ($r=mysql_fetch_array($w)) {
	if ($r['STAN']<>-99999999) {
		$ilosc=$r['STAN']-$stan;
		$stan=$r['STAN'];
		mysql_query("update analiza8 set STAN=$stan, PRZYCHOD=if($ilosc>0,$ilosc,0), ROZCHOD=if($ilosc<0,-1*$ilosc,0) where ID=".$r['ID']);
	} else {
		$stan+=$r['PRZYCHOD']-$r['ROZCHOD'];
		mysql_query("update analiza8 set STAN=$stan where ID=".$r['ID']);
	}
}
mysql_query("update analiza8p SET CZAS=Now() where ID=$ipole");

$tabelaa='analiza8';	// tu l¹duje po akcji

//echo $raport;
?>
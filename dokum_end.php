<?php

if (!$szukane) {     //je¶li nie szuka kontrahenta po czym¶ (bo wtedy idzie do tabeli firmy)
   $tabelaa='spec';  //to idzie do tabeli specyfikacji
}

$all=false;	//wszystko liczyæ ? NIE
if ($ipole<0) {
	$ipole=-$ipole;		//przywróæ prawid³owy znak $ipole
	$all=true;		//wszystko liczyæ ? TAK
}

if (!$ipole) {

   $z="Select ID from tabele where NAZWA='dokum'";
   $w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0]; $idt=$w;
   
   $z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID dokum
   $w=mysql_query($z); $w=mysql_fetch_row($w); $ipole=$w[0];

   mysql_query("update tabeles set WARUNKI='ID=$ipole' where ID_TABELE=$idt and ID_OSOBY=$ido");
}

$zz="select * from dokum where ID=$ipole";
$ww=mysql_query($zz);
$ww=mysql_fetch_array($ww);

//require('skladuj_zmienne.php');exit;
if ( ($_SESSION['osoba_dos']<>'T')
   &&($tabelaa=='spec')
   &&($ww['NABYWCA']>0)
   &&(  ($ww['DNIZWLOKI']>0)
      ||($ww['DATAT']<>$ww['DATAW'])
      ||(substr($ww['SPOSOB'],0,3)<>'got'))
   ) {
   $zzz=("
      select TERMIN
            ,NALEZNOSCI+KOREKTY-ZALICZKI
        from firmy
       where ID=$ww[NABYWCA]
   ");
   $www=mysql_query($zzz);
   if ($rrr=mysql_fetch_row($www)) {
   	$sposob=$ww['SPOSOB'];
   	$dnizwloki=$ww['DNIZWLOKI'];
   	$termin=$ww['DATAT'];
      if (($rrr[0]==0)||(substr($ww['SPOSOB'],0,3)=='got')) {  //regu³y gotówkowe
      	$sposob='gotówka';
      	$dnizwloki=0;
      	$termin=$ww[DATAW];
         if (substr($ww['SPOSOB'],0,3)<>'got') {
         	$tabelaa='Tabela_Formularz.php?natab=dokum&posx=25';	//znów do formularza dokumentu na "Sposób zap³aty"
         } elseif ($ww['DNIZWLOKI']>0) {
         	$tabelaa='Tabela_Formularz.php?natab=dokum&posx=26';	//znów do formularza dokumentu na "Dni zw³oki"
         } elseif ($ww['DATAT']<>$ww['DATAW']) {
         	$tabelaa='Tabela_Formularz.php?natab=dokum&posx=27';	//znów do formularza dokumentu na "Termin zap³aty"
         } else {
         	$tabelaa='Tabela_Formularz.php?natab=dokum&posx=24';	//znów do formularza dokumentu na "Rabat" - to nigdy nie powinno siê zdarzyæ
         }
      } elseif ($rrr[0]>0) {  //regu³y przelewowe
         if (trim($ww['SPOSOB'])=='') {
         	$tabelaa='Tabela_Formularz.php?natab=dokum&posx=25';	//znów do formularza dokumentu na "Sposób zap³aty"
//         	$tabelaa='Tabela_Formularz.php?natab=dokum&posx=26';	//znów do formularza dokumentu na "Dni zw³oki"
         }
      	$sposob='przelew';
         if (($rrr[0]<$ww['DNIZWLOKI'])||($ww['DNIZWLOKI']==0)) {
         	$dnizwloki=$rrr[0];
         	$tabelaa='Tabela_Formularz.php?natab=dokum&posx=26';	//znów do formularza dokumentu na "Dni zw³oki"
         } else {
           $zzz=("
              select 0, sum(WARTOSC-WPLACONO)
                from dokum
               where NABYWCA=$ww[NABYWCA]
                 and DateDiff(CurDate(),DATAT)>10
                 and TYP <> 'PZ'
                 and BLOKADA = ''
                 and WARTOSC <> WPLACONO
           ");
           $www=mysql_query($zzz);
           if ($rrr=mysql_fetch_row($www)) {
              if ($rrr[1]>1) {
                $sposob='gotówka';
         	      $dnizwloki=0;
         	      $tabelaa='Tabela_Formularz.php?natab=dokum&posx=25';	//znów do formularza dokumentu na "Sposób zap³aty"
              }
           }
        }
      }
      mysql_query("
         update dokum
            set DNIZWLOKI=$dnizwloki
               ,DATAT='$termin'
               ,SPOSOB='$sposob'
          where ID=$ipole
      ");
   }
}

if (strtoupper(substr(trim($ww['INDEKS']),-4,4))<>'AUTO') {
$zzz="update doktypy set NUMER='".($ww['INDEKS'])."' where TYP='".($ww['TYP'])."' limit 1";
$www=mysql_query($zzz);
}

$okk=false;	//obliczano coœ ?

$n23=$ww['NETTO23'];
$v23=$ww['VAT23'];
if ($all || ($n23<>0 && $v23==0)) {$v23=round($n23*0.23,2);$okk=true;}	//obliczane

$n22=$ww['NETTO22'];
$v22=$ww['VAT22'];
if ($all || ($n22<>0 && $v22==0)) {$v22=round($n22*0.22,2);$okk=true;}	//obliczane

$n08=$ww['NETTO8'];
$v08=$ww['VAT8'];
if ($all || ($n08<>0 && $v08==0)) {$v08=round($n08*0.08,2);$okk=true;}	//obliczane

$n07=$ww['NETTO7'];
$v07=$ww['VAT7'];
if ($all || ($n07<>0 && $v07==0)) {$v07=round($n07*0.07,2);$okk=true;}	//obliczane

$n05=$ww['NETTO5'];
$v05=$ww['VAT5'];
if ($all || ($n05<>0 && $v05==0)) {$v05=round($n05*0.05,2);$okk=true;}	//obliczane

if ($all || $okk ) {	//zapisz obliczane
	$z="update dokum SET VAT23=$v23, VAT22=$v22, VAT8=$v08, VAT7=$v07, VAT5=$v05 where ID=$ipole";
	$w=mysql_query($z);
}

$n00=$ww['NETTO0'];
$n0z=$ww['NETTOZW'];

//$idfv=$ww['NETTOCZ'];
$idfv=$ww['CZAS'];
if ((strtoupper(substr(trim($ww['TYP']),-1,1))=='K')&&(substr($idfv,2,1)<>':')&&($idfv*1>0)) {	//po u¿yciu opcji "Korekta"

   $z=("
      create temporary table specbuf (
      `ID` int(11) not null primary key default null auto_increment,
      `ID_D` int(11) not null default '0' ,
      `ID_T` int(11) not null default '0' ,
      `CENA` decimal(12,2) not null default '0.00' ,
      `ILOSC` decimal(12,3) not null default '0.000' ,
      `RABAT` decimal(3,0) not null default '0' ,
      `CENABEZR` decimal(12,2) not null default '0.00' ,
      `NETTO` decimal(12,2) not null default '0.00' ,
      `KWOTAVAT` decimal(12,2) not null default '0.00' ,
      `BRUTTO` decimal(12,2) not null default '0.00' ,
      `CENABRUTTO` decimal(12,2) not null default '0.00' ,
      `STAWKAVAT` char(3) not null default '' 
      ) ENGINE=MyISAM DEFAULT CHARSET=latin2;
   ");

   if (!$w=mysql_query($z)) {echo "<font color='red'>Error:</font> $z<br>";die;}
   
//By³o:
//faktura korygowana
	$z=("insert into specbuf select spe.ID, $ipole, spe.ID_T, spe.CENA,-spe.ILOSC, spe.RABAT, spe.CENABEZR,-spe.NETTO,-spe.KWOTAVAT,-spe.BRUTTO, spe.CENABRUTTO, spe.STAWKAVAT from spec as spe where spe.ID_D=$idfv and spe.ILOSC<>0");
   if (!$w=mysql_query($z)) {echo "<font color='red'>Error:</font> $z<br>";die;}

//dane tej faktury
	$z=("select concat(TYP, ' ', INDEKS), DATAS 
          from dokum
         where ID=$idfv
   ");
   if (!$w=mysql_query($z)) {echo "<font color='red'>Error:</font> $z<br>";die;}
   if ($r=mysql_fetch_row($w)) {
      $nrfkor=$r[0];    //numer faktury korygowanej
      $dtfkor=$r[1];    //data  faktury korygowanej
   }

//korekty do tej faktury
	$z=("select ID 
	       from dokum
	      where NUMERFD='$nrfkor'
	        and DATAO='$dtfkor'
	");
	if (!$w=mysql_query($z)) {echo "<font color='red'>Error:</font> $z<br>";die;}
	while ($r=mysql_fetch_row($w)) {
		$idfv=$r[0];
//specyfikacje tych korekt
		$z=("insert into specbuf select spe.ID, $ipole, spe.ID_T, spe.CENA,-spe.ILOSC, spe.RABAT, spe.CENABEZR,-spe.NETTO,-spe.KWOTAVAT,-spe.BRUTTO, spe.CENABRUTTO, spe.STAWKAVAT from spec as spe where spe.ID_D=$idfv and spe.ILOSC<>0");
		if (!$ww=mysql_query($z)) {echo "<font color='red'>Error:</font> $z<br>";die;}
	}

//komasowanie wyników
  	$z=("insert into spec select 0, $ipole, spe.ID_T, spe.CENA, sum(spe.ILOSC), spe.RABAT, spe.CENABEZR, sum(spe.NETTO), sum(spe.KWOTAVAT), sum(spe.BRUTTO), spe.CENABRUTTO, spe.STAWKAVAT from specbuf as spe group by spe.ID_T, spe.CENA, spe.RABAT having sum(spe.ILOSC)<>0 order by spe.ID");
	if (!$w=mysql_query($z)) {echo "<font color='red'>Error:</font> $z<br>";die;}

//Powinno byæ:
	$z=("insert into spec select 0, $ipole, spe.ID_T, spe.CENA,    -spe.ILOSC,  spe.RABAT, spe.CENABEZR,    -spe.NETTO,     -spe.KWOTAVAT,     -spe.BRUTTO,  spe.CENABRUTTO, spe.STAWKAVAT from spec    as spe where spe.ID_D=$ipole and spe.ILOSC<>0");
	if (!$w=mysql_query($z)) {echo "<font color='red'>Error:</font> $z<br>";die;}

	$idd=$ipole;
		$z=("select ID
		       from spec 
		      where ID_D=$idd
			  limit 1
		");
		$w=mysql_query($z);	
		if ($r=mysql_fetch_row($w)) {
			$ipole=$r[0];
			$all_spec=true;
			require('spec_calc.php');
		}
	$ipole=$idd;

//require('skladuj_zmienna.php');skladuj($z);

}
		//obowi¹zkowe

$n23*=1;
$v23*=1;
$n22*=1;
$v22*=1;
$n08*=1;
$v08*=1;
$n07*=1;
$v07*=1;
$n05*=1;
$v05*=1;
$n00*=1;
$n0z*=1;
      
$zz=("
     update dokum 
        SET WARTOSC=$n23+$v23+$n22+$v22+$n08+$v08+$n07+$v07+$n05+$v05+$n00+$n0z
          , CZAS=Now() 
          , DNIZWLOKI=if(DNIZWLOKI=0,DateDiff(DATAT,DATAW),DNIZWLOKI) 
          , DATAT=Date_Add(DATAW,interval DNIZWLOKI day)
      where ID=$ipole
");
$ww=mysql_query($zz);
?>
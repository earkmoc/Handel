<?php

//require('funkcje_sql.php');

set_time_limit(30*60);	// 30 min

//CREATE TABLE `magazyny` (
//  `ID` int(11) NOT NULL auto_increment,
//  `ID_F` int(11) NOT NULL default '0',
//  `ID_D` int(11) NOT NULL default '0',
//  `ID_T` int(11) NOT NULL default '0',
//  `CENA` decimal(12,2) NOT NULL default '0.00',
//  `ZAKUP` decimal(12,3) NOT NULL,
//  `STAN` decimal(12,3) NOT NULL,
//  PRIMARY KEY  (`ID`),
//  UNIQUE KEY `ID_F` (`ID_F`,`ID_D`,`ID_T`,`CENA`)
//) ENGINE=MyISAM

//require('skladuj_zmienna.php');

$idd=($ipole<0)?(-$ipole):$ipole;
$idt=$_POST['idtab'];

//********************************************************************
// zapami�taj stan tabeli dla zalogowanej osoby
$w=mysql_query("select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido"); $w=mysql_fetch_row($w);
if ($w[0]>0) {
   $w=mysql_query(     "update tabeles set ID_POZYCJI=$idd,NR_STR=$str,NR_ROW=$r,NR_COL=$c where ID_TABELE=$idt and ID_OSOBY=$ido limit 1");
} else {
   $w=mysql_query("Insert into tabeles set ID_POZYCJI=$idd,NR_STR=$str,NR_ROW=$r,NR_COL=$c,ID_TABELE=$idt,ID_OSOBY=$ido");
}
// zapami�taj stan tabeli dla zalogowanej osoby
//********************************************************************

$z="select ID from dokum where BLOKADA='' order by CZAS desc limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$ostatni=($w[0]==$idd);

$z="select BLOKADA, TYP, TYP_F, NABYWCA, MAGAZYN, INDEKS, if(DATAW<'2011-07-01',0,1), CZAS from dokum where ID=$idd limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$blokada=$w[0];
$typ=$w[1];	//FV
$tyf=$w[2];	//P	//N
$nab=$w[3];	//535
$mag=$w[4];	//0	//1
$nrdok=strtoupper(trim($w[5]));	//auto lub 1539/2006
$stop=(($w[6]==0)&&($ido<>1)&&($ido<>10));	//0	//1
$czas=($w[7]);

if ((substr($nrdok,-4,4)=='AUTO')||(substr($nrdok,-7,7)=='AUTOMAT')) {
	$z="Select MAXROWS from tabele where ID=$idt";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $mr=$w[0];

	$z="update tabeles set ID_POZYCJI=$idd, NR_STR=99999999, NR_ROW=$mr where ID_TABELE=$idt and ID_OSOBY=$ido limit 1";
	$w=mysql_query($z);
}

$z="select TYP from firmy where ID=$nab limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$typfirmy=$w[0];

$z="select MAGAZYNG, MAGAZYNP from doktypy where TYP='$typ' limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$mg=$w[0];	//2
$mp=$w[1];	//2

$moznaOtwieracZamykac=true;
if (($mp==1)||($mp==2)) {	//przych�d lub rozch�d: nie wolno otwiera� ani zamyka� je�li s� tam towary inwentaryzowane

	if ($blokada=='O') {	//zamykanie ? sprawd� czy nie ma otwartych INW z tymi towarami
		$w=mysql_query("
			select count(*)
			  from dokum
			 where TYP='INW'
			   and BLOKADA='O'
		");
		if (($r=mysql_fetch_row($w))&&($r[0]>0)) {	//s� jakie�
			$w=mysql_query("
				select ID_T
				  from spec
				 where spec.ID_D=$idd
			");
			while (($r=mysql_fetch_row($w))&&$moznaOtwieracZamykac) {
				$idt=$r[0];
				$ww=mysql_query("
					select count(*)
					  from spec
				 left join dokum
					    on dokum.ID=spec.ID_D
					 where spec.ID_T=$idt
					   and dokum.TYP='INW'
					   and dokum.BLOKADA='O'
				");
				if (($rr=mysql_fetch_row($ww))&&($rr[0]>0)) {	//s� jakie� pozycje na otwartej inwentaryzacji
					$moznaOtwieracZamykac=false;
					$ww=mysql_query("
						select INDEKS
							 , NAZWA
						  from towary
						 where ID=$idt
					");
					if ($rr=mysql_fetch_row($ww)) {
						$rr=$rr[0].' '.$rr[1];
					}
					$komunikat="Nie wolno zamyka� tego dokumentu, bo na jego specyfikacji s� pozycje figuruj�ce na otwartej inwentaryzacji (np.: $rr)";
				}
			}
		}
	} else {				//otwieranie ? sprawd� czy s� jakie� p�niejsze zamkni�te inwentaryzacje
		$w=mysql_query("
			select count(*)
			  from dokum
			 where TYP='INW'
			   and CZAS>='$czas'
			   and BLOKADA=''
		");
		if (($r=mysql_fetch_row($w))&&($r[0]>0)) {	//s� jakie�
			$w=mysql_query("
				select ID_T
				  from spec
				 where spec.ID_D=$idd
			");
			while (($r=mysql_fetch_row($w))&&$moznaOtwieracZamykac) {
				$idt=$r[0];
				$ww=mysql_query("
					select count(*)
					  from spec
				 left join dokum
					    on dokum.ID=spec.ID_D
					 where spec.ID_T=$idt
					   and dokum.TYP='INW'
					   and dokum.CZAS>='$czas'
					   and dokum.BLOKADA=''
				");
				if (($rr=mysql_fetch_row($ww))&&($rr[0]>0)) {	//s� jakie� pozycje na p�niejszych zamkni�tych inwentaryzacjach
					$moznaOtwieracZamykac=false;
					$ww=mysql_query("
						select INDEKS
							 , NAZWA
						  from towary
						 where ID=$idt
					");
					if ($rr=mysql_fetch_row($ww)) {
						$rr=$rr[0].' '.$rr[1];
					}
					$komunikat="Nie wolno otwiera� tego dokumentu, bo na jego specyfikacji s� pozycje figuruj�ce na p�niejszych zamkni�tych inwentaryzacjach (np.: $rr)";
				}
			}
		}
	}
}

if ($moznaOtwieracZamykac) {

$mnoznik=3;	//domy�lnie, np. dla INW
$mnG=0;
$mnZ=0;

//wp�yw na stan MZ (syntetyka towar�w)

if       (($mg==0)&&($mp==0))  	{$mnoznik=0;//nie wp�ywa, np. KOR
} elseif (($mg==1)&&($mp==2))  	{$mnoznik=0;//nie wp�ywa, np. MM, PWC, RWC
} elseif (($mg==2)&&($mp==1))  	{$mnoznik=0;//nie wp�ywa, np. MM, PWC, RWC
} elseif (($mg==1))          	{$mnoznik=1;//przych�d
} elseif (($mg==2))          	{$mnoznik=-1;//rozch�d
} else                     		{$mnoznik=0;
} 
//wp�yw na stan MZ (analityka towar�w, firmy.ID=1)

$mnZ=$mnoznik;

//wp�yw na stan MG nr $mag (analityka towar�w, firmy.ID=2, dokum.MAGAZYN=1 lub 0)

if       ($mg==0) {$mnG=0;			//nie wp�ywa, np. KOR
} elseif ($mg==1) {$mnG=1;		//przych�d
} elseif ($mg==2) {$mnG=-1;		//rozch�d
} else            {$mnG=0;
}  
//wp�yw na stan MP nr $nab (analityka towar�w, firmy.ID=dokum.NABYWCA)

$mnP=0;				//domy�lnie
if ($mp==0) {$mnP=0;}		//nie wp�ywa, np. KOR
if ($mp==1) {$mnP=1;}		//przych�d
if ($mp==2) {$mnP=-1;}		//rozch�d
if ($mp==3) {$mnP=1;}		//INW

//je�li na dokumencie jest inny magazyn ni� zbiorczy i jest to PZ'ka i kontrahent na dokumencie jest 'Dostawca'
//je�li kontrahent na dokumencie jest 'D' i jest to inwentaryzacja (to musi by� pod powy�szym, bo inaczej si� robi $mnP==0
if (($mag<>$nab)&&(($typ=='PZ')||($typ=='INW'))&&(strtoupper($tyf)=='D')) {
	$mag=$nab;				//to musi to by� na magazyn tego dostawcy, nawet je�li na dokumencie jest inaczej
	$z="update dokum set MAGAZYN=$mag where ID=$idd limit 1";$w=mysql_query($z);
}
//if (strtoupper($tyf)<>'P') 	{$mnP=0;}	//kontrahent na dokumencie nie jest 'P', to nie ma stan�w i ruchu
//if (strtoupper($typfirmy)<>'P') {$mnP=0;}	//kontrahent w kartotece nie jest 'P', to nie ma stan�w i ruchu
if ($mag==1)	 		{$mnZ=0;}	//magazyn jest zbiorczy, to nie powtarzaj ruchu na MZ
//if ($nab==1)	 		{$mnP=0;}	//nabywc� jest zbiorczy, to nie powtarzaj ruchu na MZ
//if ($nab==$mag) 		{$mnP=0;}	//nabywc� jest magazyn, to nie powtarzaj ruchu na MP

//--------------------------------------------------------------------------------------------------------------------------

if (($blokada=='O')&&!$stop) {	//otwarty, wi�c ZAMYKAMY

//	$z="update dokum set BLOKADA='.', CZAS=Now() where ID=$idd limit 1";$w=mysql_query($z);	//w toku, �eby nikt nie ruszy�

	if       ($mnP==-1) {$mn=-1;$ma=$nab; 	//mirror z rozchodu na podstawie stanu podmagazynu
	} elseif ($mnG==-1) {$mn=-1;$ma=$mag; 	//mirror z rozchodu na podstawie stanu magazynu g��wnego
	} elseif ($mnZ==-1) {$mn=-1;$ma=1; 	   //mirror z rozchodu na podstawie stanu magazynu zbiorczego
	} else		        {$mn=1; 		      //mirror z przychodu lub inwentaryzacji
	}

//------------------------------------------------------------------------------------------

//	if ((substr(trim($typ),-1,1)=='K')&&(substr(trim($typ),0,2)<>'PZ')) {			//korekty trzeba przeliczy� i s� analitycznie
	if (substr(trim($typ),-1,1)=='K') {   //korekty trzeba przeliczy� i s� analitycznie, bo mimo �e wi�kszo�� si� bilansuje na zero (nie ma korekty), 
                                 //to niekt�re si� nie bilansuj� i maj� pozosta� w formie by�o/ma by�, a nie zbilansowanej pojedynczej pozycji
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

	} else {									//nie korekty s� syntetycznie

		$z=("select count(*)
	           from spec 
	          where ID_D=$idd
		");
		$w=mysql_query($z);	
	
		if (($r=mysql_fetch_row($w))&&($r[0]>0)) {	//jest jaka� specyfikacja g��wna
	
//mirror sio na dokumencie
		
			$z=("delete 
		         from spec 
		        where ID_D=-$idd
		   ");
		   $w=mysql_query($z);	
	
//kopia agregowanej g��wnej specyfikacji do mirrora
			$z=("insert 
		         into spec 
		       select 0
		            , -$idd
		            , spe.ID_T
		            , spe.CENA
		            , sum(spe.ILOSC)
		            , spe.RABAT
					, spe.CENABEZR
		            , sum(spe.NETTO)
		            , sum(spe.KWOTAVAT)
		            , sum(spe.BRUTTO)
		            , spe.CENABRUTTO
					, spe.STAWKAVAT 
		         from spec as spe 
		        where spe.ID_D=$idd
			");
			if ($mp<>3) {						//inwentaryzacje maj� przyjmowa� zer�wki
				$z.=("	  and spe.ILOSC<>0");
			}
			$z.=("	 group by spe.ID_T
			 		, spe.CENA
					, spe.RABAT
			");
			$w=mysql_query($z);
	
//g��wna specyfikacja sio na dokumencie (niebezpieczny moment)
	
			$z=("delete 
		         from spec 
		        where ID_D=$idd
			");
			$w=mysql_query($z);	
		}	

//nowa g��wna specyfikacja na dokumencie (przywr�cenie bezpiecze�stwa) z mirrora
		$z=("insert 
	         into spec 
	       select 0
	            , $idd
	            , spe.ID_T
	            , spe.CENA
	            , spe.ILOSC
	            , spe.RABAT
				, spe.CENABEZR
	            , spe.NETTO
	            , spe.KWOTAVAT
	            , spe.BRUTTO
	            , spe.CENABRUTTO
				, spe.STAWKAVAT 
	         from spec as spe
	        where spe.ID_D=-$idd
		");
		$w=mysql_query($z);

		$all_spec=true;
		$ipole=mysql_insert_id();
		require('spec_calc.php');
	}

//------------------------------------------------------------------------------------------

//mirror sio na dokumencie

	$z=("delete 
         from spec 
        where ID_D=-$idd
   ");
   $w=mysql_query($z);	

//nowy mirror na dokumencie

	$inwentaryzacja=($mp==3);	//inwentaryzacja ?

	if (($mn==1)&&!$inwentaryzacja) {         //przych�d ale nie INW

		$z=("insert 
             into spec 
           select 0
                , -spe.ID_D
                , spe.ID_T
                , spe.CENA
                , spe.ILOSC
                , 0, 0
                , $nab
                , $idd
                , spe.ILOSC
                , 0, '' 
             from spec as spe 
            where spe.ID_D=$idd
      ");
      $w=mysql_query($z);

   } else {                      //rozch�d lub INW

		mysql_query("
			delete 
			  from magazyny
			 where ID_F=0
			   and ID_D=0
			   and ZAKUP=0
		");

		$z=("
       insert into spec 
            select 0
                 , -spe.ID_D
                 , spe.ID_T
                 , if(isnull(magazyny.ID) or magazyny.CENA=0,towary.CENA_Z,magazyny.CENA)
                 , if(isnull(magazyny.ID),towary.STAN_POP,magazyny.STAN)
                 , 0
                 , if(isnull(magazyny.ID),towary.STAN_POP,magazyny.STAN)
                 , if(isnull(magazyny.ID),towary.DOSTAWCA,magazyny.ID_F)
                 , if(isnull(magazyny.ID),0,magazyny.ID_D)
                 , if(isnull(magazyny.ID),towary.STAN,magazyny.ZAKUP)
                 , if(isnull(magazyny.ID),towary.STAN_POP,magazyny.STAN)
				 , towary.VAT 
              from spec as spe 
         left join towary 
                on (towary.ID=spe.ID_T) 
         left join magazyny 
                on magazyny.ID_T=spe.ID_T 
             where spe.ID_D=$idd 
               and spe.ILOSC>=0 
          order by magazyny.ID_D
      ");

//magazyny.ID_F=towary.DOSTAWCA and 
					
      if ($inwentaryzacja) {             //INW wpisuje stany od najnowszego (ostatniego) PZ
         $z.=" desc, magazyny.CENA desc";
      }
      $w=mysql_query($z);

      if ($inwentaryzacja) {             //INW wpisuje stany od najnowszego (ostatniego) PZ

//w spec.ILOSC jest rozpykana odwrotnie chronologicznie ilo�� wed�ug inwentaryzacji, czyli ustawiana/��dana ilo��
//w spec.CENABEZR jest rozpykana odwrotnie chronologicznie ilo�� wed�ug towary.STAN_POP sprzed inwentaryzacji, czyli By�o
//w spec.CENABRUTTO jest ilo�� wed�ug SPZ sprzed inwentaryzacji, czyli "czasem dziwne "By�o"", bo cz�sto SPZ."stan" <> towary.STAN
//warto�� r�nicy inwentaryzacyjnej raczej liczy� z spec.ILOSC i spec.CENABEZR

         require('specbuf_fill_inw.php');//jest problem z towary.STAN_POP, bo to tymczasowe ...

	      $z=("select towary.ID
		  			, towary.STAN_POP
		  		 from towary 
	       right join specbuf 
	               on specbuf.ID_T=towary.ID 
	            where towary.STAN_POP<>specbuf.CENABEZR 
	      ");
	      $w=mysql_query($z);
		  while ($r=mysql_fetch_row($w)) {	//wszystkie problematyczne musz� by� poprawione, �eby dostosowa� SPZ do Towary
				$idto=$r[0];
				$stan=$r[1];
				mysql_query("
					update spec
					   set CENABEZR=0
					 where ID_D=-$idd
					   and ID_T=$idto
				");
				$ww=mysql_query("
					select ID, BRUTTO
					  from spec
					 where ID_D=-$idd
					   and ID_T=$idto
				  order by KWOTAVAT desc
				");
				while (($rr=mysql_fetch_row($ww))&&($stan<>0)) {
					$idsp=$rr[0];
					$zaku=$rr[1];
					$ile=$stan;
					if ($ile>$zaku) {
						$ile=$zaku;
					}
					$stan-=$ile;
					if ($ile<0) {
						$ile=0;
					}
					if ($ile<>0) {
						mysql_query("
							update spec
							   set CENABEZR=$ile
							 where ID=$idsp
						");
					}
				}
				if ($stan<>0) {
						mysql_query("
							update spec
							   set CENABEZR=$stan
							 where ID=$idsp
						");
				}
		  }
      }

		$z=("select * 
		     from spec 
		    where ID_D=$idd 
		       or ID_D=-$idd 
		 order by ID_T, ID
		");
		$w=mysql_query($z);	//mirror do analizy

		$ids=-1;	//nawet nie zero
		$ile=0;
		while ($r=mysql_fetch_array($w)) {

			$specyfikacja=($r[ID_D]>0);	//specyfikacja g��wna ?
			$mirror=($r[ID_D]<0);		//mirror specyfikacji g��wnej ?
			$zakupione=$r[BRUTTO];
			$jeszczeNiewypykane=($ile<>0);
			$wszystkoWypykane=($ile==0);

			if ($specyfikacja&&($ids<>0)) {
				if ($jeszczeNiewypykane&&($ids<>0)) {		//zaczyna nowego, a stary nieuko�czony !!!
					$zz="update spec set ILOSC=ILOSC+$ile where ID=$ids";$ww=mysql_query($zz);
				}
				$ile=$r[ILOSC];				//do rozchodu, np. 1600
				$ids=0;
			} elseif ($inwentaryzacja&&$specyfikacja&&($ids==0)) {
				$ile=$r[ILOSC];				//do rozchodu, np. 1600
			} elseif ($inwentaryzacja&&($ile>$zakupione)) 	{	//mirror do zmniejszenia, np. 26>20
				$zz="update spec set ILOSC=$zakupione where ID=".$r[ID];$ww=mysql_query($zz);
				$ile-=$zakupione;
				$ids=$r[ID];
			} elseif ($inwentaryzacja&&($ile<=$zakupione)) 	{	//mirror do zmniejszenia, np. 6<20
				$zz="update spec set ILOSC=$ile where ID=".$r[ID];$ww=mysql_query($zz);
				$ile=0;
				$ids=$r[ID];
			} elseif ($specyfikacja&&($ids==0)) {		//kolejny nowy, wi�c to pewnie korekta w stylu By�o/Jest
				$ile+=$r[ILOSC];			//jeszcze do rozchodu
			} elseif ($r[ILOSC]<0)	{			//minus, np.: -100 do wyzerowania, wi�c jakby przych�d na tej pozycji
				$zz="update spec set ILOSC=".($r[ILOSC])." where ID=".$r[ID];$ww=mysql_query($zz);	//-100=0
				$ile-=$r[ILOSC];			//100-(-100)=200 zwi�ksza ilo�&#8224; do rozchodu z innej pozycji
				$ids=$r[ID];
			} elseif (($r[ILOSC]<=$ile)&&$jeszczeNiewypykane)	{	//mirror OK, np.: 200<=300 => 200 bo tyle jest
				$ile-=$r[ILOSC];
				$ids=$r[ID];
			} elseif (($r[ILOSC]>$ile)&&$jeszczeNiewypykane) 	{	//mirror do zmniejszenia, np. 500>100
				$zz="update spec set ILOSC=$ile where ID=".$r[ID];$ww=mysql_query($zz);
				$ile=0;
				$ids=$r[ID];
			} elseif ($wszystkoWypykane) { 				//mirror do skasowania
				$ids=$r[ID];
				$zz="update spec set ILOSC=0 where ID=$ids";$ww=mysql_query($zz);
			}
		}
		if (($ile<>0)&&($ids<>0)) {				//koniec specyfikacji, a ostatni nieuko�czony !!!
			$zz="update spec set ILOSC=ILOSC+$ile where ID=$ids";$ww=mysql_query($zz);
		}
		if (!$inwentaryzacja) {
			$zz="delete from spec where ILOSC=0 and ID_D=-$idd";$ww=mysql_query($zz);
		}
	}

//------------------------------------------------------------------------------
//stany w "Towary - syntetyka stan�w" wed�ug specyfikacji dokumentu

	if ($mnoznik==3) 	{
//      $z="update towary 
//      right join spec on spec.ID_T=towary.ID 
//             set towary.STAN=spec.ILOSC 
//           where spec.ID_D=$idd";
//      $w=mysql_query($z);  // and spec.ILOSC<>0
   } elseif ($mnoznik<>0) {

	  require('specbuf_fill.php');

      $z=("update towary 
       right join specbuf 
               on specbuf.ID_T=towary.ID 
              set towary.STAN=(towary.STAN+($mnoznik*specbuf.ILOSC)) 
            where specbuf.ID_D=$idd 
              and specbuf.ILOSC<>0
              and towary.STATUS<>'U'
	  ");
      $w=mysql_query($z);
   }

//echo "mnP=$mnP, mp=$mp";exit;

//------------------------------------------------------------------------------

	if ($mnP<>0) {      //zmiana stanu w MP=$nab

		if ($mp==3) {	  //INW

// wpisa� do "magazyny" wszystkie towary figuruj�ce na specyfikacji INW z cenami ze specyfikacji
			$z=("
              insert 
                into magazyny 
              select 0
                   , spec.NETTO
                   , spec.KWOTAVAT
                   , spec.ID_T
                   , spec.CENA
                   , spec.BRUTTO
                   , 0
                from spec 
           left join towary 
                  on (    towary.ID    =spec.ID_T 
                      and towary.STATUS='T'
                     )
               where spec.ID_D=-$idd 
         on duplicate key update magazyny.ID=magazyny.ID
         ");
         $w=mysql_query($z);

// zeruj w "magazyny" wszystkie towary figuruj�ce na specyfikacji INW
			$z=("
              update magazyny 
                 set magazyny.STAN=0
          right join spec 
                  on spec.ID_T=magazyny.ID_T 
               where spec.ID_D=-$idd 
         ");
         $w=mysql_query($z);

//nanie� nowe stany wed�ug mirrora ze spec
			$z=("  
                update magazyny
            right join spec on (
                                  spec.NETTO=magazyny.ID_F 
                              and spec.KWOTAVAT=magazyny.ID_D 
                              and spec.ID_T=magazyny.ID_T 
                              and spec.CENA=magazyny.CENA
                               ) 
                   set magazyny.STAN=spec.ILOSC 
                 where spec.ID_D=-$idd 
         ");
//                   and spec.ILOSC<>0
         $w=mysql_query($z);

//aktualizacja syntetyki towar�w

		 require('specbuf_fill.php');

         $z=("update towary 
          right join specbuf 
                  on specbuf.ID_T=towary.ID 
                 set towary.STAN=specbuf.ILOSC 
               where specbuf.ID_D=$idd 
                 and towary.STATUS<>'U'
		 ");
         $w=mysql_query($z);

		} else {

         if ($mn==1) {

//niech na pewno istniej� pozycje, na kt�rych b�dzie zaraz co� robione
   			$z=("insert into magazyny 
                      select 0, towary.DOSTAWCA, $idd, spec.ID_T, spec.CENA, 0, 0, '' 
                        from spec
                   left join towary on towary.ID=spec.ID_T 
                       where spec.ID_D=-$idd 
                         and spec.ILOSC<>0 
            on duplicate key update magazyny.ID=magazyny.ID
            ");
   //echo  $z;exit;
            $w=mysql_query($z);

         }

      	if (substr(trim($typ),-1,1)=='K') {   //korekty trzeba na 2 rzuty, tj. pozycje ujemne i dodatnie, bo przy jednym przebiegu nie widzi drugiej zmiany stanu gdy 2 pozycje dotycz� tego samego towaru  
      
//zmiana stan�w wed�ug mirrora ze spec: By�o
      			$z=("  
                      update magazyny
                  right join spec 
                          on (
                                  spec.NETTO   =magazyny.ID_F 
                              and spec.KWOTAVAT=magazyny.ID_D 
                              and spec.ID_T    =magazyny.ID_T 
                              and spec.CENA    =magazyny.CENA
                             ) 
                         set magazyny.STAN=(magazyny.STAN+($mnP*spec.ILOSC)) 
               ");
               if ($mp==1) {	// PZ
         			$z.=("  , magazyny.ZAKUP=(magazyny.ZAKUP+($mnP*spec.ILOSC))");
               }

//zmiana stan�w wed�ug mirrora ze spec: By�o
               $w=mysql_query($z." where spec.ID_D=-$idd and spec.ILOSC<0");
//zmiana stan�w wed�ug mirrora ze spec: Powinno by�
               $w=mysql_query($z." where spec.ID_D=-$idd and spec.ILOSC>0");
//sprz�tanie zer�wek
      			$z=("  
                      update magazyny
                  right join spec 
                          on (
                                  spec.NETTO   =magazyny.ID_F 
                              and spec.KWOTAVAT=magazyny.ID_D 
                              and spec.ID_T    =magazyny.ID_T 
                              and spec.CENA    =magazyny.CENA
                             ) 
                         set magazyny.ZAKUP=-99999999 
                           , magazyny.STAN=-99999999
                       where spec.ID_D=-$idd
                         and magazyny.ZAKUP=0 
                         and magazyny.STAN=0 
               ");
               $w=mysql_query($z);
               $w=mysql_query("delete from magazyny where magazyny.ZAKUP=-99999999 and magazyny.STAN=-99999999");

         } else {
//zmiana stan�w wed�ug mirrora ze spec
      			$z=("  
                      update magazyny
                  right join spec 
                          on (
                                  spec.NETTO   =magazyny.ID_F 
                              and spec.KWOTAVAT=magazyny.ID_D 
                              and spec.ID_T    =magazyny.ID_T 
                              and spec.CENA    =magazyny.CENA
                             ) 
                         set magazyny.STAN=(magazyny.STAN+($mnP*spec.ILOSC)) 
               ");
               if ($mp==1) {	// PZ
         			$z.=("  
                           , magazyny.ZAKUP=(magazyny.ZAKUP+($mnP*spec.ILOSC)) 
                  ");
               }
      			$z.=("  
                       where spec.ID_D=-$idd 
                         and spec.ILOSC<>0
               ");
               $w=mysql_query($z);
         }
		}
	}

//warto�&#8224; dokumentu wed�ug cen zakupu netto do obliczania osi�gni�tej mar�y

	if ($typ=='INW') {

		$all_spec=true;
		require('spec_calc_inw.php');

		$z=("
		   SELECT sum(spec.CENA*(spec.ILOSC-spec.CENABEZR))
		     FROM spec 
		    WHERE spec.ID_D=-$idd 
		");

	} else {
		$z=("
		   SELECT sum(spec.CENA*spec.ILOSC)
		     FROM spec 
		    WHERE spec.ID_D=-$idd 
		");
	}

	$w=mysql_query($z); 
	$w=mysql_fetch_row($w);
	$nettocz=$w[0];

//numer dokumentu

   if ((substr($nrdok,-4,4)=='AUTO')||(substr($nrdok,-7,7)=='AUTOMAT')) {
		$z="select NUMER, MASKA, Year(CurDate()), CZAS from doktypy where TYP='$typ' limit 1";
      $w=mysql_query($z); $w=mysql_fetch_row($w);
		$nrdok=$w[0];
		$maska=$w[1];
		$rok=$w[2];
		$czas=$w[3];
      $maska=str_replace('rok',$rok,$maska);
      $maska=str_replace('rocznik',substr($rok,-2,2),$maska);
		$z=1;
		while ($z>0) {
			$nrdok=$nrdok+1;
         if (substr($czas,0,4)<>$rok) {
   			$nrdok=1;
         }
         if ($maska*1>0) {
            $nrdok=substr('00000000000000000'.$nrdok,-$maska*1,$maska*1);
            $maska=substr($maska,1);
         }
			$z="select count(*) from dokum where TYP='$typ' and INDEKS='$nrdok$maska'";
			$z=mysql_query($z);
			$z=mysql_fetch_row($z);
			$z=$z[0];
		}
		$z="update doktypy set NUMER=$nrdok where TYP='$typ' limit 1";$w=mysql_query($z);
		$z="update dokum set BLOKADA='', CZAS=Now(), INDEKS='$nrdok$maska', NETTOCZ='$nettocz' where ID=$idd limit 1";$w=mysql_query($z);
//		$z="update dokum set BLOKADA='', INDEKS='$nrdok$maska' where ID=$idd limit 1";$w=mysql_query($z);
	} else {
		$z="update dokum set BLOKADA='', CZAS=Now(), NETTOCZ='$nettocz' where ID=$idd limit 1";$w=mysql_query($z);	//finito
//		$z="update dokum set BLOKADA='' where ID=$idd limit 1";$w=mysql_query($z);	//finito
	}

//	$komunikat='Dokument zamkni�ty';

} elseif (($blokada=='')&&!$stop&&($typ=='INW')&&!$ostatni) {

	$komunikat='Nie wolno otwiera� INW, je�li nie jest to ostatni zamkni�ty dokument w systemie';

// OTWIERANIE

} elseif (($blokada=='')&&!$stop&&($_SESSION['osoba_dos']=='T')) {	//zamkni�ty, wi�c OTWIERAMY i cofamy dzia�anie mirrora

	$z="update dokum set BLOKADA=':', CZAS=Now() where ID=$idd limit 1";$w=mysql_query($z);	//w toku, �eby nikt nie ruszy�
//	$z="update dokum set BLOKADA=':' where ID=$idd limit 1";$w=mysql_query($z);	//w toku, �eby nikt nie ruszy�

	if ($typ=='INW') {  //INW otwieranie

//przywracanie stan�w sprzed inwentaryzacji

//	  require('specbuf_fill_inw.php');

//      $z=("update towary 
//       right join specbuf 
//               on specbuf.ID_T=towary.ID 
//              set towary.STAN=specbuf.CENABEZR 
//      ");
//      $w=mysql_query($z);

   } elseif ($mnoznik<>0) {	

	  require('specbuf_fill.php');

      $z=("update towary 
       right join specbuf 
               on specbuf.ID_T=towary.ID 
              set towary.STAN=(towary.STAN-($mnoznik*specbuf.ILOSC)) 
            where specbuf.ID_D=$idd 
              and specbuf.ILOSC<>0
              and towary.STATUS<>'U'
      ");
      $w=mysql_query($z);
   }

	$mnP=(-$mnP);
	if ($mnP<>0) {//miejsce w analityce dla nowych danych	//zmiana stanu w MP=$nab
		
		if ($mp==3) {	//INW
		//zmiana stan�w wed�ug mirrora ze spec, gdzie poprzednie stany s� zapami�tane w CENABEZR
			$z=("  
		           update magazyny
		       right join spec 
		               on (
		                       spec.NETTO   =magazyny.ID_F 
		                   and spec.KWOTAVAT=magazyny.ID_D 
		                   and spec.ID_T    =magazyny.ID_T 
		                   and spec.CENA    =magazyny.CENA
		                  ) 
		              set magazyny.STAN=spec.CENABEZR 
		            where spec.ID_D=-$idd 
		    ");
		    $w=mysql_query($z);
		} else {
			if (substr(trim($typ),-1,1)=='K') {   //korekty trzeba na 2 rzuty, tj. pozycje ujemne i dodatnie, bo przy jednym przebiegu nie widzi drugiej zmiany stanu gdy 2 pozycje dotycz� tego samego towaru  
			
			//zmiana stan�w wed�ug mirrora ze spec: By�o
					$z=("  
			              update magazyny
			          right join spec 
			                  on (
			                          spec.NETTO   =magazyny.ID_F 
			                      and spec.KWOTAVAT=magazyny.ID_D 
			                      and spec.ID_T    =magazyny.ID_T 
			                      and spec.CENA    =magazyny.CENA
			                     ) 
			                 set magazyny.STAN=(magazyny.STAN+($mnP*spec.ILOSC)) 
			       ");
			       if ($mp==1) {	// PZ
			 			$z.=("  , magazyny.ZAKUP=(magazyny.ZAKUP+($mnP*spec.ILOSC))");
			       }
			
			//zmiana stan�w wed�ug mirrora ze spec: By�o
			       $w=mysql_query($z." where spec.ID_D=-$idd and spec.ILOSC<0");
			//zmiana stan�w wed�ug mirrora ze spec: Powinno by�
			       $w=mysql_query($z." where spec.ID_D=-$idd and spec.ILOSC>0");
			//sprz�tanie zer�wek
					$z=("  
			              update magazyny
			          right join spec 
			                  on (
			                          spec.NETTO   =magazyny.ID_F 
			                      and spec.KWOTAVAT=magazyny.ID_D 
			                      and spec.ID_T    =magazyny.ID_T 
			                      and spec.CENA    =magazyny.CENA
			                     ) 
			                 set magazyny.ZAKUP=-99999999 
			                   , magazyny.STAN=-99999999
			               where spec.ID_D=-$idd
			                 and magazyny.ZAKUP=0 
			                 and magazyny.STAN=0 
			       ");
			       $w=mysql_query($z);
			       $w=mysql_query("delete from magazyny where magazyny.ZAKUP=-99999999 and magazyny.STAN=-99999999");
			
			 } else {
			//zmiana stan�w wed�ug mirrora ze spec
				$z=("  
			           update magazyny
			       right join spec 
			               on (
			                       spec.NETTO   =magazyny.ID_F 
			                   and spec.KWOTAVAT=magazyny.ID_D 
			                   and spec.ID_T    =magazyny.ID_T 
			                   and spec.CENA    =magazyny.CENA
			                  ) 
			              set magazyny.STAN=(magazyny.STAN+($mnP*spec.ILOSC)) 
			    ");
			    if ($mp==1) {	// nie INW
				$z.=("  
			                , magazyny.ZAKUP=(magazyny.ZAKUP+($mnP*spec.ILOSC)) 
			    ");
			    }
				$z.=("  
			            where spec.ID_D=-$idd 
			              and spec.ILOSC<>0
			    ");
			    $w=mysql_query($z);
			 }
		}
	}

//	$z=("delete 
//          from spec 
//         where ID_D=-$idd
//   ");
//   $w=mysql_query($z);					//specyfikacja mirror sio

	$z=("update dokum 
           set BLOKADA='O'
             , CZAS=Now() 
         where ID=$idd 
         limit 1
   ");
   $w=mysql_query($z);	//finito
//	$komunikat='Dokument otwarty';
}

require('firma_aktualizacja.php');

}

?>
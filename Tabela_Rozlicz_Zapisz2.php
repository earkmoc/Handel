<?php

session_start();

//include('skladuj_zmienne.php');
//exit;

$tabela=$_POST['tabela'];          // zapisz tu
$tabelaa='abonenci';		//$_POST['tabelaa'];        // i id¿ tu
$szukane=$_POST['szukane'];        // zawartosc pola w ktorym stal gdy wcisnal POMOC

$idtab=$_POST['idtab'];            // ID tabeli gdzie dzia³a formularz
$id=$_POST['ID'];                  // ID pozycji w drugiej kolumnie sub czyli IDABONENTA
$op=$_POST['opole'];
$zaznaczone=$_POST['zaznaczone'];
$osoba_id=$_SESSION['osoba_id'];
$punkt=$_SESSION['osoba_pu'];

require('dbconnect.inc');

if ($op=='d') {$op="D";};
if ($op=='f') {$op="";};
if ($op=="") {$idtabeles="";};        // niech nie d³ubie w tabeles na koñcu

$z="select * from tabele where NAZWA='";        // pobierz definicjê formularza
$z.=$tabela;
$z.="'";

$przecinek=false;
$w=mysql_query($z);
if ($w){
        $w=mysql_fetch_array($w);
        $sql=$w['FORMULARZ'];
        if (!$sql) { exit;}
        else {
                $mc=-1;
                $w=explode("\n",$sql);

      $zd="insert into ";
                $zd.=$w[0];
                $zd.=" ( ";

                $za="update ";
                $za.=$w[0];
                $za.=" set ";

                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
                        if (substr($w[$i],0,4)=='from') {;}
                        elseif (substr($w[$i],0,5)=='where') {
                                $l=explode(" ", $w[$i]);							// wersja dla "UPDATE"
                                $za.=' '.$l[0].' '.($l[count($l)-1]).$id;	// where ID=$id
                        }
                        else {
                                $mc++;
                                $l=explode("|",$w[$i]);
                                  $pola[$mc]=trim($l[0]);
                                  $szer[$mc]=trim($l[2]);
                                $wart[$mc]=$_POST[$pola[$mc]];
                                if ((count(explode(".",$pola[$mc]))<2)&&(count(explode("(",$pola[$mc]))<2)) {
                                        if (!$przecinek) {$przecinek=true;}
													 else {$za.=",";}
                                        $za.=$pola[$mc];
                                        if (count(explode("*",$szer[$mc]))>1)        {        // gwiazdka w polu szeroko¶ci
                                                $za.="=password('";
                                                $za.=$wart[$mc];
                                                 $za.="')";
                                        }
                                        elseif (count(explode("t",$szer[$mc]))>1)        {        // datetime w polu szeroko¶ci
                                                $za.="='";
                                                $za.=date('Y-m-d H:i:s');
                                                $za.="'";
                                        }
                                        else {
                                                $za.="='";
//w polu liczbowym mo¿e byæ przecinek
                                                $znak=ord(substr(trim($wart[$mc]),0,1));
// wiêc jeœli pierwsza litera z lewej to minus lub cyfra
                                                if ($znak==45 || (48<=$znak && $znak<=57)) {
                                                        $wart[$mc]=str_replace(',','.',$wart[$mc]);}
                                                $za.=$wart[$mc];
                                                 $za.="'";
                                        }
                                }
                        }
                }
        }
}

if ($op=='D') {
        $n=0;
        for($i=0;$i<=$mc;$i++) {
                if ((count(explode(".",$pola[$i]))<2)&&(count(explode("(",$pola[$i]))<2)) {
                        if ($n++>0) {$zd.=",";}
                        $zd.=$pola[$i];
                }
        }
        $zd.=") ";

        $zd.=" values ( ";
        $n=0;
        for($i=0;$i<=$mc;$i++) {
                if ((count(explode(".",$pola[$i]))<2)&&(count(explode("(",$pola[$i]))<2)) {
                        if ($n++>0) {$zd.=",";}
                        if (count(explode("*",$szer[$i]))>1)        {        // gwiazdka w polu szeroko¶ci
                                $zd.="password('";
                                $zd.=$wart[$i];
                                $zd.="')";
                        }
                        elseif (count(explode("t",$szer[$i]))>1)        {        // datetime w polu szeroko¶ci
                                $zd.="'";
                                $zd.=date('Y-m-d H:i:s');
                                $zd.="'";
                        }
                        else {
                                $zd.="'";
                                $zd.=$wart[$i];
                                $zd.="'";
                        }
                }
        }
        $zd.=") ";
        $za=$zd;
}
$sql='';
$w=mysql_query($za);                // zmiana lub dopisanie pozycji w tabeli
if ($w) {

		$zz="Select ID from tabele where NAZWA='abonenci' limit 1"; $ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
		$zz="Select ID_POZYCJI from tabeles where ID_TABELE=$ww[0] and ID_OSOBY=$osoba_id limit 1"; $ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
		$ida=$ww[0];	// identyfikator abonenta ostatnio u¿ywany przez u¿ytkownika

		$ipole=$id;  // identyfikator wiersza w tabeli "specrozkp"
		$z="Select ID, KWOTA, DATE_FORMAT(DATAPRZYJ,'%Y-%m-%d'), W_NADPLATY, Z_NADPLATY, IDABONENTA, DRUK_KP, GENE_FV, DRUK_FV, TYPDOK, BANK, NRZBIOR from $tabela where ID=$ipole limit 1";
		$w=mysql_fetch_row(mysql_query($z));
		$sql.=$z; $sql.="<br>";
if (substr($w[2],0,1)=='0') {	//data zerowa
		$w[1]=0;						//kwoty te¿
		$w[3]=0;
		$w[4]=0;
}
		$dane['KWPLA']=$w[1];	// tyle wp³acono (kwota rêczna wiêc mo¿e byæ inna ni¿ siê nale¿y)
		$dane['DATAWPLATY']=$w[2];	// wtedy wp³acono
		$dane['DODNIA']=$dane['DATAWPLATY'];
		$dane['DATAPRZYJ']=date('Y.m.d');
		$dane['WNADP']=$w[3];	// kwota w nadp³atach
		$dane['ZNADP']=$w[4];	// tyle wzi¹æ z nadp³at (kwota w nadp³atach mo¿e byæ inna ni¿ ta)
		$dane['ZNADP']=($dane['ZNADP']>$dane['WNADP'] ? $dane['WNADP'] : $dane['ZNADP'] ); // (ale nie mo¿e byæ wiêksza)
		$dane['KWOTA']=$dane['KWPLA']+$dane['ZNADP'];	// do dyspozycji
		$dane['IDOPERATOR']=$osoba_id;
		$dane['IDABONENTA']=$w[5];
		$dane['DRUK_KP']=StrToUpper($w[6]);
		$dane['GENE_FV']=StrToUpper($w[7]);
		$dane['DRUK_FV']=StrToUpper($w[8]);
		$dane['KPLUBBANK']=$w[9];
		$dane['BANK']=$w[10];
		$dane['NRZBIOR']=$w[11];

		if (!$dane['KPLUBBANK']) {	// któryœ bank
			if (!$dane['BANK']&&!$dane['NRZBIOR']) {
				$dane['KPLUBBANK']='0';		// jednak kasa
			}
			else {
				$z="Select ID from banki where NAZWABANKU='".$dane['BANK']."' limit 1";
				$w=mysql_fetch_row(mysql_query($z));	// konkretny
				$sql.=$z; $sql.="<br>";
				if (!$w[0]) {
					$z="Select ID from banki limit 1";	// pierwszy z brzegu
					$w=mysql_fetch_row(mysql_query($z));
					$sql.=$z; $sql.="<br>";
				}
				$dane['KPLUBBANK']=$w[0];			// ID banku
			}
		}

		$z="Select IDGRUPY, NIPABONENT, NAZWA_F, MIEJSC_F, KOD_F, ULICA_F, NAZWISKO, IMIE, ZABLOK from abonenci where ID='";
		$z.=$dane['IDABONENTA'];
		$z.="' limit 1";
		$w=mysql_fetch_array(mysql_query($z));			// mamy IDGRUPY z "abonenci"
		$sql.=$z; $sql.="<br>";

		$dane['IDGRUPY']=trim($w['IDGRUPY']);
		$dane['TYPINST']=(($dane['IDGRUPY']<100) ? substr($dane['IDGRUPY'],0,1) : substr($dane['IDGRUPY'],1,1));
		$dane['RODZADM']=(($dane['IDGRUPY']<100) ? substr($dane['IDGRUPY'],1,1) : substr($dane['IDGRUPY'],2,1));

		$dane['NIP']=trim(StripSlashes($w['NIPABONENT']));
		$dane['NAZWA']=trim(StripSlashes($w['NAZWA_F']));
		$dane['MIASTO']=trim(StripSlashes($w['MIEJSC_F']));
		$dane['KOD']=trim(StripSlashes($w['KOD_F']));
		$dane['ULICA']=trim(StripSlashes($w['ULICA_F']));
		$dane['NAZWISKO']=trim(StripSlashes($w['NAZWISKO']));
		$dane['IMIE']=trim(StripSlashes($w['IMIE']));
		$dane['ZABLOK']=trim(StripSlashes($w['ZABLOK']));

		$suma=0;
		$sumaFV=0;

		$x=explode(',',$zaznaczone);
		for($i=0;$i<count($x);$i++) {
			$z="Select * from specopl where ID='";
			$z.=$x[$i];
			$z.="' limit 1";	// pierwsze zaznaczenie
			$o=mysql_fetch_array(mysql_query($z));		// mamy parê danych z "specopl"
			$sql.=$z; $sql.="<br>";
			if (($o['NRFAKTURY']=='')&&(substr($o['DODNIA'],0,7)==date('Y-m'))) {
				$sumaFV+=abs($o['KWOTA']);					//bêdzie z czego zrobiæ fakturê ?
			}
		}//for($i=0;$i<count($x);$i++)

		if ($dane['GENE_FV']==='T') {						//chcemy generowaæ fakturê
			if ($sumaFV==0) {									//ale jak nie bêdzie z czego, 
				$dane['GENE_FV']='N';						//to nie generujemy, bo by by³o 0
			}
		}

		$sumaFV=0;

		if ($dane['KWOTA']<>0) {

			if ($dane['GENE_FV']==='T') {		//generujemy fakturê
					$z="Select * from typydok where KOD='FA' limit 1";
					$w=mysql_fetch_array(mysql_query($z));		// mamy dane o ostatnim dokumencie z "typydok"
					$sql.=$z; $sql.="<br>";

					$dane['NUMERFV']=trim($w['NUMER']);
					$dane['MASKAFV']=trim($w['MASKA']);

					$z="Select * from typydok where KOD='FA".$punkt."' limit 1";
					$w=mysql_query($z);						// mamy dane ?
					if (!$w||(mysql_num_rows($w)==0)) {	//nie
						$z="insert into typydok values (0,'FA".$punkt."','".$dane['NUMERFV']."',0,'','',NULL,'')";
						$sql.=$z; $sql.="<br>";
						$w=mysql_query($z);
						$z="Select * from typydok where KOD='FA".$punkt."' limit 1";
						$sql.=$z; $sql.="<br>";
						$w=mysql_query($z);				// mamy dane
					}
					$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok"

					$dane['NUMERMFV']=trim($w['NUMERM']);

					$dane['NUMERFV']=($dane['NUMERFV']+1);
					$dane['NUMERMFV']=($dane['NUMERMFV']+1);
					if (substr($dane['DATAWPLATY'],0,7)<>substr($w['DATA'],0,7)) {
						$dane['NUMERMFV']=1;
					}
					$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer FA
					$z.=$dane['NUMERFV'];
					$z.="', NUMERM='";
					$z.=$dane['NUMERMFV'];
					$z.="', DATA='";
					$z.=$dane['DATAWPLATY'];
					$z.="' where KOD='FA' limit 1";
					$w=mysql_query($z);
					$sql.=$z; $sql.="<br>";

					$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer FA
					$z.=$dane['NUMERFV'];
					$z.="', NUMERM='";
					$z.=$dane['NUMERMFV'];
					$z.="', DATA='";
					$z.=$dane['DATAWPLATY'];
					$z.="' where KOD='FA".$punkt."' limit 1";
					$w=mysql_query($z);
					$sql.=$z; $sql.="<br>";

					$dane['NUMERFV']=sprintf("%'06d",$dane['NUMERFV']);

					$z="INSERT INTO faktury values (0,'";		// zapis danych do "faktury"
					$z.=$dane['IDABONENTA'];
					$z.="','".$dane['NUMERFV'];
					$z.="','".'FA';
					$z.="','".substr($dane['DATAWPLATY'],0,4);							// rok
					$z.="','".sprintf("%'02d",substr($dane['DATAWPLATY'],6,2));		// m-c
					$z.="','".$dane['NUMERMFV']."/".$punkt;
					$z.="','".$dane['DATAWPLATY'];
					$z.="','".$dane['DATAWPLATY'];
					$z.="','";
					$z.="','".$dane['IDOPERATOR'];
					$z.="','".'727-012-77-48';
					$z.="','".$dane['DATAWPLATY'];
					$z.="','1";		// gotówka
					$z.="','".$dane['NAZWA'];
					$z.="','".$dane['MIASTO'];
					$z.="','".$dane['KOD'];
					$z.="','".$dane['ULICA'];
					$z.="','".$dane['NIP'];
					$z.="','".$dane['DATAWPLATY'];
					$z.="','".$dane['NAZWISKO'];
					$z.="','".$dane['IMIE'];
					$z.="','".$dane['DRUK_FV'];
					$z.="','".$sumaFV;				//$dane['KWPLA']
					$z.="')";
					$w=mysql_query($z);
					$sql.=$z; $sql.="<br>";

					$dane['ID_FAKTURY']=mysql_insert_id();
			}

			if ($dane['ZNADP']<>0) {

				if ($zaznaczone) {

					$z="Select * from typydok where LITERA='/' limit 1";
					$w=mysql_fetch_array(mysql_query($z));		// mamy ostatni NUMER RN z "typydok"
					$sql.=$z; $sql.="<br>";

					$dane['NUMERRN']=trim($w['NUMER']);
					$dane['MASKARN']=trim($w['MASKA']);

					$dane['NUMERRN']=($dane['NUMERRN']+1);

					$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer RN
					$z.=$dane['NUMERRN'];
					$z.="', DATA='";
					$z.=$dane['DATAWPLATY'];
					$z.="' where LITERA='/' limit 1";
					$w=mysql_query($z);
					$sql.=$z; $sql.="<br>";

					$z="INSERT INTO dokwplat values (0,'";		// zapis danych do "dokwplat"
					$z.='/';
					$z.="','";
					$z.=$dane['NUMERRN'].$dane['MASKARN'];
					$z.="','";
					$z.=$dane['DATAPRZYJ'];
					$z.="','";
					$z.=$dane['IDOPERATOR'];
					$z.="','1','','','";
					$z.=$dane['IDABONENTA'];
					$z.="','";
					$z.=$dane['ZNADP'];
					$z.="','',Now())";
					$w=mysql_query($z);
					$sql.=$z; $sql.="<br>";

					$dane['ID_DOKWPLAT']=mysql_insert_id();
					$dane['KW_DOKWPLAT']=$dane['ZNADP'];

					$x=explode(',',$zaznaczone);
					for($i=0;$i<count($x);$i++) {

						$z="Select * from specopl where ID='";
						$z.=$x[$i];
						$z.="' limit 1";	// pierwsze zaznaczenie
						$o=mysql_fetch_array(mysql_query($z));			// mamy parê danych z "specopl"
						$sql.=$z; $sql.="<br>";

						$bylo=$o['KWOTA'];
						$o['KWOTA']=($o['KWOTA']<=$dane['ZNADP'] ? $o['KWOTA'] : $dane['ZNADP'] );
						$dane['ZNADP']-=$o['KWOTA'];		// tyle pozosta³o do wypykania z nadp³at
						$suma+=$o['KWOTA'];		// tyle powinno byæ zap³acone z nadp³at

						if ($o['KWOTA']<>0) {
							$z='Insert into ';
							$z.=(trim($o['Z_TABELI'])=='oplaty' ? 'wplaty' : 'splaty' );
							$z.=" values ( 0,'";
							$z.=$dane['IDGRUPY'];
							$z.="','";
							$z.=$dane['IDABONENTA'];
							$z.="','";
							$z.=$dane['TYPINST'];
							$z.="','";
							$z.=$dane['RODZADM'];
							$z.="','";
							$z.=$o['TYPTYTULU'];
							$z.="','";
							$z.=$o['ZTYTULU'];
							$z.="','";
							$z.=$o['KWOTA'];
							$z.="','";
							$z.=$dane['NUMERRN'].$dane['MASKARN'];
							$z.="','/','";
							$z.=$dane['DATAWPLATY'];
							$z.="','";
							$z.=$dane['DATAPRZYJ'];
							$z.="','";
							$z.=$o['DODNIA'];
							$z.="','";
							$z.=$osoba_id;
							$z.="','";
							if ($dane['NUMERFV']&&!$o['NRFAKTURY']&&(substr($o['DODNIA'],0,7)==date('Y-m'))) {
								$z.=$dane['NUMERFV'];
								$sumaFV+=$o['KWOTA'];
							}
							else {
								$z.=$o['NRFAKTURY'];
							}
							$z.="','";
							$z.=$o['NRPOZYCJI'];
							$z.="','";
							if (trim($o['Z_TABELI'])=='oplaty') {	// oplaty->wplaty
								$z.=$o['ZAMIESIAC'];
							}
							else {
								$z.=$o['WALUTA'];							// dlugi->splaty
								$z.="','";
								$z.=$o['NRRATY'];
							};
							$z.="','";
							$z.=$o['ID_WTABELI'];
							$z.="')";
							$w=mysql_query($z);					// zapis danych do "wplaty" lub "splaty"
							$sql.=$z; $sql.="<br>";

							$z="Update specopl set KWOTA='";	// zmniejszenie kwot w "specopl"
							$z.=($bylo-$o['KWOTA']);			// ¿eby KP za chwilê po tym nie ³azi³o
							$z.="' where ID='";
							$z.=$x[$i];
							$z.="' limit 1";
							$w=mysql_query($z);
							$sql.=$z; $sql.="<br>";

							$z='Update ';
							$z.=$o['Z_TABELI'];					// 'oplaty' lub 'nadplaty'
							$z.=" set KWOTA='";
							$z.=($bylo-$o['KWOTA']);			// zmniejszenie kwot nale¿nych
							$z.="', NRFAKTURY='";
							if ($dane['NUMERFV']&&!$o['NRFAKTURY']&&(substr($o['DODNIA'],0,7)==date('Y-m'))) {
								$z.=$dane['NUMERFV'];
								$sumaFV+=($bylo-$o['KWOTA']);
							}
							else {
								$z.=$o['NRFAKTURY'];
							}
							$z.="' where ID=";
							$z.=$o['ID_WTABELI'];
							$z.=" limit 1";
							$ww=mysql_query($z);					// zapis danych do "oplaty" lub "dlugi"
							$sql.=$z; $sql.="<br>";

						}//if ($o['KWOTA']<>0)
					}//for($i=0;$i<count($x);$i++)

					if ($suma<>0) {		// odj¹æ z nadp³at

						$z="INSERT INTO nadplaty values (0,'";
						$z.=$dane['IDGRUPY'];
						$z.="','";
						$z.=$dane['IDABONENTA'];
						$z.="','";
						$z.=$dane['TYPINST'];
						$z.="','";
						$z.=$dane['RODZADM'];
						$z.="','";
						$z.=95;				// nadp³ata
						$z.="','";
//if ($dane['ZABLOK']=='T'||$dane['ZABLOK']=='t') {			//nieskie kody
//						$z.=102;				// nadp³ata
//}
//else {
//						$z.=1102;			// nadp³ata
//}
						$z.=26;			// nadp³ata
						$z.="','";
						$z.=(-$suma);
						$z.="','";
						$z.=$dane['NUMERRN'].$dane['MASKARN'];
						$z.="','/','";
						$z.=$dane['DATAWPLATY'];
						$z.="','";
						$z.=$dane['DATAPRZYJ'];
						$z.="','";
						$z.=$dane['DODNIA'];
						$z.="',";
						$z.=$dane['IDOPERATOR'];
						$z.=",'','')";
						$sql.=$z; $sql.="<br>";
						$w=mysql_query($z);					// zapis danych do "nadplaty"
						$sql.=$z; $sql.="<br>";
					}//if ($suma<>0) 
				}//if ($zaznaczone)
			}//if ($dane['ZNADP']<>0)

			$dane['NUMERKP']='';// zaraz mo¿e byæ potrzebny do nadp³aty bez zaznaczenia

			if ($dane['KWPLA']<>0) {						// wp³ata gotówk¹

				if ($zaznaczone) {

					if ($dane['KPLUBBANK']>='1') {	// bank
						$dane['NUMERKP']=trim($dane['NRZBIOR']);
						$dane['MASKAKP']='';

						$z="update typydok set NUMER='";				// zwiêkszamy numer KP
						$z.=$dane['NUMERKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where LITERA='".$dane['KPLUBBANK']."' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";
					}
					else {
						$z="Select * from typydok where LITERA='".$dane['KPLUBBANK']."' limit 1";
						$w=mysql_fetch_array(mysql_query($z));			// mamy ostatni NUMER KP z "typydok"
						$sql.=$z; $sql.="<br>";

						$dane['NUMERKP']=trim($w['NUMER']);
						$dane['MASKAKP']=trim($w['MASKA']);

						$z="Select * from typydok where KOD='KP".$punkt."' limit 1";
						$w=mysql_query($z);						// mamy dane ?
						if (!$w||(mysql_num_rows($w)==0)) {	//nie
							$z="insert into typydok values (0,'KP".$punkt."','".$dane['NUMERKP']."',0,'','',NULL,'')";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);
							$z="Select * from typydok where KOD='KP".$punkt."' limit 1";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);				// mamy dane
						}
						$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok"

						$dane['NUMERKP']=trim($w['NUMER']);		//2006-05-30
						$dane['NUMERMKP']=trim($w['NUMERM']);	//œrenio 6000/m-c => 6000/1/12/2006
						$dane['MASKAKP']='/'.$punkt.'/'.substr($dane['DATAWPLATY'],5,2);	//6000/1/12

						$dane['NUMERKP']=($dane['NUMERKP']+1);
						$dane['NUMERMKP']=($dane['NUMERMKP']+1);
						if (substr($dane['DATAWPLATY'],0,7)<>substr($w['DATA'],0,7)) {
							$dane['NUMERMKP']=1;
						}

						$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='KP' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";
	
						$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='KP".$punkt."' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";

						$dane['NUMERKP']=$dane['NUMERMKP'];		//2006-05-30
					}
					$z="INSERT INTO dokwplat values (0,'";		// zapis danych do "dokwplat"
					$z.=$dane['KPLUBBANK'];
					$z.="','";
					$z.=$dane['NUMERKP'].$dane['MASKAKP'];
					$z.="','";
					$z.=$dane['DATAPRZYJ'];
					$z.="','";
					$z.=$dane['IDOPERATOR'];
					$z.="','1','','','";
					$z.=$dane['IDABONENTA'];
					$z.="','";
					$z.=$dane['KWPLA'];
					$z.="','',Now())";
					$w=mysql_query($z);	// zapis danych do "dokwplat"
					$sql.=$z; $sql.="<br>";

					$dane['ID_DOKWPLAT']=mysql_insert_id();
					$dane['KW_DOKWPLAT']=$dane['KWPLA'];

					$x=explode(',',$zaznaczone);

					for($i=0;$i<count($x);$i++) {

						$z="Select * from specopl where ID='";
						$z.=$x[$i];
						$z.="' limit 1";
						$o=mysql_fetch_array(mysql_query($z));
						$sql.=$z; $sql.="<br>";

						$bylo=$o['KWOTA'];
						$o['KWOTA']=($o['KWOTA']>$dane['KWPLA'] ? $dane['KWPLA'] : $o['KWOTA'] );
						$dane['KWPLA']-=$o['KWOTA'];		// tyle pozosta³o do wypykania z gotówki
						$suma+=$o['KWOTA'];		// tyle powinno byæ zap³acone

						if ($o['KWOTA']<>0) {
							$z='Insert into ';
							$z.=(trim($o['Z_TABELI'])=='oplaty' ? 'wplaty' : 'splaty' );
							$z.=" values ( 0,'";
							$z.=$dane['IDGRUPY'];
							$z.="','";
							$z.=$dane['IDABONENTA'];
							$z.="','";
							$z.=$dane['TYPINST'];
							$z.="','";
							$z.=$dane['RODZADM'];
							$z.="','";
							$z.=$o['TYPTYTULU'];
							$z.="','";
							$z.=$o['ZTYTULU'];
							$z.="','";
							$z.=$o['KWOTA'];
							$z.="','";
							$z.=$dane['NUMERKP'].$dane['MASKAKP'];			// numer o 1 wiêkszy od ostatnio u¿ytego
							$z.="','".$dane['KPLUBBANK']."','";
							$z.=$dane['DATAWPLATY'];
							$z.="','";
							$z.=$dane['DATAPRZYJ'];
							$z.="','";
							$z.=$o['DODNIA'];
							$z.="','";
							$z.=$osoba_id;
							$z.="','";
							if ($dane['NUMERFV']&&!$o['NRFAKTURY']&&(substr($o['DODNIA'],0,7)==date('Y-m'))) {
								$z.=$dane['NUMERFV'];
								$sumaFV+=$o['KWOTA'];
							}
							else {
								$z.=$o['NRFAKTURY'];
							}
							$z.="','";
							$z.=$o['NRPOZYCJI'];
							$z.="','";
							if (trim($o['Z_TABELI'])=='oplaty') {	// oplaty->wplaty
								$z.=$o['ZAMIESIAC'];
							}
							else {
								$z.=$o['WALUTA'];							// dlugi->splaty
								$z.="','";
								$z.=$o['NRRATY'];
							};
							$z.="','";
							$z.=$o['ID_WTABELI'];
							$z.="')";
							$w=mysql_query($z);					// zapis danych do "wplaty" lub "splaty"
							$sql.=$z; $sql.="<br>";

							$z='Update ';
							$z.=$o['Z_TABELI'];
							$z.=" set KWOTA='";
							$z.=($bylo-$o['KWOTA']);			// zmniejszenie kwot nale¿nych
							$z.="', NRFAKTURY='";
							if ($dane['NUMERFV']&&!$o['NRFAKTURY']&&(substr($o['DODNIA'],0,7)==date('Y-m'))) {
								$z.=$dane['NUMERFV'];
								$sumaFV+=($bylo-$o['KWOTA']);
							}
							else {
								$z.=$o['NRFAKTURY'];
							}
							$z.="' where ID=";
							$z.=$o['ID_WTABELI'];
							$z.=" limit 1";
							$ww=mysql_query($z);					// zapis danych do "oplaty" lub "dlugi"
							$sql.=$z; $sql.="<br>";

						}//if ($o['KWOTA']<>0) {
					}//for($i=0;$i<count($x);$i++) {
				}//if ($zaznaczone)
			}//if ($dane['KWPLA']<>0)

			if (!($suma==$dane['KWOTA'])) {		// nadp³ata

				if ($dane['NUMERKP']==='') {	// dot¹d nie mamy numeru dla KP nadp³aty

					if ($dane['KPLUBBANK']>='1') {	// bank
						$dane['NUMERKP']=trim($dane['NRZBIOR']);
						$dane['MASKAKP']='';

						$z="update typydok set NUMER='";				// zwiêkszamy numer KP
						$z.=$dane['NUMERKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where LITERA='".$dane['KPLUBBANK']."' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";
					}
					else {
						$z="Select * from typydok where LITERA='".$dane['KPLUBBANK']."' limit 1";
						$w=mysql_fetch_array(mysql_query($z));			// mamy ostatni NUMER KP z "typydok"
						$sql.=$z; $sql.="<br>";

						$dane['NUMERKP']=trim($w['NUMER']);
						$dane['MASKAKP']=trim($w['MASKA']);

						$z="Select * from typydok where KOD='KP".$punkt."' limit 1";
						$w=mysql_query($z);						// mamy dane ?
						if (!$w||(mysql_num_rows($w)==0)) {	//nie
							$z="insert into typydok values (0,'KP".$punkt."','".$dane['NUMERKP']."',0,'','',NULL,'')";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);
							$z="Select * from typydok where KOD='KP".$punkt."' limit 1";
							$sql.=$z; $sql.="<br>";
							$w=mysql_query($z);				// mamy dane
						}
						$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok"

						$dane['NUMERKP']=trim($w['NUMER']);		//2006-05-30
						$dane['NUMERMKP']=trim($w['NUMERM']);	//œrenio 6000/m-c => 6000/1/12/2006
						$dane['MASKAKP']='/'.$punkt.'/'.substr($dane['DATAWPLATY'],5,2);	//6000/1/12

						$dane['NUMERKP']=($dane['NUMERKP']+1);
						$dane['NUMERMKP']=($dane['NUMERMKP']+1);
						if (substr($dane['DATAWPLATY'],0,7)<>substr($w['DATA'],0,7)) {
							$dane['NUMERMKP']=1;
						}

						$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='KP' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";
	
						$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer KP
						$z.=$dane['NUMERKP'];
						$z.="', NUMERM='";
						$z.=$dane['NUMERMKP'];
						$z.="', DATA='";
						$z.=$dane['DATAWPLATY'];
						$z.="' where KOD='KP".$punkt."' limit 1";
						$w=mysql_query($z);
						$sql.=$z; $sql.="<br>";

						$dane['NUMERKP']=$dane['NUMERMKP'];		//2006-05-30
					}

					$z="INSERT INTO dokwplat values (0,'";		// zapis danych do "dokwplat"
					$z.=$dane['KPLUBBANK'];
					$z.="','";
					$z.=$dane['NUMERKP'].$dane['MASKAKP'];
					$z.="','";
					$z.=$dane['DATAPRZYJ'];
					$z.="','";
					$z.=$dane['IDOPERATOR'];
					$z.="','1','','','";
					$z.=$dane['IDABONENTA'];
					$z.="','";
					$z.=($dane['KWOTA']-$suma);
					$z.="','',Now())";
					$w=mysql_query($z);	// zapis danych do "dokwplat"
					$sql.=$z; $sql.="<br>";

					$dane['ID_DOKWPLAT']=mysql_insert_id();
					$dane['KW_DOKWPLAT']=($dane['KWOTA']-$suma);

				}//if ($dane['NUMERKP']==='')
				else {
					$z="Update dokwplat set KWOTA='";
					$z.=$dane['KW_DOKWPLAT']+$dane['KWOTA']-$suma;
					$z.="' where ID=";
					$z.=$dane['ID_DOKWPLAT'];
					$w=mysql_query($z);	// zapis danych do "dokwplat"
					$sql.=$z; $sql.="<br>";
				}

				$z="INSERT INTO nadplaty values (0,'";		// samotna nadp³ata
				$z.=$dane['IDGRUPY'];
				$z.="','";
				$z.=$dane['IDABONENTA'];
				$z.="','";
				$z.=$dane['TYPINST'];
				$z.="','";
				$z.=$dane['RODZADM'];
				$z.="','";
				$z.=95;				// nadp³ata
				$z.="','";
//if ($dane['ZABLOK']=='T'||$dane['ZABLOK']=='t') {			//niskie kody
//				$z.=102;				// nadp³ata
//}
//else {
//				$z.=1102;			// nadp³ata
//}
				$z.=26;			// nadp³ata
				$z.="','";
				$z.=$dane['KWOTA']-$suma;
				$z.="','";
				$z.=$dane['NUMERKP'].$dane['MASKAKP'];
				$z.="','";
				$z.=$dane['KPLUBBANK'];
				$z.="','";
				$z.=$dane['DATAWPLATY'];
				$z.="','";
				$z.=$dane['DATAPRZYJ'];
				$z.="','";
				$z.=$dane['DODNIA'];
				$z.="',";
				$z.=$dane['IDOPERATOR'];
				$z.=",'','')";
				$sql.=$z; $sql.="<br>";
				$z=mysql_query($z);					// zapis danych do "nadplaty"
			}//(!($suma==$dane['KWOTA']))

			if ($sumaFV&&$dane['ID_FAKTURY']) {
				$z="Update faktury set SUMABRUTTO='";
				$z.=$sumaFV;
				$z.="' where ID=";
				$z.=$dane['ID_FAKTURY'];
				$w=mysql_query($z);					// zapis danych do "faktury"
				$sql.=$z; $sql.="<br>";
			}
		}//if ($dane['KWOTA']<>0)
		else {
			$dane['DRUK_KP']='N';		//jak nie by³o kwoty do dyspozycji, to nie drukujemy nic
		}

$zz="Select sum(nadplaty.WYSWPL) from nadplaty where IDABONENTA=".$dane['IDABONENTA'];
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);

$zz="update abonenci set NADPLATA=".$ww[0]." where ID=".$dane['IDABONENTA']." limit 1";
$ww=mysql_query($zz);

?>
<html>
<head>
<title>Zapis udany</title>
</head>
<?php
	echo '<body bgcolor="#BFD2FF" onload="';
	echo "location.href='";
	if ($szukane) {echo 'Tabela.php?tabela='.$tabelaa.'&szukane='.$szukane.'"'."'";}
	elseif ($dane['DRUK_KP']==='T') {echo 'WydrukWzor.php?sio=tak&natab=abonenci&wzor=KP&ipole='.$dane['ID_DOKWPLAT']."'";}
	else {echo 'Tabela.php?tabela='.$tabelaa."';close();";};
	echo '">';
	echo '</body>';
	echo "\n";
	echo '</html>';
	exit;
}
if (!$w) echo "$za<br  /><br  />niestety nie wysz³o !!!";
//mysql_free_result($w);
require('dbdisconnect.inc');
?>
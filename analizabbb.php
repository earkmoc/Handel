<?php

//okno "StanProcesu" jest ju¿ uruchomione przez "Tabela_Formularz.php" tu¿ przed zapisem formularza i dzia³a

$time=time();
$timm=$time;	//drugi czas do porównañ
$akcja='SIO';	//na koñcu zgaœ okno procesu;

$ipole=($ipole<0?-$ipole:$ipole);

//if ($ipole==0) {
	$z="Select ID from tabele where NAZWA='analizabp'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID master
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
//}

$z="select ID_FIRMY from analizabp where ID=$ipole";
$w=mysql_query($z);
$w=mysql_fetch_row($w);

$z="select ID from dokum where dokum.NABYWCA=".($w[0])." and dokum.TYP='KOR' order by ID desc limit 1";
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$idkor=$w[0];

//if ($idkor) {
$z="update analizabp SET ID_TOWARY=$idkor, UWAGI='', AKCJA='' where ID=$ipole";	//AKCJA mo¿e mieæ 'sio' lub 'stop' z poprzedniego razu
mysql_query($z);	//w ID_TOWARY mamy ID ostatniego KOR nabywcy
$w=mysql_query("select * from analizabp where ID=$ipole"); $w=mysql_fetch_array($w);

$odilukorekt=$w['TYPYDOKR'];
$oddolu=($w['CZY']=='T');

mysql_query("delete from analizab where ID_OSOBYUPR=$ido");	//towary z tego KORa
mysql_query("insert into analizab select 0, $ido, spec.ID_D, spec.ID_T, spec.CENA, spec.ILOSC, spec.RABAT, spec.CENABEZR, 0, 1, dokum.INDEKS, dokum.DATAS, dokum.NUMERFD, dokum.DATAO from spec left join dokum on dokum.ID=spec.ID_D where spec.ID_D=$idkor");

mysql_query("truncate analizabb");	//wszystkie dokumenty typu TYPYDOKP z okresu DATA1-DATA2
$z="insert into analizabb select 0, $ido, spec.ID_D, spec.ID_T, spec.CENA, spec.ILOSC, spec.RABAT, spec.CENABEZR, spec.ILOSC, if(spec.ILOSC>0,1,0), dokum.INDEKS, dokum.DATAS, if(right(dokum.TYP,1)='K',if(substr(dokum.NUMERFD,3,1)=' ',substr(dokum.NUMERFD,4),dokum.NUMERFD),dokum.INDEKS), dokum.DATAO from spec left join dokum on dokum.ID=spec.ID_D where dokum.NABYWCA=".($w['ID_FIRMY'])." and (dokum.DATAS between '".($w['DATA1'])."' and '".($w['DATA2'])."') and FIND_IN_SET(dokum.TYP,'".($w['TYPYDOKP'])."')>0 and dokum.BLOKADA=''";
mysql_query($z);

//trzeba usun¹æ pozycje dotycz¹ce towarów niewystêpuj¹ych na KOR
mysql_query("update analizabb left join analizab on analizab.ID_T=analizabb.ID_T set analizabb.ILOSC=0 where isnull(analizab.ID)");

//trzeba usun¹æ pozycje z zerowymi iloœciami
mysql_query("delete from analizabb where ILOSC=0");

//trzeba usun¹æ korekty o datach z wskazanego okresu do faktur sprzed wskazanego okresu
mysql_query("delete from analizabb where not(DATAO between '".($w['DATA1'])."' and '".($w['DATA2'])."')");

//trzeba dodaæ korekty o datach po wskazanym okresie do faktur o datach z wskazanego okresu 

if (false&&($w['CZY']=='T')) {	//Ceny tylko te wskazane na dokumencie KOR ?

mysql_query("update analizabb left join analizab on (analizabb.ID_T=analizab.ID_T and analizabb.CENA<>analizab.CENA and analizab.ID_OSOBYUPR=$ido) set analizabb.ILOSC=0");
mysql_query("delete from analizabb where ILOSC=0 and ID_OSOBYUPR=$ido");

mysql_query("truncate analizabc");
mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), sum(ILEDOKUM), INDEKS, DATAS, NUMERFD, DATAO from analizabb group by ID_T, CENA");
mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T and analizabc.CENA=analizab.CENA) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");

}
else {

mysql_query("truncate analizabc");	//faktura i korekty do niej jako jeden dokument
mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), 1, INDEKS, DATAS, NUMERFD, DATAO from analizabb group by ID_T,NUMERFD");

mysql_query("truncate analizabb");	//przewa³ka do analizabb tych które jeszcze da siê korygowaæ (ILOSC>0)
mysql_query("insert into analizabb select 0, $ido, ID_D, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILESIEDA, 1, INDEKS, DATAS, NUMERFD, DATAO from analizabc where ILOSC>0");

mysql_query("truncate analizabc");
mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), sum(ILEDOKUM), INDEKS, DATAS, NUMERFD, DATAO from analizabb group by ID_T");
mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");
}

//}	//if ($idkor) {

mysql_query("update analizabp SET CZAS=Now() where ID=$ipole");

$w=mysql_query("select count(*) from analizab where ILOSC+ILESIEDA<0 and ID_OSOBYUPR=$ido");	//da siê w ogóle ?
$w=mysql_fetch_row($w); $w=$w[0];
if ($w>0) {	//nie da siê
	$komunikat='<h1>W zadanym okresie nie wszystko siê da skorygowaæ</h1>';
	$w=mysql_query("select concat('ID: ', ID_T), concat('indeks: ', towary.INDEKS), concat('nazwa: ', towary.NAZWA), concat('korekta o: ', format(ILOSC, 0)), concat('jest: ', format(ILESIEDA,0)), concat('po korekcie: ', format(ILOSC+ILESIEDA,0)) from analizab left join towary on towary.ID=analizab.ID_T where ILOSC+ILESIEDA<0 and ID_OSOBYUPR=$ido");
	while ($r=mysql_fetch_row($w)) {
		for ($i=0;$i<count($r);$i++) {
			$komunikat.=$r[$i].', ';
		}
		$komunikat.='<br>';
	}
}
else {		//da siê

	mysql_query("update analizabp SET ILE='', UWAGI='', WSKAZNIKI='' where ID_OSOBYUPR=$ido");

	mysql_query("truncate analizabf");	//faktura i korekty do niej jako jeden dokument, datami, malej¹co
	mysql_query("insert into analizabf select 0, $ido, ID_D, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILESIEDA, 1, INDEKS, DATAS, NUMERFD, DATAO from analizabb group by NUMERFD order by DATAS desc");

	$w=mysql_query("select count(*) from analizabf");	//max ile korekt ? (tyle ile faktur)
	$w=mysql_fetch_row($w); $n=$w[0]-1;	//wskaŸniki do tablicy od 0 wiêc -1

if ($oddolu) {

if ($odilukorekt==''||$odilukorekt<1) {$odilukorekt=1;}

for ($k=$odilukorekt;$k<=$n+1;$k++) {

	for ($i=0;$i<$k;$i++) {	//dla ka¿dej korekty indeks do faktur po korektach
		$j[$i]=$i;	//indeks od ostatniej pozycji do pocz¹tku (from analizabb order by DATAS,ID)
	}
	$j[$k-1]--;		//zaraz go zwiêkszy

$wariantuj=true;
while ($wariantuj) {
	$przesuwaj=true;
	while (($wariantuj)&&($przesuwaj)) {
		$x=$k-1;
		while (($wariantuj)&&($przesuwaj)&&($x>=0)&&(++$j[$x]>$n+$x-$k+1)) {
			$j[$x-1]++;
			for($i=$x;$i<$k;$i++) {$j[$i]=$j[$i-1]+1;}	//automatyczne ustawianie wskaŸników
			$x--;
			if ($x<0) {			//za daleko
				$przesuwaj=false;	//koniec przesuwania
				$wariantuj=false;	//koniec tego wariantu indeksów do faktur
			}
			else {
				$j[$x]--;		//zaraz go znów zwiêkszy w "while"
			}
		}
		$przesuwaj=false;	//poprzesuwane

		if ($wariantuj) {

//echo "<br>Korekt: $k, czas=".(time()-$time).", wariant wskaŸników: ";

		$kw='';
		mysql_query("truncate analizabd");	//przewa³ka do analizabb tych które jeszcze da siê korygowaæ (ILOSC>0)
		for ($i=0;$i<$k;$i++) {	//dla ka¿dej korekty pobierz pozycje faktury do korekty
			$w=mysql_query("select NUMERFD from analizabf where ID=".($j[$i]+1));
			$w=mysql_fetch_row($w); $w=$w[0];
			mysql_query("insert into analizabd select 0, $ido, ID_D, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILESIEDA, 1, INDEKS, DATAS, NUMERFD, DATAO from analizabb where NUMERFD='$w'");
//			echo ", ".($j[$i]);
			$kw.=(($i>0)?', ':'').($j[$i]);
		}
		mysql_query("update analizabp set WSKAZNIKIR='$kw', CZASR='".(time()-$time)."' where ID_OSOBYUPR=$ido");
		mysql_query("truncate analizabc");	//pokrywa zapotrzebowanie ?
		mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), sum(ILEDOKUM), INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T");
//		mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");

		//s¹ minusy ?
		$w=mysql_query("select count(*) from analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) where (isnull(analizabc.ID) or (analizab.ILOSC+analizabc.ILESIEDA)<0) and analizab.ID_OSOBYUPR=$ido");
		$w=mysql_fetch_row($w); $w=$w[0];

		$ku='';
		if ($w>0) {	//s¹ minusy
//			echo ", nie ...";
			$kk=$k-1;
			$kk=$kk.' '.(($kk==1)?"korekt¹":"korektami");
//			$ku="Zadania <font color='red'>NIE UDA siê</font> zrealizowaæ $kk.";
			$ku="Zadania NIE UDA siê zrealizowaæ $kk.";
			$kw='';
		}
		else {
//			echo ", uda siê !!!";
			$przesuwaj=false;	//koniec przesuwania
			$wariantuj=false;	//koniec tego wariantu indeksów do faktur

			mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");

			$komunikat="Czas analizy: ".(time()-$time)." sekund. Zadanie uda siê zrealizowaæ $k korektami do faktur:";
			for ($i=0;$i<$k;$i++) {	//dla ka¿dej korekty pobierz pozycje faktury do korekty
				$w=mysql_query("select ID_D, INDEKS, DATAS from analizabf where ID=".($j[$i]+1));
				while ($r=mysql_fetch_row($w)) {
					$komunikat.="\nNr: ".($r[1])." Data: ".($r[2]);
				}
			}
			$ku=$komunikat;
			$k=$n+2;		//koniec szukania wariantów
		}//else if ($w>0) {	//s¹ minusy

		if ((time()-$timm)>2) {	//co x sekund sprawdzaj 'stop'
			$timm=time();
			$w=mysql_query("select AKCJA from analizabp where ID=$ipole");
			$w=mysql_fetch_row($w); $w=$w[0];
			if ($w=='STOP') {
				$przesuwaj=false;	//koniec przesuwania
				$wariantuj=false;	//koniec tego wariantu indeksów do faktur
				$k=$n+2;		//koniec szukania wariantów
				$akcja='';
			}
		}

		if ($k<>$n+2) {mysql_query("update analizabp SET ILE=$k where ID_OSOBYUPR=$ido");}
		if ($ku<>'') {mysql_query("update analizabp SET UWAGI='$ku' where ID_OSOBYUPR=$ido");}
		if ($kw<>'') {mysql_query("update analizabp SET WSKAZNIKI='$kw' where ID_OSOBYUPR=$ido");}

		}//if ($wariantuj) {
	}//while (($wariantuj)&&($przesuwaj)) {
}//while ($wariantuj) {
}//for ($k=1;$k<=$n;$k++) {	//ile korekt
}//if ($oddolu)

else {

if ($odilukorekt==''||$odilukorekt>$n+1) {$odilukorekt=$n+1;}
$komunikat="Zadania nie uda siê zrealizowaæ $odilukorekt korektami";
$boja=0;

for ($k=$odilukorekt;$k>0;$k--) {	//ile korekt od góry

	$udane=false;		//ta iloœæ korekt jeszcze nieudana

	for ($i=0;$i<$k;$i++) {	//dla ka¿dej korekty indeks do faktur po korektach
		$j[$i]=$i;	//indeks od ostatniej pozycji do pocz¹tku (from analizabb order by DATAS,ID)
	}
	if ($boja>0) {$j[$k-1]=$boja;}	//dobrze rokuje z poprzedniego uk³adu wskaŸników
	$j[$k-1]--;		//zaraz go zwiêkszy

$wariantuj=true;
while ($wariantuj) {
	$przesuwaj=true;
	while (($wariantuj)&&($przesuwaj)) {
		$x=$k-1;
		while (($wariantuj)&&($przesuwaj)&&($x>=0)&&(++$j[$x]>$n+$x-$k+1)) {
			$j[$x-1]++;
			for($i=$x;$i<$k;$i++) {$j[$i]=$j[$i-1]+1;}	//automatyczne ustawianie wskaŸników
			$x--;
			if ($x<0) {			//za daleko
				$przesuwaj=false;	//koniec przesuwania
				$wariantuj=false;	//koniec tego wariantu indeksów do faktur
			}
			else {
				$j[$x]--;		//zaraz go znów zwiêkszy w "while"
			}
		}
		$przesuwaj=false;	//poprzesuwane

		if ($wariantuj) {

//echo "<br>Korekt: $k, czas=".(time()-$time).", wariant wskaŸników: ";

		$kw='';
		mysql_query("truncate analizabd");	//przewa³ka do analizabb tych które jeszcze da siê korygowaæ (ILOSC>0)
		for ($i=0;$i<$k;$i++) {	//dla ka¿dej korekty pobierz pozycje faktury do korekty
			$w=mysql_query("select NUMERFD from analizabf where ID=".($j[$i]+1));
			$w=mysql_fetch_row($w); $w=$w[0];
			mysql_query("insert into analizabd select 0, $ido, ID_D, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILESIEDA, 1, INDEKS, DATAS, NUMERFD, DATAO from analizabb where NUMERFD='$w'");
//			echo ", ".($j[$i]);
			$kw.=(($i>0)?', ':'').($j[$i]);
		}
		mysql_query("update analizabp SET WSKAZNIKIR='$kw', CZASR='".(time()-$time)."' where ID_OSOBYUPR=$ido");
		mysql_query("truncate analizabc");	//pokrywa zapotrzebowanie ?
		mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), sum(ILEDOKUM), INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T");
//		mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");

		//s¹ minusy ?
		$w=mysql_query("select count(*) from analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) where (isnull(analizabc.ID) or (analizab.ILOSC+analizabc.ILESIEDA)<0) and analizab.ID_OSOBYUPR=$ido");
		$w=mysql_fetch_row($w); $w=$w[0];

		$ku='';
		if ($w>0) {	//s¹ minusy
//			echo ", nie ...";
			if ($boja>0) {
				$boja=0;		//boja do dupy
				if ($k>1) {$j[$k-1]=$j[$k-2];}	//leæ od pocz¹tku
				else {$j[$k-1]=-1;}	//zaraz go zwiêkszy do zera
			}
		}
		else {
			$udane=true;
//			echo ", uda siê !!!";
			$komunikat="Zadanie uda siê zrealizowaæ $k korektami do faktur:";
			for ($i=0;$i<$k;$i++) {	//dla ka¿dej korekty pobierz pozycje faktury do korekty
				$w=mysql_query("select ID_D, INDEKS, DATAS from analizabf where ID=".($j[$i]+1));
				while ($r=mysql_fetch_row($w)) {
					$komunikat.="\nNr: ".($r[1])." Data: ".($r[2]);
				}
			}
			$boja=$j[$k-1];		//nowa boja
			$przesuwaj=false;	//koniec przesuwania
			$wariantuj=false;	//koniec tego wariantu indeksów do faktur

			mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");
			$ku=$komunikat;

		}//else if ($w>0) {	//s¹ minusy

		if ((time()-$timm)>2) {	//co x sekund sprawdzaj 'stop'
			$timm=time();
			$z="select AKCJA from analizabp where ID=$ipole";
			$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];
			if ($w=='STOP') {
				$przesuwaj=false;	//koniec przesuwania
				$wariantuj=false;	//koniec tego wariantu indeksów do faktur
				$k=0;			//koniec szukania wariantów
				$ku='';
				$akcja='';
			}
		}

		if ($k<>0) {mysql_query("update analizabp SET ILE=$k where ID_OSOBYUPR=$ido");}
		if ($ku<>'') {mysql_query("update analizabp SET UWAGI='$ku', WSKAZNIKI='$kw' where ID_OSOBYUPR=$ido");}

		}//if ($wariantuj) {
	}//while (($wariantuj)&&($przesuwaj)) {
}//while ($wariantuj)

if (!$udane) {$k=0;}		//koniec szukania wariantów, bo ostatnio nie by³o ani jednego udanego

}//for ($k=$n+1;$k>0;$k--)	//ile korekt od góry

$komunikat="Czas analizy: ".(time()-$time)." sekund. ".$komunikat;

}//else if ($oddolu)
}//else if ($w>0)		//da siê

mysql_query("update analizabp SET ILE='', AKCJA='$akcja' where ID_OSOBYUPR=$ido");
$z="select WSKAZNIKI from analizabp where ID=$ipole";
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

if ($w) {
//	$komunikat='';		//bez sygna³ów bo bêd¹ korekty
	$j=explode(',',$w);
	$k=count($j);
	mysql_query("truncate analizabd");	//przewa³ka do analizabb tych które jeszcze da siê korygowaæ (ILOSC>0)
	for ($i=0;$i<$k;$i++) {			//dla ka¿dej korekty pobierz pozycje faktury do korekty
		$w=mysql_query("select NUMERFD from analizabf where ID=".($j[$i]+1));
		$w=mysql_fetch_row($w); $w=$w[0];
		mysql_query("insert into analizabd select 0, $ido, ID_D, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILESIEDA, 1, INDEKS, DATAS, NUMERFD, DATAO from analizabb where NUMERFD='$w'");
	}
	mysql_query("truncate analizabc");	//pokrywa zapotrzebowanie ?
	mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), sum(ILEDOKUM), INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T");
	mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");

	$ipole=0;
	for ($i=0;$i<$k;$i++) {			//dla ka¿dej korekty pobierz pozycje faktury do korekty
		$w=mysql_query("select ID_D from analizabf where ID=".($j[$i]+1));
		$w=mysql_fetch_row($w); $w=$w[0];
		$idfv=$w;	//id faktury do skorygowania

		$z="select NUMER, NAZWA, MASKA, Now(), CurTime() from doktypy where TYP='FVK' limit 1";
		$w=mysql_query($z);$r=mysql_fetch_row($w);
//		$nrfvk='auto lub '.($r[0]+1).$r[2];
		$nrfvk=($r[0]+1).$r[2].' lub auto';
		$dt=$r[3];
		$ti=$r[4];

		$z="select * from dokum where ID=$idfv";$w=mysql_query($z);$r=mysql_fetch_row($w);
		$nr=$r[2].' '.$r[3];
		$zd=$r[7];

		$z="insert into dokum values (0";
		for ($n=1;$n<count($r);$n++) {$z.=",'".($r[$n])."'";}
		$z.=")";$w=mysql_query($z);
		$idfvk=mysql_insert_id();
		$z="update dokum set BLOKADA='O', TYP='FVK', INDEKS='$nrfvk', DATAW='$dt', DATAS='$dt', DATAT='$dt', NUMERFD='$nr', DATAO='$zd', CZAS='$ti', WPLACONO=0, VAT23=0, VAT22=0, VAT8=0, VAT7=0, VAT5=0, NETTO23=0, NETTO22=0, NETTO8=0, NETTO7=0, NETTO5=0, NETTO0=0, NETTOZW=0, NETTOCZ=0, WARTOSC=0 where ID=$idfvk";$w=mysql_query($z);

		$z="insert into spec select 0, $idfvk, spe.ID_T, spe.CENA, -spe.ILOSC, spe.RABAT, spe.CENABEZR from spec as spe where spe.ID_D=$idfv and spe.ILOSC<>0";$w=mysql_query($z);

		mysql_query("truncate analizabd");
		mysql_query("insert into analizabd select 0, $ido, $idfvk, ID_T, CENA, ILOSC, RABAT, CENABEZR, 0, 0, 0, '', '', '' from spec where spec.ID_D=$idfv and spec.ILOSC<>0");

		mysql_query("truncate analizabc");
		mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILOSC), 0, INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T");

		mysql_query("update analizabc left join analizab on (analizabc.ID_T=analizab.ID_T) set analizabc.ILOSC=analizabc.ILOSC+analizab.ILOSC where analizab.ID_OSOBYUPR=$ido");
		mysql_query("update analizabc set ILOSC=0 where analizabc.ILOSC<0");
		mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILOSC=analizab.ILOSC+(analizabc.ILESIEDA-analizabc.ILOSC) where analizabc.ILESIEDA<>analizabc.ILOSC and analizab.ID_OSOBYUPR=$ido");

		mysql_query("insert into spec select 0, $idfvk, ID_T, CENA, ILOSC, RABAT, CENABEZR from analizabc");

		$ipole=mysql_insert_id();
		require('spec_FVKP.end');
	}
}

mysql_query("update analizab left join spec on (spec.ID_D=$idkor and analizab.ID_T=spec.ID_T) set analizab.ILOSC=spec.ILOSC");

mysql_query("truncate analizabb");
mysql_query("truncate analizabc");
mysql_query("truncate analizabd");
mysql_query("truncate analizabf");

$tabelaa='analizab';	// tu l¹duje po akcji

$w=true;
?>

<?php

//generowanie faktur ETAPAMI - tabele uczestnicz¹ce:
//fakturyza2 - tabela parametrów generowania: daty, typy op³at, raport z wynikami, czasy
//typoplatgb - tabela wybranych typów op³at do fakturowania
//specbuf4 - tabela ³adowana pozycjami faktur (np.: ZAMIESIAC='2006.11') z tabel wplaty i oplaty
//fakturyrob - wstêpne dane do faktur: dane abonentów z punktu, wystêpuj¹cych w specbuf4
//fakturyro2 - niezerowe faktury niesortowane
//fakturyrob - koñcowe faktury posortowane po ci¹g³ym ID
//typydok2 - numery faktur
//fakturyro2 - gotowe faktury do zapisu do 'faktury'

$raport='';
$raportn=0;

$z='update fakturyzak set ';
$z.="ID_OSOBYUPR=$ido,";
$z.="CZAS=Now()";
$z.=" where ID=$ipole limit 1";
$w=mysql_query($z);

$z="Select * from fakturyzak where ID=$ipole";
$w=mysql_query($z);
$p=mysql_fetch_array($w);
$a=str_replace('-','.',$p['MC1'])*1;
$b=str_replace('-','.',$p['MC2'])*1;

$t=$p['RAPORT'];				//raport o wybranych typach op³at: "ile : identyfikatory,"
if ($t*1<=0) {exit;}			//brak wybranych typów op³at
$to=explode(':',$t);			//podzia³
$to=$to[1];						//identyfikatory
$to=explode('.',$to);		//w tablicy

$z="TRUNCATE TABLE `typoplatgb`"; $w=mysql_query($z);	//tabela wybranych typów op³at - bufor 
for($i=0;$i<count($to);$i++) {		// kolejne wybrane typy op³at
	$x=$to[$i];
	$z="INSERT into `typoplatgb` select typoplat.ZTYTULU, stawkvat2.OPISSTVAT from typoplat left join stawkvat2 on stawkvat2.ZTYTULU=typoplat.ZTYTULU where typoplat.ZTYTULU=$x;"; $w=mysql_query($z);
}

$z="DELETE from typoplatgb where UPPER(OPISSTVAT)='BRAK'";
$w=mysql_query($z);

function Zapisz($z,$ido,&$raport,$ipole,&$raportn) {
$raportn++;
//$zz="SELECT Count(*) FROM specbuf4 WHERE ID_OSOBYUPR=$ido"; $w=mysql_query($zz);
//$w=mysql_fetch_row($w);			//jeœli coœ jest
//$n=$w[0];
//if ($n>0) {
	$raport.=$z."\n";
//	$raport.=$n."\n";
	$z='update `fakturyzak` set ';
	$z.="`CZASEND`=Now(), ";
	$z.="`RAPORT`='".$raportn.': '.AddSlashes($raport)."'";
	$z.=" where `ID`='$ipole' limit 1";
	$w=mysql_query($z);
//}
//if ($n>1) {exit;}					//to sprawdziæ "specbuf4"
}

//$z="LOCK TABLES specbuf4 WRITE"; $w=mysql_query($z);

for($i=$a;$i<=$b;) {		// kolejne miesi¹ce

$ro=sprintf('%s',floor($i));	// 2006
$mc=sprintf('%s',100*(round($i-floor($i),2)));	// 03
$zm=sprintf('%.2f',$i);	// 2006.03
$po=sprintf('%3d',round($i-2000,2)*100);	// 2006.03->6.03->603
$d=$zm.'.15';				// 2006.03.15

// g³ówna pêtla generowania zapisów miesiêcznych	<=============

//wycofanie ostatniej operacji (z wskazanego roku i miesi¹ca)
//$z="UPDATE wplaty SET NRFAKTURY='', NRPOZYCJI='' WHERE NRPOZYCJI=$po"; $w=mysql_query($z);
//$z="UPDATE oplaty SET NRFAKTURY='', NRPOZYCJI='' WHERE NRPOZYCJI=$po"; $w=mysql_query($z);
//DELETE FROM faktury where faktury.DATAWYSTAW>="2005-11-30";
//UPDATE wplaty SET NRFAKTURY='', NRPOZYCJI='' WHERE NRPOZYCJI>500;
//UPDATE oplaty SET NRFAKTURY='', NRPOZYCJI='' WHERE NRPOZYCJI>500;

//$z="DELETE FROM specbuf4 WHERE ID_OSOBYUPR=$ido"; $w=mysql_query($z);
$z="TRUNCATE TABLE `specbuf4`"; $w=mysql_query($z);

//if (!$p['IDGRUPY'] || $p['IDGRUPY']=='1') {
//$z="INSERT INTO specbuf4 SELECT NULL, $ido, splaty.ZTYTULU, splaty.ID, 'splaty', left(splaty.DATAWPLATY,7), splaty.KPLUBBANK, splaty.NRDOKUM, splaty.IDABONENTA, splaty.WYSWPL, splaty.WYSWPL, '', 0, splaty.WYSWPL, splaty.WYSWPL FROM splaty,typoplatgb WHERE splaty.ZTYTULU=typoplatgb.ID and splaty.NRFAKTURY='' and left(splaty.DATAWPLATY,7)>='2005-03' and left(splaty.DATAWPLATY,7)<='2006-12'"; $w=mysql_query($z);
//}
//if (!$p['IDGRUPY'] || $p['IDGRUPY']=='2') {
//$z="INSERT INTO specbuf4 SELECT NULL, $ido, dlugi.ZTYTULU, dlugi.ID, 'dlugi', left(dlugi.DODNIA,7), '', ''               , dlugi.IDABONENTA, dlugi.KWOTA, dlugi.KWOTA, '', 0, dlugi.KWOTA, dlugi.KWOTA FROM dlugi,typoplatgb WHERE dlugi.ZTYTULU=typoplatgb.ID and dlugi.NRFAKTURY='' and left(dlugi.DODNIA,7)>='2005-03' and left(dlugi.DODNIA,7)<='2006-12'"; $w=mysql_query($z);
//}
//if (!$p['IDGRUPY'] || $p['IDGRUPY']=='3') {
//$z="INSERT INTO specbuf4 SELECT NULL, $ido, wplaty.ZTYTULU, wplaty.ID, 'wplaty', wplaty.ZAMIESIAC, wplaty.KPLUBBANK, wplaty.NRDOKUM, wplaty.IDABONENTA, wplaty.WYSWPL, wplaty.WYSWPL, '', 0, wplaty.WYSWPL, wplaty.WYSWPL FROM wplaty,typoplatgb WHERE (wplaty.ZTYTULU=typoplatgb.ID and wplaty.NRFAKTURY='' and wplaty.ZAMIESIAC>='2005.03' and wplaty.ZAMIESIAC<='2006.11')"; $w=mysql_query($z);
//}
//if (!$p['IDGRUPY'] || $p['IDGRUPY']=='4') {
//$z="INSERT INTO specbuf4 SELECT NULL, $ido, oplaty.ZTYTULU, oplaty.ID, 'oplaty', oplaty.ZAMIESIAC, '', ''            , oplaty.IDABONENTA, oplaty.KWOTA,  oplaty.KWOTA,  '', 0, oplaty.KWOTA,  oplaty.KWOTA  FROM oplaty,typoplatgb WHERE (oplaty.ZTYTULU=typoplatgb.ID and oplaty.NRFAKTURY='' and oplaty.ZAMIESIAC>='2005.03' and oplaty.ZAMIESIAC<='2006.11')"; $w=mysql_query($z);
//}

if (!$p['IDGRUPY'] || $p['IDGRUPY']=='1') {
$z="INSERT INTO specbuf4 SELECT NULL, $ido, splaty.ZTYTULU, splaty.ID, 'splaty', left(splaty.DATAWPLATY,7), '', splaty.NRFAKTURY, splaty.IDABONENTA, splaty.WYSWPL, splaty.WYSWPL, '', 0, splaty.WYSWPL, splaty.WYSWPL FROM splaty,typoplatgb WHERE splaty.ZTYTULU=typoplatgb.ID and splaty.NRFAKTURY<>'' and left(splaty.DATAWPLATY,7)>='2006-11' and left(splaty.DATAWPLATY,7)<='2006-12'"; $w=mysql_query($z);
}
if (!$p['IDGRUPY'] || $p['IDGRUPY']=='2') {
$z="INSERT INTO specbuf4 SELECT NULL, $ido, dlugi.ZTYTULU, dlugi.ID, 'dlugi', left(dlugi.DODNIA,7), '', dlugi.NRFAKTURY, dlugi.IDABONENTA, dlugi.KWOTA, dlugi.KWOTA, '', 0, dlugi.KWOTA, dlugi.KWOTA FROM dlugi,typoplatgb WHERE dlugi.ZTYTULU=typoplatgb.ID and dlugi.NRFAKTURY<>'' and left(dlugi.DODNIA,7)>='2006-11' and left(dlugi.DODNIA,7)<='2006-12'"; $w=mysql_query($z);
}
if (!$p['IDGRUPY'] || $p['IDGRUPY']=='3') {
$z="INSERT INTO specbuf4 SELECT NULL, $ido, wplaty.ZTYTULU, wplaty.ID, 'wplaty', wplaty.ZAMIESIAC, '', wplaty.NRFAKTURY, wplaty.IDABONENTA, wplaty.WYSWPL, wplaty.WYSWPL, '', 0, wplaty.WYSWPL, wplaty.WYSWPL FROM wplaty,typoplatgb WHERE (wplaty.ZTYTULU=typoplatgb.ID and wplaty.NRFAKTURY<>'' and wplaty.ZAMIESIAC>='2006.11' and wplaty.ZAMIESIAC<='2006.11')"; $w=mysql_query($z);
}
if (!$p['IDGRUPY'] || $p['IDGRUPY']=='4') {
$z="INSERT INTO specbuf4 SELECT NULL, $ido, oplaty.ZTYTULU, oplaty.ID, 'oplaty', oplaty.ZAMIESIAC, '', oplaty.NRFAKTURY, oplaty.IDABONENTA, oplaty.KWOTA,  oplaty.KWOTA,  '', 0, oplaty.KWOTA,  oplaty.KWOTA  FROM oplaty,typoplatgb WHERE (oplaty.ZTYTULU=typoplatgb.ID and oplaty.NRFAKTURY<>'' and oplaty.ZAMIESIAC>='2006.11' and oplaty.ZAMIESIAC<='2006.11')"; $w=mysql_query($z);
}

$z="delete from specbuf4 where CENANETTO=0"; $w=mysql_query($z);

$z="TRUNCATE TABLE `fakturyrob`"; $w=mysql_query($z);

//$z="INSERT INTO fakturyrob SELECT NULL, abonenci.ID, 0, 'FA', $ro, $mc, 0, '".$p['DATAUSLUGI']."', '".$p['DATAWYSTAW']."', '', $ido, '727-012-77-48', '".$p['DATAZAPL']."', '".$p['SPOSOBZAPL']."', abonenci.NAZWA_F, abonenci.MIEJSC_F, abonenci.KOD_F, abonenci.ULICA_F, abonenci.NIPABONENT, '".$p['DATAODB']."', '', '', 'N', specbuf4.WARTOSCBRUTTO from specbuf4,abonenci where specbuf4.ILOSC=abonenci.ID and if(".$punkt."=1,abonenci.ZABLOK!='T',abonenci.ZABLOK='T') order by abonenci.IDGRUPY, abonenci.IDULICY, abonenci.NRDOMU, abonenci.NRMIESZK"; $w=mysql_query($z);
$z="INSERT INTO fakturyrob SELECT NULL, abonenci.ID, 0, 'FA', $ro, $mc, 0, '".$p['DATAUSLUGI']."', '".$p['DATAWYSTAW']."', '', $ido, '727-012-77-48', '".$p['DATAZAPL']."', '".$p['SPOSOBZAPL']."', abonenci.NAZWA_F, abonenci.MIEJSC_F, abonenci.KOD_F, abonenci.ULICA_F, abonenci.NIPABONENT, '".$p['DATAODB']."', '', '', 'N', specbuf4.WARTOSCBRUTTO from specbuf4,abonenci where specbuf4.ILOSC=abonenci.ID order by abonenci.IDGRUPY, abonenci.IDULICY, abonenci.NRDOMU, abonenci.NRMIESZK"; $w=mysql_query($z);

$z="TRUNCATE TABLE `fakturyro2`"; $w=mysql_query($z);
$z="INSERT INTO fakturyro2 SELECT ID, IDABONENTA, NRFAKTURY, TYPDOKVAT, ROKDOKVAT, MSCDOKVAT, NRDOKVAT, DATAUSLUGI, DATAWYSTAW, IDFAKTANUL, IDOPWYSTAW, NIPFIRMY, DATAZAPL, SPOSOBZAPL, NAZWAABON, MIEJSCABON, KODABON, ULICAABON, NIPABONENT, DATAODB, NAZODB, IMIEODB, DRUKOWANO, sum(SUMABRUTTO) from fakturyrob group by IDABONENTA having sum(SUMABRUTTO)<>0"; $w=mysql_query($z);

$z="TRUNCATE TABLE `fakturyrob`"; $w=mysql_query($z);
$z="INSERT INTO fakturyrob SELECT NULL, IDABONENTA, NRFAKTURY, TYPDOKVAT, ROKDOKVAT, MSCDOKVAT, NRDOKVAT, DATAUSLUGI, DATAWYSTAW, IDFAKTANUL, IDOPWYSTAW, NIPFIRMY, DATAZAPL, SPOSOBZAPL, NAZWAABON, MIEJSCABON, KODABON, ULICAABON, NIPABONENT, DATAODB, NAZODB, IMIEODB, DRUKOWANO, SUMABRUTTO from fakturyro2 order by ID"; $w=mysql_query($z);

$z="Select * from typydok where KOD='FA' limit 1";
$w=mysql_fetch_array(mysql_query($z));		// mamy dane o ostatnim dokumencie z "typydok"

$dane['NUMERFV']=trim($w['NUMER']);		//narastaj¹co
$dane['MASKAFV']=trim($w['MASKA']);

$z="Select * from typydok2 where KOD='FA".$punkt."' limit 1";
$w=mysql_query($z);						// mamy dane ?
$w=mysql_fetch_array($w);		// mamy dane o ostatnim dokumencie z "typydok2"

$dane['NUMERMFV']=trim($w['NUMERM']);	//w miesi¹cu
if (substr($p['DATAWYSTAW'],0,7)<>substr($w['DATA'],0,7)) {$dane['NUMERMFV']=0;}

$z="TRUNCATE TABLE `fakturyro2`"; $w=mysql_query($z);
$z="INSERT INTO fakturyro2 SELECT ID, IDABONENTA, LPAD(".$dane['NUMERFV']."+ID,6,'0'), TYPDOKVAT, ROKDOKVAT, MSCDOKVAT, concat(".$dane['NUMERMFV']."+ID,'/".$punkt."'), DATAUSLUGI, DATAWYSTAW, IDFAKTANUL, IDOPWYSTAW, NIPFIRMY, DATAZAPL, SPOSOBZAPL, NAZWAABON, MIEJSCABON, KODABON, ULICAABON, NIPABONENT, DATAODB, NAZODB, IMIEODB, DRUKOWANO, SUMABRUTTO from fakturyrob"; $w=mysql_query($z);

//$z="UPDATE wplaty,fakturyro2,typoplatgb SET wplaty.NRFAKTURY=fakturyro2.NRFAKTURY, wplaty.NRPOZYCJI=$po WHERE (wplaty.ZTYTULU=typoplatgb.ID and wplaty.NRFAKTURY='' and wplaty.ZAMIESIAC='$zm' and wplaty.IDABONENTA=fakturyro2.IDABONENTA)"; $w=mysql_query($z);
//$z="UPDATE oplaty,fakturyro2,typoplatgb SET oplaty.NRFAKTURY=fakturyro2.NRFAKTURY, oplaty.NRPOZYCJI=$po WHERE (oplaty.ZTYTULU=typoplatgb.ID and oplaty.NRFAKTURY='' and oplaty.ZAMIESIAC='$zm' and oplaty.IDABONENTA=fakturyro2.IDABONENTA)"; $w=mysql_query($z);

$z="SELECT count(*) from `fakturyro2`"; $w=mysql_query($z);
$w=mysql_fetch_array(mysql_query($z));		// mamy dane o iloœci dokumentów w "fakturyro2"
$x=$w[0];

//$z="UPDATE typydok SET NUMER=".($x+$dane['NUMERFV']).", DATA='".$p['DATAWYSTAW']."' where KOD='FA' limit 1";; $w=mysql_query($z);
//$z="UPDATE typydok SET NUMER=".($x+$dane['NUMERFV']).", DATA='".$p['DATAWYSTAW']."' where KOD='FA".$punkt."' limit 1";; $w=mysql_query($z);

$raport.=$x.' faktur o numerach:'."\n";
$raport.=($dane['NUMERFV']+1)." - ".($dane['NUMERFV']+$x).' narastaj±co'."\n";
$raport.=($dane['NUMERMFV']+1)." - ".($dane['NUMERMFV']+$x).' w miesi±cu'."\n";

//$z="INSERT INTO faktury SELECT NULL, IDABONENTA, NRFAKTURY, TYPDOKVAT, ROKDOKVAT, MSCDOKVAT, NRDOKVAT, DATAUSLUGI, DATAWYSTAW, IDFAKTANUL, IDOPWYSTAW, NIPFIRMY, DATAZAPL, SPOSOBZAPL, NAZWAABON, MIEJSCABON, KODABON, ULICAABON, NIPABONENT, DATAODB, NAZODB, IMIEODB, DRUKOWANO, SUMABRUTTO from fakturyro2"; $w=mysql_query($z);

if(100*(round($i-floor($i),2))==12) {$i=$i+0.89;} else {$i=$i+0.01;}

}	//for($i=$a;$i<=$b;)

//$z="UNLOCK TABLES"; $w=mysql_query($z);

//$raport="Uwag: $raportn\n\n$raport";
$raport=AddSlashes($raport."\n".$t);

$z='update `fakturyzak` set ';
$z.="`CZASEND`=Now(), ";
$z.="`RAPORT`='$raport'";
$z.=" where `ID`='$ipole' limit 1";
$w=mysql_query($z);
?>

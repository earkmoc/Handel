<?php

session_start();
$ido=$_SESSION['osoba_id'];
$oso=$_SESSION['osoba_upr'];
$punkt=$_SESSION['osoba_pu'];

require('dbconnectr.inc');
//require('skladuj_zmienna.php');

$stop='';
$filename = "C:/Arrakis/Wydruki$punkt/Stop";
if (file_exists($filename)) {
	$stop='SIO';
}

$s='';
$filename = "C:/Arrakis/Wydruki$punkt/Answer";
if (file_exists($filename)) {
	$handle = fopen($filename, "r");
	$row=0;
	while (($data = fgetcsv($handle, 99, ",")) !== FALSE) {
	    $num = count($data);
	    $row++;
	    $s=$s.(($row==1?"":",").trim($data[0]));
	}
	fclose($handle);
//	unlink($filename);
//echo $s;exit;
}

$ra='';
$filename = "C:/Arrakis/Wydruki$punkt/Raport";
if (file_exists($filename)) {
	$handle = fopen($filename, "r");
	$row=0;
	while (($data = fgetcsv($handle, 99, ",")) !== FALSE) {
	    $num = count($data);
	    $row++;
	    $ra=$ra.(($row==1?"":",").trim($data[0]));
	}
	fclose($handle);
//	unlink($filename);
}

 	$z="select ILE, UWAGI, AKCJA, WSKAZNIKI, WSKAZNIKIR, CZASR from analizabp where ID_OSOBYUPR=$ido limit 1";
	$w=mysql_query($z);
	$r=mysql_fetch_row($w);

if ($stop=='SIO') {	//zatrzymanie si³¹ mysz¹ po buttonie
	$r[2]='SIO';
}
if ($r[2]=='SIO') {
 	$z="update analizabp set AKCJA='' where AKCJA='SIO'";
	mysql_query($z);
}
if ($ra<>'') {
	$r=explode(',',$ra);
}
if ($s<>'') {
 	$z="select ID_TOWARY from analizabp where ID_OSOBYUPR=$ido limit 1"; $w=mysql_query($z); $r=mysql_fetch_row($w);
	$idkor=$r[0];

 	$z="select NABYWCA from dokum where ID=$idkor limit 1"; $r=mysql_fetch_row(mysql_query($z));
	$idnab=$r[0];

	$j=explode(',',$s);
	$k=count($j);

 	$z="update analizabp set ILE=".($k+1).", UWAGI='', AKCJA='', WSKAZNIKI='$s' where ID_OSOBYUPR=$ido limit 1";
	mysql_query($z);
	$r[2]='ANSW';

	mysql_query("truncate  analizabd");
	for ($i=0;$i<$k;$i++) {			//dla ka¿dej korekty pobierz pozycje faktury do korekty
		$w=mysql_query("select NUMERFD from analizabf where ID=".($j[$i]+1));
		$w=mysql_fetch_row($w); $w=$w[0];
		mysql_query("insert into analizabd select 0, $ido, ID_D, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILESIEDA, 1, INDEKS, DATAS, NUMERFD, DATAO from analizabb where NUMERFD='$w'");
	}
	mysql_query("truncate  analizabc");	//pokrywa zapotrzebowanie ?
	mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), sum(ILEDOKUM), INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T");
	mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");

	$ipole=0;
	for ($i=0;$i<$k;$i++) {			//dla ka¿dej korekty pobierz pozycje faktury do korekty
		$z="select NUMERFD, DATAO from analizabf where ID=".($j[$i]+1);
		$w=mysql_query($z);
		$w=mysql_fetch_row($w); 
		$x=$w[1]; $w=$w[0];	//data i numer faktury korygowanej
 		$nrfv=$w;
 
		$z="select ID from dokum where TYP='FV ' and INDEKS='$w' limit 1";	// and DATAS='$x'
		$w=mysql_query($z);
		$w=mysql_fetch_row($w); $w=$w[0];
		$idfv=$w;		//id faktury korygowanej

		$z="select NUMER, NAZWA, MASKA, Now(), CurTime() from doktypy where TYP='FVK' limit 1";
		$w=mysql_query($z);$r=mysql_fetch_row($w);
		$nrfvk=($r[0]+1).$r[2].' lub auto';
		$dt=$r[3];
		$ti=$r[4];

		$z="select * from dokum where ID=$idfv";$w=mysql_query($z);$r=mysql_fetch_row($w);
		$nr=$r[2].' '.$r[3];
		$zd=$r[7];
//FVK
		$z="insert into dokum values (0";
		for ($n=1;$n<count($r);$n++) {$z.=",'".($r[$n])."'";}
		$z.=")";$w=mysql_query($z);
		$idfvk=mysql_insert_id();
		$z="update dokum set UWAGI='zwrot towaru', WYSTAWIL='$oso', BLOKADA='O', TYP='FVK', INDEKS='$nrfvk', DATAW='$dt', DATAS='$dt', DATAT='$dt', NUMERFD='$nr', DATAO='$zd', CZAS='$ti', WPLACONO=0, VAT23=0, VAT22=0, VAT8=0, VAT7=0, VAT5=0, NETTO23=0, NETTO22=0, NETTO8=0, NETTO7=0, NETTO5=0, NETTO0=0, NETTOZW=0, NETTOCZ=0, WARTOSC=0 where ID=$idfvk";$w=mysql_query($z);

//FVK_Spec By³o: to samo co FV tylko iloœci z minusem
//		mysql_query("insert into spec select 0, $idfvk, spe.ID_T, spe.CENA, -spe.ILOSC, spe.RABAT, spe.CENABEZR from spec as spe where spe.ID_D=$idfv and spe.ILOSC<>0");

		mysql_query("truncate  analizabd");	//specyfikacja faktury korygowanej
		mysql_query("insert into analizabd select 0, $ido, $idfvk, ID_T, CENA, ILOSC, RABAT, CENABEZR, 0, 0, 0, '', '', '' from spec where spec.ID_D=$idfv and spec.ILOSC<>0");// echo $z."\n\n";

		mysql_query("truncate  analizabc");	//specyfikacja dla korekty to pogrupowana specyfikacja faktury korygowanej
		mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILOSC), 0, INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T,CENA");// echo $z."\n\n";
		mysql_query("update analizabc left join analizabb on analizabb.ID_T=analizabc.ID_T and analizabb.CENA=analizabc.CENA set analizabc.ILOSC=analizabb.ILESIEDA, analizabc.ILESIEDA=analizabb.ILESIEDA where analizabb.NUMERFD='$nrfv'");// echo $z."\n\n";

		mysql_query("insert into spec select 0, $idfvk, ID_T, CENA, -ILOSC, RABAT, CENABEZR from analizabc");// echo $z."\n\n";

//FVK_Spec Ma byæ:
		mysql_query("truncate  analizabd");	//specyfikacja faktury korygowanej
		mysql_query("insert into analizabd select 0, $ido, $idfvk, ID_T, CENA, ILOSC, RABAT, CENABEZR, 0, 0, 0, '', '', '' from spec where spec.ID_D=$idfv and spec.ILOSC<>0");// echo $z."\n\n";

		mysql_query("truncate  analizabc");	//specyfikacja dla korekty to pogrupowana specyfikacja faktury korygowanej
		mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILOSC), 0, INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T,CENA");// echo $z."\n\n";
		mysql_query("update analizabc left join analizabb on analizabb.ID_T=analizabc.ID_T and analizabb.CENA=analizabc.CENA set analizabc.ILOSC=analizabb.ILESIEDA, analizabc.ILESIEDA=analizabb.ILESIEDA where analizabb.NUMERFD='$nrfv'");// echo $z."\n\n";

		mysql_query("truncate  analizabd");	//specyfikacja pogrupowana ID_T,CENA
		mysql_query("insert into analizabd select 0, $ido, $idfvk, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILOSC, 0, INDEKS, DATAS, NUMERFD, DATAO from analizabc");// echo $z."\n\n";

//zmiany w specyfikacji "analizabc" wed³ug zlecenia korekty w "analizab"
		mysql_query("update analizabc left join analizab on (analizabc.ID_T=analizab.ID_T and analizabc.CENA=analizab.CENA) set analizabc.ILOSC=analizabc.ILOSC+analizab.ILOSC where analizab.ID_OSOBYUPR=$ido");// echo $z."\n\n";
		mysql_query("update analizabc set ILOSC=0 where analizabc.ILOSC<0");// echo $z."\n\n";
		mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T and analizabc.CENA=analizab.CENA) set analizab.ILOSC=analizab.ILOSC+(analizabc.ILESIEDA-analizabc.ILOSC) where analizabc.ILESIEDA<>analizabc.ILOSC and analizab.ID_OSOBYUPR=$ido");// echo $z."\n\n";

		mysql_query("insert into spec select 0, $idfvk, ID_T, CENA, ILOSC, RABAT, CENABEZR from analizabc");// echo $z."\n\n";

		$ipole=mysql_insert_id();
		require('spec_FVKP.end');
	}	//for ($i=0;$i<$k;$i++) 	//dla ka¿dej korekty pobierz pozycje faktury do korekty

	mysql_query("truncate  analizabd");	//przewa³ka do analizabd tych które jeszcze da siê korygowaæ (ILOSC>0)
	for ($i=0;$i<$k;$i++) {			//dla ka¿dej korekty pobierz pozycje faktury do korekty
		$w=mysql_query("select NUMERFD from analizabf where ID=".($j[$i]+1));
		$w=mysql_fetch_row($w); $w=$w[0];
		mysql_query("insert into analizabd select 0, $ido, ID_D, ID_T, CENA, ILOSC, RABAT, CENABEZR, ILESIEDA, 1, INDEKS, DATAS, NUMERFD, DATAO from analizabb where NUMERFD='$w'");
	}
	mysql_query("truncate  analizabc");	//pokrywa zapotrzebowanie ?
	mysql_query("insert into analizabc select 0, $ido, ID_D, ID_T, CENA, sum(ILOSC), RABAT, CENABEZR, sum(ILESIEDA), sum(ILEDOKUM), INDEKS, DATAS, NUMERFD, DATAO from analizabd group by ID_T,CENA");// echo $z."\n\n";
	mysql_query("update analizab left join analizabc on (analizabc.ID_T=analizab.ID_T) set analizab.ILESIEDA=analizabc.ILESIEDA, analizab.ILEDOKUM=analizabc.ILEDOKUM where analizab.ID_OSOBYUPR=$ido");// echo $z."\n\n";

	mysql_query("update analizab left join spec on (spec.ID_D=$idkor and analizab.ID_T=spec.ID_T) set analizab.ILOSC=spec.ILOSC");// echo $z."\n\n";
	mysql_query("truncate  analizabb");
	mysql_query("truncate  analizabf");
	$r[2]='ANSW';

//exit;

}	//if ($s<>'')
require('dbdisconnect.inc');	//Windows-1250    iso-8859-2
?>
<html>
<head>

<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=Windows-1250">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />

<title>Stan procesu<?php for($i=0;$i<90;$i++) {echo '&nbsp;';}?></title>

<script type="text/javascript" language="JavaScript">
<!--
function Stop() {
	open("StopProces.php",'','top=250,left=250,height=250,width=500');
	window.close();
}
-->
</script>

</head>

<?php
if ($r[2]=='SIO') {
	echo '<body onload="window.close();">';
}
elseif ($r[2]=='ANSW') {
	echo '<body onload="opener.location.href=\'Tabela.php?tabela=analizab\';close();">';
}
else {
?>

<body bgcolor="#EFEFCF" onload='setTimeout("location.reload();", 3000);'>
Obecnie testowana iloœæ korekt: <input type="text" size="1" value="<?php echo $r[0];?>"/>, 
czas: <input type="text" size="2" value="<?php echo $r[5];?>"/>, 
wskaŸniki do faktur:<br>
<input type="text" size="75" value="<?php echo $r[4];?>"/>
<br>
Raport:<br>
<textarea rows='10' cols='57'><?php echo $r[1];?></textarea>
<br>
Znalezione rozwi¹zanie (wskaŸniki do faktur): <input type="text" value="<?php echo $r[3];?>"/>
<input type="button" value="Stop" onclick="Stop();">

<?php
}	//else
?>

</body>
</html>
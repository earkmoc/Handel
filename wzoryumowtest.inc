<?php
session_start();

$wzor=$_GET['wzor'];		//read only, czyli do wydruku lub obejrzenia
$ipole=$_POST['ipole'];										//wzoryumow

if ($wzor=='undefined') {$wzor='';}

$wzor=str_replace('%20',' ',$wzor);

if (!$wzor) { echo '<body bgcolor="#FFFFFF" onload="dokument.pole1.focus()">';}
else {        echo '<body bgcolor="#FFFFFF" onload="window.print()">';}

echo '<br style="font-size: 12pt">';

if (!$wzor) {

$natab=$_POST['natab'];										//wzoryumow

$z="Select ID from tabele where NAZWA='$natab' limit 1";		//ID tabeli wzoryumow
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$w=$w[0];

$z='Select ID_POZYCJI from tabeles where ID_OSOBY=';			//ID w tabeli wzoryumow
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE='.$w;
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$id=$w[0];

$z="Select TEKST from wzoryumow where ID=$id limit 1";		//tekst z wzoryumow
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$w=StripSlashes($w[0]);
//$w=$w[0];

}

else {		//if (!$wzor) {

$z="Select TEKST, ID from wzoryumow where NAZWA='$wzor' limit 1";		//tekst z wzoryumow
$w=mysql_query($z);
$w=mysql_fetch_row($w);

$id=$w[1];
$w=StripSlashes($w[0]);

}

$z="Select NAZWA, FORMAT, TEKST from wzoryumows where ID_WZORYUMOW=$id order by ID";
$wynik=mysql_query($z);													//specyfikacja pól tego wzoru
while ($wiersz=mysql_fetch_array($wynik)) {
	$f=StripSlashes($wiersz['FORMAT']);
	$z=StripSlashes($wiersz['TEKST']);
	$z=str_replace('ID_master',$ipole,$z);
	$z=str_replace('osoba_id',$_SESSION['osoba_id'],$z);
	if ($z=='osoba_upr') {
		$q[0]=$_SESSION['osoba_upr'];}
	else {
		$qq=explode(';',$z);	// mo¿e byæ kilka zapytañ
		$i=0;
		do {
//echo '...'.$qq[$i].'...';
			$q=mysql_query($qq[$i]);
			if (strtoupper(substr(trim($qq[$i]),0,6))=='SELECT') {
				$q=mysql_fetch_row($q);
				$i++;
				if ($i<count($qq)) {
					for ($j=0;$j<count($q);$j++) {		// korzystaj¹ ze swoich wyników
						$qq[$i]=str_replace('['.$j.']',$q[$j],$qq[$i]);
					}
				}
			}
			else {
				$i++;
			}
		} while ($i<count($qq));
	}
	if ($f) {	// format: "%' +30s"
		if (substr($f,3,1)=='+') {		//centrowanie
			$x=substr($f,4);				//30
			$q[0]=substr(trim($q[0]),0,$x);
			$q[0]=str_pad($q[0],$x,substr($f,2,1),STR_PAD_BOTH);
			$w=str_replace(StripSlashes($wiersz['NAZWA']),$q[0],$w);
		}
		else {
			$w=str_replace(StripSlashes($wiersz['NAZWA']),sprintf($f,$q[0]),$w);
		}
	}
	else {
		$w=str_replace(StripSlashes($wiersz['NAZWA']),$q[0],$w);
	}	
}

//$w=str_replace('"20CPI"',Chr(167),$w);		//Chr(27).'M'.Chr(27).Chr(15),$w);
//$w=str_replace('"E0"',Chr(168),$w);			//Chr(27).'F',$w);
//$w=str_replace('"E1"',Chr(147),$w);			//Chr(27).'E',$w);

$w=str_replace('"10CPI"',Chr(144),$w);		//Chr(18).Chr(27).Chr(80),$w);	//1
$w=str_replace('"12CPI"',Chr(162),$w);		//Chr(27).'M',$w);					//1
$w=str_replace('"15CPI"',Chr(164),$w);		//Chr(27).'g',$w);					//1
$w=str_replace('"17CPI"',Chr(136),$w);		//Chr(15),$w);							//1
$w=str_replace('"W0"',Chr(129),$w);			//Chr(27).'W0',$w);					//1
$w=str_replace('"W1"',Chr(131),$w);			//Chr(27).'W1',$w);					//1

if ($wzor) {
	$z="Select * from umowy where ID=$ipole limit 1";
	$q=mysql_query($z); $q=mysql_fetch_array($q);

	$z="Select * from umowy where IDABONENTA=".$q['IDABONENTA']." and IDUMOWY=".$q['IDUMOWY']." and Left(CZAS,18)=Left('".$q['CZAS']."',18)";
//	$z="Select * from umowy where IDABONENTA=".$q['IDABONENTA']." and IDUMOWY=".$q['IDUMOWY']."";
	$q=mysql_query($z);

	while ($r=mysql_fetch_array($q)) {
		$w=str_replace('name="'.$r['ZMIENNA'].'"','name="'.$r['ZMIENNA'].'"/>'.$r['WARTOSC'].'<input',$w);
	}

	$w=str_replace('<input','<input style="border:0px" disabled="disabled"',$w);
	$w=str_replace('<input','<input type="hidden"',$w);
	$w=str_replace('type="submit"','type="hidden"',$w);
}

//$w=str_replace('"EJE"',Chr(128),$w);		//Chr(12),$w);
//$w=str_replace('"INI"',Chr(138),$w);		//Chr(27).'@',$w);

//$w=str_replace(' ','&nbsp;',$w);
//$w=nl2br($w);

//echo $w;
//echo $w;

$tmpfname='bufumowa.php';
$file=fopen($tmpfname,"w");
if (!$file) {
    echo "<p>Nie mo¿na otworzyæ pliku do zapisu.\n";
    exit;
}
fputs($file,$w);
fclose($file);

require($tmpfname);
?>
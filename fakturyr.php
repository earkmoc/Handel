<?php

session_start();

$ido=$_SESSION['osoba_id'];

$z="Select ID from tabele where NAZWA='abonenci'";
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytego

$wynik[0]=$w;
$wynik[1]='automat';			//nr
$wynik[2]='FA';
$wynik[3]=date('Y');			//rok
$wynik[4]=date('m');			//mc
$wynik[5]='automat';			//nr w m-cu
$wynik[6]='727-012-77-48';	//NIP RetSta1
$wynik[7]='';					//id fakt anul
$wynik[8]=$ido;				//id operator
$wynik[9]=date('Y.m.d');	//dt us³.
$wynik[10]=date('Y.m.d');	//dt wyst.
$wynik[11]=date('Y.m.d');	//dt zap³aty
$wynik[12]='1';				//spos zap³.

$suma=0;
if (!$zaznaczone) {
	$wynik[13]='';
	$wynik[14]='';
	$wynik[15]='';
	$wynik[16]='';
	$wynik[17]='';
	$wynik[18]='';
	$wynik[19]='';
	$wynik[20]='';
	$wynik[21]='N';
	$wynik[13]="Brak wybranych pozycji";
	$mybgcolor="red";
	$posx=14;				// ustaw kursor w 4 polu formularza
}
else {
	$z="Select * from abonenci where ID=$w";						//s¹ dane abonenta
	$w=mysql_query($z); $w=mysql_fetch_array($w);

	$wynik[13]=trim(StripSlashes($w['NAZWA_F']));
	$wynik[14]=trim(StripSlashes($w['NIPABONENT']));
	$wynik[15]=trim(StripSlashes($w['KOD_F']));
	$wynik[16]=trim(StripSlashes($w['MIEJSC_F']));
	$wynik[17]=trim(StripSlashes($w['ULICA_F']));
	$wynik[18]=date('Y.m.d');
	$wynik[19]=trim(StripSlashes($w['NAZWISKO']));
	$wynik[20]=trim(StripSlashes($w['IMIE']));
	$wynik[21]='T';	//druk

	$x=count(explode(',',$zaznaczone));
	if ($zaznaczone) {
		$x=explode(',',$zaznaczone);
		for($i=0;$i<count($x);$i++) {
			$zz="Select ID, KWOTA from specoplf where ID=$x[$i] limit 1";
			$ww=mysql_query($zz);
			$ww=mysql_fetch_row($ww);
			$suma+=$ww[1];
		}
	}
	$posx=22;				// ustaw kursor w polu formularza
}

$wynik[22]=$suma;

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo '$natabb="specoplf";'; echo "\n";		// l¹dowanie po Esc w formularzu
echo '-->'; echo "\n";
echo '</script>'; echo "\n";
?>

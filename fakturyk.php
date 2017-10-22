<?php

session_start();

$ido=$_SESSION['osoba_id'];

$z="Select ID from tabele where NAZWA='fakturyA'";
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID faktury
$w=mysql_query($z); $w=mysql_fetch_row($w); $f=$w[0];								//ostatnio u¿ytej

$z="Select NRFAKTURY from faktury where ID=$f";										//jest NR faktury
$w=mysql_query($z); $w=mysql_fetch_row($w); $f=$w[0];								//ostatnio u¿ytej

$z="Select ID from tabele where NAZWA='abonenci'";
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $ida=$w[0];							//ostatnio u¿ytego

$wynik[0]=$ida;
$wynik[1]='automat';			//nr
$wynik[2]='FK';
$wynik[3]=date('Y');			//rok
$wynik[4]=date('m');			//mc
$wynik[5]='automat';			//nr w m-cu
$wynik[6]='727-012-77-48';	//NIP RetSta1
$wynik[7]=$f;					//id fakt anul
$wynik[8]=$ido;				//id operator
$wynik[9]=date('Y.m.d');	//dt us³.
$wynik[10]=date('Y.m.d');	//dt wyst.
$wynik[11]=date('Y.m.d');	//dt zap³aty
$wynik[12]='1';				//spos zap³.

$z="Select sum(round(if(PKWIU='By³o',-1,1)*ILOSC*CENABRUTTO,2)) from specbufk where ID_OSOBYUPR=$ido";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$suma=$w[0];

if (!$suma) {
	$wynik[13]='';
	$wynik[14]='';
	$wynik[15]='';
	$wynik[16]='';
	$wynik[17]='';
	$wynik[18]='';
	$wynik[19]='';
	$wynik[20]='';
	$wynik[21]='N';
	$wynik[13]="Brak korekt pozycji";
	$mybgcolor="red";
	$posx=14;				// ustaw kursor w 4 polu formularza
}
else {
	$z="Select * from abonenci where ID=$ida";						//s¹ dane abonenta
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

	$posx=22;				// ustaw kursor w polu formularza
}

$wynik[22]=$suma;

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo '$natabb="specbufk";'; echo "\n";		// l¹dowanie po Esc w formularzu
echo '-->'; echo "\n";
echo '</script>'; echo "\n";
?>

<?php
session_start();
?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">
<meta http-equiv="Reply-to" content="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<title>"Abonenci"
<?php
if ($_SESSION['osoba_upr']) {
echo ': ';
echo $_SESSION['osoba_upr'];
//echo ' (';
//echo $_SESSION['osoba_id'];
//echo ')';
}
?>
</title>

<style type="text/css">
<!--
.nag {font-size: 20pt; background-color: #Ff6600};
.zaz {font-size: 16pt; background-color: #FFCC33};
.nor {font-size: 12pt; background-color: #FFFFCC};

#f0 {POSITION: absolute; VISIBILITY: hidden; TOP:0px; LEFT: 0px; Z-INDEX:1;}
#f1 {POSITION: absolute; VISIBILITY: visible; TOP:10px; LEFT: 10px; Z-INDEX:2;}

-->
</style>

<script type="text/javascript" language="JavaScript">
<!--
var r, rr, rrr, c, cc, str, tnag, cnag, twie, cwie;

<?php

require('dbconnect.inc');

//********************************************************************
// zapamiętaj stan tabeli dla zalogowanej osoby
// gdy nie suwanie po tabeli i zalogowany i przed chwilą był w tabeli

$opole=$_POST['opole'];
if (($opole!="S")&&$_SESSION['osoba_upr']&&$_POST['ipole']) {

$z='Select ID from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$_POST['idtab'];

$w=mysql_query($z);
if ($w) {
	if (mysql_num_rows($w)>0) {

		$w=mysql_fetch_array($w);

		$z='Update tabeles';
		$z.=' set NR_STR=';
		$z.=$_POST['strpole'];
		$z.=', NR_ROW=';
		$z.=$_POST['rpole'];
		$z.=', NR_COL=';
		$z.=$_POST['cpole'];
		$z.=', ID_POZYCJI=';
		$z.=$_POST['ipole'];
		$z.=' where ID=';
		$z.=$w['ID'];
	}
	else {
		$z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL) values (';
		$z.=$_SESSION['osoba_id'];
		$z.=',';
		$z.=$_POST['idtab'];
		$z.=',';
		$z.=$_POST['ipole'];
		$z.=',';
		$z.=$_POST['strpole'];
		$z.=',';
		$z.=$_POST['rpole'];
		$z.=',';
		$z.=$_POST['cpole'];
		$z.=')';
	}
	$w=mysql_query($z);
}}

// zapamiętaj stan tabeli dla zalogowanej osoby
//********************************************************************

$tabela=$_POST['natab'];			// tabela slave aktywna
echo '$tabela="'.$tabela.'";';
echo "\n";
	
$tnag='"#FFCC33"';
echo '$tnag='.$tnag.';';
echo "\n";
	
$cnag='"#FF6600"';
echo '$cnag='.$cnag.';';
echo "\n";
	
$twie='"#FFFFCC"';
echo '$twie='.$twie.';';
echo "\n";
	
$cwie='"#FFCC66"';
echo '$cwie='.$cwie.';';
echo "\n";
	
//zmienne Java Script
//********************************************************************
?>
function enter(){
	if (event.keyCode==27) {
<?php
echo 'Adres(';
echo "'".$tabela."'".');';
echo "\n";
?>
	}
}
document.onkeypress=enter;
function Adres($ko){
	f0.sutab.value="";					//czyść, bo to koniec chodzenia po subtabeli slave
if (isNaN($ko)) {							// nazwa tabeli
	f0.natab.value=$ko;
	f0.action="Tabela.php";
	f0.odswiez.click();
}
else { // $ko=1 => numer kolumny zawierającej id tabeli
	f0.natab.value=f0.ipole.value;
	f0.action="Tabela.php";
	f0.odswiez.click();
}}
function Start(){};
-->
</script>

</head>

<?php

	$phpini=trim($_POST['phpini']);        // reszta pól
	if ($phpini=='undefined') {$phpini='';}
	if ($phpini) {
		include($phpini);
	}
?>

<?php
echo '<form id="f0" action="Tabela.php?tabela='.$tabela.'" method="post">';echo "\n";
//type="hidden" 
?>
<input type="hidden" id="natab" name="natab" value=""/>
<input type="hidden" id="batab" name="batab" value=""/>
<?php
echo '<input id="sutab"    type="hidden" name="sutab"    value="'.$tabelaa.'"/>';echo "\n";
echo '<input id="sutabpol" type="hidden" name="sutabpol" value="'.$tabelap.'"/>';echo "\n";
echo '<input id="sutabmid" type="hidden" name="sutabmid" value="'.$tabelai.'"/>';echo "\n";
?>
<input type="hidden" id="idtab" name="idtab" value=""/>
<input type="hidden" id="ipole" name="ipole" value=""/>
<?php
//echo '<input id="fpole" value=""/>';
?>
<input type="hidden" id="opole" name="opole" value=""/>
<input type="hidden" id="strpole" name="strpole" value=""/>
<input type="hidden" id="rpole" name="rpole" value=""/>
<input type="hidden" id="cpole" name="cpole" value=""/>
<input type="hidden" id="kpole" name="kpole" value=""/>
<input type="hidden" id="rrpole" name="rrpole" value=""/>
<input               id="odswiez" type="submit" value=""/>
</form>

<?php
//<form id="f1">
//echo '<input type="button" value="Esc=wyjście" onclick="Adres(';
//echo "'".$tabela."'".')"/>';
//echo "\n";
//</form>
?>
</body>
</html>

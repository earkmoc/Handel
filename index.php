<?php

$synchronizuj=false;
$new=(date('Y-m-d')<='2012-10-31');
//@ini_set("session.gc_maxlifetime","28800");

session_start();

if ($_GET['fiskalna']<>'') {$_SESSION['fiskalna']=$_GET['fiskalna'];}
$fiskalna=$_SESSION['fiskalna'];
$fiskalna=false;

require('dbconnect.inc');

function getIP()
{
	$ip_address=$_SERVER['HTTP_X_FORWARDED_FOR'];
	if ($ip_address==NULL){
		$ip_address=$_SERVER[REMOTE_ADDR]; }
		return $ip_address;
}

if ($_GET['punkt']>0) {$_SESSION['osoba_pu']=$_GET['punkt'];}

if (!$_SESSION['osoba_upr']) {
	echo '<script type="text/javascript" language="JavaScript">'."\n";
	echo '<!--'."\n";
	echo 'location.href="Tabela.php?tabela=osoby";'."\n";
	echo '-->'."\n";
	echo '</script>."\n"';
	exit;
} else {

	if ($_SESSION['osoba_se']) {
		$ttab="#D00000";
	} else {
		$ttab="#0F4F9F";	//"#D0DCE0";
	}

	$tnag="#EFEFDF";
	$cnag="#CCCCCC";	//"#FF6600";
	$twie="#F5F5F5";	//#FFFFFF";
	$cwie="#FFCC66";
	$mysz="#B0D0E0";

	$tyt='menu g³ówne';
	if ($_SESSION['osoba_upr']) {$tyt=$_SESSION['osoba_upr'];}
	if ($_SESSION['osoba_se']) {
		$tyt="<font style='background-color:white;'>&nbsp;&nbsp;&nbsp;&nbsp;BAZA TESTOWA - $tyt&nbsp;&nbsp;&nbsp;&nbsp;</font>";
	} else {
		$tyt="Handel ver 2011.02 - $tyt, stanowisko Nr ".$_SESSION['osoba_pu'].$_SESSION['osoba_dos'];
	}

	?>
<html>
<head>
<meta http-equiv="refresh" content="1200" >
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />

	<?php echo "<title>$tyt</title>";?>

<style type="text/css">
<!--
body {
	font-family: arial
}

a {
	text-decoration: none;
	font-style: normal
}

td {
	line-height: 12pt
}

.nagtab {
	background: <?php echo $cnag; ?>;
	font-family: arial, sans serif;
	font-size: 12pt
}
-->
</style>

<script type="text/javascript" language="JavaScript">
<!--

var r, m;

r=1;
m=1;

<?php

//mysql_query("update spec set STAWKAVAT='8%' where STAWKAVAT=' 8%'");
//mysql_query("update spec set STAWKAVAT='7%' where STAWKAVAT=' 7%'");
//mysql_query("update spec set STAWKAVAT='5%' where STAWKAVAT=' 5%'");
//mysql_query("update spec set STAWKAVAT='3%' where STAWKAVAT=' 3%'");
//mysql_query("update spec set STAWKAVAT='0%' where STAWKAVAT=' 0%'");
mysql_query("update dokum set WPLACONO=WARTOSC where TYP IN ('ZAM','ZNI','INW')");
//mysql_query("update dokum set BRUTTODOS=WARTOSC where TYP not IN ('PZ','PZK')");  //za d³ugo liczy

$z=("
	SELECT WARTOSC
        ,timediff(Now(),WARTOSC)
      from parametry
     where NAZWA='int2fak'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$czas_int2fak=$r[0];				//ostatni czas synchronizacji internet -> faktury
  if ($_SESSION['osoba_pu']==1) { //tylko serwer
    if ($r[1]*1>0) {  // jeœli dawniej ni¿ godzina od ostatniej synchronizacji
      $synchronizuj=true;
//      header('Location: int2fakpre.php?automat=on');
//      exit;
    }
  }  
}

$z=("
	SELECT WARTOSC
      from parametry
     where NAZWA='int_akt'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$czas_int_akt=$r[0];				//ostatni czas synchronizacji Handel -> internet
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='FI'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_FI=$r[0];				//ilo¶æ otwartych dokumentów FI
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='PI'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_PI=$r[0];				//ilo¶æ otwartych dokumentów PI
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='FM'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_FM=$r[0];				//ilo¶æ otwartych dokumentów FM
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='FMK'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_FMK=$r[0];				//ilo¶æ otwartych dokumentów FMK
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='PM'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_PM=$r[0];				//ilo¶æ otwartych dokumentów PM
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='PM'
	   and WARTOSC<>WPLACONO
	   and BLOKADA<>'A'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ilenPM=$r[0];				//ilo¶æ nierozliczonych dokumentów PM
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='PMK'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_PMK=$r[0];				//ilo¶æ otwartych dokumentów PMK
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='FA'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_FA=$r[0];				//ilo¶æ otwartych dokumentów FA
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='FAK'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_FAK=$r[0];				//ilo¶æ otwartych dokumentów FAK
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='PA'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_PA=$r[0];				//ilo¶æ otwartych dokumentów PA
}

$z=("
	SELECT count(*)
      from dokum
     where TYP='PAK'
	   and BLOKADA='O'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$ile_PAK=$r[0];				//ilo¶æ otwartych dokumentów PAK
}

$z=("
	SELECT INDEKS
      from dokum
     where TYP='FM'
	   and DATAW>'2011-06-30'
	   group by INDEKS
	   having count(*)>1
");
$dubel_FM='';
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$dubel_FM.=(($dubel_FM=='')?'':',').$r[0];				//zdublowany numer faktury FM
}


$w=mysql_query("
  select INDEKS 
    from towary
   where STATUS='T'
 group by INDEKS
   having count(*)>1
");
$iledubli=mysql_num_rows($w);

//********************************************************************
// zapamiêtaj stan tabeli dla zalogowanej osoby
$ido=$_SESSION['osoba_id'];
$idt=$_POST['idtab'];
if ($ido) {
	if ($idt) {
		$ipole=$_POST['ipole'];
		$str=$_POST['strpole'];
		$r=$_POST['rpole'];
		$c=$_POST['cpole'];
		$ox=$_POST['offsetX'];
		$oy=$_POST['offsetY'];
		$w=mysql_query("select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido"); $w=mysql_fetch_row($w);
		if ($w[0]>0) 	{$w=mysql_query(     "update tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c,OX_POZYCJI=$ox,OY_POZYCJI=$oy where ID_TABELE=$idt and ID_OSOBY=$ido limit 1");}
		else 		{$w=mysql_query("Insert into tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c,OX_POZYCJI=$ox,OY_POZYCJI=$oy,ID_TABELE=$idt,ID_OSOBY=$ido");}
	}
	$w=mysql_query("select * from tabeles where ID_TABELE=0 and ID_OSOBY=$ido");
	if ($w&&mysql_num_rows($w)>0) {
		$w=mysql_fetch_row($w);
		if ($w[5]) {
			echo 'r='.$w[5].';';
			echo 'm='.$w[6].';';
		}
	}
	else {
		echo 'r=1;';
		echo 'm=1;';
	}
	require('dbdisconnect.inc');
}
else {
	echo 'r=1;';
	echo 'm=1;';
}
// zapamiêtaj stan tabeli dla zalogowanej osoby
//********************************************************************
?>

function UstawKursor()  {

//	if(m==1&&r<=0) {m=4;r=7;}	//w górê
//	if(m==2&&r<=0) {m=5;r=4;}
//	if(m==3&&r<=0) {m=6;r=1;}
//	if(m==4&&r<=0) {m=1;r=13;}
//	if(m==5&&r<=0) {m=2;r=10;}
//	if(m==6&&r<=0) {m=3;r=10;}

	if(m==1&&r<=0) {m=1;r=1;}	//w górê
	if(m==2&&r<=0) {m=2;r=1;}
	if(m==3&&r<=0) {m=3;r=1;}
	if(m==4&&r<=0) {m=1;r=13;}
	if(m==5&&r<=0) {m=2;r=11;}
	if(m==6&&r<=0) {m=3;r=7;}

	if(m<=0)       {m=6;}	   //w lewo
	if(m>6)        {m=1;}	   //w prawo

//	if(m==1&&r>13) {m=4;r=1;}	//w dó³
//	if(m==2&&r>10) {m=5;r=1;}
//	if(m==3&&r>10) {m=6;r=1;}
//	if(m==4&&r>7)  {m=1;r=1;}
//	if(m==5&&r>3)  {m=2;r=1;}
//	if(m==6&&r>1)  {m=3;r=1;}

	if(m==1&&r>13) {m=4;r=1;}	//w dó³
	if(m==2&&r>11) {m=5;r=1;}
	if(m==3&&r>7)  {m=6;r=1;}
	if(m==4&&r>5)  {m=4;r=5;}
	if(m==5&&r>10) {m=5;r=10;}
	if(m==6&&r>10) {m=6;r=10;}

	eval("p"+m+r+".focus();");
	Nad2(eval("t"+m+r),1);
}

function Nad2(x,n)  {
	if (n==1) {x.style.background="<?php echo $cwie; ?>";}
	if (n==0) {x.style.background="";}
}

function Nad(x,n)  {
	if (n==1) {x.style.background="<?php echo $mysz; ?>";}
	if (n==0) {x.style.background="";}
}
function enter(){
        if (event.keyCode==27) {location.href="Tabela_End.php";}
        if (event.keyCode==37) {Nad2(eval("t"+m+r),0);m--;UstawKursor();}		//lewo
        if (event.keyCode==38) {Nad2(eval("t"+m+r),0);r--;UstawKursor();}		//góra
        if (event.keyCode==39) {Nad2(eval("t"+m+r),0);m++;UstawKursor();}		//prawo
        if (event.keyCode==40) {Nad2(eval("t"+m+r),0);r++;UstawKursor();}		//dó³
}
document.onkeypress=enter;
document.onkeydown=enter;

<?php
if ($fiskalna) {
?>
function OpenPort(com) {
	ThermalLib.THLOpenPort(com);
	info.value = ThermalLib.LBIDRQ();
	ThermalLib.THLClosePort();
//	info.value = com;
}
<?php
}
?>

function start() {
<?php
if ($synchronizuj) {
//  echo 'window.open("int2fakpre.php?automat=on", "_new");';
}
?>
  window.focus();
  UstawKursor();
}

-->
</script>
</head>

<!--
±=‘
¶=
¼=¥
-->

<body onload="start()" bgcolor="<?php echo $ttab; ?>"
	text="#000000" link="#000000" alink="#000000" vlink="#000000">

<?php
if ($fiskalna) {
	?>
<OBJECT id=ThermalLib height=0 width=0
	classid=clsid:904511D2-5407-4033-8DAD-07B33EC7317E>
	<PARAM NAME="_Version" VALUE="65536">
	<PARAM NAME="_ExtentX" VALUE="26">
	<PARAM NAME="_ExtentY" VALUE="26">
	<PARAM NAME="_StockProps" VALUE="0">
</OBJECT>

<div style="position: absolute; top: 530;">Drukarka fiskalna: <INPUT
	name="info"
	style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; FONT-SIZE: medium; BORDER-LEFT: 0px; WIDTH: 252px; COLOR: red; BORDER-BOTTOM: 0px; HEIGHT: 22px; TEXT-ALIGN: center"
	border=0 size=110></div>

<script type="text/javascript" language="JavaScript">
<!--
//OpenPort('Com1');
-->
</script>
	<?php
}
?>

<table width="100%" border=0 cellpadding="10" cellspacing="0">
<?php echo '<caption align="center" style="font-size:14pt; font-family:Times; color:#FFFFFF">'.$tyt.'</caption>';?>
	<tr valign="top" align="center">

		<td>
		<table width="100%" bgcolor="<?php echo $twie; ?>" border=1
			cellpadding="4" cellspacing="0">
			<th class="nagtab"><font color="blue">D</font><font color="#A00000">o</font><font
				color="#EFEF1F">k</font><font color="blue">u</font><font
				color="#3F9F0F">m</font><font color="#A00000">e</font>nty sprzeda¿y</th>

			<tr>
				<td id="t11" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p11" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=1&doktyp=FM&doktypnazwa=Faktury magazyn">
				<img src="MagS.jpg" style="border: 0" /> &nbsp;Faktury VAT magazyn
				(FM)&nbsp; <?php
				if ($dubel_FM<>'') {
					echo '<font style="color:red;"><b>(dubel: '.$dubel_FM.')</b></font>';
				}
				?> <?php
				if ($ile_FM) {
					echo '<font style="color:red">(otwartych: '.$ile_FM.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t12" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p12" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=2&doktyp=PM&doktypnazwa=Paragony magazyn">
				<img src="MagS.jpg" style="border: 0" /> &nbsp;Paragony magazyn
				(PM)&nbsp; <?php
				if ($ile_PM) {
					echo '<font style="color:red">(otwartych: '.$ile_PM.')</font>';
				}
				?> <?php
				if ($ilenPM) {
					echo '<font style="color:red">(nierozliczonych: '.$ilenPM.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t13" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p13" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=3&doktyp=FMK&doktypnazwa=Faktury magazyn - korekty">
				<img src="MagS.jpg" style="border: 0" /> &nbsp;Faktury VAT magazyn -
				korekty (FMK)&nbsp; <?php
				if ($ile_FMK) {
					echo '<font style="color:red">(otwartych: '.$ile_FMK.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t14" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p14" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=4&doktyp=PMK&doktypnazwa=Paragony magazyn - korekty">
				<img src="MagS.jpg" style="border: 0" /> &nbsp;Paragony magazyn -
				korekty(PMK)&nbsp; <?php
				if ($ile_PMK) {
					echo '<font style="color:red">(otwartych: '.$ile_PMK.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t15" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p15"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=5&doktyp=FA&doktypnazwa=Faktury Allegro">
				<img src="AllegroS.jpg" style="border: 0" /> &nbsp;Faktury VAT
				Allegro (FA)&nbsp; <?php
				if ($ile_FA) {
					echo '<font style="color:red">(otwartych: '.$ile_FA.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t16" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p16" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=6&doktyp=PA&doktypnazwa=Paragony Allegro">
				<img src="AllegroS.jpg" style="border: 0" /> &nbsp;Paragony Allegro
				(PA)&nbsp; <?php
				if ($ile_PA) {
					echo '<font style="color:red">(otwartych: '.$ile_PA.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t17" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p17" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=7&doktyp=FAK&doktypnazwa=Faktury Allegro - korekty">
				<img src="AllegroS.jpg" style="border: 0" /> &nbsp;Faktury VAT
				Allegro - korekty (FAK)&nbsp; <?php
				if ($ile_FAK) {
					echo '<font style="color:red">(otwartych: '.$ile_FAK.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t18" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p18" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=8&doktyp=PAK&doktypnazwa=Paragony Allegro - korekty">
				<img src="AllegroS.jpg" style="border: 0" /> &nbsp;Paragony Allegro
				- korekty (PAK)&nbsp; <?php
				if ($ile_PAK) {
					echo '<font style="color:red">(otwartych: '.$ile_PAK.')</font>';
				}
				?> </a></td>
			</tr>
			<tr>
				<td id="t19" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p19"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=9&doktyp=FI&doktypnazwa=Faktury internet">
				<img src="IEs.jpg" style="border: 0" /> &nbsp;Faktury VAT internet
				(FI)&nbsp; <?php
				if ($ile_FI) {
					echo '<font style="color:red">(otwartych: '.$ile_FI.')</font>';
               echo '<img src="images/New.gif" style="border:0pt; height:11pt;" />';
				}
				?> &nbsp; </a></td>
			</tr>
			<tr>
				<td id="t110" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p110" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=10&doktyp=PI&doktypnazwa=Paragony internet">
				<img src="IEs.jpg" style="border: 0" /> &nbsp;Paragony internet
				(PI)&nbsp; <?php
				if ($ile_PI) {
					echo '<font style="color:red">(otwartych: '.$ile_PI.')</font>';
               echo '<img src="images/New.gif" style="border:0pt; height:11pt;" />';
				}
				?> &nbsp; </a></td>
			</tr>
			<tr>
				<td id="t111" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p111" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=11&doktyp=FIK&doktypnazwa=Faktury internet - korekty">
				<img src="IEs.jpg" style="border: 0" /> &nbsp;Faktury VAT internet -
				korekty (FIK)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t112" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p112" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=12&doktyp=PIK&doktypnazwa=Paragony internet - korekty">
				<img src="IEs.jpg" style="border: 0" /> &nbsp;Paragony internet -
				korekty (PIK)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t113" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p113"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=1&mm=13&doktyp=ALL&doktypnazwa=Wszystkie dokumenty">
				&nbsp;Wszystkie dokumenty&nbsp;</a></td>
			</tr>
		</table>

		<br>

		<table width="100%" bgcolor="<?php echo $twie; ?>" border=1
			cellpadding="4" cellspacing="0">
			<th class="nagtab"><font color="blue">I</font>nne</th>
			<tr>
				<td id="t41" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p41" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=wzoryumow&m=4&mm=1"> &nbsp;Wzory
				wydruków&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t42" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p42" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Konserwuj.php?m=4&mm=2"> &nbsp;Konserwacja danych&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t43" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p43" onfocus="Nad2(this,1)" onblur="Nad2(this,0)" href="<?php if($_SESSION['osoba_dos']=='T') {echo 'close_all_naprawa_pre.phpold';}?>">
				&nbsp;&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t44" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)" style="border-top: double #000000">
            <a id="p44"	onfocus="Nad2(this,1)" onblur="Nad2(this,0)" href="<?php if($_SESSION['osoba_dos']=='T') {echo 'int_akt.php';}?>">
				&nbsp;Synchronizacja: pe³na ( ostatnio : <?php echo $czas_int_akt;?>)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t45" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)">
            <a	id="p45" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"	href="<?php if($_SESSION['osoba_dos']=='T') {echo 'int2fakpre.php';}?>"> 
            &nbsp;Synchronizacja: szybka (ostatnio: <?php echo $czas_int2fak;?>)&nbsp;</a></td>
			</tr>
		</table>

		</td>

		<td>

		<table width="100%" bgcolor="<?php echo $twie; ?>" border=1
			cellpadding="4" cellspacing="0">
			<th class="nagtab"><font color="blue">K</font>artoteki</th>
			<tr>
				<td id="t21" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p21" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towary&m=2&mm=1"> &nbsp;Magazyn&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t22" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p22"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=firmy&m=2&mm=2"> &nbsp;Kontrahenci&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t23" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p23" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=firmyold&m=2&mm=3"> &nbsp;Kontrahenci
				nieu¿ywani&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t24" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p24"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=magazyny&m=2&mm=4"> &nbsp;Towary -
				analityka&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t25" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p25" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towaryold&m=2&mm=5"> &nbsp;Towary
				nieu¿ywane&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t26" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p26"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=odsetki&m=2&mm=6"> &nbsp;Odsetki
				ustawowe&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t27" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p27" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=doktypy&m=2&mm=7"> &nbsp;Typy
				dokumentów&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t28" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p28" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=osobyz&m=2&mm=8"> &nbsp;Osoby
				uprawnione&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t29" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p29"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=parametry&m=2&mm=9&doktyp=%&doktypnazwa=Parametry systemu">
				&nbsp;Parametry systemu&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t210" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p210" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=parametryk&m=2&mm=10&doktyp=kategorie&doktypnazwa=Parametry systemu - Kategorie">
				&nbsp;Parametry systemu - Kategorie&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t211" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p211" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=parametry&m=2&mm=11&doktyp=producenci&doktypnazwa=Parametry systemu - Producenci">
				&nbsp;Parametry systemu - Producenci&nbsp;</a></td>
			</tr>
		</table>

		<br>

		<table width="100%" bgcolor="<?php echo $twie; ?>" border=1
			cellpadding="4" cellspacing="0">
			<th class="nagtab"><font color="blue">D</font>okumenty magazynowe</th>
			<tr>
				<td id="t51" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p51" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=1&doktyp=ZAM&doktypnazwa=Zamówienia">
				&nbsp;Zamówienia (ZAM)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t52" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p52" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=2&doktyp=PZ&doktypnazwa=Przyjêcia Zewnêtrzne">
				&nbsp;Przyjêcia Zewnêtrzne (PZ)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t53" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p53" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=3&doktyp=PZK&doktypnazwa=Przyjêcia Zewnêtrzne - korekty">
				&nbsp;Przyjêcia Zewnêtrzne - korekty (PZK)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t54" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p54" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=4&doktyp=WZ&doktypnazwa=Wydania Zewnêtrzne">
				&nbsp;Wydania Zewnêtrzne (WZ)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t55" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p55" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=5&doktyp=INW&doktypnazwa=Inwentaryzacje&przepisuj=1">
				&nbsp;Inwentaryzacje (INW) - wyrywkowe&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t56" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p56" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=6&doktyp=INW&doktypnazwa=Inwentaryzacje&nieprzepisuj=1">
				&nbsp;Inwentaryzacje (INW) - ca³o¶ciowe&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t57" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p57" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=7&doktyp=RE&doktypnazwa=Reklamacje">
				&nbsp;Reklamacje (RE)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t58" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p58" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=8&doktyp=ZN&doktypnazwa=Zniszczenia">
				&nbsp;Zniszczenia (ZN)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t59" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p59" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokum&m=5&mm=9&doktyp=ZW&doktypnazwa=Zwroty do dostawcy">
				&nbsp;Zwroty do dostawcy (ZW)&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t510" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p510" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towary_inw&m=5&mm=10">
				&nbsp;Magazyn - ró¿nice inwentaryzacyjne&nbsp;
<?php
				if ($new) {echo '<img src="images/New.gif" style="border:0pt; height:11pt;" />';}
?>       </a></td>
			</tr>
		</table>

		</td>

		<td>
		<table width="100%" bgcolor="<?php echo $twie; ?>" border=1
			cellpadding="4" cellspacing="0">
			<th class="nagtab"><font color="blue">A</font>nalizy</th>
			<tr>
				<td id="t31" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p31" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towarydubl&m=3&mm=1"> &nbsp;Towary - duble
				indeksów&nbsp;</a>
<?php   if ($iledubli) {
					echo '<font style="color:red">('.$iledubli.' szt.)</font>';
				  echo '<img src="images/New.gif" style="border:0pt; height:11pt;" />';
				}
?>        
        </td>
			</tr>
			<tr>
				<td id="t32" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p32" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towarySTAN&m=3&mm=2"> &nbsp;Towary -
				odchylenia stanów Magazynu&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t33" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p33" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towarySTPZ&m=3&mm=3"> &nbsp;Towary -
				odchylenia stanów wed³ug PZ&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t34" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p34" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towarySPZK&m=3&mm=4"> &nbsp;Towary -
				kompensowalne stany wed³ug PZ&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t35" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p35" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=tab_obl&m=3&mm=5"> &nbsp;Obroty
				magazynu&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t36" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p36" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=todo&m=3&mm=6"> &nbsp;Rejestr zmian&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t37" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p37" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=towaryNINT&m=3&mm=7"> &nbsp;Towary - ró¿ne nazwy dla internetu&nbsp;</a></td>
			</tr>
		</table>

		<br>

		<table width="100%" bgcolor="<?php echo $twie; ?>" border=1
			cellpadding="4" cellspacing="0">
			<th class="nagtab"><font color="blue">R</font>ozliczenia</th>
			<tr>
				<td id="t61" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p61" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentKB&m=6&mm=1&tabSubNazwa=dokumentb2&doktyp=RK%&doktypnazwa=Raporty Kasowe"> &nbsp;Kasa&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t62" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p62" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentb4&m=6&mm=2&tabSubNazwa=dokumentb2&doktyp=RK%&doktypnazwa=Raporty Kasowe wszystkie - analityka">
				&nbsp;Kasa - analityka&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t63" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p63"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentBA&m=6&mm=3&tabSubNazwa=dokumentb3&doktyp=WB&doktypnazwa=Wyci±gi Bankowe">
				&nbsp;Bank&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t64" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p64" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentb4&m=6&mm=4&tabSubNazwa=dokumentb3&doktyp=WB&doktypnazwa=Wyci±gi Bankowe - analityka">
				&nbsp;Bank - analityka&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t65" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p65"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentBA&m=6&mm=5&tabSubNazwa=dokumentb3&doktyp=WBA&doktypnazwa=Wyci±gi Bankowe - Allegro">
				&nbsp;Bank Allegro&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t66" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p66" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentb4&m=6&mm=6&tabSubNazwa=dokumentb3&doktyp=WBA&doktypnazwa=Wyci±gi Bankowe - Allegro - analityka">
				&nbsp;Bank Allegro - analityka&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t67" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p67"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentBA&m=6&mm=7&tabSubNazwa=dokumentb3&doktyp=WBK&doktypnazwa=Wyci±gi Bankowe - Koszty">
				&nbsp;Bank Koszty&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t68" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p68" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentb4&m=6&mm=8&tabSubNazwa=dokumentb3&doktyp=WBK&doktypnazwa=Wyci±gi Bankowe - Koszty - analityka">
				&nbsp;Bank Koszty - analityka&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t69" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"
					style="border-top: double #000000"><a id="p69"
					onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentb4&m=6&mm=9&tabSubNazwa=dokumentb3&doktyp=WB%&doktypnazwa=Wyci±gi Bankowe wszystkie - analityka">
				&nbsp;Banki wszystkie - analityka&nbsp;</a></td>
			</tr>
			<tr>
				<td id="t610" onmouseover="Nad(this,1)" onmouseout="Nad(this,0)"><a
					id="p610" onfocus="Nad2(this,1)" onblur="Nad2(this,0)"
					href="Tabela.php?tabela=dokumentb4&m=6&mm=10&doktyp=%&doktypnazwa=Dokumenty Kasowe i Bankowe wszystkie - analityka">
				&nbsp;Kasa i Banki wszystkie - analityka&nbsp;</a></td>
			</tr>

		</table>

		<?php
if (!$_SESSION['osoba_se']) {
?> <br>

		<div align="center"><img alt="ericom - mgr in¿. Arkadiusz Moch"
			src="Logo_small.png" onclick="location='http://www.ericom.pl'" /> <!-- zawiniêcie menu do pierwszej pozycji -->
		</div>
		<?php
}
?></td>

	</tr>
</table>

</body>
</html>

<?php 
} 
?>
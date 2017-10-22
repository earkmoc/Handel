<?php

//@ini_set("session.gc_maxlifetime","28800");

session_start();
$ido=$_SESSION['osoba_id'];

$kolor=($_GET['kolor']?$_GET['kolor']:"#0F4F9F");		//"#D0DCE0"

if ($_GET['doktyp']) {$_SESSION['doktyp']=$_GET['doktyp'];}
$doktyp=$_SESSION['doktyp'];

if ($_GET['doktypnazwa']) {$_SESSION['doktypnazwa']=$_GET['doktypnazwa'];}
$doktypnazwa=$_SESSION['doktypnazwa'];

$ox=0;
$oy=0;
//require('skladuj_zmienne.php');
if ( (($_GET['tabela']=='tabele')&&($ido<>1))
||(($_GET['tabela']=='wzoryumow')&&($_SESSION['osoba_dos']<>'T'))
||(((strtoupper(substr($_GET['tabela'],0,9)))=='DOKUMENTB')&&($_SESSION['osoba_dos']<>'T'))
||(($_GET['tabela']=='tab_obl')&&($_SESSION['osoba_dos']<>'T'))
||(($_GET['tabela']=='magazyny')&&($_SESSION['osoba_dos']<>'T'))
) {
	?>
<html>
<head>
</head>
<body>
<script type="text/javascript" language="JavaScript">
<!--
location.href="index.php";
-->
</script>
</body>
</html>

	<?php
	exit;
}

//echo '<!DOCTYPE html>';

?>
<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=iso-8859-2">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />
<title><?php

if ($_GET['punkt']>0) {$_SESSION['osoba_pu']=$_GET['punkt'];}
if ($_GET['serwer']>0) {$_SESSION['osoba_se']=$_GET['serwer'];}
$_SESSION['osoba_os']='XP';
//if ($_GET['windows']) {$_SESSION['osoba_os']=$_GET['windows'];}

//if ($_GET['autor']=='AMoch') {
//	$_SESSION['osoba_gr']=1;
//	$_SESSION['osoba_id']=50;
//	$_SESSION['osoba_upr']='Arkadiusz Moch';
//}

if ($_SESSION['osoba_upr']) {
	//	echo ': ';
	echo $_SESSION['osoba_upr'];
	//	echo ' (op=';
	//	echo $_SESSION['osoba_id'];
	//	if ($_SESSION['osoba_pu']) {
	//		echo ', st=';
	//		echo $_SESSION['osoba_pu'];
	//	}
	//	echo ')';
	$osoba_gr=$_SESSION['osoba_gr'];
	$osoba_pu=$_SESSION['osoba_pu'];
}

if ($_GET['idtab_master']) {
	$_SESSION['idtab_master']=$_GET['idtab_master'];	//zapamiêtaj ID w tabeli master
}
//	echo ' [';
//	echo $_SESSION['ntab_mast'];
//	echo ']';


?></title>

<?php
if ($_SESSION['osoba_pu']==1) {
	?>

<link type="text/css" href="css/start/jquery-ui-1.8.13.custom.css"
	rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>

<script>
	$(function() {
		$( "button, input:submit, input:button, a", ".demo" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
	</script>

	<?php
}
?>

<script type="text/javascript" src="advajax.js"></script>
<script type="text/javascript" language="JavaScript">
<!--

function Ajax_indeks(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_indeks.php?indeks="+$w
                     +"&ilosc="+document.getElementById("towar_"+$i+"_6").value
                     +"&rabat="+document.getElementById("towar_"+$i+"_9").value,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_3").value = ss; //indeks 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_4").innerHTML = ss;   //nazwa

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_8").value = ss;       //cenabezr

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_9").value = ss;       //rabat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_10").innerHTML = ss;   //cena

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_5").innerHTML = ss;   //stan

                  document.getElementById("towar_"+$i+"_6").value = 1;        //ilo¶æ

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_7").innerHTML = ss;  //jm

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_12").innerHTML = ss;  //%VAT



                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto


                  if (document.getElementById("towar_"+$i+"_5").innerHTML*1 < document.getElementById("towar_"+$i+"_6").value*1) {
                     document.getElementById("towar_"+$i+"_6").style.background='red';
//	                  document.getElementById("towar_"+$i+"_6").value = 0;        //ilo¶æ
                  } else {
                     document.getElementById("towar_"+$i+"_6").style.background='white';
                  }

                  tab_czysc();
                  $r=$i+2;
                  $r=($r>$rr?$rr:$r);
                  tab_kolor();
                  
                }
});
}
function Ajax_cena(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_cena.php?cena="+$w
                     +"&rabat="+document.getElementById("towar_"+$i+"_9").value
                     +"&ilosc="+document.getElementById("towar_"+$i+"_6").value
                     +"&vat="+document.getElementById("towar_"+$i+"_12").innerHTML,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_7").innerHTML = ss;  //cena 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto
                }
});
}
function Ajax_rabat(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_cena.php?rabat="+$w
                     +"&cena="+document.getElementById("towar_"+$i+"_8").value
                     +"&ilosc="+document.getElementById("towar_"+$i+"_6").value
                     +"&vat="+document.getElementById("towar_"+$i+"_12").innerHTML,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_9").innerHTML = ss;  //cena 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto
                }
});
}
function Ajax_ilosc(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_ilosc.php?ilosc="+$w
                     +"&cena="+document.getElementById("towar_"+$i+"_10").innerHTML
                     +"&vat="+document.getElementById("towar_"+$i+"_12").innerHTML,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto

                  if (document.getElementById("towar_"+$i+"_5").innerHTML*1 < document.getElementById("towar_"+$i+"_6").value*1) {
                     document.getElementById("towar_"+$i+"_6").style.background='red';
                  } else {
                     document.getElementById("towar_"+$i+"_6").style.background='white';
                  }

                  tab_czysc();
                  $r=$i+2;
                  $r=($r>$rr?$rr:$r);
                  tab_kolor();
                }
});
}
-->
</script>

<style type="text/css">
<!--
@media screen {
	.bez {
		display: none;
	}
}

body {
	background-color: <?php echo "$kolor";?>;
}

form {
	background-color: "#0F4F9F";
}

.nag {
	font: bold 10pt times;
}

.nor {
	font: normal 10pt arial;
}

.nor2 {
	font: normal 8pt arial;
	color: white;
}

.zaz {
	font: normal 22pt arial;
	background-color: red;
}

.sza {
	font: normal 10pt arial;
	background-color: gray;
}

#f0 {
	POSITION: absolute;
	VISIBILITY: visible;
	TOP: -530px;
	LEFT: 0px;
	Z-INDEX: 999;
}

#f1 {
	POSITION: absolute;
	VISIBILITY: visible;
	TOP: 0px;
	LEFT: 0px;
	Z-INDEX: 999;
}

#f3 {
	POSITION: absolute;
	VISIBILITY: hidden;
	TOP: -500px;
	LEFT: 5px;
	Z-INDEX: 998;
}

.ui-button {
	padding: .3em 0em;
	margin-top: .1em;
}

input.ui-button {
	padding: .4em .4em;
	font-size: 11pt;
	font: bold;
	line-height: 11pt;
}
-->
</style>

<script type="text/javascript" language="JavaScript">
<!--
var r, rr, rrr, c, cc, str, tnag, cnag, twie, cwie;
var timerID=0;
var nn='', nnn='';

function MD(e){
	nnn=nn;
	X=event.offsetX;
	Y=event.offsetY;
}
function MM(e){
//	if (event.clientX<5) {
//		f1.style.pixelTop=event.clientY+document.body.scrollTop;
//	}
	if (nnn=='') {;} else {
//		f1.projektsave.style.visibility="visible";
		eval(nnn+".style.pixelLeft=event.clientX-X+document.body.scrollLeft;");
		eval(nnn+".style.pixelTop=event.clientY-Y+document.body.scrollTop;");
		return false;
	}
}
function MU() {
	nn='';
	nnn='';
}

document.onmousedown=MD;
document.onmousemove=MM;
document.onmouseup=MU;

function f3_ShowOnOff() {
	if (f3.style.visibility=="visible") {
		f3_Hide();
		f3.style.pixelTop=-500;
	} 
	else {
		f3.style.pixelTop=document.body.scrollTop+90;
		f3.style.pixelLeft=document.body.scrollLeft+250;
		f3_Show();
	}
}
function f3_Show() {f3.style.visibility="visible";}
function f3_Hide() {f3.style.visibility="hidden";}

function ruszamy(x,n) {
//	if (f1.projektmode.checked) {
//		if (n==1) {
//			nn=x.id;
//			x.style.color="blue";
//		}
//		else {
//			nn='';
//			x.style.color="black";
//		}
//	}
}

<?php

require('dbconnect.inc');

//mysql_query('SET CHARACTER SET latin2');
//mysql_query('SET collation_connection = latin2_general_ci');
//mysql_query('SET character_set_connection=latin1', $db);
//mysql_query('SET character_set_client=latin1', $db);
//mysql_query('SET character_set_results=latin1', $db);

//********************************************************************
// zapamiêtaj stan menu dla zalogowanej osoby

if ($ido&&$_GET['m']&&$_GET['mm']) {
	$w=mysql_query("select * from tabeles where ID_TABELE=0 and ID_OSOBY=$ido");
	if ($w&&mysql_num_rows($w)>0) {
		$w=mysql_fetch_row($w);
                $z='Update tabeles';
                $z.=' set NR_ROW=';
                $z.=$_GET['mm'];
                $z.=', NR_COL=';
                $z.=$_GET['m'];
                $z.=' where ID=';
                $z.=$w[0];
	}
	else {
                $z='Insert into tabeles (ID_OSOBY,ID_TABELE,NR_ROW,NR_COL) values (';
                $z.=$ido;
                $z.=',0,';
                $z.=$_GET['mm'];
                $z.=',';
                $z.=$_GET['m'];
                $z.=')';
	}
	mysql_query($z);
}

//********************************************************************

if ($_POST['idtab']) {
	$z='Select NAZWA from tabele where ID='.($_POST['idtab']); $w=mysql_query($z); $w=mysql_fetch_row($w); $ntab_master=$w[0];
	if (($ntab_master)==='dokum')       {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumenty')   {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentFV') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentKB') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentZA') {$_SESSION['ntab_mast']=$ntab_master;};
}
if ($_GET['tabela']) {
	$ntab_master=$_GET['tabela'];
	if (($ntab_master)==='dokum')       {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumenty')   {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentFV') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentKB') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentZA') {$_SESSION['ntab_mast']=$ntab_master;};
	if ($ntab_master==='tab_master') {
		$_GET['tabela']=$_SESSION['ntab_mast'];
		$z="Select ID from tabele where NAZWA='".($_GET['tabela'])."'"; $w=mysql_query($z); $w=mysql_fetch_row($w); $_POST['idtab']=$w[0];
	};
}
$ntab_master=$_SESSION['ntab_mast'];
if (!$ntab_master) {$ntab_master=' '; $_SESSION['idtab_master']='';}

$gdzie='';
if ($ntab_master&&$_SESSION['idtab_master']) {
	$z="Select left(GDZIE,1) from dokumenty where ID=".$_SESSION['idtab_master']; $w=mysql_query($z); 
	$w=mysql_fetch_row($w); $gdzie=$w[0];
}

echo '$ntab_master="'.$ntab_master.'";';
echo "\n";

//********************************************************************
// zapamiêtaj stan tabeli dla zalogowanej osoby
// gdy nie suwanie po tabeli i zalogowany i przed chwil± by³ w tabeli

$warunek="";
$sortowanie="";
$opole=$_POST['opole'];
$ipole=$_POST['ipole'];
if (($opole!="S")&&$_SESSION['osoba_upr']&&$ipole) {

$z='Select ID, WARUNKI, SORTOWANIE from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$_POST['idtab'];

$w=mysql_query($z);
if (!$w) {exit;}
else {
        if (mysql_num_rows($w)>0) {

                $w=mysql_fetch_array($w);

                $warunek=StripSlashes($w['WARUNKI']);
                $sortowanie=StripSlashes($w['SORTOWANIE']);

                $z='Update tabeles';
                $z.=' set NR_STR=';
                $z.=$_POST['strpole'];
                $z.=', NR_ROW=';
                $z.=$_POST['rpole'];
                $z.=', NR_COL=';
                $z.=$_POST['cpole'];
                $z.=', ID_POZYCJI=';
                $z.=$_POST['ipole'];
                $z.=', OX_POZYCJI=';
                $z.=$_POST['offsetX'];
                $z.=', OY_POZYCJI=';
                $z.=$_POST['offsetY'];
                $z.=' where ID=';
                $z.=$w['ID'];
        }
        else {
                $z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL,OX_POZYCJI,OY_POZYCJI) values (';
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
                $z.=',';
                $z.=$_POST['offsetX'];
                $z.=',';
                $z.=$_POST['offsetY'];
                $z.=')';
        }
//        $sql0=$z;
        $w=mysql_query($z);
}}

// zapamiêtaj stan tabeli dla zalogowanej osoby
//********************************************************************

//********************************************************************
//zmienne PHP i Java Script steruj±ce dalszym zachowaniem

if ($_POST['sutab']) {                            // tryb Master/Slave (SubTab=podtabela)
        if ($opole=="S") {                                 // suwanie siê po tej samej tabeli
                $tabela=$_POST['natab'];          // tabela slave aktywna
                $tabelaa=$_POST['sutab'];         // tabela master nieaktywna
                $tabelap=$_POST['sutabpol'];      // pole ³±cznik do tabeli slave
                $tabelai=$_POST['sutabmid'];      // identyfikator pozycji w master
        }
        else {
                $tabela=$_POST['sutab'];          // tabela slave aktywna
                $tabelaa=$_POST['natab'];         // tabela master nieaktywna
                $tabelap=$_POST['sutabpol'];      // pole ³±cznik do tabeli slave
                $tabelai=$_POST['ipole'];         // identyfikator pozycji w master
        }
}
else {
        $tabela=$_GET['tabela'];
        if (!$tabela) {$tabela=$_POST['natab'];};
}
if (!$tabela) {                // brak podanej tabeli
        $tabela='tabele'; // => l±duj w tabeli g³ównej
        $tabelaa="";                // tabela master nieaktywna
        $tabelap="";                // pole ³±cznik do tabeli slave
        $tabelai="";                 // identyfikator pozycji w master
};

if (!$_SESSION['osoba_upr']||!$_SESSION['osoba_pu']) {$tabela='osoby';}; // niezalogowany l±duje w osoby

//********************************************************************
// mo¿e tryb Master/Slave jest okre¶lony w definicji tabeli ?

if ($opole!="S") {                                        // nie suwanie siê

$z=ord(substr($tabela,0,1));
if (48<=$z && $z<=57) {				//$tabela mo¿e byæ liczb± ID tabeli
        $z="select * from tabele where ID='";
        $z.=$tabela;
        $z.="'";
}
else {						//$tabela mo¿e byæ nazw± tabeli
	if (count($w=explode(",",$tabela))>1) {	// jest przecinek
		$tabela=$w[0];
	}
        $z="select * from tabele where NAZWA='";                // WYKAZYSPE
        $z.=$tabela;
        $z.="'";
}
$w=mysql_query($z);
if ($w){
        $w=mysql_fetch_array($w);
        $sql=StripSlashes($w['TABELA']);
        if (!$sql) { exit;}
        else {
                $w=explode("\n",$sql);
                $z=trim($w[0]);
                if (count($w=explode(",",$z))>1) {  // jest przecinek
                        $tabela=$w[0];              // tabela slave aktywna
                        $tabelaa=$w[1];             // tabela master nieaktywna
                        $tabelap=trim($w[2]);       // pole ³±cznik do tabeli slave
                        $tabelai='';                // ID pozycji w Master za chwilê ...
                  }
        }
}
}
// mo¿e tryb Master/Slave jest okre¶lony w definicji tabeli ?
//********************************************************************

if ($tabelaa=='tab_master') {$tabelaa=$ntab_master;}

echo '$tabela="'.$tabela.'";';
echo "\n";

$zaznaczone=$_POST['zaznaczone'];
echo '$zaznaczone=';
echo "'$zaznaczone';";
echo "\n";

$tnag='"#CCCCCC"';	//'"#CCCCCC"';	//FFCC33, #EFEFDF
echo '$tnag='.$tnag.';';
echo "\n";

$cnag='"#FF6600"';	//'"#FF6600"' cegla; #FFDF9F blady
echo '$cnag='.$cnag.';';
echo "\n";

$twie='"#EFEFDF"';	//FFFFCC
$twie2='#EFEFDF';	//FFFFCC
echo '$twie='.$twie.';';
echo "\n";

$cwie='"#FFCC66"';	//'"#FFCC66"';    '"#FFDF8F"';
echo '$cwie='.$cwie.';';
echo "\n";

//zmienne Java Script
//********************************************************************

if ($tabelaa) {
//********************************************************************
// wariant z tabel± MASTER (nieaktywn±)

$zprequery=0;
$prequery=array();
$tna=array();
$tp=array();
$mca=$cca;
$ca=1;

$z=ord(substr($tabelaa,0,1));
if (48<=$z && $z<=57) {
        $z="select * from tabele where ID='";
        $z.=$tabelaa;
        $z.="'";
}
else {
        $z="select * from tabele where NAZWA='";
        $z.=$tabelaa;
        $z.="'";
}
//echo $z;
$wa=mysql_query($z);
if ($wa){
        $wa=mysql_fetch_array($wa);
        $idtaba=$wa['ID'];
        $tabelaa=StripSlashes($wa['NAZWA']);
        echo '$tabelaa="'.($wa['NAZWA']).'";';
        echo "\n";

//        $tyta=StripSlashes($wa['OPIS']);
        $tyta=str_replace('$doktypnazwa',$doktypnazwa,StripSlashes($wa['OPIS']));
        $sqla=StripSlashes($wa['TABELA']);
        $funa=StripSlashes($wa['FUNKCJE']);
        if (!$sqla) { exit;}
        else {
                $mca=0;
                $wa=explode("\n",$sqla);
                $sqla='';

                if (count($bazaa=explode(",",$wa[0]))>1) {        // jest przecinek
   	             if (count($bazaa=explode(",",$wa[0]))>3) {   // s¹ nawet 3: abonenciG,grupy,[1].[2],abonenci
   	                $bazaa=trim($bazaa[3]);
         		    } else {
                      $bazaa=$bazaa[0];
         		    }
                } else {
                   $bazaa=trim($wa[0]);
                }
                $z='Select';
                $cca=Count($wa);
                for($i=1;$i<$cca;$i++) {
			               $wa[$i]=trim($wa[$i]);
                        if     (!$wa[$i]) {;}
                        elseif (substr($wa[$i],0,4)=='from')  {$z.=' '.$wa[$i];}
                        elseif (substr($wa[$i],0,5)=='order') {$zorder=' '.$wa[$i];}
                        elseif (substr($wa[$i],0,5)=='where') {
                           if ($doktyp=='ALL') {
                              $zwhere=' '.str_replace('$doktyp',"' or ''='",$wa[$i]);  //SQL injection
                           } else {
                              $zwhere=' '.str_replace('$doktyp',$doktyp,$wa[$i]);
                           }
                        }
                        elseif (substr($wa[$i],0,5)=='group') {$zgroup=' '.$wa[$i];}
                        elseif (substr($wa[$i],0,8)=='prequery'){$prequery[$zprequery]=substr($wa[$i],9);$zprequery++;}
                        elseif (substr($wa[$i],0,6)=='having') {$zhaving=' '.$wa[$i];}
                        else {
                                if($i==1) {$z.=' ';} else {$z.=',';};
                                $la=explode("|",$wa[$i]);
                                if (!($bazaa=='Select')&&(count(explode(".",$la[0]))<2)&&(count(explode("(",$la[0]))<2)) {
                                        $z.=$bazaa;
                                        $z.=".";
                                }
                                $z.=$la[0];
                                if(!$la[1]) {$tna[$i-1]=trim($la[0]);} else {$tna[$i-1]=trim($la[1]);};
                                $szera[$mca]=$la[2];                //szeroko¶æ
                                if (substr($szera[$mca],0,1)=='+') {$ca=$mca+1;};
                                $styla[$mca]=$la[3];                //style="font-size: 70pt; color: red; font-weight: normal"
                                $styna[$mca]=$la[4];                //font-family: serif; font-size: 18pt; text-align: center
                                $mca++;
                        }
                }
                $cca=$mca;
        }
}
if (!$tabelai) {                                                        // nie ma ID pozycji w Master
        $za='Select ID, ID_POZYCJI from tabeles where ID_OSOBY=';
        $za.=$_SESSION['osoba_id'];
        $za.=' and ID_TABELE=';
        $za.=$idtaba;
        $wa=mysql_query($za);
        $wa=mysql_fetch_array($wa);
        $tabelai=$wa['ID_POZYCJI'];                                          // ID pozycji w Master
}
if ($zgroup) {
        $z.=' '.$zgroup;                 // "group by" zamiast "where"
        if ($zhaving) {                // "having" za "group by"
                $z.="$zhaving";
                if (substr($tabelap,0,1)=='[') {        // odwo³anie do pól mastera
                        $tr=explode('.',$tabelap);                // [1].[2]
                        for($i=0;$i<count($tr);$i++) {
                                $j=substr($tr[$i],1)*1;	//mo¿e mieæ 2 cyfry i wiêcej
                                $z=str_replace($tr[$i],$tra[$j],$z);
                        }
                }
                else {$z.=" and $bazaa.ID=$tabelai";};
        }
        else {                                          // nie ma "having", wiêc ma byæ
                $z.=" having $bazaa.ID=$tabelai";
        }
}
else {
        if ($zwhere) {                                                                                // jest "where"
                if (substr($tabelap,0,1)=='[') {        // odwo³anie do pól mastera
                	$z.=" where $bazaa.ID=$tabelai";	//master nie odwo³uje siê do mastera tylko polega na ID
                }
                else {$z.="$zwhere and $bazaa.ID=$tabelai";};
        }
        else {
                $z.=" where $bazaa.ID=$tabelai";                 // nie ma "where"
        }
}
if ($zorder) {$z.=' '.$zorder;}                                  // "order by" za "where"
$z.=' limit 1';
$z=str_replace('ID_master',$tabelai,$z);
$z=str_replace('osoba_id',$_SESSION['osoba_id'],$z);
$sqla=$z.';';
if (1==2&&$opole!="S") {                                        // nie suwanie siê
  if ($zprequery) {
	for($k=0;$k<count($prequery);$k++) {
		$prequery[$k]=str_replace('ID_master',$tabelai,$prequery[$k]);
		$prequery[$k]=str_replace('osoba_id',$_SESSION['osoba_id'],$prequery[$k]);
		$sql.='   '.$prequery[$k].';';
		if (substr($prequery[$k],0,1)=='?') {
			$prequery[$k]=substr($prequery[$k],1);
			$wa=mysql_query($prequery[$k]);
			$wa=mysql_fetch_row($wa);
			$sql.='   '.$wa[0].';';
			if ($wa[0]) {$k=count($prequery);};	//finito prequerys
		}
		else {
			$wa=mysql_query($prequery[$k]);
			if (strtoupper(substr(trim($prequery[$k]),0,6))=='SELECT') {	//jeœli typu "SELECT"
				$qs=mysql_fetch_row($wa);										//to coœ zwraca
				$i=$k+1;					//nastêpne prequery
				if ($i<count($prequery)) {			//jeœli s¹ nastêpne, to
					for ($j=0;$j<count($qs);$j++) {		//korzystaj¹ ze swoich wyników
						$prequery[$i]=str_replace('{'.$j.'}',$qs[$j],$prequery[$i]);
					}
				}
			}
		}
	}
  }
}

@$wa=mysql_query($z);		//trzeba st³umiæ ostrze¿enia, bo w przypadku gdy master jest slavem innego mastera pojawia siê odwo³anie do niezast¹pionego pola [0] wy¿szego mastera
@$na=mysql_num_rows($wa);
@$tra=mysql_fetch_row($wa);
for($j=0;$j<Count($tra);$j++) {$tra[$j]=StripSlashes($tra[$j]);}

// wariant z tabel± MASTER (nieaktywn±)
//********************************************************************
}

//********************************************************************
// tabela Slave (aktywna)

$zwhere="";                // zerowanie zmiennych, które za chwilê znów bêd± u¿yte
$zorder="";
$zgroup="";
$zhaving="";
$zunion=0;
$uniony=array();
$zprequery=0;
$prequery=array();
$pola=array();
$tn=array();
$sumy=array();
$sumyp=array();
$sumyok=false;
$mc=$cc;
$sql='';

//$tabela mo¿e byæ liczb± ID tabeli lub nazw± tabeli
$z=ord(substr($tabela,0,1));
if (48<=$z && $z<=57) {
        $z="select * from tabele where ID='";
        $z.=$tabela;
        $z.="'";
}
else {
        $z="SELECT * FROM tabele WHERE NAZWA='";
        $z.=$tabela;
        $z.="'";
}
$w=mysql_query($z);
if (!$w) {
	echo "Nie wyszlo: $z\r";
	exit;}
else {
        $w=mysql_fetch_array($w);
        $idtab=$w['ID'];
        echo '$idtab='.$idtab.';';
        echo "\n";

        $tabela=$w['NAZWA'];
        echo '$tabela="'.($w['NAZWA']).'";';
        echo "\n";

        $tyt=str_replace('$doktypnazwa',$doktypnazwa,StripSlashes($w['OPIS']));
        $sql=StripSlashes($w['TABELA']);
        $fun=StripSlashes($w['FUNKCJE']);
	if ($_GET['maxrow']) {$rr=$_GET['maxrow'];} else {$rr=$w['MAXROWS'];}
        if ($rr==0) {$rr=20;}
        $rrr=$rr;

        $z='Select NR_STR, NR_ROW, NR_COL, WARUNKI, SORTOWANIE, OX_POZYCJI, OY_POZYCJI from tabeles where ID_OSOBY=';
        $z.=$_SESSION['osoba_id'];
        $z.=' and ID_TABELE=';
        $z.=$idtab;
        $ww=mysql_query($z);
        if ($ww and mysql_num_rows($ww)>0) {
                $ww=mysql_fetch_array($ww);
        };

        $warunek=StripSlashes($ww['WARUNKI']);
        $sortowanie=StripSlashes($ww['SORTOWANIE']);

        $r=$ww['NR_ROW'];
        if (!$r) {$r=1;};

        $str=$ww['NR_STR'];
        if (!$str) {$str=1;};
        if ($_POST['opole']=="S") {
                $str=$_POST['strpole'];
                if ($str>0) {$r=1;};        //jak dodaje strony, to najpierw staje na pierwszym wierszu
	        $ox=$_POST['offsetX'];
        }
        else {
                if ($tabelaa) {                // po wej¶ciu do Slave w trybie Maste/Slave stoi na szczycie
//                        $r=1;
//                        $str=1;
                }

	        $ox=$ww['OX_POZYCJI'];
	        $oy=$ww['OY_POZYCJI'];
        };
	$ox=($ox?$ox:'0');
	$oy=($oy?$oy:'0');

        if ($str<0) {$str=-$str; $r=$rr;};        //jak cofa strony, to najpierw staje na ostatnim wierszu

        if ($r>$rr) {
                $r=$rr;
        };

        echo '$str='.$str.';';
        echo "\n";

//	if (substr($tabela,0,4)=='spec') {$r=1;}		//w tej tabeli zawsze zaczyna od pierwszego wiersza

        echo '$r='.$r.';';
        echo "\n";

        echo '$rr='.$rr.';';
        echo "\n";

        echo '$rrr='.$rrr.';';
        echo "\n";

        $cc=11;

        $c=$ww['NR_COL'];
        if (!$c) {$c=2;};
        if (!$sql) { exit;}
        else {
                $mc=0;
                $w=explode("\n",$sql);
                $z='Select';

                if (count($baza=explode(",",$w[0]))>1) {        // jest przecinek
	             if (count($baza=explode(",",$w[0]))>3) {   // s¹ nawet 3: abonenciG,grupy,[1].[2],abonenci
                        $baza=trim($baza[3]);
		     }
		     else {
                        $baza=$baza[0];
		     }
                }
                else {
                        $baza=trim($w[0]);
                }
                echo '$baza="'.$baza.'";';
                echo "\n";

                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
			               $w[$i]=trim($w[$i]);
//			               $w[$i]=str_replace('CENA_Z','CENA_Z*0',trim($w[$i]));
                        if     (!$w[$i]) {;}
                        elseif (substr($w[$i],0,4)=='from')   {     $z.=' '.$w[$i];}
                        elseif (substr($w[$i],0,5)=='where')  { 
                           if ($doktyp=='ALL') {
                              $zwhere=' '.str_replace("'".'$doktyp'."'",'dokum.TYP',$w[$i]);  //SQL injection
                           } else {
                              $zwhere=' '.str_replace('$doktyp',$doktyp,$w[$i]);
                           }
                        }
                        elseif (substr($w[$i],0,5)=='order')  { $zorder=' '.$w[$i];}
                        elseif (substr($w[$i],0,5)=='group')  { $zgroup=' '.$w[$i];}
                        elseif (substr($w[$i],0,5)=='union')  { $uniony[$zunion]=$w[$i];$zunion++;}
                        elseif (substr($w[$i],0,8)=='prequery'){$prequery[$zprequery]=substr($w[$i],9);$zprequery++;}
                        elseif (substr($w[$i],0,6)=='having') {$zhaving=' '.$w[$i];}
                        else {
                           if($i==1) {$z.=' ';} else {$z.=',';};
                           $l=explode("|",$w[$i]);
                           if (!($baza=='Select')&&(count(explode(".",$l[0]))<2)&&(count(explode("(",$l[0]))<2)) {
                                  $z.=$baza;
                                  $z.=".";
                           }
                           $z.=$l[0];
               				$pola[$i-1]=$l[0];
                           if(!$l[1]) {
               					$tn[$i-1]=trim($l[0]);
               				} else {
               					$tn[$i-1]=trim($l[1]);
               					if (count(explode("[",$tn[$i-1]))>1) {	//s¹ jakieœ odwo³ania w nazwie kolumny
               						$zz=explode('.',$tabelap);	//[1].[2]
               						for($ii=0;$ii<count($zz);$ii++) {
               							$jj=substr($zz[$ii],1)*1;
               							$tn[$i-1]=str_replace($zz[$ii],$tra[$jj],$tn[$i-1]);
               						}
               					}
               				}
                             $szer[$mc]=$l[2];                //szeroko¶æ

							if  ( ($baza=='towary')
								&&($_SESSION['osoba_dos']<>'T') 
								&&(substr($pola[$mc],0,2)=='((')
								) {
								$szer[$mc]='0';
							}

//                                if (substr($szer[$mc],0,1)=='+') {$c=$mc+1;};
                             $sumy[$mc]='';
                             if ((strpos($szer[$mc],'+')>0)||($szer[$mc]=='+')) {        //"+" z prawej
                                     $sumy[$mc]='0';
                                     $sumyok=true;
                             };
                             $styl[$mc]=$l[3];                //style="font-size: 70pt; color: red; font-weight: normal"
                             $styn[$mc]=$l[4];                //font-family: serif; font-size: 18pt; text-align: center
                             $mc++;
                        }
                }
                $cc=$mc;
//                $sql=$z.';';
        }
}

if ($tabelaa) {                                // tryb Master/Slave
   if ($zgroup) {
      if ($zwhere) {                        // jest "where", wiêc "and"
         $z.="$zwhere";
      }
      $z.=' '.$zgroup;                 // "group by" zamiast "where"
      if ($zhaving) {                // "having" za "group by"
         $z.="$zhaving";
         if (substr($tabelap,0,1)=='[') {        // odwo³anie do pól mastera
            $tr=explode('.',$tabelap);                // [1].[2]
            for($i=0;$i<count($tr);$i++) {
               $j=substr($tr[$i],1)*1;
               $z=str_replace($tr[$i],$tra[$j],$z);
            }
         } else {
            $z.=" and ($baza.$tabelap=$tabelai)";
         }
      } else {                                          // nie ma "having", wiêc ma byæ
      //                        $z.=" having $baza...$tabelap=$tabelai";
      }
      if ($warunek) {
      	$warunek="($warunek)";
      	if ($zhaving) {$z.=" and $warunek";} else {$z.=" having $warunek";}
      }
      if ($sortowanie) {
      	$z.=" order by $sortowanie";
      	$zorder='';
      }
   } else {
          if ($zwhere) {                        // jest "where", wiêc "and"
               $z.="$zwhere";
               if (substr($tabelap,0,1)=='[') {        // odwo³anie do pól mastera
                 $tr=explode('.',$tabelap);                // [1].[2]
                 for($i=0;$i<count($tr);$i++) {
      					$j=substr($tr[$i],1)*1;
      					$z=str_replace($tr[$i],$tra[$j],$z);
                 }
               } else {
                  $z.=" and ($baza.$tabelap=$tabelai)";
               }
   			if ($warunek) {
   				$warunek="($warunek)";
   				$z.=" and $warunek";
   			}
   			if ($sortowanie) {
   				$z.=" order by $sortowanie";
   				$zorder='';
   			}
         } else {                                          // nie ma "where", wiêc ma byæ
      		if ($warunek) {
      			$warunek="($warunek)";
      			$z.=" where ($baza.$tabelap=$tabelai) and $warunek";
      		} else {
               $z.=" where $baza.$tabelap=$tabelai";
      		}
      		if ($sortowanie) {
      			$z.=" order by $sortowanie";
      			$zorder='';
      		}
         }
   }
} else {                                                // tryb Slave
   if ($zgroup) {
      $z.="$zwhere ";                        // trzeba w koñcu uwzglêdniæ warunek "where"
      $z.=' '.$zgroup;                                                                          // "group by" zamiast "where"
      if ($zhaving) {                // "having" za "group by"
         $z.="$zhaving";
         if (substr($tabelap,0,1)=='[') {        // odwo³anie do pól mastera
             $tr=explode('.',$tabelap);                // [1].[2]
             for($i=0;$i<count($tr);$i++) {
                $j=substr($tr[$i],1)*1;
                $z=str_replace($tr[$i],$tra[$j],$z);
             }
         } elseif ($tabelap) {
            $z.=" and $baza.$tabelap=$tabelai";
         }
      } else {                                          // nie ma "having", wiêc ma byæ
      //                        $z.=" ...having $baza.$tabelap=$tabelai";
      }
      if ($warunek) {
      	$warunek="($warunek)";
      	if ($zhaving) {
            $z.=" and $warunek";
         } else {
            $z.=" having $warunek";
         }
      }
      if ($sortowanie) {
      	$z.=" order by $sortowanie";
      	$zorder='';
      }
   } else {
      if ($_GET['szukane']) {
         $zwhere=str_replace('[1]',$_GET['szukane'],$zwhere);
      } else {                                        // nic ne szukamy
         if (count($w=explode("[1]",$zwhere))>1) {  // definicja SQL jest przeznaczona do szukania
            $zwhere=''; // trzeba zrezygnowaæ z ograniczeñ
            $zorder=''; // trzeba zrezygnowaæ z uporz¹dkowania "po nazwie" na rzecz "po ID", bo po "Dopisz" by siê na nim nie ustawia³
         }
      }
      $z.="$zwhere ";                        // trzeba w koñcu uwzglêdniæ warunek "where"
      if ($warunek) {
         $warunek="($warunek)";
         if ($zwhere) {
            $z.=" and $warunek";
         } else {
            $z.=" where $warunek";
         }
      }
      if ($sortowanie) {
         $z.=" order by $sortowanie";
         $zorder='';
      }
   }
}
if ($zorder) {$z.=' '.$zorder;}         // "order by" za "where"
if ($zunion) {
	$z='('.$z.')';
	for($i=0;$i<$zunion;$i++) {
		$z.=' '.$uniony[$i];
	}
}
if (substr($tabelap,0,1)=='[') {        // odwo³anie do pól mastera m.in w "unionach"
	$tr=explode('.',$tabelap);           // [1].[2]
	for($i=0;$i<count($tr);$i++) {
		$j=substr($tr[$i],1)*1;				// 1 lub 2, a nawet 25 i wiêksze
		$z=str_replace($tr[$i],$tra[$j],$z);
		if ($zprequery) {
			for($k=0;$k<count($prequery);$k++) {
				$prequery[$k]=str_replace($tr[$i],$tra[$j],$prequery[$k]);
			}
		}
	}
}
$sql='';
if ($opole!="S") {                                        // nie suwanie siê
  if ($zprequery) {
	for($k=0;$k<count($prequery);$k++) {
		$prequery[$k]=str_replace('ID_master',$tabelai,$prequery[$k]);
		$prequery[$k]=str_replace('osoba_id',$_SESSION['osoba_id'],$prequery[$k]);
		$sql.='   '.$prequery[$k].';';
		if (substr($prequery[$k],0,1)=='?') {
			$prequery[$k]=substr($prequery[$k],1);
			$w=mysql_query($prequery[$k]);
			$w=mysql_fetch_row($w);
			$sql.='   '.$w[0].';';
			if ($w[0]) {$k=count($prequery);};	//finito prequerys
		}
		else {
			$w=mysql_query($prequery[$k]);
			if (strtoupper(substr(trim($prequery[$k]),0,6))=='SELECT') {	//jeœli typu "SELECT"
				$qs=mysql_fetch_row($w);										//to coœ zwraca
				$i=$k+1;					//nastêpne prequery
				if ($i<count($prequery)) {			//jeœli s¹ nastêpne, to
					for ($j=0;$j<count($qs);$j++) {		//korzystaj¹ ze swoich wyników
						$prequery[$i]=str_replace('{'.$j.'}',$qs[$j],$prequery[$i]);
					}
				}
			}
		}
	}
  }
}

if (strpos($z,'where')) {
   if (($baza=='firmy')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('where','where firmy.TYP not in ("D","M") and ',$z);
   }
   if (($baza=='dokum')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('where','where dokum.TYP_F not in ("D","M") and ',$z);
   }
} else {
   if (($baza=='firmy')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('from firmy','from firmy where firmy.TYP not in ("D","M") ',$z);
   }
   if (($baza=='dokum')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('from dokum','from dokum where dokum.TYP_F not in ("D","M") ',$z);
   }
}

$z=str_replace('$osoba_gr',$osoba_gr,$z);  // wra¿liwo¶æ na grupê usera
$z=str_replace('$osoba_pu',$osoba_pu,$z);  // wra¿liwo¶æ na punkt usera
$z=str_replace('osoba_id',$_SESSION['osoba_id'],$z);  // wra¿liwo¶æ na ID usera

$strr='';
$zz=explode('from',$z);
$zz='select count(*) from '.$zz[1];
$zz=explode(' order by',$zz);
$zz=$zz[0];

$n=0;
$sql.='<br>zz. '.$zz.';';
if ($w=mysql_query($zz)) {
	if (($n=mysql_num_rows($w))==1) {$w=mysql_fetch_row($w);$n=$w[0];}

   if ($tabela=='spec_sheet'&&$rrr<=$n) {  //je¶li pozycji jest wiêcej ni¿ pojemno¶æ arkusza
      $rrr=$n+20;                         //to zwiêksz pojemno¶æ do tej ilo¶ci + 10 pustych
   }

	$sql.='<br>ile. '.$n.';';
	$strr=floor(($n-1)/($rrr))+1;				// ile jest wszystkich stron ?
	$sql.='<br>str. '.$str.';';
	$sql.='<br>strr. '.$strr.';';
}

$zz=$z;						// bez limitu do obliczeñ jak nic nie wyjdzie

if ($strr<$str) {
	$n=0;		//i tak nic nie wyjdzie, wiêc nie rób tego zapytania, tylko od razu ustal ile to stron
} else {
	$z.=" limit ";					// "limit" na koñcu
	$z.=sprintf("%d",($str-1)*$rrr).",";
	$z.=sprintf("%d",$rrr);

	$sql.='<br>2. '.$z.';';
	$w=mysql_query($z);				// czêœæ zawartoœci tabeli Slave
	if ($w) {$n=mysql_num_rows($w);} else {$n=0;};
}

if (!$n) {					//jak z limitem, to puchy
	$sql.='<br>zz. '.$zz.';';
	$w=mysql_query($zz);			// ca³a zawartoœæ tabeli Slave bez limitu
	if ($w) {$n=mysql_num_rows($w);} else {$n=0;};
	if (!$n) {						// total puchy
		$r=1;
		$str=1;
		echo '$str=1;';
		echo "\n";
	} else {							//jak bez limitu, to coœ jest
		$sql.=';<br>n='.$n.';';
		$sql.=';<br>rrr='.$rrr.';';
		$strr=floor(($n-1)/($rrr))+1;			// ile to stron ?
		if ($str>$strr) {$str=$strr;}			//ograniczenie
		$sql.=';<br>strr='.$strr.';';
		echo '$str='.$str.';';
		echo "\n";

		$z=$zz." limit ";				// "limit" na koñcu
		$z.=sprintf("%d",($str-1)*$rrr).",";
		$z.=sprintf("%d",$rrr);

		$sql.='<br>'.$z.';';
		$w=mysql_query($z);				// czêœæ zawartoœci tabeli Slave
		if ($w) {$n=mysql_num_rows($w);} else {$n=0;};
		$r=($n<$r?$n:$r);
	}
	echo '$r='.$r.';';
	echo "\n";
} else {					//z limitem coœ jest
	if ($r>$n) {
		$r=$n;
		echo '$r='.$r.';';
		echo "\n";
	}
}

// tabela Slave (aktywna)
//********************************************************************

if ($cc<$c) {$c=$cc;};
echo '$c='."$c;\n";
echo '$cc='."$cc;\n";

//********************************************************************

if ($sumyok) {
	$sumyok=($str==1&&$strr==1);
}

?>

function Odswiez($kierunek){
        if ($str==0) {
                $str=1;
                f0.strpole.value=$str;
                return false;
        };
        if (($rr<$rrr)&&($kierunek>0)) {                // str niepe³na wiêc na pewno dalej nic nie ma
                $str-=1;                                // nie skacz w przepa¶æ (przed chwil± $str by³o zwiêkszone o jeden)
                f0.strpole.value=$str*$kierunek;
                return false;
        };
        f0.strpole.value=$str*$kierunek;
        f0.opole.value="S";                // dotyczy wêdrówki po tabeli (inna strona)
        f0.odswiez.click();
        return true;
};
function Przesun() {
//   if(timerID) {clearTimeout(timerID);}
	f1.style.pixelTop=document.body.scrollTop; //+(-2);
	f1.style.pixelLeft=document.body.scrollLeft+0; //(-2);
        f0.offsetX.value=document.body.scrollLeft;
        f0.offsetY.value=document.body.scrollTop;
	timerID  = setTimeout("Przesun()", 100);
}

function Start(){
	scrollTo(<?php echo $ox;?>,<?php echo $oy;?>);
   timerID  = setTimeout("Przesun()", 100);
	f0.zaznaczone.value=$zaznaczone;
   $rr=<?php echo (($tabela=='spec_sheet')?$rrr:'tab.summary');?>;                //przejêcie praktycznej ilo¶ci wierszy na stronie z tajnego elementu tabeli wype³nianego przez PHP podczas definowania strony

<?php if ($tabela=='spec_sheet') {
         echo 'document.getElementById("towar_'.$n.'_3").focus();';
         echo '$r='.($n+1).';';
         echo '$c=4;';
      }?>

   if ($rr==0) {                //cofaj stronê, gdy pusta
          $str-=1;
          tab_kolor();
          if (Odswiez(-1)) {
                  return;
          }
   }
   klawisz(<?php echo "'".$_GET['klawisz']."'"; ?>);
}
//*********************************************************************************************
//function sendCtrlF() {
//  var evtObj = document.createEventObject();
//
//  var str = 'F';
//  evtObj.altKey = true;
//  evtObj.keyCode = str.charCodeAt(0);
//  
//  document.fireEvent("onkeydown",evtObj);
//  event.cancelBubble = true;
//} 
//*********************************************************************************************
function klawisz($skok) {

var $zombie=true;
//alert(event.keyCode);
//alert(String.fromCharCode(event.keyCode));
//if (String.fromCharCode(event.keyCode)=='Z') {sendCtrlF();}

        tab_czysc();

// do pierwszej
        if (($skok=='1')&&($str>1)) {$str=1; if (Odswiez(-1)) {return;};};

// do poprzedniej
        if (($skok=='p')&&($str>1)) {$str-=1; if (Odswiez(-1)) {return;};};

// do nastêpnej
<?php if ($tabela<>'spec_sheet') {?>
        if ($skok=='n') {$str+=1; if (Odswiez(1)) {return;};};
<?php }?>

// do ostatniej
<?php if ($tabela<>'spec_sheet') {?>
        if ($skok=='o') {$str=999999; if (Odswiez(1)) {return;};};
<?php }?>

// w dó³
<?php if ($tabela<>'spec_sheet') {?>
        if ((event.keyCode==40)&&$r==$rr)  {$str+=1; if (Odswiez(1)) {return;};}
<?php }?>
        if ((event.keyCode==40)&&$r<$rr)   {

<?php if ($tabela=='spec_sheet') {?>
         if ($c!=7) {
           $c=4;
         }
        document.getElementById("towar_"+$r+"_"+($c-1)).focus();
<?php }?>
            $r+=1;
            $zombie=false;
        }

// w górê
        if ((event.keyCode==38)&&$r==1)    {$str-=1; if (Odswiez(-1)) {return;};};
        if ((event.keyCode==38)&&$r>1)     {
            $r-=1;
            $zombie=false;
<?php if ($tabela=='spec_sheet') {?>
         if ($c!=7) {
           $c=4;
         }
        document.getElementById("towar_"+($r-1)+"_"+($c-1)).focus();
        if ($r<10&&document.body.scrollTop<200&&document.body.scrollTop>0) { 
//           alert(document.body.scrollTop);
           scrollTo(document.body.scrollLeft,document.body.scrollTop-60);
        } 
<?php }?>
        }

// w prawo
        if ((event.keyCode==39)&&$c<$cc)   {$c+=1;$zombie=false;}

// w lewo
        if ((event.keyCode==37)&&$c>1)     {$c-=1;$zombie=false;}

// PgUp
        if ((event.keyCode==33))    {$str-=1; if (Odswiez(-1)) {return;};}; //&&$r==1
        if (event.keyCode==33)             {
            $r=1;

<?php if ($tabela=='spec_sheet') {?>
         if ($c!=7) {
           $c=4;
         }
        document.getElementById("towar_"+($r-1)+"_"+($c-1)).focus();
        scrollTo(0);
<?php }?>

        }

// PgDn
<?php if ($tabela<>'spec_sheet') {?>
        if ((event.keyCode==34))  {$str+=1; if (Odswiez(1)) {return;};};  //&&$r==$rr
<?php }?>

        if (event.keyCode==34)             {
            $r=$rr;

<?php if ($tabela=='spec_sheet') {?>
         if ($c!=7) {
           $c=4;
         }
        document.getElementById("towar_"+($r-1)+"_"+($c-1)).focus();
<?php }?>

        }

// Enter
<?php if ($tabela=='spec_sheet') {?>
        if (event.keyCode==13)             {
            if ($c==4) {
               if (document.getElementById("towar_"+($r-1)+"_"+($c-1)).value) {
//                  $c=10;
                  $r++;
                  $r=($r>$rr?$rr:$r);
                  document.getElementById("towar_"+($r-1)+"_"+($c-1)).focus();
               }
            } else {
               Ajax_ilosc(document.getElementById("towar_"+($r-1)+"_"+($c-1)),$r-1,$c);
               $c=4;
               if ($r<$rr) {
                  $r++;
               }
               document.getElementById("towar_"+($r-1)+"_"+($c-1)).focus();
            }
        }
<?php }?>


// Home
        if (event.keyCode==36)             {$c=1; scrollTo(0);};

// End
        if (event.keyCode==35)             {$c=$cc; scrollTo(1000);};
        tab_kolor();
//        tab.focus();        //po wybraniu Alt+W focus zostaje na "Wydruk" i Enter uruchamia go ponownie, a jest mi potrzebny do "Formularz=Enter", wiêc focus musi siê przenie¶æ do objektu nieaktywnego, np.: tabeli.
   if ($skok=='F') {Formularz();}

   return $zombie;
}
document.onkeydown=klawisz;

function projectstart() {
	f0project.style.visibility="visible";
	document.getElementById('projectKod').focus();
}

function projectstop() {
	f0project.style.visibility="hidden";
}

function projectacti($x) {
	if (!$x) {
		$x=document.getElementById('projectKod').value;
		f0.action="projectaction.php?projectKod="+$x;
	} else {
		if ($x=='c') {											//current column
	        $x=eval('tab_'+($r-1)+'_'+($c-1)+'.innerHTML');     //zawarto¶æ bie¾¥cego pola tabeli
		} else {
	        $x=eval('tab_'+($r-1)+'_'+($x-1)+'.innerHTML');     //zawarto¶æ wskazanego w $x pola tabeli
		}
		f0.action="projectaction.php?natab=towarywyb&projectKod="+$x;
		f0.odswiez.click();
	}
}

function enter(){

if (f0project.style.visibility=="visible") {
	if (event.keyCode==13) {
		projectacti();
	}
	if (event.keyCode==27) {
		projectstop();
	}
	return;	
}

if (event.keyCode==13) {<?php
	if (!$fun) { exit;}
	else {
		$f=explode("\n",$fun);
		$cc=Count($f);
		for($i=0;$i<$cc;$i++) {
			$l=explode("|",$f[$i]);
			if ($l[0]=='Enter') {
				$ok=true;
				if ($l[3]) {
					if (count(explode("[",$l[3]))>1) {	//s¹ jakieœ odwo³ania do mastera
						$zz=explode('.',$tabelap);	//[1].[2]
						for($ii=0;$ii<count($zz);$ii++) {
							$jj=substr($zz[$ii],1)*1;
							$l[3]=str_replace($zz[$ii],strip_tags($tra[$jj]),$l[3]);
						}
						eval('$ok=('.$l[3].');');	//np.: [1]=='O' => dokument jest otwarty
					}
				}
				if ($ok) {
					echo trim($l[2]).';';
				}
			}
		}
	}
?>}
else if (event.keyCode==27) {<?php
	$ok=false;
	if (!$fun) { exit;}
	else {
		$f=explode("\n",$fun);
		$cc=Count($f);
		for($i=0;$i<$cc;$i++) {
			$l=explode("|",$f[$i]);
			if ($l[0]=='Esc') {
				echo trim($l[2]).';';
				$ok=true;
			}
		}
	}
	if (!$ok) {
		echo 'location.href="Tabela_Zapisz.php?ID="';
		echo "+eval('tab_'+($r-1)+";
		echo '"_0.innerHTML")+"&r="+$r+"&c="+$c+"&str="+$str+"&idtab="+$idtab+"&phpf=Tabela_End.php";';
	}
?>}
<?php
        if ($fun) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
			$x=$l[0];
                        if ($x=='Esc'||$x=='Enter'||$x=='') {;}
			else {
			   if ($l[1]<>'') {
				$ok=true;
				if ($l[3]) {
					if (count(explode("[",$l[3]))>1) {	//s¹ jakieœ odwo³ania do mastera
						$zz=explode('.',$tabelap);	//[1].[2]
						for($ii=0;$ii<count($zz);$ii++) {
							$jj=substr($zz[$ii],1)*1;
							$l[3]=str_replace($zz[$ii],strip_tags($tra[$jj]),$l[3]);
						}
						eval('$ok=('.$l[3].');');	//np.: [1]=='O' => dokument jest otwarty
					}
				}
				if ($ok) {
					$l[2]=trim($l[2]);
					$ww=$l[1];
				        $ww=mysql_query("select OK from osobyprawa where ID_OSOBY=$osoba_id and OPCJA='$ww'");
					if ($ww) {$ww=mysql_fetch_row($ww);$ww=($ww[0]=='-');}
					if (!$ww) {
						echo "else if (String.fromCharCode(event.keyCode).toUpperCase()=='$x') {";
                		                echo trim($l[2]).';}'."\n";
					}
				}
			   }
                        }
                }
        }
?>
}
document.onkeypress=enter;

function mysza($x,$y){
   tab_czysc();
	if ($x>=0) {
<?php if ($tabela<>'spec_sheet') {?>
      if (($r==$rr)&&($r==$x+1))  {$str+=1; if (Odswiez(1)) {return;};};
<?php }?>
      if (($r==1)&&($r==$x+1))    {$str-=1; if (Odswiez(-1)) {return;};};
      $r=$x+1;
	}
   $c=$y+1;
   tab_kolor();
}
function mysza2($x,$y){
        tab_czysc();
        $r=$x+1;
        $c=$y+1;
        tab_kolor();
<?php
        if (!$fun) { exit;}
        else {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if ($l[0]=='Enter') {
                                echo $l[2].';';
                        }
                }
        }
?>
}
function mysza3($x,$y){
			tab_czysc();
			$r=$x+1;
			$c=$y+1;
			tab_kolor();
			if (eval('cb_'+($r-1)+'.checked')) {eval('cb_'+($r-1)+'.checked=false;');}
			else {eval('cb_'+($r-1)+'.checked=true;');}
}
function nag_kolor($x) {
//        $posx=$x;
   eval('tab1'+$x+'.style.background="'+$cnag+'";');                //nag³ówek
   moze_klawisze=false;
}
function nag_czysc($x){
   eval('tab1'+$x+'.style.background="";');
   moze_klawisze=true;
}
function tab_czysc(){
	if (!$r==0&&$r<=$rr+1) {
   		eval('tab_'+$r+'.style.background="'+$twie+'";');  //wiersz
	}
	if (!$c==0&&$c<=$cc+1) {
   		eval('tab_0'+$c+'.style.background="'+$tnag+'";'); //nag³ówek
	}
//	document.getElementById('tab_'+($r-1)+'_'+($c-1)).style.outline="none";  //komórka
//	eval('tab_'+($r-1)+'_'+($c-1)+'.style.background="'+$twie+'";');  //komórka
//	eval('tab_'+($r-1)+'_'+($c-1)+'.style.borderTop="solid 1pt gray";');  //komórka
//	eval('tab_'+($r-1)+'_'+($c-1)+'.style.borderLeft="solid 1pt gray";');  //komórka
//	eval('tab_'+($r-1)+'_'+($c-1)+'.style.borderRight="solid 1pt #CCCCCC";');  //komórka
//	eval('tab_'+($r-1)+'_'+($c-1)+'.style.borderBottom="solid 1pt #CCCCCC";');  //komórka
}
function tab_kolor(){
        f0.batab.value=$baza;        //nazwa bazy dla tabeli
//        f0.sutab.value="";                //nazwa subtabeli
//        f0.sutabpol.value="";        //nazwa pola ³±cza subtabeli
        f0.idtab.value=$idtab;        //identyfikator tabeli
        f0.natab.value=$tabela;        //nazwa tabeli
        f0.opole.value="";                // operacja
        f0.ipole.value=eval('tab_'+($r-1)+'_0.innerHTML');        //identyfikator wiersza tabeli (zawarto¶æ 1 kolumny)
//        f0.fpole.value=eval('tab_'+($r-1)+'_'+($c-1)+'.innerHTML');        //zawarto¶æ pola tabeli
        f0.kpole.value=event.keyCode;
        f0.rpole.value=$r;
        f0.cpole.value=$c;
        f0.rrpole.value=$rr;
        f0.rrrpole.value=$rrr;
        f0.strpole.value=$str;
//		if (($tabela=='dokum' || $tabela=='abonencisz') && eval('tab_'+($r-1)+'_13.innerHTML')=='') {
//        eval('tab_'+$r+'.style.background="gray";');                //wiersz
//		}
//		else {
        eval('tab_'+$r+'.style.background="'+$cwie+'";');                //wiersz
//		}
        eval('tab_0'+$c+'.style.background="'+$cnag+'";');                //nag³ówek

//		document.getElementById('tab_'+($r-1)+'_'+($c-1)).style.outline="double solid blue";  //komórka
//		getElementByID('tab_'+($r-1)+'_'+($c-1)).style.outline="double solid red";  //komórka
//		eval('tab_'+($r-1)+'_'+($c-1)+'.style.outline="double solid red";');  //komórka
//		eval('tab_'+($r-1)+'_'+($c-1)+'.style.background="'+$cnag+'";');  //komórka
//		eval('tab_'+($r-1)+'_'+($c-1)+'.style.border="solid 2pt blue";');  //komórka
}
//echo '<input type="checkbox" id="cb_'.$i.'">';

function round_float(x,n){
  if(!parseInt(n))
  	var n=2;
  if(!parseFloat(x))
  	return 0;
  return Math.round(x*Math.pow(10,n))/Math.pow(10,n);
}

function Zaznacz($mode,$kolumna) {
	if (!$mode) {
        if (!$r==0&&$r<=$rr+1) {
            eval('tab_'+$r+'.style.background="'+$twie+'";')
        }
        if (eval('cb_'+($r-1)+'.checked')) {
			eval('cb_'+($r-1)+'.style.background="none";');
			eval('cb_'+($r-1)+'.checked=false;');
           f0.zaznaczone.value='x';
           if ($kolumna) {
             s=eval('tab_'+($r-1)+'_'+($kolumna-1)+'.innerHTML');
             if (s!="") {
	             xx=s.indexOf(',',s);
	             if (!(xx==0)) {
	               s=s.substring(0,xx)+s.substring(xx+1); 
	             }
	           	eval('tab_0'+($kolumna+1)+'.innerHTML=round_float(-parseFloat("'+s+'")+parseFloat(tab_0'+($kolumna+1)+'.innerHTML))+"&nbsp;z³"');	// = do rozliczenia
             }
            }
	  	} else {
			eval('cb_'+($r-1)+'.style.background="black";');
			eval('cb_'+($r-1)+'.checked=true;');
		    if (f0.zaznaczone.value!=='x') {
		        if (f0.zaznaczone.value) {f0.zaznaczone.value+=',';}//oddzielone przecinkami
		        f0.zaznaczone.value+=eval('tab_'+($r-1)+'_0.innerHTML');//lista zaznaczonych oplaty.ID
			}
            if ($kolumna) {
	             s=eval('tab_'+($r-1)+'_'+($kolumna-1)+'.innerHTML');
	             if (s!="") {
		             xx=s.indexOf(',',s);
		             if (!(xx==0)) {
		               s=s.substring(0,xx)+s.substring(xx+1); 
		             }
		             eval('tab_0'+($kolumna+1)+'.innerHTML=parseFloat(tab_0'+($kolumna+1)+'.innerHTML)');
		             if (eval('tab_0'+($kolumna+1)+'.innerHTML')=='NaN') {
		                eval('tab_0'+($kolumna+1)+'.innerHTML=0.00');
		             }
		             eval('tab_0'+($kolumna+1)+'.innerHTML=round_float(parseFloat("'+s+'")+parseFloat(tab_0'+($kolumna+1)+'.innerHTML))+"&nbsp;z³"');	// = do rozliczenia
	             }	
            }
	  	}
//        if ($r==$rr)  {$str+=1; if (Odswiez(1)) {return;};};
        if ($r<$rr)   {$r+=1};
        tab_kolor();
	}
	if ($mode==1) {
		for($ij=0;$ij<$rr;$ij++) {eval('cb_'+($ij)+'.checked=true;');}
		f0.zaznaczone.value='x';
	}
	if ($mode==2) {	//odzaznacz wszystko
		for($ij=0;$ij<$rr;$ij++) {eval('cb_'+($ij)+'.checked=false;');}
		f0.zaznaczone.value='x';
	}
	if ($mode==3) {	//przeznacz wszystko
		for($ij=0;$ij<$rr;$ij++) {
       if (eval('cb_'+($ij)+'.checked')) {
				  eval('cb_'+($ij)+'.checked=false;');}
        else {eval('cb_'+($ij)+'.checked=true;');}
		}
		f0.zaznaczone.value='x';
	}
   if (!$r==0&&$r<=$rr+1) {eval('cb_'+($r-1)+'.focus();')};
}
//Formularz('WYKAZY','ID_ODBIO')                                  // plik php inicjuj±cy pola
function Zaznaczal(){
   if (f0.zaznaczone.value=='x') {		//coœ zaznacza³, ale kolejnoœæ trudno ustaliæ
        f0.zaznaczone.value='';
        for($i=0;$i<$rr;$i++) {
			if (eval('cb_'+$i+'.checked')) {
	        if (f0.zaznaczone.value) {f0.zaznaczone.value+=',';}//oddzielone przecinkami
	        f0.zaznaczone.value+=eval('tab_'+$i+'_0.innerHTML');//lista zaznaczonych oplaty.ID
			}
        }
	}
}
function Formularz($nata,$polenaID,$bata,$phpini,$opo){
	Zaznaczal();
   f0.phpini.value=$phpini;                         // przekazanie do formularza
   if ($opo) {f0.opole.value=$opo};						 // operacja
   f0.sutabpol.value="";                            // mo¿e to tylko Esc (bez zapisu)
	if (isNaN($nata)) {
	   if ($nata) {					// jeste¶my w WYKAZYODBW
         if (!$bata) {$bata=$nata};               // i wci¶niêto Enter lub Esc
         f0.natab.value=$nata;                    // nazwa tabeli formularza
         f0.batab.value=$bata;                    // nazwa bazy tabeli formularza
         f0.idtab.value=-f0.idtab.value;          // niech sobie sam ustali
         f0.sutabpol.value=$polenaID;             // ID_ODBIO trzeba wype³niæ
         f0.sutabmid.value=f0.ipole.value;        // identyfikatorem pozycji
	   }
      f0.action="Tabela_Formularz.php?tabela="+f0.batab.value+"&ID="+f0.ipole.value;   // akcja
   } else {
	   f0.action="Tabela_Formularz.php?blokada="+eval('tab_'+($r-1)+"_"+($nata-1)+".innerHTML");
	}
   f0.odswiez.click();
}
function Szukaj($nata,$polenaID,$bata,$phpini){
        f0.phpini.value=$phpini;                         // przekazanie do formularza
        f0.sutabpol.value="";                            // mo¿e to tylko Esc (bez zapisu)
        f0.opole.value="S";                              // operacja
        if ($nata) {                                     // jeste¶my w WYKAZYODBW
                if (!$bata) {$bata=$nata};               // i wci¶niêto Enter lub Esc
                f0.natab.value=$nata;                    // nazwa tabeli formularza
                f0.batab.value=$bata;                    // nazwa bazy tabeli formularza
                f0.idtab.value="";                       // niech sobie sam ustali
                f0.sutabpol.value=$polenaID;             // ID_ODBIO trzeba wype³niæ
                f0.sutabmid.value=f0.ipole.value;        // identyfikatorem pozycji
        }
        f0.action="Tabela_Szukaj.php";                // akcja
        f0.odswiez.click();
}
function XSzukaj($polaszukane,$nata,$polenaID,$bata,$phpini){
        f0.phpini.value=$polaszukane;                    // przekazanie do formularza
        f0.sutabpol.value="";                            // mo¿e to tylko Esc (bez zapisu)
        f0.opole.value="S";                              // operacja
        if ($nata) {                                     // jeste¶my w WYKAZYODBW
                if (!$bata) {$bata=$nata};               // i wci¶niêto Enter lub Esc
                f0.natab.value=$nata;                    // nazwa tabeli formularza
                f0.batab.value=$bata;                    // nazwa bazy tabeli formularza
                f0.idtab.value="";                       // niech sobie sam ustali
                f0.sutabpol.value=$polenaID;             // ID_ODBIO trzeba wype³niæ
                f0.sutabmid.value=f0.ipole.value;        // identyfikatorem pozycji
        }
        f0.action="Tabela_XSzukaj.php";                // akcja
        f0.odswiez.click();
}
function Sortuj($nata,$polenaID,$bata,$phpini){
        f0.phpini.value=$phpini;                         // przekazanie do formularza
        f0.sutabpol.value="";                            // mo¿e to tylko Esc (bez zapisu)
        f0.opole.value="X";                              // operacja
        if ($nata) {                                     // jeste¶my w WYKAZYODBW
                if (!$bata) {$bata=$nata};               // i wci¶niêto Enter lub Esc
                f0.natab.value=$nata;                    // nazwa tabeli formularza
                f0.batab.value=$bata;                    // nazwa bazy tabeli formularza
                f0.idtab.value="";                       // niech sobie sam ustali
                f0.sutabpol.value=$polenaID;             // ID_ODBIO trzeba wype³niæ
                f0.sutabmid.value=f0.ipole.value;        // identyfikatorem pozycji
        }
        f0.action="Tabela_Sortuj.php";                // akcja
        f0.odswiez.click();
}
//R|Rozlicz|Rozlicz('specrozkp','specrozkp','RozliczINI.php')
function Rozlicz($nata,$bata,$phpini,$polenaID){
	Zaznaczal();
//        f0.zaznaczone.value='';
//        for($i=0;$i<$rr;$i++) {
//		if (eval('cb_'+$i+'.checked')) {
//	        if (f0.zaznaczone.value) {f0.zaznaczone.value+=',';}//oddzielone przecinkami
//	        f0.zaznaczone.value+=eval('tab_'+$i+'_0.innerHTML');//lista zaznaczonych oplaty.ID
//		}
//        }
        f0.phpini.value=$phpini;                         // przekazanie do formularza
        f0.sutabpol.value="";                            // mo¿e to tylko Esc (bez zapisu)
        f0.opole.value="F";                              // operacja
        if ($nata) {                                     // jeste¶my w WYKAZYODBW
                if (!$bata) {$bata=$nata};               // i wci¶niêto Enter lub Esc
                f0.natab.value=$nata;                    // nazwa tabeli formularza
                f0.batab.value=$bata;                    // nazwa bazy tabeli formularza
                f0.idtab.value="";                       // niech sobie sam ustali
                f0.sutabpol.value=$polenaID;             // ID_ODBIO trzeba wype³niæ
                f0.sutabmid.value=f0.ipole.value;        // identyfikatorem pozycji
        }
        f0.action="Tabela_Rozlicz.php";                // akcja
        f0.odswiez.click();
}
function Kopiuj($phpini){                                                // plik php inicjuj±cy pola
        f0.phpini.value=$phpini;                                         // przekazanie do formularza
        f0.opole.value="D";                                                         // operacja
        if (f0.ipole.value=="0") {                // identyfikator zerowy, czyli tabela pusta
                f0.opole.value="N";
                f0.ipole.value=eval('tab_'+($r-1)+'_1.innerHTML');        //zawarto¶æ 2 kolumny, bo to mo¿e byæ interesuj±ce, a kolumna 1 niewa¿na, bo to dopisywanie, wiêc i tak siê zaraz zmieni na now±
        }
        f0.action="Tabela_Formularz.php";                // akcja
        f0.odswiez.click();
}
function Dopisz($phpini){                                                // plik php inicjuj±cy pola
        f0.phpini.value=$phpini;                                         // przekazanie do formularza
        f0.opole.value="N";                                                         // operacja
        f0.ipole.value="0";                // identyfikator zerowy, czyli tabela pusta
        f0.ipole.value=eval('tab_'+($r-1)+'_1.innerHTML');        //zawarto¶æ 2 kolumny, bo to mo¿e byæ interesuj±ce, a kolumna 1 niewa¿na, bo to dopisywanie, wiêc i tak siê zaraz zmieni na now±
	if ((f0.ipole.value=='')||!((1*f0.ipole.value)==f0.ipole.value)) {		//nie liczba, wiêc siê nie przyda jako ID sub
	        f0.ipole.value=eval('tab_'+($r-1)+'_0.innerHTML');        //zawarto¶æ 1 kolumny
	};
        f0.action="Tabela_Formularz.php";                // akcja
        f0.odswiez.click();
}
function Usun($tabsub,$tabpole,$phpini){
<?php
	if ($osoba_gr>0) {
?>
if (confirm('Na pewno usun±æ t± pozycjê ?')) {
   if ($tabsub) { //  U|Usuñ|Usun('WYKAZYSPE','ID_WYKAZY')
        location.href="Tabela_Usun.php?ID="+eval('tab_'+($r-1)+"_0.innerHTML")+"&tabela="+$tabela+"&baza="+$baza+"&r="+$r+"&c="+$c+"&str="+$str+"&phpini="+$phpini+"&tabsub="+$tabsub+"&tabpole="+$tabpole;
   } else {       //  U|Usuñ|Usun()
        location.href="Tabela_Usun.php?ID="+eval('tab_'+($r-1)+"_0.innerHTML")+"&tabela="+$tabela+"&baza="+$baza+"&r="+$r+"&c="+$c+"&str="+$str+"&phpini="+$phpini;
   }
}
<?php
	}
?>
}
function Login(){
        f0.opole.value='L';                                                         // operacja
        f0.action="Tabela_Formularz.php";                // akcja
        f0.odswiez.click();
}
function NowaTabela(){
        location.href="Tabela_Create.php?ID="+eval('tab_'+($r-1)+"_0.innerHTML")+"&tabela="+$tabela;
}
function SioTabela(){
	if (confirm('Na pewno usun±æ t± tabelê ?')) {
        location.href="Tabela_Drop.php?ID="+eval('tab_'+($r-1)+"_0.innerHTML")+"&tabela="+$tabela;
	}
}
function TabSub($tabsub,$tabpole){          //TabSub('WYKAZYSPE','ID_WYKAZY')
        f0.opole.value="T";                 // operacja wywo³anie podtabeli z tabeli
        f0.sutab.value=$tabsub;             //nazwa subtabeli
        f0.sutabpol.value=$tabpole;         //nazwa pola ³±cza subtabeli
        f0.action="Tabela.php";
        f0.odswiez.click();
}
function Wydruk($tabsub,$tabpole,$plik,$czy_zera){    //Wydruk('WYKAZYSPE','ID_WYKAZY')
        f0.opole.value="T";                 // operacja wywo³anie podtabeli z tabeli
        f0.sutab.value=$tabsub;             //nazwa subtabeli
        f0.sutabpol.value=$tabpole;         //nazwa pola ³±cza subtabeli
		  if ($czy_zera===1) {
	        f0.opole.value='';
   	     f0.sutab.value='';
      	  f0.sutabpol.value='';
		  }
        if ($plik) {f0.action="Wydruk.php?wydruk="+$plik;}
        else       {f0.action="Wydruk.php?wydruk=Wydruk";}
        f0.odswiez.click();
}
function WydrukWzor($wzor){
	if (isNaN($wzor)) {								// nazwa wzoru, np. KP, FA
		f0.action="WydrukWzor.php?wzor="+$wzor;
	} else {
//      f0.ipole.value=eval('tab_'+($r-1)+'_1.innerHTML');
		f0.action="WydrukWzor.php?wzor="+eval('tab_'+($r-1)+"_"+($wzor-1)+".innerHTML");
	}
	f0.odswiez.click();
}
function Raport($tabsub,$tabpole,$typ){          //Wydruk('WYKAZYSPE','ID_WYKAZY')
		if (!$typ) {$typ='';};
		Wydruk($tabsub,$tabpole,'Raport'+$typ);
}
function Lista($tabsub,$tabpole){        //Wydruk('WYKAZYSPE','ID_WYKAZY')
		Wydruk($tabsub,$tabpole,'Raport',1);
}
function Adres($ko,$h){
	$ok=(1==2);
	if (!$h) {$ok=(1==1);}
	else {
		if (confirm($h)) {$ok=(1==1);};
	}
   if ($ok) {
      f0.sutab.value="";                                        //czy¶æ, bo to koniec chodzenia po subtabeli slave
      if (isNaN($ko)) {                                                // nazwa tabeli
             f0.natab.value=$ko;
             f0.action="Tabela.php";
             f0.odswiez.click();
      } else { // $ko=1 => numer kolumny zawieraj±cej id tabeli
         if (!$ko) {
            f0.natab.value=f0.ipole.value;
         } else {
         f0.natab.value=eval('tab_'+($r-1)+"_"+($ko-1)+".innerHTML");
         }
         f0.action="Tabela.php";
         f0.odswiez.click();
      }
   }
}
function PlikHTML($ko,$wzor){
   f0.phpini.value=$ko;
	if (isNaN($wzor)) {								// nazwa wzoru, np. KP, FA
		f0.action="PlikHTML.php?wzor="+$wzor;
	}
	else {
		f0.action="PlikHTML.php?wzor="+eval('tab_'+($r-1)+"_"+($wzor-1)+".innerHTML");
	}
	f0.odswiez.click();
}
//3|3.ile ?|PlikPHP('Tabela_SQL.php','Obliczyæ ?','select MC1, MC2 from tab_master where ID=id_master; select count(*), ` pozycji p³aconych na KP` from wplaty where WYSWPL<>0 and ZAMIESIAC between [0] and [1]')
//f02 to formularz 0 lub formularz 2 (t³o abonentów)
function PlikPHP($ko,$h,$pa,$f02){
//	if ($pa) {$h=$h+"\n"+$pa;}
	Zaznaczal();
	$ok=(1==2);
	if ($h=='') {
		$ok=(1==1);
	} else {
		if ($h.indexOf(':',$h)>1) {
			if ($h=prompt($h,$rr)) {
				$ko=$ko+"?parametr=wpisane&wpisane="+$h;
				$ok=(1==1);
			}
		} else {
			if (confirm($h)) {
				$ok=(1==1);
			}
		}
	}
	if ($ok) {
		f0.phpini.value=$pa;
		f0.action=$ko;
		f0.odswiez.click();
	}
}
function PlikPHP2($ko,$h){
	if (confirm($h)) {
		f2.action=$ko;
		f2.zapiszsz.click();
	}
}
function PlikPHP3($h){
	if (confirm($h)) {
		spec_sheet.submit();
	} else {
		spec_sheet.reset();
		spec_sheet.submit();
   }
}
function OknoForm(){
	f0.zaznaczone.value='1';
	Zaznaczal();
	open("Tabela_Rozlicz2.php?zaznaczone="+f0.zaznaczone.value,"title","directories=no,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,toolbar=no,width=400,left=390,height=380");	//
}
function Rabaty($dokum_tab,$spec_tab,php){
	if (!php) {php='Rabaty';}
	open(php+".php?dokum_tab="+$dokum_tab+"&spec_tab="+$spec_tab,"title","directories=no,location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,top=200,width=250,left=290,height=20");
}
function Stop() {if (timerID) {clearTimeout(timerID);}}
function WieleStron($h) {
	if ($h=prompt('Podaj ilo¶æ wierszy na stronie tej tabeli ( jednorazowa zmiana tymczasowa ) :',$rr)) {
		f0.action="Tabela.php?tabela="+$tabela+"&maxrow="+$h;
		$str=($str-1)*$rrr+$r;		//obecna pozycja
		$str=($str-1)/$h+1;	//strona z t¹ pozycj¹ w nowych warunkach iloœci wierszy na stronie
                f0.strpole.value=$str;
	        f0.opole.value="S";
		f0.odswiez.click();
	}
}
-->
</script>

</head>

<?php
if ($_SESSION['osoba_se']) {
	?>

<body bgcolor="#000000" onload="Start()" onunload="Stop()">

	<?php
}
else {
	echo '<body onload="Start()" onunload="Stop()">';
}
echo "\n";
echo '<br style="font-size: 12pt">';
echo '<br style="font-size:  6pt">';

if ($tabelaa) { // tabela master (ta nieaktywna)
	echo '<br style="font-size: 12pt">';
	echo '<br style="font-size: 12pt">';
	echo '<br style="font-size:  6pt">';
}

echo "\n";

?>

<form id="f1"><?php

$ileobok=0;

if ($tabela=='spec_sheet') {
	echo '<table border=1 cellpadding=3 cellspacing=0 style="font-size: 7pt;"><tr><td>';
} else {
	echo '<table width="1000" border=0 cellpadding=7 cellspacing=0 style="font-size: 7pt;"><tr><td>';
}

if ($fun) {
	$f=explode("\n",$fun);
	$cc=Count($f);
	$ok_esc=false;
	for($i=0;$i<$cc;$i++) {
		$l=explode("|",$f[$i]);
		if ($l[0]=="Esc") {
			$ok_esc=true;
		}
	}

	echo '<div class="demo">';

	if (!$ok_esc) {
		if ($tyt<>'Stan magazynu') {
			echo '<input style="cursor:hand;" type="button" value="Esc=wyj¶cie" onclick="window.close()"/>';echo "\n";
		}
	}
	for($i=0;$i<$cc;$i++) {
		$l=explode("|",$f[$i]);
		if (($l[1])&&($l[3]=='1')) {
			$ileobok++;
		} elseif ($l[1]) {
			$ok=true;
			if ($l[3]) {
				if (count(explode("[",$l[3]))>1) {	//s¹ jakieœ odwo³ania do mastera
					$zz=explode('.',$tabelap);	//[1].[2]
					for($ii=0;$ii<count($zz);$ii++) {
						$jj=substr($zz[$ii],1)*1;
						$l[3]=str_replace($zz[$ii],strip_tags($tra[$jj]),$l[3]);
					}
					eval('$ok=('.$l[3].');');	//np.: [1]=='O' => dokument jest otwarty
				}
			}
			if ($ok) {
				$l[2]=trim($l[2]);
				$ww=$l[1];
				//				        $ww=mysql_query("select OK from osobyprawa where ID_OSOBY=$osoba_id and OPCJA='$ww'");
				//					if ($ww) {$ww=mysql_fetch_row($ww);$ww=($ww[0]=='-');}
				//					if (!$ww) {
				if (
				(substr($l[1],0,3)=='Wyd')
				|| (substr($l[1],0,3)=='Lis')
				|| (substr($l[1],0,3)=='X=s')
				|| (substr($l[1],0,3)=='Szu')
				|| (substr($l[1],0,3)=='Usu')
				|| (substr($l[1],0,3)=='Kop')
				|| (substr($l[1],0,3)=='Dop')
				|| (substr($l[1],0,3)=='For')
				|| (substr($l[1],0,3)=='Esc')
				) {
					if (substr($l[1],0,3)=='X=s') {
						$l[1]='Sortuj';
					}
					echo '<button style="position: absolute; visibility:hidden" class="but" id="button'.$l[0].'" title="'.$l[4].'"';
					echo (trim($l[1])==''?'style="visibility: hidden"':'').' accesskey="'.$l[0].'" onclick="'.$l[2].'">';
					echo (str_replace($l[0],'<u>'.$l[0].'</u>',$l[1])).'</button>';
					echo '<button title="'.$l[1].' (klawisz '.$l[0].')" accesskey="'.$l[0].'" onclick="f1.button'.$l[0].'.click()"><img src="images/'.substr($l[1],0,3).'.png" /></button>';echo "\n";
				} else {
					echo '<input style="cursor:hand;" type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'" title="'.$l[4].'"';
					echo "/>\n";
				}
			}
		}
	}
	echo '</div>';
}
if ((substr($tabelaa,0,5)=='dokum' || $tabelaa=='abonencisz')) {
	//echo '<input id="projektmode" type="checkbox" style="visibility: hidden"/>';
	//echo '<input id="projektsave" type="button" value="Save" style="visibility: hidden" onmousedown="ZapisPozycji()"/>';
}
echo '</td></tr></table>';
echo '</form>';
echo "\n";
echo "\n";




echo '<form id="f3" style="visibility: hidden;" ';
echo 'onmouseover="f3_Show();" ';
echo 'onmouseout="f3_Hide();" ';
echo '>';
if ($ileobok>0) {
	echo '<table bgcolor="#F5F5F5" border=2 cellpadding=4 cellspacing=0 style="font-size: 10pt; font-family: arial, sans serif;" >';
	echo "\n";
	echo '<th height="5pt" style="background:'."'#CCCCCC'".'; font-family: arial, sans serif; font-size: 12pt">';
	echo 'Wybierz wariant:';
	echo "</th>\n";
	if ($fun) {
		$f=explode("\n",$fun);
		$cc=Count($f);
		for($i=0;$i<$cc;$i++) {
			$l=explode("|",$f[$i]);
			if (($l[1])&&($l[3]=='1')) {
				$l[2]=trim($l[2]);
				$ww=$l[1];
				//				$mr="select OK from osobyprawa where ID_OSOBY=$osoba_id and OPCJA='$ww'";
				//				$ww=mysql_query($mr);
				//				if ($ww) {$ww=mysql_fetch_row($ww);$ww=($ww[0]=='-');}
				//				if (!$ww) {
				echo '<tr height="5pt">';
				echo '<td onmouseover="this.style.background='."'#FFCC66'".';" onmouseout="this.style.background='."''".';">';
				echo '<a class="opt" style="background:'."''".'" id="button'.$l[0].'" alt="'.$l[1].'" '.(trim($l[1])==''?'style="visibility: hidden"':'').' accesskey="'.$l[0].'" onclick="'.$l[2].'" onmouseover="this.style.background='."'#FFCC66'".';" onmouseout="this.style.background='."''".';" />'.str_replace($l[0],'<b>'.$l[0].'</b>',$l[1]).'</a>';
				echo "</td></tr>\n";
				//				}
			}
		}
	}
	echo '</table>';
}
echo '</form>';
echo "\n";




if ($tabela=='spec_sheet') {
	$mr=$rrr;
} else {
	$mr=$n;
}

if ($n==0) {
	if ($r<2&&$str<2) {
		$n=1;
		//                echo '<h1 align="center"><br><br><br>BRAK DANYCH<br><br><br></h1>';
	}
	else {
		echo '<h1 align="center"><br>TO JEST OSTATNIA POZYCJA</h1>';
	}
};

if ($tabelaa) { // tabela master (ta nieaktywna)
	//	if (substr($tabelaa,0,5)=='dokum') {
	//			include('formularz.php');
	if ($tabelaa==$ntab_master) {
		include('formularz.php');
	} elseif ($tabelaa=='abonencisz') {
		//echo "\n";
		//echo '<form id="f0" action="Tabela.php?tabela='.$tabela.'" method="post">';echo "\n";
		include('formularzsz.php');
	}
	else {
		if ($rrr<>99&&$rrr<>999&&$rrr<>9999) {
			$j=($rrr-$n)/2-2;
			for($i=0;$i<$j;$i++) {
				echo '<br style="font-size: 12pt">';
			}
		}
		echo '<table align="center" border="1" cellpadding="2" cellspacing="0"> '; echo "\n";
		echo '<caption align="left"><font color="white"><b>'.$tyta;
		//        echo '  (';
		//        echo $sqla;
		echo '</b></font></caption>';
		echo "\n";
		echo '<tr bordercolor="#0F4F9F">';
		echo "\n";
		for($j=0;$j<=$mca-1;$j++) {
			echo '<td ';
			echo 'align="center" ';
			//             if (!$styna[$j]||$styna[$j]=="\r") {;} else {echo $styna[$j];};
			if (($szera[$j]==='0')||($szera[$j]=='.')) {
				echo ' CLASS="bez" ';	//	echo 'width=0 style="font-size:0"';
			}
			else {
				echo ' CLASS="nag" ';	//	echo 'width=0 style="font-size:0"';
			}
			echo ' bgcolor='.$tnag.'>';
			if (($szera[$j]==='0')||($szera[$j]=='.')) {echo '.';}
			else {echo $tna[$j];}
			echo '</td>';
			echo "\n";
		}
		echo '</tr>';
		for($i=0;$i<1;$i++){
			//                $tra=mysql_fetch_row($wa);
			echo "\n";
			echo '<tr height=1 bgcolor='.$twie.'>';
			echo "\n";
			for($j=0;$j<$mca;$j++){
				echo '<td width='.($szera[$j]*12);
				if ($szera[$j]==='0')    {echo ' class="bez" ';}
				elseif ($szera[$j]=='.') {echo ' class="bez" ';}
				else {                    echo ' class="nor" ';}
				echo ' align="center" nowrap ';
				if (!$styla[$j]||$styla[$j]=="\r") {;} else {echo $styla[$j];};
				if (($szera[$j]==='0')||($szera[$j]=='.')) {
					echo 'width=0 style="font-size:0"';
				}
				echo ' >';
				if (count($z=explode(":",$szera[$j]))>1) {                        //obrazek
					if (!$z[0]) echo '<img src="'.$tra[$j].'" alt="" height='.$z[1].'>';
					if (!$z[1]) echo '<img src="'.$tra[$j].'" alt="" width='.$z[0].' >';
					if ($z[0]&&$z[1]) echo '<img src="'.$tra[$j].'" alt="" width='.$z[0].' height='.$z[1].'>';
				}
				else {                                                                                                                  //tekst
					$buf=$szera[$j];
					$buf=str_replace('@s','',$buf);
					if (count($z=explode("@Z",$buf))>1) {		// bez zer
						$buf=str_replace('@Z','',$buf);
						$buf=str_replace('%','',$buf);
						$buf=str_replace('+','',$buf);
						if (strip_tags($tra[$j])*1==0) {
							$buf='';
							$tra[$j]='';
						}
						elseif (count($z=explode("@z",$buf))>1) {		//zera po kropce ucinamy
							$buf=str_replace('@z','',$buf);
							if (count($z=explode(".",$tra[$j]))>1) {		// bez zer po kropce
								$tra[$j]=$z[0];
								$z[0]='';
								if ($z[1]*1>0) {
									$tra[$j]=$tra[$j].'.';
								} else {
									$tra[$j]=$tra[$j].'&nbsp;';
									if ($buf<>'') {$buf=$buf*1+5;}	//twarde spacje zajmuj¹ wiêcej
								}
								while (substr($z[1],-1,1)==='0') {
									$z[1]=substr($z[1],0,strlen($z[1])-1);
									$z[0]=$z[0].'&nbsp;';
									if ($buf<>'') {$buf=$buf*1+5;}
								}
								$tra[$j]=$tra[$j].$z[1].$z[0];
							}
						}
					}
					if (!$buf) {echo $tra[$j];}
					elseif ($buf==='0') {echo $tra[$j];}
					elseif ($buf=='.') {echo '.';}
					elseif ($buf==='i') {echo number_format($tra[$j],0,'.',',');}
					elseif ($buf==='w') {echo number_format($tra[$j],2,'.',',');}
					elseif (substr($buf,0,1)=='%') {printf($buf,$tra[$j]);}
//					elseif (strlen($tra[$j])>$buf) {echo substr($tra[$j],0,$buf).'...';}
					elseif (strlen($tra[$j])>$buf) {echo $tra[$j];}
					else {echo substr($tra[$j],0,$buf);};
				}
				echo '</td>';
				echo "\n";
			}
			echo '</tr>';
		}
		echo "\n";
		echo '</table>';
		echo "\n";
		echo "\n";
	}
}
else {
	if ($tyt<>'Stan magazynu') {
		if ($rrr<>99&&$rrr<>999) {
			$j=($rrr-$n)/2;
			if ($cc>11) {$j+=($cc/9);}
			for($i=0;$i<$j;$i++) {
				if ($tabela<>'spec_sheet') {
					echo '<br style="font-size: 12pt">';        echo "\n";
				}
			}
		}
	}
}

echo "\n";

if ($tabelaa==$ntab_master) {
	if ($tabelaa=='dokumentKB') {
		echo '<div id="tabslave" style="position: absolute; background:#D0DCE0; top:230; left:15; Z-INDEX:99;" onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)">';
		//   } elseif (substr($tabelaa,0,5)=='dokum') {
		//   	echo '<div id="tabslave" style="position: absolute; background:#D0DCE0; top:370; left:25; Z-INDEX:99;" onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)">';
	} else {
		if ($tabela=='towarywyb') {
			echo '<div id="tabslave" style="position: absolute; background:#D0DCE0; top:50; left:10; Z-INDEX:99;" onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)">';
		} else {
			echo '<div id="tabslave" style="position: absolute; background:#0F4F9F; top:'.(($_SESSION['screen_w']<=800)?'370':'535').'; left:10; Z-INDEX:99;" onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)">';
		}
	}
}

if ($tabela=='spec_sheet') {
	echo '<form id="spec_sheet" action="sheet_save.php" method="post">'; echo "\n";
}

if ($warunek<>'') {
	$warunek=' '.substr($warunek,1);
	$warunek=substr($warunek,0,-1).' ';
	$warunek=str_replace(' and ',' i ',$warunek);
	$warunek=str_replace(' or ',' lub ',$warunek);
	$warunek=str_replace(' like ',' jak ',$warunek);
	$warunek=str_replace(' between ',' miêdzy ',$warunek);
	$warunek=str_replace($baza.'.','',$warunek);
	for ($i=0;$i<count($tn);$i++) {
		if (!$styn[$i]||$styn[$i]=="\r") {;} else {
			$pola[$i]=$styn[$i];
			//         $pola[$i]=str_replace(".","krooopka",$pola[$i]);
			$pola[$i]=str_replace(" ","_",$pola[$i]);
		}
		$pola[$i]=str_replace($baza.'.','',$pola[$i]);
		$warunek=str_replace(' '.$pola[$i].' ',' <b>'.$tn[$i].'</b> ',$warunek);
	}
	$warunek=trim($warunek);
}

if ($sortowanie) {
	$sortowanie=' '.$sortowanie.' ';
	$sortowanie=str_replace(' asc',' ',$sortowanie);
	$sortowanie=str_replace(' desc',' malej±co',$sortowanie);
	$sortowanie=str_replace($baza.'.','',$sortowanie);
	$sortowanie=str_replace('1*','liczbowo ',$sortowanie);
	for ($i=0;$i<count($tn);$i++) {
		if (!$styn[$i]||$styn[$i]=="\r") {;} else {
			$pola[$i]=$styn[$i];
			//         $pola[$i]=str_replace(".","krooopka",$pola[$i]);
			$pola[$i]=str_replace(" ","_",$pola[$i]);
		}
		$pola[$i]=str_replace($baza.'.','',$pola[$i]);
		$sortowanie=str_replace(' '.$pola[$i].' ',' <b>'.$tn[$i].'</b> ',$sortowanie);
	}
}

if ($tabelaa=='dokum') {
	echo '<table cellpadding="2" bgcolor="#3366FF"><tr><td>';	//t³o tabelki slave (teraz niebieskie)
	echo '<table bgcolor="gray"                id="tab" summary="'.$n.'" border="1" cellpadding="2" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080"';
}
else {
	echo '<table width="100%"><tr><td>';
	echo '<table bgcolor="gray" align="center" id="tab" summary="'.$n.'" border="1" cellpadding="2" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080"';
}

if ($tabelaa<>'') {echo 'width="100%">';} else {echo '>';}; echo "\n";

//if (substr($tabelaa,0,5)=='dokum') {
//	echo '<caption align="left"><font color="black"><b>'.$tyt.'</b>';
//}
//else {
//	echo '<caption align="left"><font color="black"><b>'.$tyt.'</b>';
//}

//if ((($str-1)*$rrr+1)==1&&(($str-1)*$rrr+$mr)==1) {;}
//else {
echo '<caption align="left">';		//FFCC33 ¿ó³ty, #EFEFDF jasny szary
echo '<table bgcolor="#0F4F9F" border="';   //nag³ówek nad podtabel±	//bgcolor="#D0DCE0"

if ($tabelaa=='dokum') {
	echo '1';
} else {
	echo '0';
}

if ((($tabelaa<>'')||($tabelaa=='dokum')||($rr>21))&&($warunek||$sortowanie)) {
	echo '" cellspacing="0" cellpadding="0"><tr><td align="left" width="375"><font style="color:white"><b>&nbsp;'.$tyt.'</font></td>';
	//	echo '" cellspacing="0" cellpadding="0"><tr><td align="left" width="375"><b>&nbsp;'.$tyt.'</td>';
	echo '<td align="center" width="200">';
	echo '<font class="nor2">';
	if ($warunek<>'') {echo "Filtr: $warunek";}
	if ($warunek&&$sortowanie) {echo ", <br>";}
	if ($sortowanie) {echo "Sort: $sortowanie";}
	echo '</font>';
	echo '</td>';

	echo '<td align="right" width="375">';
	echo '<font style="color:white">';
	if ($strr>1) {
		echo '<input type="button" style="cursor:hand;" onclick="klawisz('."'1'".')" value="<<" />';	// alt="skok do pierwszej strony"
		echo '<input type="button" style="cursor:hand;" onclick="klawisz('."'p'".')" value="<" />';	// alt="skok do poprzedniej strony"

		echo ' <span style="cursor:hand;" onclick="WieleStron()">str. '.floor($str);
		if ($strr) {echo "/$strr";}
		echo ': pozycje '.floor(($str-1)*$rrr+1).'-'.floor(($str-1)*$rrr+$mr).'</span>';

		echo ' <input type="button" style="cursor:hand;" onclick="klawisz('."'n'".')" value=">" />';	// alt="skok do nastêpnej strony"
		echo '<input type="button" style="cursor:hand;" onclick="klawisz('."'o'".')" value=">>" />';	// alt="skok do ostatniej strony"
	} else {
		echo ' <span style="cursor:hand;" onclick="WieleStron()">str. '.floor($str);
		if ($strr) {echo "/$strr";}
		echo ': pozycje '.floor(($str-1)*$rrr+1).'-'.floor(($str-1)*$rrr+$mr).'</span>';
	}
	echo '</font>';
	echo '</td></tr></table>';
	echo '</caption>';
}
else {
	echo '" cellspacing="0" cellpadding="0"><tr><td align="left" width="475"><font style="color:white"><b>&nbsp;'.$tyt.'</font></td>';
	echo '<td align="right" width="475">';
	echo '<font style="color:white">';
	if ($strr>1) {
		echo '<input type="button" style="cursor:hand;" onclick="klawisz('."'1'".')" value="<<" />';	// alt="skok do pierwszej strony"
		echo '<input type="button" style="cursor:hand;" onclick="klawisz('."'p'".')" value="<" />';	// alt="skok do poprzedniej strony"

		echo ' <span style="cursor:hand;" onclick="WieleStron()">str. '.floor($str);
		if ($strr) {echo "/$strr";}
		echo ': pozycje '.floor(($str-1)*$rrr+1).'-'.floor(($str-1)*$rrr+$mr).'</span>';

		echo ' <input type="button" style="cursor:hand;" onclick="klawisz('."'n'".')" value=">" />';	// alt="skok do nastêpnej strony"
		echo '<input type="button" style="cursor:hand;" onclick="klawisz('."'o'".')" value=">>" />';	// alt="skok do ostatniej strony"
	} else {
		echo ' <span style="cursor:hand;" onclick="WieleStron()">str. '.floor($str);
		if ($strr) {echo "/$strr";}
		echo ': pozycje '.floor(($str-1)*$rrr+1).'-'.floor(($str-1)*$rrr+$mr).'</span>';
	}
	echo '</font>';
	echo '</td></tr></table>';
	echo '</caption>';
}
//}
//echo '</font></caption>';

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo "document.title='$tyt, '+document.title;\n";
//echo "location.replace(location.href);\n";
echo '-->'; echo "\n";
echo '</script>'; echo "\n";

echo "\n";
echo '<tr style="cursor:hand;" bordercolor="black">';	//nag³ówki tabeli
echo "\n";
for($j=0;$j<=$mc-1;$j++) {
	if ($szer[$j]==='0')    {echo '<td id="tab_0'.($j+1).'" nowrap class="bez" ';}
	elseif ($szer[$j]=='.') {echo '<td id="tab_0'.($j+1).'" nowrap class="bez" ';}	//width=0 style="font-size:0"
	else {                   echo '<td id="tab_0'.($j+1).'" class="nag" onclick="mysza(-1,'.$j.');" ';}
	echo ' align="center" ';
	//        if (!$styn[$j]||$styn[$j]=="\r") {;} else {echo $styn[$j];};
	echo ' bgcolor='.$tnag.'>';
	if ($szer[$j]=='.') {echo '.';}
	else {echo $tn[$j];};
	echo '</td>';
	echo "\n";
}
echo '</tr>';

if ($mr==0) {                // pusta sub tabela
	$mr=1;
	for($i=0;$i<1;$i++){
		echo "\n";
		echo '<tr style="cursor:hand;" id="tab_'.($i+1).'" height=1 bgcolor='.$twie.'>';
		echo "\n";
		for($j=0;$j<$mc;$j++){
			if ($szer[$j]==='0')    {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
			elseif ($szer[$j]=='.') {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
			else {                   echo '<td id="tab_'.$i.'_'.$j.'" class="nor" width='.($szer[$j]*12);}
			echo ' nowrap align="center" ';
			if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
			echo ' onclick="mysza('.$i.','.$j.');" ondblclick="mysza2('.$i.','.$j.');">';
			if ($j==1&&$tabelaa) { // tabela master nieaktywna
				echo $tra[0];        // ID tabeli master (³±cznik z SUB)
			}
			else {
				if ($j==0) {echo '0';} else {echo '...';};
			}
			echo '</td>';
			echo "\n";
		}
		echo '</tr>';
	}
} else {
	for($i=0;$i<$mr;$i++) {
		$tr=mysql_fetch_row($w);
		for($j=0;$j<count($tr);$j++) {
			$tr[$j]=StripSlashes($tr[$j]);
		}
		echo "\n";
		//		if ((substr($tabela,0,5)=='dokum' || $tabela=='abonencisz') && $tr[13]=='') {
		//        echo '<tr id="tab_'.($i+1).'" height=1 bgcolor="#CCCCCC">';
		//		}
		//		else {
		echo '<tr style="cursor:hand; background:'.$twie2.'" id="tab_'.($i+1).'" height=1 bgcolor='.$twie.'>';
		//		}
		echo "\n";
		for($j=0;$j<$mc;$j++) {

			if ($szer[$j]==='0')    {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
			elseif ($szer[$j]=='.') {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
			else {                   echo '<td id="tab_'.$i.'_'.$j.'" class="nor" width='.($szer[$j]*12);}
			echo ' align="center" ';
			if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};

			echo ' onclick="mysza('.$i.','.$j.');" ondblclick="mysza2('.$i.','.$j.');"';
			if (count($z=explode(":",$szer[$j]))>1) {         //obrazek
				echo '>';
				if (!$z[0]) echo '<img src="'.$tr[$j].'" alt="" height='.$z[1].'>';
				if (!$z[1]) echo '<img src="'.$tr[$j].'" alt="" width='.$z[0].' >';
				if ($z[0]&&$z[1]) echo '<img src="'.$tr[$j].'" alt="" width='.$z[0].' height='.$z[1].'>';
			} elseif (count($z=explode("checkbox",$szer[$j]))>1) {  // pole "Checkbox"
				echo '>';
				echo '<input type="checkbox" id="'.($styn[$j]?$styn[$j]:'cb').'_'.$i.'" ';
				if($tr[$j]*1==1) {echo 'checked style=\'background-color="black"\'';}
				echo ' onclick="mysza3('.$i.','.$j.');"';
				echo '>';
			} elseif (count($z=explode("text",$szer[$j]))>1) {  // pole "input"
				echo '>';
				if (!$z[0]) {
					echo '<input type="text" id="tx_'.$i.'_'.$j.'" name="tx_'.$i.'_'.$j.'" maxlength="'.$z[1].'"  size="'.($z[1]/2).'" value="'.$tr[$j].'"/>';
				} else {
					echo '<input type="text" id="tx_'.$i.'_'.$j.'" name="tx_'.$i.'_'.$j.'" maxlength="'.$z[1].'"  size="'.$z[0].'" value="'.$tr[$j].'"/>';
				}
			} elseif (count($z=explode("-",$szer[$j]))>1) {		// obszar tekstu ³amanego
				echo '>';
				echo nl2br($tr[$j]);
			} else {                                                                                                                  //tekst
				echo ' nowrap >';				// linia tekstu nie³amanego

				if (($sumyok)&&(!($sumy[$j]===''))) {             // wiersz sum: ile max miejsc po przecinku ?
					$sumy[$j]+=str_replace(',','',$tr[$j]);
					$buf=explode('.',str_replace('&nbsp;','#',$tr[$j]));
					$sumyp[$j]=Max(strlen($buf[1]),$sumyp[$j]);
					$buf=explode('&nbsp;',$tr[$j]);
					$sumyp[$j]=Max(count($buf)-2,$sumyp[$j]);
				}

				$buf=$szer[$j];
				$jw=((count(explode("j",$szer[$j]))>1)&&($tr[$j]==$tp[$j]));	//&&($tr[$j]==$tp[$j])
				$buf=str_replace('j','',$buf);			//j.w.

				$buf=str_replace('@s','',$buf);		//tylko na ekranie
				if (count($z=explode("@Z",$buf))>1) {		// bez zer
					$buf=str_replace('@Z','',$buf);
					$buf=str_replace('+','',$buf);
					//         				$buf=str_replace('%','',$buf);
					if (strip_tags($tr[$j])*1==0) {
						$buf='';
						$tr[$j]='';
					} elseif (count($z=explode("@z",$buf))>1) {		//zera po kropce ucinamy
						$buf=str_replace('@z','',$buf);
						if (count($z=explode(".",$tr[$j]))>1) {		// bez zer po kropce
							$tr[$j]=$z[0];
							if (count(explode("i",$buf))>1) {		// format ilo¶ci
								$buf=str_replace('i','',$buf);
								$tr[$j]=number_format($tr[$j],0,'.',',');
							}
							$z[0]='';
							if ($z[1]*1>0) {
								$tr[$j]=$tr[$j].'.';
							} else {
								$tr[$j]=$tr[$j].'&nbsp;';
								if ($buf<>'') {$buf=$buf*1+5;}	//twarde spacje zajmuj¹ wiêcej
							}
							while (substr($z[1],-1,1)==='0') {
								$z[1]=substr($z[1],0,strlen($z[1])-1);
								$z[0]=$z[0].'&nbsp;';
								if ($buf<>'') {$buf=$buf*1+5;}
							}
							$tr[$j]=$tr[$j].$z[1].$z[0];
						}
					}
				}
				if ($jw) {
					echo "";
				} elseif (!$buf) {
					if ($tabela=='spec_sheet') {
						$tr[$j]=str_replace('&nbsp;','',$tr[$j]);
						if ($j==3) {         //indeks
							echo "<input name='towar_".$i."_".$j."' onchange='Ajax_indeks(this,".$i.",".$j.")' value='$tr[$j]' size=5 />";
						} elseif ($j==8) {   //cena
							echo "<input name='towar_".$i."_".$j."' onchange='Ajax_cena(this,".$i.",".$j.")' value='$tr[$j]' size='2' style='text-align:right' />";
						} elseif ($j==9) {   //rabat
							echo "<input name='towar_".$i."_".$j."' onchange='Ajax_rabat(this,".$i.",".$j.")' value='$tr[$j]' size='1' style='text-align:right' />";
						} elseif ($j==6) {   //ilosc
							echo "<input name='towar_".$i."_".$j."' onchange='Ajax_ilosc(this,".$i.",".$j.")' value='' size='5' style='text-align:right' />";
						} else {
							echo "<span id='towar_".$i."_".$j."'>$tr[$j]</span>";
						}
					} else {
						echo $tr[$j];
					}
					//                   } elseif (true) {
					//                     echo $buf;
				} elseif ($buf==='0') {
					echo $tr[$j];
				} elseif ($buf==='+') {
					echo $tr[$j];
				} elseif ($buf==='w') {
					echo number_format($tr[$j],2,'.',',');
				} elseif ($buf==='i') {
					echo number_format($tr[$j],0,'.',',');
				} elseif ($buf=='.') {
					echo '.';
				} elseif (substr($buf,0,1)=='%') {
					printf($buf,$tr[$j]);
				} elseif (strlen(str_replace('&nbsp;','#',$tr[$j]))>$buf) {
					echo substr($tr[$j],0,$buf).'...';     //+++
				} else {
					if ($tabela=='spec_sheet' && $j==9) {
						echo "<input name='towar_".$i."_".$j."' onchange='Ajax_ilosc(this,".$i.",".$j.")' value='".str_replace('&nbsp;','',substr($tr[$j],0,$buf))."' size='5' style='text-align:right' />";
					} else {
						echo substr($tr[$j],0,$buf);
					}
				}
			}
			$tp[$j]=$tr[$j];
			echo '</td>';
			echo "\n";
		}
		echo '</tr>';
	}
	if ($sumyok) {                // wiersz sum
		echo "\n";
		echo '<tr bgcolor='.$twie.'>';
		echo "\n";
		$sumyok=true;
		for($j=0;$j<$mc;$j++) {
			echo "<td nowrap";
			if (($sumyok)&&(!$sumy[$j+1]=='')) {
				$sumy[$j]='Suma:';
				$sumyok=false;
			}
			if ($szer[$j]==='0') {
				echo ' class="bez" ';
			} elseif ($szer[$j]=='.') {
				echo ' class="bez" ';
			} else {
				echo ' class="nor" ';
			}
			if (count($z=explode("@Z",$szer[$j]))>1) {		// bez zer
				$szer[$j]=str_replace('@Z','',$szer[$j]);
				$szer[$j]=str_replace('+','',$szer[$j]);
				$szer[$j]=str_replace('w','',$szer[$j]);
				//      			$szer[$j]=str_replace('i','',$szer[$j]);
				if (count($z=explode("@z",$szer[$j]))>1) {		// bez zer po kropce
					$szer[$j]=str_replace('@z','',$szer[$j]);
					if ((count($z=explode("i",$szer[$j]))>1)||($sumyp[$j]>0 && $sumy[$j] && $sumy[$j]<>'Suma:')) {
						$sumy[$j]=number_format($sumy[$j]*1,$sumyp[$j],'.',',');
					}
					//echo '<!--'.($sumy[$j]).'-->';
					if (count($z=explode(".",$sumy[$j]))>1) {		// bez zer po kropce
						$sumy[$j]=$z[0];
						$z[0]='';
						if ($z[1]*1>0) {
							$sumy[$j]=$sumy[$j].'.';
						} else {
							$sumy[$j]=$sumy[$j].'&nbsp;';
						}
						while (substr($z[1],-1,1)==='0') {
							$z[1]=substr($z[1],0,strlen($z[1])-1);
							$z[0]=$z[0].'&nbsp;';
						}
						$sumy[$j]=$sumy[$j].$z[1].$z[0];
						$sumyp[$j]=0;
						$szer[$j]=strlen(str_replace('&nbsp;','#',$sumy[$j]));
					} else {
						for ($x=0;$x<$sumyp[$j];$x++) {
							$sumy[$j]=$sumy[$j].'&nbsp;';
						}
					}
				}
			}
			if (substr($szer[$j],0,1)=='%') {
				$szer[$j]=substr($szer[$j],2);
			}
			echo ' width='.($szer[$j]*12);
			if (!$sumy[$j]=='') {
				echo ' style="border-top: double #000000" ';
			}
			echo ' align="center" ';
			if (!$styl[$j]||$styl[$j]=="\r") {;
			} else {
				echo $styl[$j];
			}
			echo ' >';
			//echo "if ($sumyp[$j]>0 && $sumy[$j] && $sumy[$j]<>'Suma:') {";
			if ($sumyp[$j]>0 && $sumy[$j] && $sumy[$j]<>'Suma:') {
				$sumy[$j]=number_format($sumy[$j]*1,$sumyp[$j],'.',',');
			}
			//echo $szer[$j];
			if (!$szer[$j]) {
				echo $sumy[$j];	// kolumny bez okreœlonej szerokoœci
			} elseif ($szer[$j]==='+') {
				echo substr($sumy[$j],0,strpos($sumy[$j],'.')+3);
			} elseif (strlen(str_replace('&nbsp;','#',$sumy[$j]))>$szer[$j]) {
				echo substr($sumy[$j],0,$szer[$j]).'...';
			} elseif (!$sumy[$j]||$sumy[$j]=='Suma:') {
				echo substr($sumy[$j],0,$szer[$j]);
			} elseif ($sumyp[$j]>0 && $sumyp[$j]<>2) {
				printf("%.".($sumyp[$j])."f",$sumy[$j]);
			} else {
				echo $sumy[$j];
			}
			echo "</td>";
			echo "\n";
		}
		echo '</tr>';
	}
}
if ($w) {
	mysql_free_result($w);
}

//mysql_free_result($f);
require('dbdisconnect.inc');

echo "\n";
echo "</table>";
echo "\n\n";

if ($tabela=='spec_sheet') {
	echo '</form>'; echo "\n\n";
}

if (true || $tabelaa<>'dokum') {
	if ((substr($tabelaa,0,5)<>'dokum')&&($warunek||$sortowanie)) {
		echo '<table width="100%" bgcolor="#0F4F9F" align="center" border="0" cellpadding="2" cellspacing="0">';
		echo '<tr>';
		echo '<td align="left" >';	//bgcolor="#0F4F9F"
		echo '<font class="nor2">';
		if (substr($tabelaa,0,5)=='dokum') {echo '<font color="white">';}
		if ($warunek<>'') {echo "Filtr: $warunek";}
		if ($warunek&&$sortowanie) {echo ",&nbsp;";}
		if ($sortowanie) {echo "Sort: $sortowanie";}
	} else {
		echo '<table width="100%" align="center" border="0" cellpadding="2" cellspacing="0">';
		echo '<tr>';
		echo '<td align="left" bgcolor="#0F4F9F" >';
		echo '<font class="nor2">';
		if (substr($tabelaa,0,5)=='dokum') {echo '<font color="white">';}
	}
	if (substr($tabelaa,0,5)=='dokum') {echo '</font>';}
	echo '</font>';
	echo '</td>';
	if (substr($tabelaa,0,5)<>'dokum') {
		echo '<td align="right" bgcolor="#0F4F9F">';
		echo "<font color='white' size='2pt'>";
		echo '	Handel ver 2011.02';
		echo '</font>';
		echo '</td>';
	}
	echo '</tr>';
	echo '</table>';
	echo '</td></tr></table>';
	//	echo "<br>";
	//	echo "\n";
}

if ((substr($tabelaa,0,5)=='dokum' || $tabelaa=='abonencisz')) {
	echo '</div>';
}

echo '<form id="f0" action="Tabela.php?tabela='.$tabela.'" method="post">';echo "\n";
?>

<div id="f0project" nowrap
	style="position: absolute; top: 860; left: 310; visibility: hidden; background-color: #CCCCCC; border: solid black 1pt; padding: 15pt;">
Kod : <input id="projectKod"
	title="Wprowad¼ kod paskowy, indeks lub polecenie" type="input" /><br>
<br>
<button id="projectEsc" title="Wyj¶cie" style="cursor: hand;"
	onmousedown="projectstop()">Esc=Anuluj</button>
<button id="projectSave" title="Wykonanie" style="cursor: hand;"
	onmousedown="projectacti()">Enter=Zapisz</button>
</div>

<input type="hidden" id="natab" name="natab" value="" /> <input
	type="hidden" id="batab" name="batab" value="" /> <?php
	echo '<input id="sutab"    type="hidden" name="sutab"    value="'.$tabelaa.'"/>';echo "\n";
	echo '<input id="sutabpol" type="hidden" name="sutabpol" value="'.$tabelap.'"/>';echo "\n";
	echo '<input id="sutabmid" type="hidden" name="sutabmid" value="'.$tabelai.'"/>';echo "\n";
	?> <input type="hidden" id="idtab" name="idtab" value="" /> <input
	type="hidden" id="ipole" name="ipole" value="" /> <?php
	//echo '<input id="fpole" value=""/>';
	?> <input type="hidden" id="opole" name="opole" value="" /> <input
	type="hidden" id="strpole" name="strpole" value="" /> <input
	type="hidden" id="rpole" name="rpole" value="" /> <input type="hidden"
	id="cpole" name="cpole" value="" /> <input type="hidden" id="kpole"
	name="kpole" value="" /> <input type="hidden" id="rrpole" name="rrpole"
	value="" /> <input type="hidden" id="rrrpole" name="rrrpole" value="" />
<input type="hidden" id="phpini" name="phpini" value="" /> <input
	type="hidden" id="zaznaczone" name="zaznaczone" value="" /> <input
	type="hidden" id="offsetX" name="offsetX" value="" /> <input
	type="hidden" id="offsetY" name="offsetY" value="" /> <input
	type="submit" id="odswiez" value="Anuluj" /></form>

	<?
	//echo $waruneksql;
	if ($ido==1) { //test   &&1==2
		echo '<br>SQL1:'.$sqla;
		echo '<br>SQL2:'.$sql;
	}
	?>

</body>
</html>

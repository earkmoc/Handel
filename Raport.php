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
.nag {font-size: 20pt};
.nor {font-size: 12pt};
.zaz {font-size: 22pt; background-color: red};

#f0 {POSITION: absolute; VISIBILITY: hidden; TOP:0px; LEFT: 0px; Z-INDEX:1;}
#f1 {POSITION: absolute; VISIBILITY: hidden; TOP:0px; LEFT: 10px; Z-INDEX:2;}
#glowka2 {POSITION: absolute; VISIBILITY: visible; TOP:10px; right: 10px; Z-INDEX:3;}
#glowka3 {POSITION: absolute; VISIBILITY: visible; TOP:80px; right: 10px; Z-INDEX:4;}
//#master {POSITION: absolute; VISIBILITY: visible; TOP:10px; LEFT: 10px; Z-INDEX:5;}

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

$warunek="";
$sortowanie="";
$opole=$_POST['opole'];
if (($opole!="S")&&$_SESSION['osoba_upr']&&$_POST['ipole']) {

$z='Select ID, WARUNKI, SORTOWANIE from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$_POST['idtab'];

$w=mysql_query($z);
if ($w) {
	if (mysql_num_rows($w)>0) {

		$w=mysql_fetch_array($w);

		$warunek=$w['WARUNKI'];
		$sortowanie=$w['SORTOWANIE'];

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
//	$sql0=$z;
	$w=mysql_query($z);
}}

// zapamiętaj stan tabeli dla zalogowanej osoby
//********************************************************************

//********************************************************************
//zmienne PHP i Java Script sterujące dalszym zachowaniem

if ($_POST['sutab']) { 					// tryb Master/Slave (SubTab=podtabela)
	if ($opole=="S") {								// suwanie się po tej samej tabeli
		$tabela=$_POST['natab'];			// tabela slave aktywna
		$tabelaa=$_POST['sutab'];			// tabela master nieaktywna
		$tabelap=$_POST['sutabpol'];		// pole łącznik do tabeli slave
		$tabelai=$_POST['sutabmid']; 		// identyfikator pozycji w master
	}
	else {
		$tabela=$_POST['sutab'];			// tabela slave aktywna
		$tabelaa=$_POST['natab'];			// tabela master nieaktywna
		$tabelap=$_POST['sutabpol'];		// pole łącznik do tabeli slave
		$tabelai=$_POST['ipole']; 			// identyfikator pozycji w master
	}
}
else {
	$tabela=$_GET['tabela'];
	if (!$tabela) {$tabela=$_POST['natab'];};
}
if (!$tabela) {		// brak podanej tabeli
	$tabela='tabele'; // => ląduj w tabeli głównej
	$tabelaa="";		// tabela master nieaktywna
	$tabelap="";		// pole łącznik do tabeli slave
	$tabelai=""; 		// identyfikator pozycji w master
};

if (!$_SESSION['osoba_upr']) {$tabela='osoby';}; // niezalogowany ląduje w osoby

//********************************************************************
if ($opole!="S") {					// nie suwanie się
// może tryb Master/Slave jest określony w definicji tabeli ?

//$tabela może być liczbą ID tabeli lub nazwą tabeli
$z=ord(substr($tabela,0,1));
if (48<=$z && $z<=57) {
	$z="select * from tabele where ID='";
	$z.=$tabela;
	$z.="'";
}
else {
	if (count($w=explode(",",$tabela))>1) {	// jest przecinek
		$tabela=$w[0];
	}
	$z="select * from tabele where NAZWA='";		// WYKAZYSPE
	$z.=$tabela;
	$z.="'";
}
$sql0=$z;
$w=mysql_query($z);
if ($w){
	$w=mysql_fetch_array($w);
 	$sql=$w['TABELA'];
	if (!$sql) { exit;}
	else {
		$w=explode("\n",$sql);
		$z=trim($w[0]);
		if (count($w=explode(",",$z))>1) {	// jest przecinek
			$tabela=$w[0];						// tabela slave aktywna
			$tabelaa=$w[1]; 					// tabela master nieaktywna
			$tabelap=trim($w[2]); 					// pole łącznik do tabeli slave
			$tabelai=''; 	  	 	 			// ID pozycji w Master za chwilę ...
 	 	}
	}
}
}
// może tryb Master/Slave jest określony w definicji tabeli ?
//********************************************************************

echo '$tabela="'.$tabela.'";';
echo "\n";
	
$tnag='"#FFFFFF"';              //'"#FFCC33"';
echo '$tnag='.$tnag.';';
echo "\n";
	
$cnag='"#FFFFFF"';					//'"#FF6600"';
echo '$cnag='.$cnag.';';
echo "\n";
	
$twie='"#FFFFFF"';					//'"#FFFFCC"';
echo '$twie='.$twie.';';
echo "\n";
	
$cwie='"#FFFFFF"';					//'"#FFCC66"';
echo '$cwie='.$cwie.';';
echo "\n";
	
//zmienne Java Script
//********************************************************************

if ($tabelaa) {
//********************************************************************
// wariant z tabelą MASTER (nieaktywną)

$tna=array();
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
$sql0=$z;
$wa=mysql_query($z);
if ($wa){
	$wa=mysql_fetch_array($wa);
	$idtaba=$wa['ID'];
	$tabelaa=$wa['NAZWA'];
	$sql0=$tabelaa;
	echo '$tabelaa="'.($wa['NAZWA']).'";';
	echo "\n";

	$tyta=StripSlashes($wa['OPIS']);
	$sqla=StripSlashes($wa['TABELA']);
	$sql0=$sqla;
	$funa=StripSlashes($wa['FUNKCJE']);
	if (!$sqla) { exit;}
	else {
		$mca=0;
		$wa=explode("\n",$sqla);
		if (count($bazaa=explode(",",$wa[0]))>1) {	// jest przecinek
			$bazaa=$bazaa[0];
		}
		else {
			$bazaa=trim($wa[0]);
		}
		$z='Select';
		$cca=Count($wa);
		for($i=1;$i<$cca;$i++) {
			if     (substr($wa[$i],0,4)=='from')  {$z.=' '.$wa[$i];}
			elseif (substr($wa[$i],0,5)=='order') {$zorder=' '.$wa[$i];}
			elseif (substr($wa[$i],0,5)=='where') {$zwhere=' '.$wa[$i];}
			elseif (substr($wa[$i],0,5)=='group') {$zgroup=' '.$wa[$i];}
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
				$szera[$mca]=$la[2];		//szerokość
				if (substr($szera[$mca],0,1)=='+') {$ca=$mca+1;};
				$styla[$mca]=$la[3];		//style="font-size: 70pt; color: red; font-weight: normal"
				$styna[$mca]=$la[4];		//font-family: serif; font-size: 18pt; text-align: center
				$mca++;
			}
		}
		$cca=$mca;
	}
}
if (!$tabelai) {							// nie ma ID pozycji w Master
	$za='Select ID, ID_POZYCJI from tabeles where ID_OSOBY=';
	$za.=$_SESSION['osoba_id'];
	$za.=' and ID_TABELE=';
	$za.=$idtaba;
	$wa=mysql_query($za);
	$wa=mysql_fetch_array($wa);
	$tabelai=$wa['ID_POZYCJI'];  					// ID pozycji w Master
} 	  	  		
if ($zgroup) {
        $z.=' '.$zgroup;                 // "group by" zamiast "where"
        if ($zhaving) {                // "having" za "group by"
                $z.="$zhaving";
                if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
                        $tr=explode('.',$tabelap);                // [1],[2]
                        for($i=0;$i<count($tr);$i++) {
                                $j=substr($tr[$i],1,1);
//                                        $z.=' '.$tra[$j];
                                $z=str_replace($tr[$i],$tra[$j],$z);
                        }
                }
//                else {$z.="$zhaving and $bazaa.ID=$tabelai";};
                else {$z.=" and $bazaa.ID=$tabelai";};
        }
        else {                                          // nie ma "having", więc ma być
                $z.=" having $bazaa.ID=$tabelai";
        }
}
else {
        if ($zwhere) {                                                                                // jest "where"
                $z.="$zwhere and $bazaa.ID=$tabelai";
        }
        else {
                $z.=" where $bazaa.ID=$tabelai";                 // nie ma "where"
        }
}
if ($zorder) {	$z.=' '.$zorder;}					// "order by" za "where"
$sqla=$z.';';
$wa=mysql_query($z);
$na=mysql_num_rows($wa);
$tra=mysql_fetch_row($wa);

// wariant z tabelą MASTER (nieaktywną)
//********************************************************************
}

//********************************************************************
// tabela Slave (aktywna)

$zwhere="";		// zerowanie zmiennych, które za chwilę znów będą użyte
$zorder="";
$zgroup="";
$zhaving="";
$tn=array();
$sumy=array();
$sumyok=false;
$mc=$cc;
$sql='';

//$tabela może być liczbą ID tabeli lub nazwą tabeli
$z=ord(substr($tabela,0,1));
if (48<=$z && $z<=57) {
	$z="select * from tabele where ID='";
	$z.=$tabela;
	$z.="'";
}
else {
	$z="select * from tabele where NAZWA='";
	$z.=$tabela;
	$z.="'";
}
$sql0=$z;
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

	$tyt=StripSlashes($w['OPIS']);
	$sql=StripSlashes($w['TABELA']);
	$sql0=$sql;
	$fun=StripSlashes($w['FUNKCJE']);
//wydruk nie ma ogranicznika na ilość wierszy na stronie
	$rr=999;
//	$rr=$w['MAXROWS'];
//	if ($rr==0) {$rr=20;}
	$rrr=$rr;
	
   $z='Select NR_STR, NR_ROW, NR_COL, WARUNKI, SORTOWANIE from tabeles where ID_OSOBY=';
	$z.=$_SESSION['osoba_id'];
	$z.=' and ID_TABELE=';
	$z.=$idtab;
	$ww=mysql_query($z);
	if ($ww and mysql_num_rows($ww)>0) {
		$ww=mysql_fetch_array($ww);
	};
	
   $warunek=$ww['WARUNKI'];
   $sortowanie=$ww['SORTOWANIE'];

	$r=$ww['NR_ROW'];
	if (!$r) {$r=1;};

	$str=$ww['NR_STR'];
	if (!$str) {$str=1;};
	if ($_POST['opole']=="S") {
		$str=$_POST['strpole'];
		if ($str>0) {$r=1;};	//jak dodaje strony, to najpierw staje na pierwszym wierszu
	}
	else {
		if ($tabelaa) {		// po wejściu do Slave w trybie Maste/Slave stoi na szczycie
//			$r=1;
			$str=1;
		}
	};
	if ($str<0) {$str=-$str; $r=$rr;};	//jak cofa strony, to najpierw staje na ostatnim wierszu
	echo '$str='.$str.';';
	echo "\n";
	
	if ($rr<$r) {$r=$rr;};
	 
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
	             if (count($baza=explode(",",$w[0]))>3) {   // sš nawet 3: abonenciG,grupy,[1].[2],abonenci
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
			if     (substr($w[$i],0,4)=='from')  {$z.=' '.$w[$i];}
			elseif (substr($w[$i],0,5)=='where') {$zwhere=' '.$w[$i];}
			elseif (substr($w[$i],0,5)=='order') {$zorder=' '.$w[$i];}
			elseif (substr($w[$i],0,5)=='group') {$zgroup=' '.$w[$i];}
			elseif (substr($w[$i],0,6)=='having') {$zhaving=' '.$w[$i];}
			else {
				if($i==1) {$z.=' ';} else {$z.=',';};
				$l=explode("|",$w[$i]);
				if (!($baza=='Select')&&(count(explode(".",$l[0]))<2)&&(count(explode("(",$l[0]))<2)) {
					$z.=$baza;
					$z.=".";
				}
				$z.=$l[0];
				if(!$l[1]) {$tn[$i-1]=trim($l[0]);} else {$tn[$i-1]=trim($l[1]);};
				$szer[$mc]=$l[2];		//szerokość
				if (substr($szer[$mc],0,1)=='+') {$c=$mc+1;};
				$sumy[$mc]='';
				if (strpos($szer[$mc],'+')>0) {	//"+" z prawej
					$sumy[$mc]='0';
					$sumyok=true;
				};
				$styl[$mc]=$l[3];		//style="font-size: 70pt; color: red; font-weight: normal"
				$styn[$mc]=$l[4];		//font-family: serif; font-size: 18pt; text-align: center
				$mc++;
			}
		}
		$cc=$mc;
//		$sql=$z.';';
	}
}

if ($tabelaa) {				// tryb Master/Slave
	if ($zgroup) {
		$z.=' '.$zgroup;	 	// "group by" zamiast "where"
		if ($zhaving) {		// "having" za "group by"
			$z.="$zhaving";
			if (substr($tabelap,0,1)=='[') {	// odwołanie do pól mastera
				$tr=explode('.',$tabelap);		// [1],[2]
				for($i=0;$i<count($tr);$i++) {
					$j=substr($tr[$i],1,1);
					$z=str_replace($tr[$i],$tra[$j],$z);
				}
			}
         else {$z.=" and ($baza.$tabelap=$tabelai)";};
      }
      else {                                          // nie ma "having", więc ma być
         $z.=" having $baza.$tabelap=$tabelai";
      }
   }
        else {
                if ($zwhere) {                        // jest "where", więc "and"
                        $z.="$zwhere";
                        if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
                                $tr=explode('.',$tabelap);                // [1],[2]
                                for($i=0;$i<count($tr);$i++) {
													if (substr($tr[$i],2,1)==']') {$j=substr($tr[$i],1,1);}
													else {$j=substr($tr[$i],1,2);}
                                       $z=str_replace($tr[$i],$tra[$j],$z);
                                }
                        }
                        else {$z.=" and ($baza.$tabelap=$tabelai)";};
                }
                else {                                          // nie ma "where", więc ma być
                        $z.=" where $baza.$tabelap=$tabelai";
                }
        }
}
else {						// tryb Slave
        if ($zgroup) {
                $z.=' '.$zgroup;                                                                          // "group by" zamiast "where"
                if ($zhaving) {                // "having" za "group by"
                        $z.="$zhaving";
                        if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
                                $tr=explode('.',$tabelap);                // [1],[2]
                                for($i=0;$i<count($tr);$i++) {
                                        $j=substr($tr[$i],1,1);
//                                        $z.=' '.$tra[$j];
                                        $z=str_replace($tr[$i],$tra[$j],$z);
                                }
                        }
                        else {$z.="$zhaving and $baza.$tabelap=$tabelai";};
                }
                else {                                          // nie ma "having", więc ma być
//                        $z.=" ...having $baza.$tabelap=$tabelai";
                }
					if ($warunek) {
						$warunek="($warunek)";
                if ($having) {$z.=" and $warunek";} else {$z.=" having $warunek";}
					};
					if ($sortowanie) {
						$z.=" order by $sortowanie";
						$zorder='';
					};
        }
        else {
                if ($_GET['szukane']) {
                   $zwhere=str_replace('[1]',$_GET['szukane'],$zwhere);
                }
                else {                                        // nic ne szukamy
                   if (count($w=explode("[1]",$zwhere))>1) {  // definicja SQL jest przeznaczona do szukania
                      $zwhere=''; // trzeba zrezygnować z ograniczeń
                      $zorder=''; // trzeba zrezygnować z uporzšdkowania "po nazwie" na rzecz "po ID", bo po "Dopisz" by się na nim nie ustawiał
                   }
                };
                $z.="$zwhere";                        // trzeba w końcu uwzględnić warunek "where"
					if ($warunek) {
						$warunek="($warunek)";
                if ($zwhere) {$z.=" and $warunek";} else {$z.=" where $warunek";}
					};
					if ($sortowanie) {
						$z.=" order by $sortowanie";
						$zorder='';
					};
        }
}
if ($zorder) {	$z.=' '.$zorder;}		// "order by" za "where"
$z.=" limit ";								// "limit" na końcu
$z.=sprintf("%d",($str-1)*$rr).",";
$z.=sprintf("%d",$rr);
$sql=$z.';';
$sql0=$sql;
$w=mysql_query($z);
if ($w) {
	$n=mysql_num_rows($w);
}
else {
	$n="0";
};
if (!$n) {
		$r=1;
		echo '$r='.$r.';';
		echo "\n";
}
else {
	if ($n<$r) {
		$r=$n;
		echo '$r='.$r.';';
		echo "\n";
	}
}

// tabela Slave (aktywna)
//********************************************************************

if ($cc<$c) {$c=$cc;};
echo '$c='.$c.';';
echo "\n";
	
echo '$cc='.$cc.';';
echo "\n";
	
//********************************************************************
?>

function Odswiez($kierunek){};
function klawisz() {}
document.onkeydown=klawisz;

function enter(){
	if (event.keyCode==27) {
<?php
	$ok=false;
	if (!$fun) { exit;}
	else {
		$f=explode("\n",$fun);
		$cc=Count($f);
		for($i=0;$i<$cc;$i++) {
			$l=explode("|",$f[$i]);
			if ($l[0]=='Esc') {
				echo $l[2].';';
				$ok=true;
			}
		}
	}
?>
	};
}
document.onkeypress=enter;

function mysza($x,$y){}
function mysza2($x,$y){}
function tab_czysc(){}
function tab_kolor(){}
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
function Start(){
<?php
	include("Raport_p.html");
?>
};
-->
</script>

</head>

<body bgcolor="#FFFFFF" onload="Start()">

<?php
	include("Raport_n.html");
?>

<br style="font-size: 12pt">

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

<form id="f1">
<?php
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
		if (!$ok_esc) {
			echo '<input type="button" value="Esc=wyjście" onclick="window.close()"/>';echo "\n";
		}
// inne niż Esc funkcje nie są potrzebne
//		for($i=0;$i<$cc;$i++) {
//			$l=explode("|",$f[$i]);
//			if ($l[1]) {
//				echo '<input type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/>';
//				echo "\n";
//			}
//		}
	}
?>
</form>

<div id="master">

<?php

$mr=$n;
if ($n==0) {
	if ($r<2&&$str<2) {
		$n=1;
//		echo '<h1 align="center"><br><br><br>BRAK DANYCH<br><br><br></h1>';
	}
	else {
		echo '<h1 align="center"><br>TO JEST OSTATNIA POZYCJA</h1>';
	}
};

$startkol=1;
if ($tabelaa) { // tabela master nieaktywna
	$startkol=2;
	echo '<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080" title="'.$tyta.'"> '; echo "\n";
	echo '<caption align="left">'.$tyta;
//	echo '  (';
//	echo $sqla;
	echo '</caption>';
	echo "\n";
	echo '<tr bordercolor="black">';
	echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
	for($j=1;$j<=$mca-1;$j++) {
		echo '<td ';
		echo 'align="center" CLASS="nag" ';
		if (!$styna[$j]||$styna[$j]=="\r") {;} else {echo $styna[$j];};
		echo ' bgcolor='.$tnag.'>'.$tna[$j] .'</td>';
		echo "\n";
	}
	echo '</tr>';
	for($i=0;$i<1;$i++){
//		$tra=mysql_fetch_row($wa);
		echo "\n";
		echo '<tr height=1 bgcolor='.$twie.'>';
		echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
		for($j=1;$j<$mca;$j++){
// wydruk nie tnie treści kolumn tabeli  "nowrap "
			echo '<td id="taba_'.$i.'_'.$j.'" width='.($szera[$j]*12);
			echo ' align="center" CLASS="nor" ';
			if (!$styla[$j]||$styla[$j]=="\r") {;} else {echo $styla[$j];};
			echo ' >';
			if (count($z=explode(":",$szera[$j]))>1) {			//obrazek
				if (!$z[0]) echo '<img src="'.$tra[$j].'" alt="" height='.$z[1].'>';
				if (!$z[1]) echo '<img src="'.$tra[$j].'" alt="" width='.$z[0].' >';
				if ($z[0]&&$z[1]) echo '<img src="'.$tra[$j].'" alt="" width='.$z[0].' height='.$z[1].'>';
			}
			else {											  			//tekst
				echo $tra[$j];
// wydruk nie tnie treści kolumn tabeli  "nowrap "
//				if (!$szera[$j]) {echo $tra[$j];}
//				elseif (strlen($tra[$j])>$szera[$j]) {echo substr($tra[$j],0,$szera[$j]).'...';}
//				else {echo substr($tra[$j],0,$szera[$j]);};
			}
			echo '</td>';
			echo "\n";
		}
		echo '</tr>';
	}
}
echo '</table>';
echo '</div>';
echo "\n";

echo '<br style="font-size: 12pt">';
echo "\n";
echo '<div id="slave">';
echo "\n";
echo '<table border="1" align="center" id="tab" summary="'.$n.'"  cellpadding="0" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080" title="'.$tyt.'"> '; echo "\n";
echo '<caption align="left">'.$tyt;
//echo '  (';
//echo $sql;
echo '</caption>';

echo "\n";
echo '<tr bordercolor="black">';
echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
for($j=$startkol;$j<=$mc-1;$j++) {
   if ($szer[$j]==='0')    {echo '<td id="tab_0'.($j+1).'" nowrap width=0 style="font-size:0"';}
   elseif ($szer[$j]=='.') {echo '<td id="tab_0'.($j+1).'" nowrap width=0 style="font-size:0"';}
   else {                   echo '<td id="tab_0'.($j+1).'" ';}
	echo 'align="center" CLASS="nag" ';
	if (!$styn[$j]||$styn[$j]=="\r") {;} else {echo $styn[$j];};
	echo ' bgcolor='.$tnag.'>';
	if ($szer[$j]=='.') {echo '.';}
	else {echo $tn[$j];};
	echo '</td>';
	echo "\n";
}
echo '</tr>';

if ($mr==0) {		// brak specyfikacji
	$mr=1;
for($i=0;$i<1;$i++){
	$tr=mysql_fetch_row($w);
	echo "\n";
	echo '<tr id="tab_'.($i+1).'" height=1 bgcolor='.$twie.'>';
	echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
	for($j=$startkol;$j<$mc;$j++){
// wydruk nie tnie treści kolumn tabeli  "nowrap "
                if ($szer[$j]==='0')    {echo '<td id="tab_'.$i.'_'.$j.'" width=0 style="font-size:0"';}
                elseif ($szer[$j]=='.') {echo '<td id="tab_'.$i.'_'.$j.'" width=0 style="font-size:0"';}
                else {                   echo '<td id="tab_'.$i.'_'.$j.'" width='.($szer[$j]*12);}
		echo ' align="center" CLASS="nor" ';
		if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
		echo ' >';
		echo '...';
		echo '</td>';
		echo "\n";
	}
	echo '</tr>';
}
}
else {
for($i=0;$i<$mr;$i++){
	$tr=mysql_fetch_row($w);
	echo "\n";
	echo '<tr id="tab_'.($i+1).'" height=1 bgcolor='.$twie.'>';
	echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
	for($j=$startkol;$j<$mc;$j++){
// wydruk nie tnie treści kolumn tabeli  "nowrap "
		if ($szer[$j]==='0')    {echo '<td id="tab_'.$i.'_'.$j.'" width=0 style="font-size:0"';}
		elseif ($szer[$j]=='.') {echo '<td id="tab_'.$i.'_'.$j.'" width=0 style="font-size:0"';}
		else {                   echo '<td id="tab_'.$i.'_'.$j.'" width='.($szer[$j]*12);}
		echo ' align="center" CLASS="nor" ';
		if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
		echo ' >';
		if (count($z=explode(":",$szer[$j]))>1) {			//obrazek
			if (!$z[0]) echo '<img src="'.$tr[$j].'" alt="" height='.$z[1].'>';
			if (!$z[1]) echo '<img src="'.$tr[$j].'" alt="" width='.$z[0].' >';
			if ($z[0]&&$z[1]) echo '<img src="'.$tr[$j].'" alt="" width='.$z[0].' height='.$z[1].'>';
		}
		else {											  			//tekst
// wydruk nie tnie treści kolumn tabeli  "nowrap "
			if ($szer[$j]=='.') {echo '.';}
			else {echo $tr[$j];};
//			if (!$szer[$j]) {echo $tr[$j];}
//			elseif (strlen($tr[$j])>$szer[$j]) {echo substr($tr[$j],0,$szer[$j]).'...';}
//			else {echo substr($tr[$j],0,$szer[$j]);};
		}
		if (($sumyok)&&(!($sumy[$j]==''))) {		// wiersz sum
			$sumy[$j]+=$tr[$j];
		}
		echo '</td>';
		echo "\n";
	}
	echo '</tr>';
}
if ($sumyok) {		// wiersz sum
	echo "\n";
	echo '<tr bgcolor='.$twie.'>';
	echo "\n";
	$sumyok=true;
	for($j=$startkol;$j<$mc;$j++){
		if ($sumyok&&!$sumy[$j+1]=='') {
			$sumy[$j]='Suma:';
			$sumyok=false;
		}
		echo '<td width='.($szer[$j]*12);
		if (!$sumy[$j]=='') {
			echo ' style="border-top: double #000000" ';
		}
		echo ' align="center" CLASS="nor" ';
		if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
		echo ' >';
		if (!$szer[$j]) {echo $sumy[$j];}
		elseif (strlen($sumy[$j])>$szer[$j]) {echo substr($sumy[$j],0,$szer[$j]).'...';}
		else {echo substr($sumy[$j],0,$szer[$j]);};
		echo '</td>';
		echo "\n";
	}
	echo '</tr>';
}
}
mysql_free_result($w);
//mysql_free_result($f);
require('dbdisconnect.inc');

echo '</table>';
echo '</div>';
echo "\n";

include("Raport_s.html");

?>

</body>
</html>

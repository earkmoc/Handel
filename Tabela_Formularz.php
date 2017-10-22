<?php

session_start();

//require('skladuj_zmienne.php');exit;
//require('skladuj_echozmienne.php');die;

if ($_GET['doktyp']) {$_SESSION['doktyp']=$_GET['doktyp'];}
$doktyp=$_SESSION['doktyp'];

if ($_GET['doktypnazwa']) {$_SESSION['doktypnazwa']=$_GET['doktypnazwa'];}
$doktypnazwa=$_SESSION['doktypnazwa'];

$test=false;
$ido=$_SESSION['osoba_id'];
$punkt=$_SESSION['osoba_pu'];
$osoba_id=$ido;
$batab=$_POST['batab'];

$mybgcolor='#0F4F9F';	//#D0DCE0';	//

if ($_POST['natab']&&($_POST['natab']!=='osoby')) {
	if (!$_SESSION['osoba_upr']) {
	        echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
	        echo "<title>OK</title></head><body bgcolor='#BFD2FF' ";
	        echo "onload='";
	        echo 'location.href="Tabela_End.php"';
	        echo "'\'>";
	//        echo '<h1 align="center"><br><br><br>Przetwarzanie danych w toku ...</h1>';
	        echo '</body></html>';
	        exit;
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<title>
<?php
if ($_SESSION['osoba_upr']) {echo $_SESSION['osoba_upr'];}
?>
</title>

<style type="text/css">
<!--
#f0project {POSITION: absolute; VISIBILITY: hidden; TOP:-5; LEFT: -5; Z-INDEX:2; }
.nag {font: normal 10pt};
.nor {font: normal 10pt};
.niesel {font: normal 10pt};
.niefoc {font: normal 10pt};
input {background-color:white;}
.blue   {font: normal 10pt; padding-left:2pt; height: 14pt; border: solid black 1pt; background-color: rgb(51,204,255);   font: bold; }
.yellow {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: yellow;            font: bold; }
.orange {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: orange;            font: bold; }
.green  {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: green;             font: bold; }
.granat {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: #0063FF;           font: bold; }
.red    {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: red;               font: bold; }
.lightgray {background-color:#CCCCCC; border: solid black 1pt; padding: 2pt;}
.cegla  {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: orangered;         font: bold; }
-->
</style>

<script language="JavaScript" src="calendar_db.js"></script>
<link rel="stylesheet" href="calendar.css">

<script type="text/javascript" src="advajax.js"></script>
<script type="text/javascript" language="JavaScript">

function Ajax_indeks_next($name,$i,$j) {
	$w=document.getElementsByName($name)[0].value;
	advAJAX.get({
	    url : "indeks_next.php?indeks="+$w,
	    onSuccess : function(obj) {
			s=obj.responseText;
			document.getElementsByName($name)[0].value=s;
	    }
	});
}

var tnag, cnag, twie, cwie, posx, posxx, r, c, str, okgoradol;

$okgoradol=true;

<?php

$zaznaczone=$_POST['zaznaczone'];
echo '$zaznaczone="'.$zaznaczone.'";';
echo "\n";

?>
</script>

<?php

require('dbconnect.inc');

$idtab=$_POST['idtab'];
if (!$idtab) {$idtab=$_GET['idtab'];}
if ($idtab<=0) {	//trzeba zapisać stan tabeli a potem zapomnieć o tym "$idtab", żeby sam go sobie ustalił

	$idtab=abs($idtab);
	$_POST['idtab']=$idtab;

//********************************************************************
// zapamiętaj stan tabeli dla zalogowanej osoby
// gdy zalogowany i przed chwil był w tabeli

require('Tabela_Save_Stan.php');

// zapamiętaj stan tabeli dla zalogowanej osoby
//********************************************************************

	$idtab='';	// sam go sobie ustali

}

$natab=$_POST['natab'];                // definicja tabeli formularza, np. spec
if (!$natab) {$natab=$_GET['natab'];};
if (!$natab) {$natab='osoby';};

if (count(explode(',',$natab))>1) {		//można okrelić w $natab, gdzie ma być po Esc i Enter: "abonencisz,opldodasz2,opldodasz2"
	$r=explode(',',$natab);
	$natab=$r[0];
	$nataba=$r[1];
	$natabb=$r[2];
}

$ipole=$_POST['ipole'];                // id pozycji tabeli, np. ipole=419
$opole=$_POST['opole'];                // jaka operacja dla Tabela_Formularz_Zapisz, np. opole=D
if (!$opole) {$opole="_";};
$oopole=$opole;
if ($opole=="N") {
        $opole="D";
        $_POST['opole']="D";
}

$rrr=$_POST['rrrpole'];
$rr=$_POST['rrpole'];
$r=$_POST['rpole'];
$c=$_POST['cpole'];
$str=$_POST['strpole'];

//&&($ipole!="0")
if (($opole!="D")&&(!$idtab||!$ipole)) {        // za mało więc pewno był HELP do kartoteki odbiorców lub innej i teraz wraca syn marnotrawny bez informacji, więc trzeba mu je odtworzyć
        $z="select * from tabele where NAZWA='";
        $z.=$natab;
        $z.="'";
        $w=mysql_query($z);
        $w=mysql_fetch_array($w);
        $idtab=$w['ID'];                                                // jest ID podanej tabeli

   $z='Select ID_POZYCJI,NR_ROW,NR_COL,NR_STR from tabeles where ID_OSOBY=';
        $z.=$_SESSION['osoba_id'];
        $z.=' and ID_TABELE=';
        $z.=$idtab;
        $w=mysql_query($z);
        $w=mysql_fetch_array($w);
        $ipole=$w['ID_POZYCJI'];                        // jest i reszta parametrów
	$_POST['ipole']=$ipole;                // id pozycji tabeli
        $str=$w['NR_STR'];
        $r=$w['NR_ROW'];
        $c=$w['NR_COL'];

        if ($_POST['sutabpol']) {        // było Enter w WYKAZYODBW
                $z='Update ';                                                        // więc wypełniamy pole ID_ODBIO
                $z.=$_POST['batab'];                 // tabela do zapisu: WYKAZY
                $z.=' set ';
                $z.=$_POST['sutabpol'];        // pole do zapisu: ID_ODBIO
                $z.='=';
                $z.=$_POST['sutabmid'];        // wartoć do zapisu: ID z WYKAZYODBW
                $z.=' where ID=';
                $z.=$ipole;                                                                // ID pola na którym działa formularz
                $w=mysql_query($z);                                        // zapis
                $phpini=trim($_POST['phpini']);        // reszta pól
                if ($phpini=='undefined') {$phpini='';}
                if ($phpini) {
                        include($phpini);
                }
        }
}

$iipole=$ipole;                                                // pozycja przed dopisywaniem, np. ipole=419

if ($_POST['opole']=='L') {                // logowanie
        $_SESSION['osoba_upr']='';                // wyloguj poprzedniego
        $_SESSION['osoba_id']='';
        $z="insert into logi (ID_OSOBY,CZAS) values (";
        $z.=$ipole;
        $z.=",'";
        $z.=date('Y-m-d H:i:s');
        $z.="');";
        $w=mysql_query($z);
//echo mysql_error();die;
        $ipole=mysql_insert_id();                // identyfikator nowego wiersza w tabeli logi
        $natab='logi';                                                // teraz tabela to logi
        $nataba='tabele';                                         // jak hasła będ zgodne to tu
        $natabb='osoby';                                        // jak hasła NIE BĘDˇ zgodne to tu
}

?>

<script type="text/javascript" language="JavaScript">
<!--

<?php
echo '$ipole="'.$ipole.'";';		//np. ipole=419
echo "\n";

$posxx=20;

echo '$natab="'.$natab.'";';		//np. natab=spec
echo "\n";

echo '$opole="'.$opole.'";';		//np. opole=D
echo "\n";

if (!$nataba) {$nataba=$natab;};                // gdzie ma wyldować po zapisaniu formularza
echo '$nataba="'.$nataba.'";';		//np. nataba=spec
echo "\n";

if (!$natabb) {$natabb=$natab;};                // gdzie ma wyldować po Esc formularza
echo '$natabb="'.$natabb.'";';		//np. natabb=spec
echo "\n";

if (!$r) {$r=1;};
echo '$r='.$r.';';
echo "\n";

if (!$rr) {$rr=10;};
echo '$rr='.$rr.';';
echo "\n";

if (!$rrr) {$rrr=10;};
echo '$rrr='.$rrr.';';
echo "\n";

if (!$c) {$c=1;};
echo '$c='.$c.';';
echo "\n";

if (!$str) {$str=1;};
echo '$str='.$str.';';
echo "\n";

$posx=$c-1;
if (!$posx) {$posx=1;};

echo '$posx='.$posx.';';
echo "\n";

echo '$posxx='.$posxx.';';
echo "\n";

//$tnag='"#FFCC33"';
$tnag='""';		// nowe
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
?>

//typowe obliczeniowe zastosowanie do zwykłych "input"
//onblur="Am(`CENA_S=Gr((100+{MARZA})*{CENA_Z})`)"

//warunkowe zastosowanie do zwykłych "input"
//onchange="Am(`CENNIK=({TYP}==``D``?0:1))`)"

//znak "[" i "]", żeby to była obróbka obiektu "select"
//onchange="Am(`[CENNIK]=(([TYP]<>``N``)?0:1)`)"
//   $x=$x.replace("`","'");
//   $x=$x.replace("`","'");
//   $x=document.getElementsByName("TYP")[0].selectedIndex;
//   $x=document.getElementsByName("TYP")[0].options[$x].text;
//   $x=$x.substring(0,1);
//   alert($x);

function Am($x){
	$y=$x.substring(0,$x.indexOf('=',$x));
	if ($y.indexOf('.',$y)) {
		$y=$x.substring(0,$y.indexOf('.',$y)+1);
	}
	$x='document.getElementsByName("'+$x;
	$x=$x.substring(0,$x.indexOf('=',$x))+'")[0].value'+$x.substring($x.indexOf('=',$x));
	while ($x.indexOf( '{',$x)>0) {$x=$x.substring(0,$x.indexOf('{',$x))+'document.getElementsByName("'+$y+$x.substring($x.indexOf('{',$x)+1,$x.indexOf('}',$x))+'")[0].value'+$x.substring($x.indexOf('}',$x)+1);}
	while ($x.indexOf('1*',$x)>0) {$x=$x.substring(0,$x.indexOf('1*',$x))+'parseInt'+$x.substring($x.indexOf('1*',$x)+2);}
	while ($x.indexOf('1.00*',$x)>0) {$x=$x.substring(0,$x.indexOf('1.00*',$x))+'parseFloat'+$x.substring($x.indexOf('1.00*',$x)+5);}
//	alert($x);
	eval($x);
	$x=$x.substring(0,$x.indexOf('=',$x));
//   alert($x);
	if (eval($x)=='N.aN') {
		$x=$x+'="0.00";';
		eval($x);
	}
}
function Gr($x){
var $s='';
   $x=Math.round($x);
   $s=''+$x;
   $x=$s.length;
   if ($x>2) {
      $x=$s.substring(0,$x-2)+'.'+$s.substring($x-2);
   } else {
      if ($x>1) {
         $x='0.'+$s;
      } else {
         if ($x>0) {
            $x='0.0'+$s;
         }
      }
   }
   return $x;
}
function tab_ruch($k,$t){
var $s='';
	$s=eval('tab1'+($posx+$k)+'.className');
	if ($s=='niefoc') {;
	} else {tab_czysc();
	}
   $posx+=$k;
   tab_kolor($t);
}
function tab_czysc(){
        eval('tab1'+$posx+'.style.background="'+$tnag+'";');                //nagłówek
}
function tab_kolor($t){
var $s='';
        f0.kla.value=event.keyCode;
        f0.opole.value=$opole;
        f0.posx.value=$posx;
        f0.posxx.value=$posxx;
        f0.zmrrr.value=$rrr;
        f0.zmrr.value=$rr;
        f0.zmr.value=$r;
        f0.zmc.value=$c;
        f0.zmstr.value=$str;
        f0.zmtabela.value=$natab;
        f0.zmtabelaa.value=$nataba;
        f0.zaznaczone.value=$zaznaczone;
        f0.screen_w.value=screen.width;
        f0.screen_h.value=screen.height;
        if (!$t) {
            eval("document.getElementById('tab2"+$posx+"').focus()");
      		$s=eval("document.getElementById('tab2"+$posx+"').className");
            if ($s=='niesel') {  	//alert($s);
            } else {
               eval("document.getElementById('tab2"+$posx+"').select()");
		      }
        }
}
function nag_kolor($x) {
        $posx=$x;
        eval('tab1'+$x+'.style.background="'+$cnag+'";');                //nagłówek
}
function nag_czysc($x){
        eval('tab1'+$x+'.style.background="'+$tnag+'";');                //nagłówek
}
function klawisz() {
   if ($okgoradol) {
          if ((event.keyCode==40)&&$posx<$posxx)	{tab_ruch(1)};
          if ((event.keyCode==38)&&$posx>1)	{tab_ruch(-1)};
   }
   return event.keyCode;
}
document.onkeydown=klawisz;

function sio(){
//	if (f0.nie.style.visibility!="hidden") {
		f0.tak.style.visibility="hidden";
		location.href="Tabela.php?tabela="+$natabb;
//	}
}
function enter(){
	if (event.keyCode==27 && f0.nie.style.visibility!="hidden") {sio();};
}
document.onkeypress=enter;

function Zapisz(php) {
	if (php&&f0.zmtabelaa.value=='analizabp') {
//	open('StanProcesu.php','title','directories=no,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,toolbar=no,top=400,left=200,height=100,width=400');
	open(php,'','top=250,left=250,height=350,width=530');
	}
	f0.tak.click();
	f0.tak.style.visibility="hidden";
	f0.nie.style.visibility="hidden";
	f0.projectSave.style.visibility="hidden";
}

var nn='', nnn='', X=0, Y=0;

function SetLang(){
	if (f0.projectID.value=='') {;} else {
		s=eval(f0.projectID.value+".lang");
		s=s.substring(0,s.indexOf(',',s));
		s+=','+f0.projectY.value;
		s+=','+f0.projectX.value;
		s+=','+f0.projectStyleField.value;
		s+=','+f0.projectStyleNag.value;
		s+=','+f0.projectLabel.value;
		s+=','+f0.projectW.value;
		s+=','+f0.projectH.value;
		eval(f0.projectID.value).lang=s;
	}
}
function MD(e){
	nnn=nn;
	X=event.offsetX;
	Y=event.offsetY;
	if (nnn=='') {;} else {
      if (f0.projectID.value!='' && nnn=='f0project') {;}
      else {
         SetLang(); 
         f0.projectID.value=nnn;
   		if (f0.projectDX.value>0) {f0.projectX.value=eval(nnn+".style.pixelLeft");}
   		if (f0.projectDY.value>0) {f0.projectY.value=eval(nnn+".style.pixelTop");}
         s=eval(nnn+".lang");
   		f0.projectName.value=s.substring(0,s.indexOf(',',s));
   		s=s.substring(s.indexOf(',',s)+1,s.length);
   		s=s.substring(s.indexOf(',',s)+1,s.length);
   		s=s.substring(s.indexOf(',',s)+1,s.length);
   		f0.projectStyleField.value=s.substring(0,s.indexOf(',',s));
   		s=s.substring(s.indexOf(',',s)+1,s.length);
   		f0.projectStyleNag.value=s.substring(0,s.indexOf(',',s));
   		s=s.substring(s.indexOf(',',s)+1,s.length);
   		f0.projectLabel.value=s.substring(0,s.indexOf(',',s));
   		s=s.substring(s.indexOf(',',s)+1,s.length);
   		f0.projectW.value=s.substring(0,s.indexOf(',',s));
   		f0.projectH.value=s.substring(s.indexOf(',',s)+1,s.length);
		}
	}
}
function MM(e){
var xx, yy;
	if (nnn=='') {;} else {
      if (f0.projectSize.checked) {
         if (f0.projectID.value!='f0project') {
   			xx=event.clientX-f0.style.pixelLeft-1-eval(f0.projectID.value).style.pixelLeft; 
   			xx=Math.floor(xx/f0.projectDX.value)*f0.projectDX.value;
            eval("e"+f0.projectID.value).style.width=xx;
   			f0.projectW.value=xx;

   			yy=event.clientY-f0.style.pixelTop-1-eval(f0.projectID.value).style.pixelTop;
   			yy=Math.floor(yy/f0.projectDY.value)*f0.projectDY.value;
            eval("e"+f0.projectID.value).style.height=yy;
   			f0.projectH.value=yy;
         }
      } else {
         if (f0.projectID.value!='f0project' && nnn=='f0project') {;}
      	else {
      		if (f0.projectDX.value>0) {
      			xx=event.clientX-X+document.body.scrollLeft-10; 
      			xx=Math.floor(xx/f0.projectDX.value)*f0.projectDX.value;
      			eval(nnn).style.pixelLeft=xx;
      			f0.projectX.value=xx;
      		}
      		if (f0.projectDY.value>0) {
      			yy=event.clientY-Y+document.body.scrollTop-10;
      			yy=Math.floor(yy/f0.projectDY.value)*f0.projectDY.value;
      			eval(nnn).style.pixelTop=yy;
      			f0.projectY.value=yy;
      		}
     		}
      }
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

function startproject() {
		f0.tak.style.visibility="hidden";
		f0.nie.style.visibility="hidden";
		f0project.style.visibility="visible";
}

function ruszamy(x,n) {
   if (projectmode.lang=='1') {
   	if (n==1) {
   		nn=x.id;
   		x.style.color="blue";
   	}
   	else {
   		nn='';
   		x.style.color="black";
   	}
   }
}

function ZapisPozycji() {
	$s='';
	SetLang();
	for($i=0;$i<$posxx;$i++) {
		$s=$s+eval("tab1"+($i+1)+".lang");
		$s=$s+"\n";
	}
   f0.opole.value=$s;
	f0.action="ZapiszPoz.php";
   f0.tak.click();
}

function start($mxx,$mx,$op){
        if ($mx>$mxx) {$mx=$mxx;};
        $posxx=$mxx;
        $posx=$mx;
        $opole=$op;
        tab_ruch(0);
}
function LTrim($s){
        $w='';
        $ok=true;
        for ($i=0;$i<$s.length;$i++)
        {
            if ($ok && $s.charAt($i)==' ') {
            }
            else {
                 $ok=false;
                 $w+=$s.charAt($i);
            }
        }
        return $w
}
function Adres($ko,$szuka){   // help do formularza = Alt+O (Odbiorcy) = Adres('WYKAZYODB', 3)
   if (f0.opole.value=="D") {
      f0.opole.value="d";
   } else {
      f0.opole.value="f";
   }
   f0.zmtabelaa.value=$ko;                // nazwa tabeli docelowej po zapisie
   if ($szuka) {
      f0.zmszukane.value=eval('f0.tab2'+$szuka+'.value');
      f0.zmszukane.value=LTrim(f0.zmszukane.value);
      if (f0.zmszukane.value=='') {f0.zmszukane.value='%';};
   }
   f0.tak.click();
}
function Rabaty($dokum_tab,$spec_tab,php){
	if (!php) {php='Rabaty';}
	open(php+".php?dokum_tab="+$dokum_tab+"&spec_tab="+$spec_tab,"title","directories=no,location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,top=200,width=250,left=290,height=20");
}
-->
</script>

</head>

<?php                        // zapamiętaj stan tabeli dla zalogowanej osoby

require('Tabela_Save_Stan.php');

$tn=array();
$szer=array();
$styl=array();
$styn=array();
$typp=array();

$pola=array();
$poleTop=array();
$poleLeft=array();
$poleWidth=array();
$poleHeight=array();

$mc=Count($tn);

$z="select * from tabele where NAZWA='$natab' limit 1";

$w=mysql_query($z);
if ($w) {
        $w=mysql_fetch_array($w);
        $tyt=StripSlashes($w['OPIS']);
        $tyt=str_replace('$doktypnazwa',$doktypnazwa,$tyt);
        $fun=StripSlashes($w['FUNKCJEF']);
        $sql=StripSlashes($w['FORMULARZ']);

        if (substr($sql,0,1)=='#') {   //definicja jest gdzie indziej

           $sql=substr($sql,1);

           $z="select * from tabele where NAZWA='$sql' limit 1";
           $w=mysql_query($z);
           $w=mysql_fetch_array($w);

	       $fun=StripSlashes($w['FUNKCJEF']);
           $sql=StripSlashes($w['FORMULARZ']);
        }

        $par=StripSlashes($w['PARAMSF']);
        if (!$sql) { exit;}
        else {
                $mc=-1;
                $w=explode("\n",$sql);
                $p=explode("\n",$par);
                $z='Select';   //$w[0];
                $b=trim($w[0]);        // w pierwszej linii nazwa bazy głównej
                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
                        if (substr($w[$i],0,4)=='from') {$z.=' '.trim($w[$i]);}
                        elseif (substr($w[$i],0,5)=='where') {$z.=' '.trim($w[$i]);}
                        elseif (substr($w[$i],0,5)=='group') {$z.=' '.trim($w[$i]);}
                        elseif (substr($w[$i],0,6)=='having') {$z.=' '.trim($w[$i]);}
                        else { //if ((substr($w[$i],0,2)!='ID') && (Count($w[$i])>2))
                           $mc++;
                           if($mc==0) {$z.=' ';} else {$z.=',';};
                           $l=explode("|",$w[$i]);
                              if ($mc==0&&($l[0]<>'(9.0)')) {
                                 $l[0]='(9.0)';
                                 $l[1]='<table width="100%" height="100%" border=1 cellspacing=0><tr height="75"><td bgcolor="#BFD2FF"></td></tr><tr height="50"><td bgcolor="#EFEFCF"></td></tr><tr><td bgcolor="#FFEF9F"></td></tr><tr height="75"><td bgcolor="#EFEFDF"></td></tr></table>';
                                 $l[2]='<div id= ></div>';
                                 $l[3]='';$l[4]='';$l[5]='';
                        			$poleWidth[$mc]='965';
                        			$poleHeight[$mc]='490';
            							$poleTop[$mc]='52';
            							$poleLeft[$mc]='10';
            							$i--;
                                 $posx++;   //jeden dalej bo wskoczyło tło
                              }
                              if (substr($l[0],0,3)=='(0.') {
                                 $posx++;   //jeden dalej bo wskoczyło tło
                              }
                           if (!($b=='Select')&&(count(explode(".",$l[0]))<2)) {
                                  $z.=$b;
                                  $z.=".";
                           }

//if(dokum.WARTOSC=dokum.BRUTTODOS,dokum.BRUTTODOS,concat(`<font style=``background-color:red``>`,dokum.BRUTTODOS,`</font>`))|Brutto razem|12|style="text-align:right"|
//							$buf=str_replace('``','"',$l[0]);
//							$buf=str_replace('`',"'",$buf);
//							$z.=$buf;

							$z.=$l[0];

                           $pola[$mc]=trim($l[0]);
                           $tn[$mc]=trim($l[1]);
                           $szer[$mc]=trim($l[2]);
                           $styl[$mc]=$l[3];
                           $styn[$mc]=$l[4];
                           if (substr($szer[$mc],0,1)==='+') {
                                  $szer[$mc]=substr($szer[$mc],1);
                                  $posx=$mc+1;
                           }
                           if ($par) {		// są parametry pól
                           	$j=0;
                           	$ok=true;
                           	while ($ok) {
                           		$bufor=explode(",",$p[$j]);
                           		if (count($buf=explode("),",$p[$j]))>1) {
                           			$bufor[0]=$buf[0].')';
                           		}
                           		if ($pola[$mc]==trim($bufor[0])) {
                           			$poleTop[$mc]=trim($bufor[1]);
                           			$poleLeft[$mc]=trim($bufor[2]);
                           			if (trim($bufor[3])) {$styl[$mc]=trim($bufor[3]);}
                           			if (trim($bufor[4])) {$styn[$mc]=trim($bufor[4]);}
                           			if (trim($bufor[5])) {$tn[$mc]=trim($bufor[5]);}
                           			if (trim($bufor[6])) {$poleWidth[$mc]=trim($bufor[6]);}
                           			if (trim($bufor[7])) {$poleHeight[$mc]=trim($bufor[7]);}
                           			$ok=false;	// nie leć dalej
                           		}
                           		$j++;		// następna linia
                           		if ($j>=count($p)) {$ok=false;};	// nie leć dalej
                           	}
                           }
                        }
                }
        }
}
if ($_POST['opole']=='L') {                // logowanie
   $z.=$ipole;
} elseif ($oopole=="N") {
   $z.="0";
} else {
   // dzięki temu, że iipole # ipole mamy kopię pozycji,
   // na której stalimy przy dopisywaniu
   $z.=$iipole;
   if ($iipole=='0') {
      $z.=" or $b.ID_OSOBYUPR=$ido";
   }
}
$testphp=$z;
$w=mysql_query($z);
if ($w) {
        $n=mysql_num_rows($w);                        // jak dobrze poszło to = 1
        $wynik=mysql_fetch_row($w);        // wartoci pól dla formularza
        for($j=0;$j<Count($wynik);$j++) {$wynik[$j]=StripSlashes($wynik[$j]);}
        mysql_free_result($w);
} else {
        $n="0";
        $wynik="";
}
if ($oopole=="N") {                                // Nowe=Dopisz pierwsze pole w pustej bazie
        $wynik[1]=$ipole;                                // wartoć pola łcznikowego z sub'em
}

if ($_GET['posx']) {
	$posx=($_GET['posx']);
}

if ($opole=="D") {
        $phpini=trim($_POST['phpini']);
        if ($phpini=='undefined') {$phpini='';}
        if ($phpini) {include($phpini);}
}

$mr=$n;
//if ($n==0) {exit;};

echo '<body bgcolor="'.$mybgcolor.'" onload="start(';	//BFD2FF
echo $mc+1;
echo ',';
echo $posx;
echo ",'";
echo $_POST['opole'];
echo "'";
echo ')">';
echo "\n";

echo '<form id="f0" action="Tabela_Formularz_Zapisz.php" method="post" style="position: absolute; top:10; left: 10; Z-INDEX:99;" >'; echo "\n";
?>
<div id="f0project" nowrap class="lightgray" style="position: absolute; top: 550; left:10" onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)">

          <input id="projectName"       size="7"   title="nazwa pola" disabled />
          <input id="projectLabel"      size="20"  title="etykieta pola" />
       ID:<input id="projectID"         size="3"   title="identyfikator pola" />
      Top:<input id="projectY"          size="2"   title="pozycja pionowa (od góry)" />
     Left:<input id="projectX"          size="2"   title="pozycja pozioma (od lewej)" />
   deltaT:<input id="projectDY"         size="2"   title="skok pionowy zmiany położenia lub rozmiaru"  value="1" />
   deltaL:<input id="projectDX"         size="2"   title="skok poziomy zmiany położenia lub rozmiaru"  value="1" />
    Width:<input id="projectW"          size="2"   title="szerokość" />
   Height:<input id="projectH"          size="2"   title="wysokość" />
     Size:<input id="projectSize"  type="checkbox" title="tryb modyfikacji rozmiaru" class="lightgray" />

<br>      <input id="projectStyleNag"   size="123" title="styl etykiety" />
         <button id="projectEsc"                   title="Wyjście bez zapisu zmian" style="cursor:hand;" onmousedown="sio()"          />Anuluj</button>
<br>      <input id="projectStyleField" size="123" title="styl pola"     />
         <button id="projectSave"                  title="Zapis zmian i wyjście"    style="cursor:hand;" onmousedown="ZapisPozycji()" />Zapisz</button>
</div>

<input type="hidden" id="kla" value="">
<input type="hidden" id="opole" name="opole" value="">
<input type="hidden" id="posx" name="posx" value="">
<input type="hidden" id="posxx" value="">
<input type="hidden" id="zmrrr" name="rrr" value="">
<input type="hidden" id="zmrr" name="rr" value="">
<input type="hidden" id="zmr" name="r" value="">
<input type="hidden" id="zmc" name="c" value="">
<input type="hidden" id="zmstr" name="str" value="">
<input type="hidden" id="zmtabela" name="tabela" value="">
<input type="hidden" id="zmtabelaa" name="tabelaa" value="">
<input type="hidden" id="zmszukane" name="szukane" value="">
<input type="hidden" id="zaznaczone" name="zaznaczone" value="">
<input type="hidden" id="screen_w" name="screen_w" value="">
<input type="hidden" id="screen_h" name="screen_h" value="">
<?php
echo '<input type="hidden" id="idtab" name="idtab" value="'.$idtab.'">'; echo "\n";
echo '<input type="hidden" id="ide" name="ID" value="'.$ipole.'">'; echo "\n";

for($j=0;$j<=$mc;$j++) {

echo "\n";
echo "\n";
echo '<div id="tab1'.($j+1).'" lang='."'".$pola[$j].','.trim($poleTop[$j]).','.trim($poleLeft[$j]).','.StripSlashes(trim($styl[$j])).','.StripSlashes(trim($styn[$j])).','.((substr(StripSlashes(trim($tn[$j])),0,1)=='<')?'':StripSlashes(trim($tn[$j]))).','.trim($poleWidth[$j]).','.trim($poleHeight[$j])."'".' nowrap onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)" style="position: absolute; text-valign:top; ';

//if (  	(	($batab=='towary')
//		||	($batab=='spec')
//		)
//	&&($_SESSION['osoba_dos']<>'T')
if (($_SESSION['osoba_dos']<>'T')
	&&(		(substr($pola[$j],-6,6)=='CENA_Z')
		||	(substr($pola[$j],-5,5)=='MARZA')
		||	(substr($pola[$j],-6,6)=='MARZA2')
		||	(substr($pola[$j],-6,6)=='MARZA3')
		||	(substr($pola[$j],-6,6)=='MARZA4')
		||	(substr($pola[$j],-6,6)=='MARZA5')
		||	(substr($pola[$j],-7,7)=='CENA_S3')
		||	(substr($pola[$j],-7,7)=='CENA_B3')
		|| ((substr($pola[$j],-3,3)=='TYP'   )&&(substr($natab,0,5)=='firmy'))
		|| ((substr($pola[$j],-6,6)=='TERMIN')&&(substr($natab,0,5)=='firmy'))
		|| ((substr($pola[$j],-5,5)=='RABAT' )&&(substr($natab,0,5)=='firmy'))
		|| ((substr($pola[$j],-6,6)=='CENNIK')&&(substr($natab,0,5)=='firmy'))
		)
	) {
      echo 'visibility:hidden; ';
}

if ($par) {	
   echo 'top:'.$poleTop[$j].'; left: '.$poleLeft[$j].'; ';
	if ((substr($tn[$j],0,4)=='TOYA')&&($wynik[$j])) {
	  echo 'background:#FF6600;';
	}
} else {
   echo 'top:'.(($poleTop[$j])?$poleTop[$j]:(($j-1)*50+80)).';';
   echo 'left:'.(($poleLeft[$j])?$poleLeft[$j]:20).';';
}
echo ' color:black;" ';

if (substr($szer[$j],0,1)=='<') {
   echo 'class="niefoc" ';
	echo '>'."\n";
	if ($tn[$j]) {
   	echo '<font id="etab1'.($j+1).'" ';
      if (!$styn[$j]||$styn[$j]=="\r") {echo 'class="nag"';} else {echo $styn[$j];}
      if ($poleWidth[$j]||$poleHeight[$j]) {
         echo ' style="';
         if ($poleWidth[$j]) {echo 'width:'.$poleWidth[$j].'; ';}
         if ($poleHeight[$j]) {echo 'height:'.$poleHeight[$j].'; ';}
         echo '"';
      }
   	echo '>';
   	if ($tn[$j]<>':') {echo trim($tn[$j]);}		//same dwukropki nie pisz
   	if (substr($tn[$j],-1,1)<>':') {echo '<br>';} else {echo "&nbsp;";}	//normalnie pole pod labelem
   	echo '</font>';
	}
	echo "\n".str_replace('id=','id="tab2'.($j+1).'"',$szer[$j]);	//niefocusowe !!!
} else {
	echo 'class="nor" ';
	echo '>'."\n";
	if (trim($tn[$j])) {
   	echo '<font id="etab1'.($j+1).'" ';
      if (!$styn[$j]||$styn[$j]=="\r") {echo 'class="nag"';} else {echo $styn[$j];};
      if ($poleWidth[$j]||$poleHeight[$j]) {
         echo ' style="';
         if ($poleWidth[$j]) {echo 'width:'.$poleWidth[$j].'; ';}
         if ($poleHeight[$j]) {echo 'height:'.$poleHeight[$j].'; ';}
         echo '"';
      }
   	echo '>';  //."\n";
   }
	$jest=0;

	if ($fun) {
		$f=explode("\n",$fun);
		$cc=Count($f);
		for($i=0;$i<$cc;$i++) {
			$l=explode("|",$f[$i]);$l[1]=trim($l[1]);
			if ($l[1]&&($l[1]==$tn[$j])) {
				$jest=1;
				if (count(explode(':',$l[1]))>1) {	//jest dwukropek, to button obok pola
//					echo '<br>';
					echo '<button style="cursor:hand;" accesskey="'.$l[0].'" onclick="'.$l[2].'" title="'.$l[3].'">'.(str_replace($l[0],'<u>'.$l[0].'</u>',$l[1])).'</button>';echo "\n";
				}
				else {	//jak nie ma to piętrowo button i pole
					echo '<button style="cursor:hand;" accesskey="'.$l[0].'" onclick="'.$l[2].'">'.(str_replace($l[0],'<u>'.$l[0].'</u>',$l[1])).'</button><br>';echo "\n";
				}
			}
		}
	}
	if (trim($tn[$j])) {
      if (!$jest) {
   		if ($tn[$j]<>':') {echo trim($tn[$j]);}		//same dwukropki nie pisz
      }
      echo '</font>';
   	if ($jest) {
        echo "&nbsp;";
	} else {
   		if (substr($tn[$j],-1,1)==':') {
            echo "&nbsp;";
         } elseif (substr($tn[$j],-1,1)==';') {
            echo "";
         } else {
            echo '<br>';
         }
   	}
   }
}

echo "\n";

   if (count($z=explode("option:",$szer[$j]))>1) {
      echo "\n".'<select CLASS="niesel" id="tab2'.($j+1).'" name="'.$pola[$j].'" ';
	  if ($z[0]>1) {
	  	echo ' style="width:'.($z[0]*5+10).'pt"  ';
	  }
      if (!$styl[$j]||$styl[$j]=="\r") {;
      } else {
         echo str_replace('~',"`",str_replace('`',"'",str_replace('``','~',$styl[$j])));
      }
      echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
      echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '>';
		$buf2=false;
   	if (strtoupper(substr($z[1],0,6))=='SELECT') {
   		$z=$z[1];	//zapytanie, np.: select TRESC from slownik where TYP='dokumenty'
   		for ($i=0;$i<$j;$i++) {$z=str_replace("wynik$i",$wynik[$i],$z);}
   		$w=mysql_query($z);
   		while ($z=mysql_fetch_row($w)) {
   		        echo "\n".'<option';
   			if (!$buf2 && (strlen($wynik[$j])>0) && strtoupper($wynik[$j])==strtoupper(substr($z[0],0,strlen($wynik[$j])))) {
   				echo ' selected';
   				$buf2=true;
   			}
   			echo '>'.$z[0];
   		}
   	} else {
   		$buf=count($z=explode(",",$z[1]));
   		for ($i=0;$i<$buf;$i++) {
   		        echo "\n".'<option';
   			if (!$buf2 && (strlen($wynik[$j])>0) && strtoupper($wynik[$j])==strtoupper(substr($z[$i],0,strlen($wynik[$j])))) {
   				echo ' selected';
   				$buf2=true;
   			}
   			echo '>'.$z[$i];
   		}
   	}
		if (!$buf2) {
         echo '<option selected>'.$wynik[$j];
		}
      echo "\n".'</select>';
   } elseif (substr($szer[$j],0,1)=='<') {      // anchor
//        	echo str_replace('id=','id="tab2'.($j+1).'" name="'.$pola[$j].'" ',$szer[$j]);
   } elseif (count($z=explode("/",$szer[$j]))>1) {				// textarea
      echo '<textarea CLASS="nor" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
	   if (count($z=explode("blue",$styl[$j]))>1) {
	      echo ' onfocus="'."getElementById('tab2".($j+2)."').focus();".'"';	//disabled
		} else {
         echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
		}
      echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '>';
      if (count($z=explode("blue",$styl[$j]))>1) {
      $wynik[$j]=strip_tags($wynik[$j]);
      $wynik[$j]=str_replace('&brvbar;'."\n\r".'&brvbar;','&brvbar;&brvbar;',$wynik[$j]);
      $wynik[$j]=str_replace('&brvbar;&brvbar;','&brvbar;'."\n".'&brvbar;',$wynik[$j]);
      }
      echo str_replace('</textarea>','<//textarea>',$wynik[$j]);
      echo '</textarea>';
   } elseif (count($z=explode("-",$szer[$j]))>1) {
      echo '<textarea CLASS="nor" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
      echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
      echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '>';
      echo str_replace('</textarea>','<//textarea>',$wynik[$j]);
      echo '</textarea>';
   } elseif (count($z=explode("*",$szer[$j]))>1) { // password
      echo '<input type="password" CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
      echo 'name="'.$pola[$j].'" value="" ';
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
      echo ' onfocus="nag_kolor('.($j+1).');" ';
      echo 'onblur="nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '/>';
   } elseif (count($z=explode("checkbox",$szer[$j]))>1) {    //checkbox
      echo '<input type="checkbox" CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
      if ($wynik[$j]) {echo 'checked ';}
      echo 'name="'.$pola[$j].'" value="1" ';
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
      echo ' onfocus="nag_kolor('.($j+1).');" ';
      echo 'onblur="nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '/>';
   } elseif (count($z=explode("t",$szer[$j]))>1) {
      echo '<input CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
      echo 'name="'.$pola[$j].'" value="'.date('Y-m-d H:i:s').'" ';
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
      echo ' onfocus="nag_kolor('.($j+1).');" ';
      echo 'onblur="nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '/>';
   } elseif (count($z=explode("D",$szer[$j]))>1) {
      echo '<input CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
      if (!(trim($wynik[$j]))) {
                echo 'name="'.$pola[$j].'" value="'.(date('Y-m-d')).'" ';
      } else {
                echo 'name="'.$pola[$j].'" value="'.($wynik[$j]).'" ';
      }
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
      echo ' onfocus="nag_kolor('.($j+1).');" ';
      echo 'onblur="nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '/>';
   } elseif (count($z=explode("d",$szer[$j]))>1) {
      echo '<input CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
      echo 'name="'.$pola[$j].'" value="'.($wynik[$j]).'" ';
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
      echo ' onfocus="nag_kolor('.($j+1).');" ';
      echo 'onblur="nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '/>';
   } elseif (count($z=explode(":",$szer[$j]))>1) {
      if (!$z[0]&&!$z[1])    echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab2'.($j+1).'" name="'.$pola[$j].'">';
      elseif (!$z[0])        echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab2'.($j+1).'" height='.$z[1].' name="'.$pola[$j].'">';
      elseif (!$z[1])        echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab2'.($j+1).'" width='.$z[0].' name="'.$pola[$j].'">';
      elseif ($z[0]&&$z[1]) echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab2'.($j+1).'" width='.$z[0].' height='.$z[1].' name="'.$pola[$j].'">';
   } else {																// input
      $buf=$szer[$j];
      $bufor=$wynik[$j];
//		echo "$bufor&nbsp;";
      if (count($z=explode("@Z",$buf))>1) {		// bez zer
      	$buf=str_replace('@Z','',$szer[$j]);
      	$buf=str_replace('+','',$buf);
      	$buf=str_replace('%','',$buf);
      	if ($bufor*1==0) {                    //jest zero
      		$buf=str_replace('@z','',$buf);
      		$buf=str_replace('w','',$buf);
      		$bufor='';
      	} elseif (count($z=explode("@z",$buf))>1) {	//zera po kropce ucinamy
      		$buf=str_replace('@z','',$buf);
      	   if (count($z=explode(".",$bufor))>1) {		// bez zer po kropce
      			$bufor=$z[0];
      			$z[0]='';
      			if ($z[1]*1>0) {$bufor=$bufor.'.';}
      			else {
      				$bufor=$bufor.'&nbsp;';
      //						if ($buf<>'') {$buf=$buf*1+5;}	//twarde spacje zajmują więcej
      			}
      			while (substr($z[1],-1,1)==='0') {
      				$z[1]=substr($z[1],0,strlen($z[1])-1);
      				$z[0]=$z[0].'&nbsp;';
      //						if ($buf<>'') {$buf=$buf*1+5;}
      			}
      			$bufor=$bufor.$z[1].$z[0];
      		}
      	} elseif (count($z=explode("w",$buf))>1) {	//waluta
         	$buf=str_replace('w','',$buf);
      		$bufor=number_format($bufor*1.00,2,'.',',');
         }
		} else {
			$buf=1*$buf;	//wymuszenie liczby nawet jeśli dalej sa jakies napisy
		}
		$buf=floor($buf*1);
		if (count(explode('"',$bufor))>1) {
			$bufor="'".$bufor."'";
		} else {
			$bufor='"'.$bufor.'"';
		}
		echo "\n";
		if (count($z=explode("(",$szer[$j]))>1) {
			echo '<input CLASS="nor" id="tab2'.($j+1).'" maxlength="'.($z[1]*1).'"  size="'.($z[0]*1).'" ';
	    } else {
			echo '<input CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$buf.'"  size="'.$buf.'" ';
	    }
      echo 'name="'.$pola[$j].'" value='.$bufor.' ';
      if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo str_replace('`',"'",$styl[$j]);};
	   if (count($z=explode("blue",$styl[$j]))>1) {
	     echo ' onfocus="'."getElementById('tab2".($j+2)."').focus();".'"';	//disabled
		} else {
	     echo ' onfocus="nag_kolor('.($j+1).');" ';
		}
      echo ' onblur="nag_czysc('.($j+1).');" ';
      if ($test) {echo 'title="'.$pola[$j].'" ';}
      echo '/>';

      if (!(stripos($pola[$j],'DATA')===false)&&(stripos($styl[$j],"blue")===false)) {
			echo '<script language="JavaScript">';
			echo "new tcal ({'formname': 'f0','controlname': '".$pola[$j]."'});";
			echo '</script>';
      }
   }
echo "\n".'</div>';
}
echo "\n";

echo '<div id="buttons" style="position: absolute; Z-INDEX:1;">';
        $ok_esc=false;
        $ok_enter=false;
        if ($fun) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if (substr($l[0],0,3)=='Esc') {
                                echo '<button style="cursor:hand;" id="nie" type="reset" accesskey="'.$l[0].'" onclick="'.$l[2].'" title="'.$l[3].'">'.(str_replace($l[0],'<u>'.$l[0].'</u>',$l[1])).'</button>';echo "\n";
                                $ok_esc=true;
                        }
                        if (substr($l[0],0,5)=='Enter') {
                        	if ($l[2]<>'sio()') {
                                echo '<button style="cursor:hand;" id="tak" type="submit" accesskey="'.$l[0].'" onclick="'.$l[2].'" title="'.$l[3].'">'.(str_replace($l[0],'<u>'.$l[0].'</u>',$l[1])).'</button>';echo "\n";
                           }
                           $ok_enter=true;
                        }
                }
        }
        if (!$ok_esc) {
            echo '<button style="cursor:hand;" id="nie" type="reset"  name="nie" onclick="sio()"><u>Esc</u>=anuluj zmiany i wyjdź</button>';echo "\n";
        }
        if (!$ok_enter) {
      		$l='Enter=Zapisz';
      		$ww=$l;
//      		$z="select OK from osobyprawa where ID_OSOBY=$ido and OPCJA='$ww'";
//      		$ww=mysql_query($z);
//      		if ($ww) {$ww=mysql_fetch_row($ww);$ww=($ww[0]=='-');}
//      		if (!$ww) {
      			echo '<button style="cursor:hand;" id="tak" type="submit" accesskey="Z" name="tak" onclick="Zapisz()"';
               if (isset($_GET['blokada'])) {
                  echo (strip_tags($_GET['blokada'])<>'O')?' style="visibility: hidden"':'';
               }
               echo '><u>Enter</u>=<u>Z</u>apisz</button>';echo "\n";
//      		}
        }
        if ($fun) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if (substr($l[0],0,3)=='Esc') {;}
                        elseif (substr($l[0],0,5)=='Enter') {;}
                        elseif ($l[1]) {
               				$jest=0; for($j=0;$j<=$mc;$j++) {if ($l[1]==$tn[$j]) {$jest=1;};}
               				if (!$jest) {
                              echo '<button style="cursor:hand;" accesskey="'.$l[0].'" onclick="'.$l[2].'" title="'.$l[3].'">'.(str_replace($l[0],'<u>'.$l[0].'</u>',$l[1])).'</button>';echo "\n";
               				}
                        }
                }
        }

echo '</div>';//if (substr($batab,0,5)<>'dokum') {echo '<br>';}//echo "<br><font color='black'><b>$tyt</b></font>";
?>
</form>

<?php
echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo "document.title='$tyt, '+document.title;\n";
echo '-->'; echo "\n";
echo '</script>'; echo "\n";

include('stopka.html');
//echo $testphp;
require('dbdisconnect.inc');
echo "\n";

if ($jsf) {
  echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
  echo '<!--'; echo "\n";
  echo $jsf;  echo "\n";
  echo '-->'; echo "\n";
  echo '</script>'; echo "\n";
}

?>
</body>
</html>
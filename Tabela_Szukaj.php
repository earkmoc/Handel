<?php

session_start();

$doktyp=$_SESSION['doktyp'];
$doktypnazwa=$_SESSION['doktypnazwa'];

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
}};

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<title>
<?php
if ($_SESSION['osoba_upr']) {
        echo ': ';
        echo $_SESSION['osoba_upr'];
}
?>
</title>

<style type="text/css">
<!--
#f00 {POSITION: absolute; VISIBILITY: visible; TOP:10px; LEFT: 10px; Z-INDEX:2;}
.nag {font: normal 20pt};
.nor {font: normal 15pt times};
.nor2 {font: normal 10pt};
span {color: blue; font: bold};
-->
</style>


<script type="text/javascript" language="JavaScript">
<!--
var tnag, cnag, twie, cwie, posx, posxx, r, c, str, okgoradol;

$okgoradol=true;

<?php

$natab=$_POST['natab'];                // definicja tabeli formularza
if (!$natab) {$natab='osoby';};

$idtab=$_POST['idtab'];                // gdzie ma otworzyć formularz
$ipole=$_POST['ipole'];                // id pozycji tabeli
$opole=$_POST['opole'];                // jaka operacja dla Tabela_Szukaj_Zapisz
if (!$opole) {$opole="_";};
$oopole=$opole;
$rrr=$_POST['rrrpole'];
$rr=$_POST['rrpole'];
$r=$_POST['rpole'];
$c=$_POST['cpole'];
$str=$_POST['strpole'];

require('dbconnect.inc');

//if (!$idtab||!$ipole) {
if (!$idtab) {   // za mało informacji, więc pewno był HELP do kartoteki odbiorców lub innej i teraz wraca syn marnotrawny bez informacji, więc trzeba mu je odtworzyć
        $z="select * from tabele where NAZWA='";
        $z.=$natab;
        $z.="'";
        $w=mysql_query($z);
        $w=mysql_fetch_array($w);
        $idtab=$w['ID'];                                                // jest ID podanej tabeli

   $z='Select ID_POZYCJI,NR_ROW,NR_COL,NR_STR,ID from tabeles where ID_OSOBY=';
        $z.=$_SESSION['osoba_id'];
        $z.=' and ID_TABELE=';
        $z.=$idtab;
        $w=mysql_query($z);
        $w=mysql_fetch_array($w);
        $ipole=$w['ID_POZYCJI'];                        // jest i reszta parametrów
        $idtabeles=$w['ID'];
        $str=$w['NR_STR'];
        $r=$w['NR_ROW'];
        $c=$w['NR_COL'];

        if ($_POST['sutabpol']) {        // było Enter w WYKAZYODBW
                $z='Update ';                                                        // więc wypełniamy pole ID_ODBIO
                $z.=$_POST['batab'];                 // tabela do zapisu: WYKAZY
                $z.=' set ';
                $z.=$_POST['sutabpol'];        // pole do zapisu: ID_ODBIO
                $z.='=';
                $z.=$_POST['sutabmid'];        // wartość do zapisu: ID z WYKAZYODBW
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

$iipole=$ipole;                                                // pozycja przed dopisywaniem

echo '$ipole="'.$ipole.'";';
echo "\n";

$posxx=20;

echo '$natab="'.$natab.'";';
echo "\n";

echo '$opole="'.$opole.'";';
echo "\n";

if (!$nataba) {$nataba=$natab;};                // gdzie ma wylądować po zapisaniu formularza
echo '$nataba="'.$nataba.'";';
echo "\n";

if (!$natabb) {$natabb=$natab;};                // gdzie ma wylądować po Esc formularza
echo '$natabb="'.$natabb.'";';
echo "\n";

if (!$r) {$r=1;};
echo '$r='.$r.';';
echo "\n";

echo '$rr='.$rr.';';
echo "\n";

echo '$rrr='.$rrr.';';
echo "\n";

if (!$c) {$c=1;};

echo '$c='.$c.';';
echo "\n";

if (!$str) {$str=1;};
echo '$str='.$str.';';
echo "\n";

$posx=0;
if (!$posx) {$posx=1;};

echo '$posx='.$posx.';';
echo "\n";

echo '$posxx='.$posxx.';';
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
?>
function Edycja(){
	if (f0.tab21.style.visibility=="hidden") {
		f0.zmieniony.checked=false;
		f0.edytor.style.visibility="hidden";
		f0.tab21.style.visibility="visible"; f0.tab21.select(); f0.tab21.focus();
		f0.dodany_i.style.visibility="visible";
		f0.dodany_lub.style.visibility="visible";
	}
	else {
		f0.zmieniony.checked=true;
		f0.edytor.style.visibility="visible"; f0.edytor.select(); f0.edytor.focus();
		f0.tab21.style.visibility="hidden";
		f0.dodany_i.style.visibility="hidden";
		f0.dodany_lub.style.visibility="hidden";
	}
}
function tab_ruch($k,$t){
        tab_czysc();
        $posx+=$k;
        tab_kolor($t);
}
function tab_czysc(){
        eval('tab11.style.background="'+$tnag+'";');                //nagłówek
}
function tab_kolor($t){
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
        if (!$t) {
                eval('f0.tab21.focus()');                                                                //wiersz
                eval('f0.tab21.select()');                                                                //wiersz
        };
}
function nag_kolor($x) {
        $posx=$x;
        eval('tab1'+$x+'.style.background="'+$cnag+'";');                //nagłówek
}
function nag_czysc($x){
        eval('tab1'+$x+'.style.background="'+$tnag+'";');                //nagłówek
}
function klawisz() {
//        if (event.keyCode==27) {location.href="Tabela.php?r="+$r+"&c="+$c+"&str="+$str;};
//        if ((event.keyCode==9)&&$posx<$posxx)                {tab_ruch(1,1)};
        if ($okgoradol) {
                if ((event.keyCode==40)&&$posx<$posxx)                {tab_ruch(1)};
                if ((event.keyCode==38)&&$posx>1)                        {tab_ruch(-1)};
        }
        return event.keyCode;
}
document.onkeydown=klawisz;

function sio(){location.href="Tabela.php?tabela="+$natabb;}
function enter(){if (event.keyCode==27) {sio();};}
document.onkeypress=enter;

function start($mxx,$mx,$op){
        if ($mxx<$mx) {$mx=$mxx;};
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
if (f0.opole.value=="D") {f0.opole.value="d";} else {f0.opole.value="f";}
        f0.zmtabelaa.value=$ko;                // nazwa tabeli docelowej po zapisie
        if ($szuka) {
           f0.zmszukane.value=eval('f0.tab2'+$szuka+'.value');
           f0.zmszukane.value=LTrim(f0.zmszukane.value);
           if (f0.zmszukane.value=='') {f0.zmszukane.value='%';};
        };
        f0.tak.click();
}
-->
</script>

</head>

<?php                        // zapamiętaj stan tabeli dla zalogowanej osoby

if ($_SESSION['osoba_upr']) {

$z='Select ID from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$idtab;
$z.=' limit 1';

$w=mysql_query($z);
if ($w) {
        if (mysql_num_rows($w)>0) {

                $w=mysql_fetch_array($w);

	        $idtabeles=$w['ID'];

                $z='Update tabeles';
                $z.=' set NR_STR=';
                $z.=$str;
                $z.=', NR_ROW=';
                $z.=$r;
                $z.=', NR_COL=';
                $z.=$c;
                $z.=', ID_POZYCJI=';
                $z.=$ipole;
                $z.=' where ID=';
                $z.=$idtabeles;
                $z.=' limit 1';
	        $w=mysql_query($z);
        }
        else {
                $z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL) values (';
                $z.=$_SESSION['osoba_id'];
                $z.=',';
                $z.=$idtab;
                $z.=',';
                $z.=$ipole;
                $z.=',';
                $z.=$str;
                $z.=',';
                $z.=$r;
                $z.=',';
                $z.=$c;
                $z.=')';
	        $w=mysql_query($z);
	        $idtabeles=mysql_insert_id();
        }
}}

$tn=array();
$pola=array();
$szer=array();
$mc=Count($tn);

$z="select * from tabele where NAZWA='";
$z.=$natab;
$z.="'";
$z.=' limit 1';

$w=mysql_query($z);
if ($w) {
        $w=mysql_fetch_array($w);
        $tyt=StripSlashes($w['OPIS']);
        $tyt=str_replace('$doktypnazwa',$doktypnazwa,StripSlashes($w['OPIS']));

        $sql=StripSlashes($w['TABELA']);
        $z=explode("\n",$sql);		// na linie
        $z=trim($z[0]);
        $z=explode(",",$z);
        $baza=trim($z[3]);
        if (!$baza) {
           $baza=trim($z[0]);
        }

        $fun='';	//StripSlashes($w['FUNKCJEF']);
        if (!$sql) { exit;}
        else {
                $mc=-1;
                $w=explode("\n",$sql);

                $z='Select';   //$w[0];
                $b=trim($w[0]);        // w pierwszej linii nazwa bazy głównej
                if (count($b=explode(",",$w[0]))>1) {        // jest przecinek
   	             if (count($b=explode(",",$w[0]))>3) {   // s nawet 3: abonenciG,grupy,[1].[2],abonenci
                        $b=trim($b[3]);
         		     } else {
                        $b=$b[0];
         		     }
                } else {
                        $b=trim($w[0]);
                }

                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
                        if     (!$w[$i]) {;}
                        elseif (substr($w[$i],0,4)=='from') {$z.=' '.trim($w[$i]);}
                        elseif (substr($w[$i],0,5)=='group') {$z.=' '.trim($w[$i]);}
                        elseif (substr($w[$i],0,6)=='having') {$z.=' '.trim($w[$i]);}
                        elseif (substr($w[$i],0,5)=='where') {$z.=' '.trim($w[$i]).' and '.$b.'.ID=';}
                        elseif (substr($w[$i],0,5)=='order') {$z.='';}
                        else { //if ((substr($w[$i],0,2)!='ID') && (Count($w[$i])>2))
                                $mc++;
                                if($mc==0) {$z.=' ';} else {$z.=',';};
                                $l=explode("|",$w[$i]);
                                if (!($b=='Select')&&(count(explode(".",$l[0]))<2)) {
                                        $z.=$b;
                                        $z.=".";
                                }
                                $z.=$l[0];
                                $pola[$mc]=trim($l[0]);
                                $pola[$mc]=str_replace(".","krooopka",$pola[$mc]);
                                $pola[$mc]=str_replace(" ","_",$pola[$mc]);
                                $tn[$mc]=(!(trim($l[1]))?trim($l[0]):trim($l[1]));
                                $szer[$mc]=trim($l[2]);
                                $styl[$mc]=$l[3];                //style=""
                                $styn[$mc]=$l[4];                //style=""
                                if (substr($szer[$mc],0,1)=='+') {
                                        $szer[$mc]=substr($szer[$mc],1);
                                        $posx=$mc+1;
                                }
                        }
                }
        }
}
if ($_POST['opole']=='L') {                // logowanie
                $z.=$ipole;}
elseif ($oopole=="N") {
                $z.="0";
        }
else {
        // dzięki temu, że iipole # ipole mamy kopię pozycji,
        // na której staliśmy przy dopisywaniu
                $z.=$iipole;
}

$testphp=$z;
$w=mysql_query($z);
if ($w) {
        $n=mysql_num_rows($w);                        // jak dobrze poszło to = 1
        $wynik=mysql_fetch_row($w);        // wartości pól dla formularza
        for($j=0;$j<Count($wynik);$j++) {$wynik[$j]=StripSlashes($wynik[$j]);}
        mysql_free_result($w);
}
else {
        $n="0";
        $wynik="";
}
if ($oopole=="N") {                                // Nowe=Dopisz pierwsze pole w pustej bazie
        $wynik[0]=$ipole;                                // wartość pola łącznikowego z sub'em
}
if ($opole=="D") {
        $phpini=trim($_POST['phpini']);
        if ($phpini=='undefined') {$phpini='';}
        if ($phpini) {
                include($phpini);
        }
}

$warunek='';
if ($w=mysql_query("select WARUNKI from tabeles where ID=$idtabeles")) {
	$w=mysql_fetch_row($w);
	$warunek=$w[0];
}

require('dbdisconnect.inc');

$mr=$n;
//if ($n==0) {exit;};

echo '<body bgcolor="#BFD2FF" onload="start(';
//echo $mc+1;
echo '1,1';
//echo $posx;
echo ",'";
echo $_POST['opole'];
echo "'";
echo ')">';
echo "\n";
echo '<form id="f0" action="Tabela_Szukaj_Zapisz.php" method="post">';
        $ok_esc=false;
        $ok_enter=false;
        if ($fun) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if (substr($l[0],0,3)=='Esc') {
                                echo '<input id="nie" type="reset" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/>';echo "\n";
                                $ok_esc=true;
                        }
                        if (substr($l[0],0,5)=='Enter') {
                                echo '<input id="tak" type="submit" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/>';echo "\n";
                                $ok_enter=true;
                        }
                }
        }
        if (!$ok_esc) {
echo '<input id="nie" type="reset"  value="Esc=anuluj zmiany i wyjdź" name="nie" onclick="sio()">';echo "\n";
        }
        if (!$ok_enter) {
echo '<input id="tak" type="submit" accesskey="Z" value="Enter=Znajdź" name="tak">';echo "\n";
        }
        if (false) {		//($fun) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if (substr($l[0],0,3)=='Esc') {;}
                        elseif (substr($l[0],0,5)=='Enter') {;}
                        elseif ($l[1]) {
									$jest=0; for($j=0;$j<=$mc;$j++) {if ($l[1]==$tn[$j]) {$jest=1;};}
									if (!$jest) {
                                echo '<input type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/>';echo "\n";
									}
                        }
                }
        }
//type="hidden"
?>
<input type="hidden" id="kla" value="">
<input type="hidden" id="opole" name="opole" value="">
<input type="hidden" id="posx" name="posx" value="">
<input type="hidden" id="posxx" value="">
<input type="hidden" id="zmszukane" name="szukane" value="">
<?php
echo '<input type="hidden" id="zmrrr" name="rrr" value="'.$rrr.'">';echo "\n";
echo '<input type="hidden" id="zmrr" name="rr" value="'.$rr.'">';echo "\n";
echo '<input type="hidden" id="zmr" name="r" value="'.$r.'">';echo "\n";
echo '<input type="hidden" id="zmc" name="c" value="'.$c.'">';echo "\n";
echo '<input type="hidden" id="zmstr" name="str" value="'.$str.'">';echo "\n";
echo '<input type="hidden" id="zmtabela" name="tabela" value="'.$natab.'">';echo "\n";
echo '<input type="hidden" id="zmtabelaa" name="tabelaa" value="'.$natab.'">';echo "\n";
echo '<input type="hidden" id="idtab" name="idtab" value="'.$idtab.'">';echo "\n";
echo '<input type="hidden" id="ide" name="ID" value="'.$ipole.'">';echo "\n";
//
echo "\n";
echo "\n";
if ($tyt<>'Stan magazynu') {
	//echo '<input type="button" value="'.$natab.'">';
	if (($rr<>99)&&($natab<>'logi')&&($natab<>'osoby')&&($natab<>'osobyz')) {
        $j=2;
//        for($i=0;$i<$j;$i++) {
//                echo '<br style="font-size: 12pt">';
//        }
	}
}
echo "\n";

echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
echo '<!--'; echo "\n";
echo "document.title='$tyt, '+document.title;\n";
echo '-->'; echo "\n";
echo '</script>'; echo "\n";

echo '<table id="tab" align="center" width="100%" border="1" cellpadding="10" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080">'; echo "\n";
echo '<caption align="left">Znajdź dane w tabeli: <b>'.$tyt.'</b>';
//echo '  (';
//echo $testphp;
echo '</caption>';
for($j=0;$j<=$mc;$j++) {
	if ($j==$c-1) {
        echo '<tr>';        echo "\n";
        echo '<td nowrap CLASS="nag" id="tab11"';
	if (!$warunek) {echo ' valign="top"';}
	echo ' align="right" ';
        if (!$styn[$j]||$styn[$j]=="\r") {;} else {
   		$pola[$j]=$styn[$j];
   		$pola[$j]=str_replace(".","krooopka",$pola[$j]);
   		$pola[$j]=str_replace(" ","_",$pola[$j]);
	     }
        echo ' bgcolor='.$tnag.'>'.$tn[$j] .'&nbsp:&nbsp</td>';        echo "\n";
        echo '<td CLASS="nor" align="left" bgcolor='.$twie.'>';        echo "\n";
        if (count($z=explode("/",$szer[$j]))>1) {
                echo '<textarea CLASS="nor" id="tab21" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor(1);" ';
                echo 'onblur="$okgoradol=true;nag_czysc(1);" ';
                echo '>';
                echo $wynik[$j];
                echo '</textarea>';
        }
        elseif (count($z=explode("-",$szer[$j]))>1) {
                echo '<textarea CLASS="nor" id="tab21" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor(1);" ';
                echo 'onblur="$okgoradol=true;nag_czysc(1);" ';
                echo '>';
                echo $wynik[$j];
                echo '</textarea>';
        }
        elseif (count($z=explode("*",$szer[$j]))>1) {
                echo '<input type="password" CLASS="nor" id="tab21" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
                echo 'name="'.$pola[$j].'" value="" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor(1);" ';
                echo 'onblur="nag_czysc(1);" ';
                echo '/>';
        }
        elseif (count($z=explode("t",$szer[$j]))>1) {
                echo '<input CLASS="nor" id="tab21" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
                echo 'name="'.$pola[$j].'" value="'.date('Y-m-d H:i:s').'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor(1);" ';
                echo 'onblur="nag_czysc(1);" ';
                echo '/>';
        }
        elseif (count($z=explode(":",$szer[$j]))>1) {
                if (!$z[0])        echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab21" height='.$z[1].' name="'.$pola[$j].'">';
                elseif (!$z[1])        echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab21" width='.$z[0].' name="'.$pola[$j].'">';
                elseif ($z[0]&&$z[1]) echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab21" width='.$z[0].' height='.$z[1].' name="'.$pola[$j].'">';
        }
        else {
 	if ($warunek) {

		echo '<textarea name="edytor" CLASS="nor" style="visibility:hidden; position: absolute; top: 120; left: 20" rows="5" cols="118">';
                echo $warunek;
                echo '</textarea>';

		echo '<input type="checkbox" name="zmieniony" CLASS="nor" style="visibility:hidden; position: absolute; top: 120; left: 20" />';

		$warunek=' '.$warunek.' ';
		$warunek=str_replace(' and ',' i ',$warunek);
		$warunek=str_replace(' or ',' lub ',$warunek);
		$warunek=str_replace(' like ',' jak ',$warunek);
		$warunek=str_replace(' between ',' między ',$warunek);
		$warunek=str_replace($baza.'.','',$warunek);
		for ($i=0;$i<count($tn);$i++) {
		        if (!$styn[$i]||$styn[$i]=="\r") {;} else {
				$pola[$i]=$styn[$i];
				$pola[$i]=str_replace(".","krooopka",$pola[$i]);
				$pola[$i]=str_replace(" ","_",$pola[$i]);
			}
			$pole=str_replace('krooopka','.',$pola[$i]);
			$pole=str_replace('_',' ',$pole);
			$pole=str_replace($baza.'.','',$pole);
//echo "/".$pola[$i]."/".$tn[$i]."/\n<br>";
			$warunek=str_replace(' '.$pole.' ',' <b>'.$tn[$i].'</b> ',$warunek);
		}
		$warunek=trim($warunek);
		echo 'Obecny warunek: <a style="cursor:hand;" onclick="Edycja()"><font style="color:blue"><u>';
		echo $warunek;
		echo "</u></font></a><br><br>";

	}
               echo '<input CLASS="nor" id="tab21" size="40" ';
                echo 'name="'.$pola[$j].'" value="" ';	//'.$wynik[$j].'
//                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor(1);" ';
                echo 'onblur="nag_czysc(1);" ';
                echo '/>';

		if ($warunek) {
		echo "\n<br><br>";

                echo "<font size:10pt>";
                echo '<input CLASS="nor2" maxlength="'.$szer[$j].'"  size="'.$szer[$j].'" ';
                echo 'type="checkbox" name="dodany_i" value="on" ';
//                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor(1);" ';
                echo 'onblur="nag_czysc(1);" ';
                echo '/>dodać do obecnego warunku z łącznikiem "i"';

		echo "\n<br>";
                echo '<input CLASS="nor2" maxlength="'.$szer[$j].'"  size="'.$szer[$j].'" ';
                echo 'type="checkbox" name="dodany_lub" value="on" ';
//                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor(1);" ';
                echo 'onblur="nag_czysc(1);" ';
                echo '/>dodać do obecnego warunku z łącznikiem "lub"';
                echo '</font>';
		}
        }
        echo '</td>';        echo "\n";
        echo '</tr>';        echo "\n";
	}
}
?>
</table>
</form>

<?php
echo '<b>Instrukcja wypełniania pola warunku:</b><ul>';
echo '<li><span>puste pole</span> oznacza "<b>pokaż wszystkie dane</b>". Następuje wtedy odwołanie ewentualnych dotychczasowych warunków pokazywania danych w tabeli (widocznych pod tabelą z lewej strony po słowie "Filtr:")';
echo '<li>';
echo '<li>wpisanie tekstu lub liczby oznacza "pokaż dane rozpoczynające się od podanego tekstu/liczby", więc:';
echo '<li><b>15</b> pokaże dane o wartościach <b>15</b> jak <b>15</b>7 i <b>15</b>97 itp.';
echo '<li><b>WALC</b> pokaże dane o wartościach <b>WALC</b>ZAK, <b>WALC</b>ZYŃSKA itp.';
echo '<li>';
echo '<li>można stosować znaki "<span>=<>%+*</span>" lub dwuznak "<span>::</span>" (dwa dwukropki), np.:';
echo '<li><span>=</span><b>15</b> oznacza dane o wartościach dokładnie równych <b>15</b>';
echo '<li><span><=</span><b>15</b> oznacza dane o wartościach mniejszych lub równych <b>15</b>';
echo '<li><span>></span><b>"2006-06-01"</b> oznacza dane o datach późniejszych niż <b>2006-06-01</b>';
echo '<li><span>%</span><b>15</b> oznacza dane o wartościach <b>15</b> gdzieś w środku, np: <b>15</b>7777, 77<b>15</b>77, 7777<b>15</b>';
echo '<li><span>%</span><b>WALC</b> oznacza dane o wartościach <b>WALC</b> gdzieś w środku, np: <b>WALC</b>ZAK, KO<b>WALC</b>ZYK, GROM-<b>WALC</b>';
echo '<li><span>*</span><b>15</b> oznacza dodanie do dotychczasowego warunku z łącznikiem "i", warunku szukania liczby <b>15</b> na początku ';
echo '<li><span>+</span><b>15</b> oznacza dodanie do dotychczasowego warunku z łącznikiem "lub", warunku szukania liczby <b>15</b> na początku ';
echo '<li><b>10</b><span>::</span><b>15</b> oznacza dane o wartościach z zakresu: od <b>10</b> (włącznie) do <b>15</b> (włącznie)';
echo '<li>';
echo '<li>Uwaga: jeśli pole na które nakładamy warunek nie jest typu liczbowego, czyli jest typu "tekst" lub "data", to po użyciu znaków "<span>=<></span>" dalszą część warunku trzeba ująć w cudzysłowy, np.: <span>=</span><b>"WALCZAK"</b>, <span>>=</span><b>"2006-06-01"</b>';
echo '<li><span><></span><b>""</b> oznacza dane o wartościach niepustych';
echo '</ul>';
?>
</body>
</html>
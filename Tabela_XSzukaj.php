<?php

session_start();

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
<title>"Abonenci"
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
.nag {font: normal 15pt};
.nor {font: normal 20pt};
-->
</style>


<script type="text/javascript" language="JavaScript">
<!--
var tnag, cnag, twie, cwie, posx, posxx, r, c, str, okgoradol;

$okgoradol=true;

<?php

$natab=$_POST['natab'];                // definicja tabeli formularza
if (!$natab) {$natab='osoby';};

$idtab=$_POST['idtab'];                // gdzie ma otworzy� formularz
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

$iipole=$ipole;                                                // pozycja przed dopisywaniem

echo '$ipole="'.$ipole.'";';
echo "\n";

$posxx=20;

echo '$natab="'.$natab.'";';
echo "\n";

echo '$opole="'.$opole.'";';
echo "\n";

if (!$nataba) {$nataba=$natab;};                // gdzie ma wyl�dowa� po zapisaniu formularza
echo '$nataba="'.$nataba.'";';
echo "\n";

if (!$natabb) {$natabb=$natab;};                // gdzie ma wyl�dowa� po Esc formularza
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
function tab_ruch($k,$t){
        tab_czysc();
        $posx+=$k;
        tab_kolor($t);
}
function tab_czysc(){
        eval('tab1'+$posx+'.style.background="'+$tnag+'";');                //nag��wek
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
                eval('f0.tab2'+$posx+'.focus()');                                                                //wiersz
                eval('f0.tab2'+$posx+'.select()');                                                                //wiersz
        };
}
function nag_kolor($x) {
        $posx=$x;
        eval('tab1'+$x+'.style.background="'+$cnag+'";');                //nag��wek
}
function nag_czysc($x){
        eval('tab1'+$x+'.style.background="'+$tnag+'";');                //nag��wek
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
<?php
$phpini=trim($_POST['phpini']);
if ($phpini=='undefined') {$phpini='';}
echo 'f0.zmszukane.value=';
echo "'";
echo $phpini;
echo "';";
echo "\n";
?>
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

<?php                        // zapami�taj stan tabeli dla zalogowanej osoby

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
                $z.=$w['ID'];
                $z.=' limit 1';
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
        }
        $w=mysql_query($z);
}}

$tn=array();
$pola=array();
$szer=array();
$mc=Count($tn);

$z="select * from tabele where NAZWA='";
$z.=$natab;
$z.="'";
$z.=' limit 1';

$polaszukane=explode(",",$phpini);

function PoleSzukane($linia,$ps) {
	$l=explode("|",$linia);
	for ($i=0;$i<count($ps);$i++) {
		if ($l[0]==$ps[$i]) {
			return true;
		}
	}
	return false;
}

$w=mysql_query($z);
if ($w) {
        $w=mysql_fetch_array($w);
        $tyt=StripSlashes($w['OPIS']);
        $sql=StripSlashes($w['FORMULARZ']);
        $fun=StripSlashes($w['FUNKCJEF']);
        if (!$sql) { exit;}
        else {
                $mc=-1;
                $w=explode("\n",$sql);
                $z='Select';   //$w[0];
                $b=trim($w[0]);        // w pierwszej linii nazwa bazy g��wnej
                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
                        if (substr($w[$i],0,4)=='from') {$z.=' '.trim($w[$i]);}
                        elseif (substr($w[$i],0,5)=='where') {$z.=' '.trim($w[$i]).' and '.$b.'.ID=';}
                        elseif (substr($w[$i],0,5)=='order') {$z.='';}
                        elseif (!PoleSzukane($w[$i],$polaszukane)) {$z.='';}
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
                                $tn[$mc]=trim($l[1]);
                                $szer[$mc]=trim($l[2]);
                                $styl[$mc]=$l[3];                //style=""
                                $styn[$mc]=$l[4];                //style=""
                                if (substr($szer[$mc],0,1)==='+') {
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
        // dzi�ki temu, �e iipole # ipole mamy kopi� pozycji,
        // na kt�rej stali�my przy dopisywaniu
                $z.=$iipole;
}

$testphp=$z;
$w=mysql_query($z);
if ($w) {
        $n=mysql_num_rows($w);                        // jak dobrze posz�o to = 1
        $wynik=mysql_fetch_row($w);        // warto�ci p�l dla formularza
        for($j=0;$j<Count($wynik);$j++) {$wynik[$j]=StripSlashes($wynik[$j]);}
        mysql_free_result($w);
}
else {
        $n="0";
        $wynik="";
}
if ($oopole=="N") {                                // Nowe=Dopisz pierwsze pole w pustej bazie
        $wynik[0]=$ipole;                                // warto�� pola ��cznikowego z sub'em
}
if ($opole=="D") {
        $phpini=trim($_POST['phpini']);
        if ($phpini=='undefined') {$phpini='';}
        if ($phpini) {
                include($phpini);
        }
}
require('dbdisconnect.inc');

$mr=$n;
//if ($n==0) {exit;};

echo '<body bgcolor="#BFD2FF" onload="start(';
echo $mc+1;
echo ',';
echo $posx;
echo ",'";
echo $_POST['opole'];
echo "'";
echo ')">';

echo '<form id="f0" action="Tabela_XSzukaj_Zapisz.php" method="post">';
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
echo '<input id="nie" type="reset"  value="Esc=anuluj zmiany i wyjd�" name="nie" onclick="sio()">';echo "\n";
        }
        if (!$ok_enter) {
echo '<input id="tak" type="submit" accesskey="Z" value="Enter=Znajd�" name="tak">';echo "\n";
        }
//        if ($fun) {
        if (1==2) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if (substr($l[0],0,3)=='Esc') {;}
                        elseif (substr($l[0],0,5)=='Enter') {;}
                        elseif ($l[1]) {
                                echo '<input type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/>';echo "\n";
                        }
                }
        }
//type="hidden"
?>
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
<?php
echo '<input type="hidden" id="idtab" name="idtab" value="'.$idtab.'">';
echo '<input type="hidden" id="ide" name="ID" value="'.$ipole.'">';
//

//echo '<input type="button" value="'.$natab.'">';
if (($rr<>99)&&($natab<>'logi')&&($natab<>'osoby')&&($natab<>'osobyz')) {
        $j=2;
        for($i=0;$i<$j;$i++) {
                echo '<br style="font-size: 12pt">';
        }
}

echo '<table id="tab" align="center" width="100%" border="1" cellpadding="0" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080">'; echo "\n";
echo '<caption align="left">Znajd� dane w tabeli: '.$tyt;
//echo '  (';
//echo $testphp;
echo '</caption>';
for($j=0;$j<=$mc;$j++) {

        echo '<tr>';        echo "\n";

        echo '<td nowrap CLASS="nag" id="tab1'.($j+1).'" align="right" ';
			if (!$styn[$j]||$styn[$j]=="\r") {;} 
			else {echo $styn[$j];}
        echo ' bgcolor='.$tnag.'>';
			
			$jest=0;

//			if ($fun) {
			if (1==2) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if ($l[1]==$tn[$j]) {
									$jest=1;
                           echo '<input type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/>';echo "\n";
                        }
                }
		      }
				if (!$jest) {
					echo $tn[$j];
				}
			echo '&nbsp:&nbsp</td>'; echo "\n";

        echo '<td CLASS="nor" align="left" bgcolor='.$twie.'>';        echo "\n";
        if (count($z=explode("/",$szer[$j]))>1) {				// textarea
                echo '<textarea CLASS="nor" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
                echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
                echo '>';
                echo $wynik[$j];
                echo '</textarea>';
        }																	// textarea
        elseif (count($z=explode("-",$szer[$j]))>1) {
                echo '<textarea CLASS="nor" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
                echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
                echo '>';
                echo $wynik[$j];
                echo '</textarea>';
        }																	// password
        elseif (count($z=explode("*",$szer[$j]))>1) {
                echo '<input type="password" CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
                echo 'name="'.$pola[$j].'" value="" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }																	// timestamp
        elseif (count($z=explode("t",$szer[$j]))>1) {
                echo '<input CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
                echo 'name="'.$pola[$j].'" value="'.date('Y-m-d H:i:s').'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }																	// image
        elseif (count($z=explode(":",$szer[$j]))>1) {
                if (!$z[0])        echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab2'.($j+1).'" height='.$z[1].' name="'.$pola[$j].'">';
                elseif (!$z[1])        echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab2'.($j+1).'" width='.$z[0].' name="'.$pola[$j].'">';
                elseif ($z[0]&&$z[1]) echo '<img src="'.$wynik[$j].'" CLASS="nor" id="tab2'.($j+1).'" width='.$z[0].' height='.$z[1].' name="'.$pola[$j].'">';
        }
        else {																// input
					$bufor=$wynik[$j];
					if (count(explode('"',$bufor))>1) {
						$bufor="'".$bufor."'";
					}
					else {
						$bufor='"'.$bufor.'"';
					}
//					$bufor=addslashesstr_replace('"',"'",$bufor);
//					$bufor=addslashes($bufor);
                echo '<input CLASS="nor" id="tab2'.($j+1).'" maxlength="'.$szer[$j].'"  size="'.$szer[$j].'" ';
                echo 'name="'.$pola[$j].'" value='.$bufor.' ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }
        echo '</td>';        echo "\n";
        echo '</tr>';        echo "\n";
}
?>
</table>
</form>
<?php
echo 'Instrukcja wype�niania p�l warunku:<ul>';
echo '<li>puste pole oznacza "to pole nie ma znaczenia dla ustalania warunku pokazywania danych"';
echo '<li>';
echo '<li>wpisanie tekstu do pola "id Ulicy" oznacza "poka� dane rozpoczynaj�ce si� od podanego tekstu", wi�c:';
echo '<li>wpisanie litery "h" poka�e dane o warto�ciach "H01", "H02" itp.';
echo '<li>';
echo '<li>wpisanie liczby do pola "Nr domu" lub "Nr miesz." oznacza "poka� dane o warto�ciach r�wnych podanej liczbie uzupe�nionej z lewej zerami do d�ugo�ci pola", wi�c:';
echo '<li>wpisanie liczby 1 poka�e dane o warto�ciach "001"';
echo '<li>wpisanie liczby i tekstu "1u" poka�e dane o warto�ciach "001U"';
echo '<li>';
echo '<li>wpisanie tekstu lub liczby do pola "Uwagi stare" (czasem numer bloku, czasem telefon lub inne notatki) oznacza "poka� dane rozpoczynaj�ce si� od podanego tekstu/liczby", wi�c:';
echo '<li>wpisanie liczby 162 poka�e dane o warto�ciach 162 jak i 162/56 itp.';
echo '</ul>';
?>
</body>
</html>
<?php

session_start();

$mybgcolor='#0F4F9F';

$punkt=$_SESSION['osoba_pu'];

if (!$_SESSION['osoba_upr']) {
        echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
        echo "<title>OK</title></head><body bgcolor='#BFD2FF' ";
        echo "onload='";
        echo 'location.href="Tabela_End.php"';
        echo "'\'>";
        echo '</body></html>';
        exit;
};

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

require('dbconnect.inc');

$natab='specrozkp';                // definicja tabeli formularza

$idtab='';
$ipole=0;
$opole='F';
$rrr=0;
$rr=0;
$r=1;
$c=1;
$str=1;
$zaznaczone=$_GET['zaznaczone'];

$iipole=$ipole;                                                // pozycja przed dopisywaniem

echo '$ipole="'.$ipole.'";';
echo "\n";

echo '$zaznaczone="'.$zaznaczone.'";';
echo "\n";

$posxx=20;

echo '$natab="'.$natab.'";';
echo "\n";

echo '$opole="'.$opole.'";';
echo "\n";

if (!$nataba) {$nataba=$natab;};                // gdzie ma wyl�dowa� po zapisaniu formularza
echo '$nataba="'.$nataba.'";';
echo "\n";

if (!$natabb) {$natabb='specopl';};                // gdzie ma wyl�dowa� po Esc formularza
echo '$natabb="'.$natabb.'";';
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
        f0.zaznaczone.value=$zaznaczone;
        if (!$t) {
                eval('f0.tab2'+$posx+'.focus()');                                                                //wiersz
                eval('f0.tab2'+$posx+'.select()');                                                                //wiersz
        };
//        nag_kolor($posx)
//        eval('tab1'+$posx+'.style.background="'+$cnag+'";');                //nag��wek
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

function sio(){close();}
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

<?php                        // zapami�taj stan tabeli dla zalogowanej osoby

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
                        elseif (substr($w[$i],0,9)=='where ID=') {$z.=' '.str_replace(' ID=',' '.$b.'.ID=',trim($w[$i]));}
                        elseif (substr($w[$i],0,5)=='where') {$z.=' '.trim($w[$i]).' and '.$b.'.ID=';}
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
                                if (substr($szer[$mc],0,1)=='+') {
                                        $szer[$mc]=substr($szer[$mc],1);
                                        $posx=$mc+1;
                                }
                        }
                }
        }
}
require('RozliczINI.php');

$z.=$iipole;
$testphp=$z;
$w=mysql_query($z);
if ($w) {
        $n=mysql_num_rows($w);                        // jak dobrze posz�o to = 1
        $wynik=mysql_fetch_row($w);        // warto�ci p�l dla formularza
        mysql_free_result($w);
}
else {
        $n="0";
        $wynik="";
}
require('dbdisconnect.inc');

$mr=$n;

echo '<body bgcolor="'.$mybgcolor.'" onload="start(';	//BFD2FF
echo $mc+1;
echo ',';
echo $posx;
echo ",'F'";
echo ')">';

echo '<form id="f0" action="Tabela_Rozlicz_Zapisz2.php" method="post">';
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
echo '<input id="tak" type="submit" accesskey="Z" value="Enter=Zapisz" name="tak">';echo "\n";
        }
        if ($fun) {
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
<input type="hidden" id="zaznaczone" name="zaznaczone" value="">
<?php
echo '<input type="hidden" id="idtab" name="idtab" value="'.$idtab.'">';
echo '<input type="hidden" id="ide" name="ID" value="'.$ipole.'">';

for($i=0;$i<0;$i++) {
	echo '<br style="font-size: 12pt">';
}

echo '<table id="tab" align="center" width="100%" border="1" cellpadding="0" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080">'; echo "\n";
echo '<caption align="left"><font color="white">'.$tyt;
//echo '  (';
//echo $testphp;
echo '</font></caption>';
for($j=0;$j<=$mc;$j++) {
        echo '<tr>';        echo "\n";
        echo '<td nowrap CLASS="nag" id="tab1'.($j+1).'" align="right" ';
        if (!$styn[$j]||$styn[$j]=="\r") {;} else {echo $styn[$j];};
        echo ' bgcolor='.$tnag.'>'.$tn[$j] .'&nbsp:&nbsp</td>';        echo "\n";
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
</body>
</html>
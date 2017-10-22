<?php

$ido=$_SESSION['osoba_id'];

$zz="Select ID from tabele where NAZWA='typyoplAT'";
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww); $ww=$ww[0];

$zz="Select ID_POZYCJI from tabeles where ID_TABELE=$ww and ID_OSOBY=$ido";	//jest ID abonenta
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$ipole=$ww[0];																					//ostatnio u¿ytego

function formabo($ipole) {

$mybgcolor='#0F4F9F';

$tn=array();
$pola=array();
$poleTop=array();
$poleLeft=array();
$szer=array();
$mc=Count($tn);

$z="select * from tabele where NAZWA='typyoplAT'";
$w=mysql_query($z);
$testphp=$z;
if ($w) {
        $w=mysql_fetch_array($w);
        $tyt=StripSlashes($w['OPIS']);
        $sql=StripSlashes($w['FORMULARZ']);
        $fun=StripSlashes($w['FUNKCJEF']);
        $par=StripSlashes($w['PARAMSF']);
        if (!$sql) { exit;}
        else {
                $mc=-1;
                $w=explode("\n",$sql);
                $p=explode("\n",$par);
                $z='Select';   //$w[0];
                $b=trim($w[0]);        // w pierwszej linii nazwa bazy g³ównej
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
                                if (!($b=='Select')&&(count(explode(".",$l[0]))<2)) {
                                        $z.=$b;
                                        $z.=".";
                                }
                                $z.=$l[0];
                                $pola[$mc]=trim($l[0]);
											if ($par) {								// s¹ parametry pól
												$j=0;
												$ok=true;
												while ($ok) {
													$bufor=explode(",",$p[$j]);
													if ($pola[$mc]==trim($bufor[0])) {
														$poleTop[$mc]=trim($bufor[1]);
														$poleLeft[$mc]=trim($bufor[2]);
														$ok=false;	// nie leæ dalej
													}
													$j++;		// nastêpna linia
													if ($j>=count($p)) {$ok=false;};	// nie leæ dalej
												}
											}
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

$z.=$ipole;

$testphp.=$z;

$w=mysql_query($z);
if ($w) {
        $n=mysql_num_rows($w);                        // jak dobrze posz³o to = 1
        $wynik=mysql_fetch_row($w);        // warto¶ci pól dla formularza
        for($j=0;$j<Count($wynik);$j++) {$wynik[$j]=StripSlashes($wynik[$j]);}
        mysql_free_result($w);
}

$mr=$n;

echo "<br>";
echo '<style type="text/css">';
echo '<!--';
echo '#f00 {POSITION: absolute; VISIBILITY: visible; TOP:10px; LEFT: 10px; Z-INDEX:2;}';
echo '.nagg {font: normal 10pt};';
echo '.norr {font: normal 10pt};';
echo '-->';
echo '</style>';

for($j=0;$j<=$mc;$j++) {

echo "\n";
if ($par) {
	echo '<div id="tab1'.($j+1).'" style="position: absolute; color:black; top:'.$poleTop[$j].'; left: '.$poleLeft[$j].'" onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)">';
}
else {
	echo '<div id="tab1'.($j+1).'" style="position: absolute; color:black; top:'.($j*50+70).'; left: 20px" onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)">';
}
echo "\n";

//echo '<form>'; echo "\n";
echo '<font CLASS="nagg">';
$jest=0;
if ($fun) {
	$f=explode("\n",$fun);
	$cc=Count($f);
	for($i=0;$i<$cc;$i++) {
		$l=explode("|",$f[$i]);
		if ($l[1]==$tn[$j]) {
			$jest=1;
			echo '<input type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/><br>';echo "\n";
		}
	}
}
if (!$jest) {
	echo $tn[$j].'<br>';
}
echo '</font>';

        if (count($z=explode("/",$szer[$j]))>1) {				// textarea
                echo '<textarea CLASS="norr" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
                echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
                echo '>';
                echo $wynik[$j];
                echo '</textarea>';
        }																	// textarea
        elseif (count($z=explode("-",$szer[$j]))>1) {
                echo '<textarea CLASS="norr" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
                echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
                echo '>';
                echo $wynik[$j];
                echo '</textarea>';
        }																	// password
        elseif (count($z=explode("*",$szer[$j]))>1) {
                echo '<input type="password" CLASS="norr" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
                echo 'name="'.$pola[$j].'" value="" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }																	// timestamp
        elseif (count($z=explode("t",$szer[$j]))>1) {
                echo '<input CLASS="norr" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
                echo 'name="'.$pola[$j].'" value="'.date('Y-m-d H:i:s').'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }																	// image
        elseif (count($z=explode("D",$szer[$j]))>1) {
                echo '<input CLASS="norr" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
if (!(trim($wynik[$j]))) {
                echo 'name="'.$pola[$j].'" value="'.(date('Y-m-d')).'" ';}
else {          echo 'name="'.$pola[$j].'" value="'.($wynik[$j]).'" ';}
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }																	// image
        elseif (count($z=explode("d",$szer[$j]))>1) {
                echo '<input CLASS="norr" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
                echo 'name="'.$pola[$j].'" value="'.($wynik[$j]).'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }																	// image
        elseif (count($z=explode(":",$szer[$j]))>1) {
                if (!$z[0])        echo '<img src="'.$wynik[$j].'" CLASS="norr" id="tab2'.($j+1).'" height='.$z[1].' name="'.$pola[$j].'">';
                elseif (!$z[1])        echo '<img src="'.$wynik[$j].'" CLASS="norr" id="tab2'.($j+1).'" width='.$z[0].' name="'.$pola[$j].'">';
                elseif ($z[0]&&$z[1]) echo '<img src="'.$wynik[$j].'" CLASS="norr" id="tab2'.($j+1).'" width='.$z[0].' height='.$z[1].' name="'.$pola[$j].'">';
        }
        else {																// input
					echo "&nbsp;";
					$bufor=$wynik[$j];
					if (count(explode('"',$bufor))>1) {
						$bufor="'".$bufor."'";
					}
					else {
						$bufor='"'.$bufor.'"';
					}
                echo '<input CLASS="norr" id="tab2'.($j+1).'" maxlength="'.$szer[$j].'"  size="'.$szer[$j].'" ';
                echo 'name="'.$pola[$j].'" value='.$bufor.' ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="nag_kolor('.($j+1).');" ';
                echo 'onblur="nag_czysc('.($j+1).');" ';
                echo '/>';
        }
echo '</div>';
}
echo "\n";
?>
</form>

<table align="center" width="100%" height="90%" border="1" cellpadding="0" cellspacing="0" bordercolorlight=""#C0C0C0" bordercolordark="#808080">
<tr height="15%"><td bgcolor="#FFCF3F"></td></tr>
<tr height="10%"><td bgcolor="#EFEFCF"></td></tr>
<tr             ><td bgcolor="#FFFFAF"></td></tr>
<tr height="15%"><td bgcolor="#EFEFDF"></td></tr>
</table>

<?php
include('stopka.html');
}	//function formabo()

formabo($ipole);

?>

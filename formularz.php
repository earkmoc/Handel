<?php

$ido=$_SESSION['osoba_id'];
$ntab_master=$_SESSION['ntab_mast'];

$zz="Select ID from tabele where NAZWA='$ntab_master'";
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww); $ww=$ww[0];

$zz="Select ID_POZYCJI from tabeles where ID_TABELE=$ww and ID_OSOBY=$ido";	//jest ID abonenta
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$ipole=$ww[0];																					//ostatnio u¿ytego

function formabo($ipole, $tabelaa) {

$mybgcolor='#0F4F9F';

$nip=false;
$akt=false;

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

$ntab_master=$_SESSION['ntab_mast'];
$z="select * from tabele where NAZWA='$ntab_master'";
$w=mysql_query($z);
$testphp=$z;
if ($w) {
        $w=mysql_fetch_array($w);
        $tyt=StripSlashes($w['OPIS']);
        $sql=StripSlashes($w['FORMULARZ']);
        $fun=StripSlashes($w['FUNKCJEF']);		//formularz w tle niech nie ma swoich buttonów'';	//
        $par=StripSlashes($w['PARAMSF']);
        $jsf=str_replace('f0.','f2.',StripSlashes($w['JAVASCRIPT']));
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
                                 $posx=$mc+2;
                              }
                             if (!($b=='Select')&&(count(explode(".",$l[0]))<2)) {
                                     $z.=$b;
                                     $z.=".";
                             }
                             $z.=$l[0];
                             $pola[$mc]=trim($l[0]);
                             $tn[$mc]=trim($l[1]);
                             $szer[$mc]=trim($l[2]);
                             $styl[$mc]=$l[3];
                             $styn[$mc]=$l[4];
                             $typp[$mc]=$l[5];
                             if (substr($szer[$mc],0,1)==='+') {
                                     $szer[$mc]=substr($szer[$mc],1);
                                     $posx=$mc+1;
                             }
                  				if ($par) {								// s¹ parametry pól
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
                  							$ok=false;	// nie leæ dalej
                  						}
                  						$j++;		// nastêpna linia
                  						if ($j>=count($p)) {$ok=false;};	// nie leæ dalej
                  					}
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
echo '#f00 {POSITION: absolute; VISIBILITY: visible; TOP:5px; LEFT: 10px; width:990; Z-INDEX:2;}';
echo '.nagg {font: normal 10pt; z-index: 2;};';
echo '.norr {font: normal 10pt; z-index: 2;};';
echo '.norr_red {background-color: red; };';
echo '.norr_zoom {font: normal 20pt; position: absolute; width:900; z-index:3;};';
echo '.norr_zoou {font: normal 14pt; position: absolute; width:900; z-index:3;};';
echo '.check {font: normal 5pt; z-index: 2;};';
echo '.niesel {font: normal 10pt};';
echo '.niefoc {font: normal 10pt};';
echo 'input {background-color:white;}';
echo '.blue   {font: normal 10pt; padding-left:2pt; height: 14pt; border: solid black 1pt; background-color: rgb(51,204,255);   font: bold; width: 119pt; }';
echo '.yellow {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: yellow;            font: bold; width: 119pt; }';
echo '.orange {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: orange;            font: bold; width: 119pt; }';
echo '.green  {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: green;             font: bold; }';
echo '.granat {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: #0063FF;           font: bold; width: 119pt; }';
echo '.red    {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: red;               font: bold; }';
echo '.lightgray {background-color:#CCCCCC; border: solid black 1pt; padding: 2pt;}';
echo '.cegla  {font: normal 10pt; padding:2pt;      height: 14pt; border: solid black 1pt; background-color: orangered;         font: bold; }';
echo '-->';
echo '</style>';
echo "\n";

if (($tabelaa=='abonenci')||($tabelaa=='abonencisz')) {
   echo '<form id="f2" action="AbonentUwagi.php?tabela='.($_POST['sutab']).'" method="post" style="position: absolute; top:10; left: 10; width:990; Z-INDEX:99;">'; echo "\n";
} else {
   echo '<form id="f2"                                                                      style="position: absolute; top: 0; left: 10; width:990; Z-INDEX:99;">'; echo "\n";
}

for($j=0;$j<=$mc;$j++) {

echo "\n";
echo "\n";
echo '<div id="tab1'.($j+1).'" lang='."'".$pola[$j].','.trim($poleTop[$j]).','.trim($poleLeft[$j]).','.StripSlashes(trim($styl[$j])).','.StripSlashes(trim($styn[$j])).','.((substr(StripSlashes(trim($tn[$j])),0,1)=='<')?'':StripSlashes(trim($tn[$j]))).','.trim($poleWidth[$j]).','.trim($poleHeight[$j])."'".' nowrap onmouseover="ruszamy(this,1)" onmouseout="ruszamy(this,0)" style="position: absolute; text-valign:top; ';
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
	if (trim($tn[$j])) {
   	echo '<font id="etab1'.($j+1).'" ';
      if (!$styn[$j]||$styn[$j]=="\r") {echo 'class="nagg"';} else {echo $styn[$j];}
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
	echo 'class="norr" ';
	echo '>'."\n";
	if (trim($tn[$j])) {
   	echo '<font id="etab1'.($j+1).'" ';
      if (!$styn[$j]||$styn[$j]=="\r") {echo 'class="nagg"';} else {echo $styn[$j];};
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
   				echo '<br>';
   				echo '<input disabled style="cursor:hand;" type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/>';echo "\n";
   			}
   			else {	//jak nie ma to piêtrowo button i pole
   				echo '<input disabled style="cursor:hand;" type="button" value="'.$l[1].'" accesskey="'.$l[0].'" onclick="'.$l[2].'"/><br>';echo "\n";
   			}
   		}
   	}
   }
	if (trim($tn[$j])) {
      if (!$jest) {
   		if ($tn[$j]<>':') {echo trim($tn[$j]);}		//same dwukropki nie pisz
      }
      echo '</font>';
   	if (!$jest) {
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
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
                echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
                echo '>';
		$buf2=false;
	if (strtoupper(substr($z[1],0,6))=='SELECT') {
		$z=$z[1];	//zapytanie, np.: select TRESC from slownik where TYP='dokumenty'
		$w=mysql_query($z);
		while ($z=mysql_fetch_row($w)) {
		        echo "\n".'<option';
			if (!$buf2 && (strlen($wynik[$j])>0) && strtoupper($wynik[$j])==strtoupper(substr($z[0],0,strlen($wynik[$j])))) {
				echo ' selected';
				$buf2=true;
			}
			echo '>'.$z[0];
		}
	}
	else {
		$buf=count($z=explode(",",$z[1]));
		for ($i=0;$i<$buf;$i++) {
		        echo "\n".'<option';
			if (!$buf2 && (strlen($wynik[$j])>0) && strtoupper($wynik[$j])==strtoupper(substr($z[$i],0,strlen($wynik[$j])))) {
				echo ' selected';
				$buf2=true;
			}
			echo '>'.$z[$i];
		}
		if (!$buf2) {
	                echo '<option selected>'.$wynik[$j];
		}
	}
                echo "\n".'</select>';
    } elseif (substr($szer[$j],0,1)=='<') {
    } elseif (count($z=explode("/",$szer[$j]))>1) {				// textarea
                echo '<textarea CLASS="norr';
         				if (($typp[$j]=='!!!')&&(strpos($wynik[$j],'!!!')>0)) {
                 echo '_red';
                }
                echo '" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
                echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
                echo '>';
                echo str_replace('</textarea>','<//textarea>',$wynik[$j]);	//w umowie musi byæ "<br>"
                echo '</textarea>';
        }																	// textarea
        elseif (count($z=explode("-",$szer[$j]))>1) {
                echo '<textarea CLASS="norr" id="tab2'.($j+1).'" rows='.$z[1].' cols='.$z[0].' name="'.$pola[$j].'" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' onfocus="$okgoradol=false;nag_kolor('.($j+1).');" ';
                echo 'onblur="$okgoradol=true;nag_czysc('.($j+1).');" ';
                echo '>';
                echo str_replace('</textarea>','<//textarea>',str_replace('<br>',"\n",$wynik[$j]));	//¿eby nie by³o widaæ <br>
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
        elseif (count($z=explode("checkbox",$szer[$j]))>1) {
            echo '<input type="checkbox" CLASS="norr" id="tab2'.($j+1).'" maxlength="'.$z[1].'"  size="'.$z[1].'" ';
            if ($wynik[$j]) {echo 'checked ';}
            echo 'name="'.$pola[$j].'" value="1" ';
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
               echo 'name="'.$pola[$j].'" value="'.(date('Y-m-d')).'" ';
             } else {
               echo 'name="'.$pola[$j].'" value="'.($wynik[$j]).'" ';
             }
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
      		$buf=$szer[$j];
      		$bufor=$wynik[$j];
            if (count($z=explode("@Z",$buf))>1) {		// bez zer
      			$buf=str_replace('@Z','',$szer[$j]);
      			$buf=str_replace('+','',$buf);
      			$buf=str_replace('%','',$buf);
      			if ($bufor*1==0) {
      				$buf=str_replace('@z','',$buf);
		      		$buf=str_replace('w','',$buf);
      				$bufor='';
      			} elseif (count($z=explode("@z",$buf))>1) {		//zera po kropce ucinamy
      				$buf=str_replace('@z','',$buf);
      			   if (count($z=explode(".",$bufor))>1) {		// bez zer po kropce
      					$bufor=$z[0];
      					$z[0]='';
      					if ($z[1]*1>0) {
                        $bufor=$bufor.'.';
                     } else {
      						$bufor=$bufor.'&nbsp;';
      //						if ($buf<>'') {$buf=$buf*1+5;}	//twarde spacje zajmuj¹ wiêcej
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
   			   $buf=1*$buf;	//wymuszenie liczby nawet jeœli dalej sa jakies napisy
            }
      		$buf=floor($buf*1);
      		echo "&nbsp;";
      		if (count(explode('"',$bufor))>1) {
      			$bufor="'".$bufor."'";
      		} else {
      			$bufor='"'.$bufor.'"';
      		}
            echo '<input CLASS="norr" id="tab2'.($j+1).'" maxlength="'.$buf.'" size="'.$buf.'" ';
            echo 'name="'.$pola[$j].'" value='.$bufor.' ';
            if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
            if (count($z=explode("blue",$styl[$j]))>1) {		// bez zer
             echo ' disabled';
            }
            echo ' onfocus="nag_kolor('.($j+1).');" ';
            echo 'onblur="nag_czysc('.($j+1).');" ';
            echo '/>';
      }
      echo "\n".'</div>';
   }
   echo "\n";
?>
<div style="position: absolute; color:black; top:-500; left: 0">
<input disabled type="submit" id="zapiszsz" value=""/>
</div>
</form>

<?php
include('stopka.html');

if ($jsf) {
  echo '<script type="text/javascript" language="JavaScript">'; echo "\n";
  echo '<!--'; echo "\n";
  echo $jsf;  echo "\n";
  echo '-->'; echo "\n";
  echo '</script>'; echo "\n";
}

}	//function formabo()

formabo($ipole, $tabelaa);

?>

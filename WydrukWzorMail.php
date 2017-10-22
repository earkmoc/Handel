<?php

//***************************************************************************************

function Dekoduj($tab,$kod,$j,$n,$d,$s,$t,$ml,$md,$bl) {

if(strlen($kod)==0) {return '';}

$wynik='';
$kod.='   '.$kod;
$c1=intval(substr($kod,-3,1));
$c2=intval(substr($kod,-2,1));
$c3=intval(substr($kod,-1,1));

$wynik.=( $c1==0 ? '' : $s[$c1-1].' ' );  // setki

if($c2==0) {;}
elseif($c2==1) {$wynik.=($c3==0 ? $d[$c2-1] : $n[$c3-1] ).' ';} // nastki
else {$wynik.=$d[$c2-1].' ';                       // dzesi†tki
}

$wynik.=($c3==0||$c2==1 ? '' : $j[$c3-1].' ');  // jednožci

if($c1+$c2+$c3<>0) {       // dopisek o rz'dzie wielkožci
   $c3=sprintf("%1d",$c3);
   if(!$tab) {;}
   elseif(sprintf("%1d",$c2)=='1') {$wynik.=$tab[2].' ';}    // nastki
   elseif($c3=='1') {$wynik.=($c1+$c2==0 ? $tab[0] : $tab[2] ).' ';}
   elseif($c3=='2'||$c3=='3'||$c3=='4') {$wynik.=$tab[1].' ';}
   else {$wynik.=$tab[2].' ';
   }
}

return $wynik;

}

//***************************************************************************************

function Slownie($w,$znak,$czesc) {

$ww=$w;	//orygina³ do póŸniejszych porównañ

if ($czesc==1) {
   $liczba=trim(substr(sprintf("%' 19.3f",$w),0,15));
	$w=intval($w);
}
else {
	$w=$w-intval($w);
   $liczba=substr(sprintf("%' 5.2f",$w),-2);
}

if (!$w||($w==0)) {
	$liczba='zero';
	if ($czesc==1) {
		if ($znak) {
		   if($ww<0)  {$liczba='minus '.$liczba;}
	   	else       {$liczba='plus ' .$liczba;}
		}
		elseif($ww<0) {$liczba='minus '.$liczba;}
	}
	return $liczba;
}

$j=array('jeden','dwa','trzy','cztery','piêæ','sze¶æ','siedem','osiem','dziewiêæ');
$n=array('jedena¶cie','dwana¶cie','trzyna¶cie','czterna¶cie','piêtna¶cie','szesna¶cie','siedemna¶cie','osiemna¶cie','dziewiêtna¶cie');
$d=array('dziesiêæ','dwadzie¶cia','trzydzie¶ci','czterdzie¶ci','piêædziesi±t','sze¶ædziesi±t','siedemdziesi±t','osiemdziesi±t','dziewiêædziesi±t');
$s=array('sto','dwie¶cie','trzysta','czterysta','piêæset','sze¶æset','siedemset','osiemset','dziewiêæset');

$t =array('tysi±c','tysi±ce','tysiêcy');
$ml=array('milion','miliony','milionów');
$md=array('miliard','miliardy','miliardów');
$bl=array('bilion','biliony','bilionów');

$rzedy=array($bl,$md,$ml,$t,NULL);
$trojki=array('','','','','');

$k=strlen($liczba)/3;              // ilož tr¢jek
$k=($k>intval($k) ? intval($k)+1 : intval($k));
$k=($k>count($trojki) ? count($trojki) : $k);      // max tr¢jek

for($i=0;$i<$k;$i++) {
    $trojki[count($trojki)-$i-1]=substr($liczba,-3);
    $liczba=substr($liczba,0,strlen($liczba)-3);
}

$liczba='';
for($i=0;$i<count($trojki);$i++) {
	$liczba.=Dekoduj($rzedy[$i],$trojki[$i],$j,$n,$d,$s,$t,$ml,$md,$bl);
}

if ($czesc==1) {
	if ($znak) {
	   if($w<0) {$liczba='minus '.$liczba;}
   	else     {$liczba='plus ' .$liczba;}
	}
	elseif($w<0) {$liczba='minus '.$liczba;}
}

return trim( $liczba );
}

//***************************************************************************************

function WydrukWzorMail($ipole,$osoba_id,$osoba_pu,$osoba_upr,$wzor) {

$q=array();
$qs=array();

if (!$wzor) {
   $w='Mail';
} else {
   $w=$wzor;
}

$z="Select TEKST, ID, SPACJE, WIERSZE from wzoryumow where NAZWA='$w'";
$w=mysql_query($z);
$w=mysql_fetch_row($w);
$z=$w[1];
$spacje=$w[2];
$blokmax=$w[3];			//max ile pozycji na stronie
$w=StripSlashes($w[0]);	//musi byæ na koñcu

$blokn='';	//nag³ówek
$bloks='';	//stopka
$blokstr=1;	//strona
$blokbreak=false;	//page break

$z="Select NAZWA, FORMAT, TEKST from wzoryumows where ID_WZORYUMOW=$z order by ID";
$wynik=mysql_query($z);
while ($wiersz=mysql_fetch_array($wynik)) {
	$f=StripSlashes($wiersz['FORMAT']);
	$z=StripSlashes($wiersz['TEKST']);
	$z=str_replace('ID_master',$ipole,$z);
	$z=str_replace('osoba_id',$osoba_id,$z);
	$z=str_replace('osoba_pu',$osoba_pu,$z);
	if ($f=='+'||$f=='n'||$f=='s') {	//subtabela, np.: specyfikacja, nag³ówek, stopka
		$lps=0;					//l.p. w subtabeli
		$bloklp=0;				//l.p. w ramach bloku
		if (trim($z)=='') {	//nie by³o ¿adnych zapytañ, wiêc jeden wiersz (pewnie nag³ówek lub stopka)
			$lpsmax=1;
		}
		else {
			$qq=explode(';',$z);	//mo¿e byæ kilka zapytañ ¿eby ustaliæ iloœæ wierszy
			$i=0;
			do {
				$qqs=mysql_query($qq[$i]);	//kolejne zapytanie
				if (strtoupper(substr(trim($qq[$i]),0,6))=='SELECT') {	//jeœli typu "SELECT"
					$qs=mysql_fetch_row($qqs);										//to coœ zwraca
					$i++;
					if ($i<count($qq)) {							//jeœli s¹ nastêpne, to
						for ($j=0;$j<count($qs);$j++) {		//korzystaj¹ ze swoich wyników
							$qq[$i]=str_replace('['.$j.']',$qs[$j],$qq[$i]);
						}
					}
				}
				else {
					$i++;
				}
			} while ($i<count($qq));

			if (count($qs)>1) {	//wiêcej pól wyniku, to znaczy, ¿e s¹ tam pola dla specyfikacji w formacie [1], [2], itd.
				$lpsmax=-1;			//i nie wiadomo ile bêdzie wierszy
				$blok0=$qs[0];		//wartoœæ poprzedniego wiersza w ramach bloku
				$blok1=$qs[0];		//wartoœæ bie¿¹cego wiersza w ramach bloku
			}
			else {					//tylko jedno pole wyniku, to znaczy, ¿e 
				$lpsmax=$qs[0];	//wynik ostatniego zapytania to iloœæ wierszy do obróbki
			}
		}	//if (trim($z)!='')

		$fvs=StripSlashes($wiersz['NAZWA']);	//s³owo: FVspec

	while ($lpsmax==-1 ? (1==1) : ($lps<$lpsmax)) {
		$zs="Select TEKST, ID from wzoryumow where NAZWA='$fvs'";	// tekst FVspec
		$ws=mysql_query($zs);
		$ws=mysql_fetch_row($ws);
		$zs=$ws[1];									//ID definicji FVspec
		$ws=StripSlashes($ws[0]);				//linia z tekstami do zamiany

		if (count(explode('[lp]',$ws))>0) {	//jeœli '[lp]' jest ju¿ w tekœcie g³ównym specyfikacji
			$ws=str_replace('[lp]',$lps+1,$ws);
		}

		if ($lpsmax<0) {		//wiêcej pól wyniku, to znaczy, ¿e s¹ tam pola dla specyfikacji w formacie [1], [2], itd.
			for ($j=0;$j<count($qs);$j++) {		//korzysta z wyników zapytania g³ównego specyfikacji
				$ws=str_replace('['.$j.']',$qs[$j],$ws);
			}
			$blok1=$qs[0];
			if ($blok0!=$blok1&&!$blokbreak) {	//zmieni³ siê numer bloku i nie ³ama³ strony ledwo co
				$bloklp=0;			//liczymy od nowa
				$blokstr++;			//next strona dla nowego bloku
				$ws=str_replace('[str]',$blokstr-1,$bloks).'<P CLASS="breakhere">'.str_replace('[str]',$blokstr,$blokn).$ws;
			}
			$blok0=$qs[0];
			if (count(explode('[bloklp]',$ws))>0) {//jeœli '[bloklp]' jest ju¿ w tekœcie g³ównym specyfikacji
				$ws=str_replace('[bloklp]',$bloklp+1,$ws);
			}
		}

		$blokbreak=false;

		$zs="Select NAZWA, FORMAT, TEKST from wzoryumows where ID_WZORYUMOW=$zs";
		$wyniks=mysql_query($zs);			//definicje zamian
		while ($wierszs=mysql_fetch_array($wyniks)) {	//lecimy po polach do zmiany
			$ns=StripSlashes($wierszs['NAZWA']);
			$fs=StripSlashes($wierszs['FORMAT']);
			$zs=StripSlashes($wierszs['TEKST']);
			$zs=str_replace('ID_master',$ipole,$zs);
			$zs=str_replace('osoba_id',$osoba_id,$zs);
			$zs=str_replace('osoba_pu',$osoba_pu,$zs);
			$zs=str_replace('[lp]',$lps,$zs);
//			$zs=str_replace('[0]','aaaaa',$zs);
			if ($zs=='osoba_upr') {
				$qs[0]=$osoba_upr;}
			elseif ($zs=='lp') {
				$qs[0]=$lps+1;}
			else {
				if (count($qq=explode(';',$zs))>1) {		// kilka zapytañ
					$i=0;
					do {
						$qs=mysql_query($qq[$i]);
						if (strtoupper(substr(trim($qq[$i]),0,6))=='SELECT') {
							$qs=mysql_fetch_row($qs);
							$i++;
							if ($i<count($qq)) {
								for ($j=0;$j<count($qs);$j++) {		// korzystaj¹ ze swoich wyników
									$qq[$i]=str_replace('['.$j.']',$qs[$j],$qq[$i]);
								}
							}
						}
						else {
							$i++;
						}
					} while ($i<count($qq));
				}
				else {
					$qs=mysql_query($zs);
					$qs=mysql_fetch_row($qs);
				}
			}
			if ($fs) {	// format: "%' +30s"
				if (substr($fs,3,1)=='+') {		//centrowanie
					$xs=substr($fs,4);				//30
					$qs[0]=substr(trim($qs[0]),0,$xs);
					$qs[0]=str_pad($qs[0],$xs,substr($fs,2,1),STR_PAD_BOTH);
					$ws=str_replace($ns,$qs[0],$ws);
				} elseif (substr($fs,-1,1)=='z') {		//koszenie zer
					$xs=sprintf($fs,$qs[0]);				//12.3000z
					$xs=substr($xs,0,strlen($xs)-1);    //12.3000     
					if (count($x=explode('.',$xs))>1) {     //jest kropka
						$xs=$x[0];
						$x[0]='';
						if ($x[1]*1>0) {     //jest liczba po kropce
						 $xs.='.';         //konieczna kropka
						} else {
						 $xs.='^';         //puste
						}
						while (substr($x[1],-1,1)==='0') {
							$x[1]=substr($x[1],0,strlen($x[1])-1);
							$x[0]=$x[0].'^';
						}
						$xs.=$x[1].$x[0];
					}
					$ws=str_replace($ns,$xs,$ws);
				} else {
					$ws=str_replace($ns,sprintf($fs,$qs[0]),$ws);
				}
			}
			else {
				$ws=str_replace($ns,$qs[0],$ws);
			}	
		}
		$bloklp++;					// nastêpny wiersz
		$lps++;						// nastêpny wiersz

		if ($blokmax<>0 && $bloklp%$blokmax==0) {
			$blokstr++;
			$blokbreak=true;	//page break przy zmianie strony
		}

		if ($lpsmax<0) {		//wiêcej pól wyniku, to znaczy, ¿e s¹ tam pola dla specyfikacji w formacie [1], [2], itd.
			if ($qs=mysql_fetch_row($qqs)) {
				if ($blokbreak) {
					$ws=$ws.str_replace('[str]',$blokstr-1,$bloks).'<P CLASS="breakhere">'.str_replace('[str]',$blokstr,$blokn);
				}
				if ($blokmax<>0) {
					$w=str_replace($fvs,$ws.$fvs,$w);	//kontynuacja FVspec
				}
				else {
					$w=str_replace($fvs,$ws.'<eol>'.$fvs,$w);	//kontynuacja FVspec
				}
			}
			else {
				$ws=$ws.str_replace('[str]',$blokstr,$bloks);	//stopka z prawdziwym numerem ostatniej strony
				$w=str_replace($fvs,$ws,$w);		//koniec FVspec
				$lpsmax=-2;
			}
		}
		elseif ($lps<$lpsmax) {
			$w=str_replace($fvs,$ws.'<eol>'.$fvs,$w);	//kontynuacja FVspec
		}
		else {
			if ($f!='s') {									//jak nie stopka (ona ma numer 1)
				$w=str_replace($fvs,$ws.$fvs,$w);	//kontynuacja FVspec
			}
		}
	}	//while ($lpsmax==-1 ? (1==1) : ($lps<$lpsmax))
		$w=str_replace($fvs,'',$w);		//koniec FVspec
		$w=str_replace('[str]',$blokstr,$w);	//numer strony na pierwszej stronie
		if ($f=='n') {$blokn=$ws;}			//mamy nag³ówek
		if ($f=='s') {$bloks=$ws;}			//mamy stopkê
	}	//if ($f=='+')
	else {
		if ($z=='osoba_upr') {
			$q[0]=$osoba_upr;}
		else {
			$qq=explode(';',$z);	// mo¿e byæ kilka zapytañ
			$i=0;
			do {
//echo $qq[$i];
				if ((strtoupper(substr($x=trim($qq[$i]),0,5)))=='LINIA') {	//jeœli typu "LINIA"
					$n=substr($x,5)*1;
					$x=explode(chr(13).chr(10),$q[0]); // w "$q[0]" jest wynik ostatniego selecta
					$q[0]=$x[$n];                       // n-ta linia tekstu jako wynik
					$i++;
					if ($i<count($qq)) {
						$qq[$i]=str_replace('[0]',$q[0],$qq[$i]);
					}
				} else {
					$q=mysql_query($qq[$i]);
					if (strtoupper(substr(trim($qq[$i]),0,6))=='SELECT') {
						$q=mysql_fetch_row($q);
						$i++;
						if ($i<count($qq)) {
							for ($j=0;$j<count($q);$j++) {		// korzystaj¹ ze swoich wyników
								$qq[$i]=str_replace('['.$j.']',$q[$j],$qq[$i]);
							}
						}
					}
					else {
						$i++;
					}
				}
			} while ($i<count($qq));
		}
		if (StripSlashes($wiersz['NAZWA'])=='"s³ownie"') {
			$q[0]=Slownie($q[0],'',1).' z³. '.Slownie($q[0],'',2).' gr.';
		}
		if ($f) {	// format: "%' +30s"
			if (substr($f,3,1)=='+') {		//centrowanie
				$x=substr($f,4);				//30
				$q[0]=substr(trim($q[0]),0,$x);
				$q[0]=str_pad($q[0],$x,substr($f,2,1),STR_PAD_BOTH);
				$w=str_replace(StripSlashes($wiersz['NAZWA']),$q[0],$w);
			}
			else {
				$w=str_replace(StripSlashes($wiersz['NAZWA']),sprintf($f,$q[0]),$w);
			}
		}
		else {
			$w=str_replace(StripSlashes($wiersz['NAZWA']),$q[0],$w);
		}	
	}
}

$w=str_replace('"10CPI"','',$w);		//Chr(18).Chr(27).Chr(80),$w);	//1
$w=str_replace('"12CPI"','',$w);		//Chr(27).'M',$w);					//1
$w=str_replace('"15CPI"','',$w);		//Chr(27).'g',$w);					//1
$w=str_replace('"17CPI"','',$w);		//Chr(15),$w);							//1
$w=str_replace('"W0"','',$w);			//Chr(27).'W0',$w);					//1
$w=str_replace('"W1"','',$w);			//Chr(27).'W1',$w);					//1

$w=str_replace('^','&nbsp;',$w);
$w=str_replace(' ','&nbsp;',$w);
$w=nl2br($w);
if ($spacje=='N') {
   $w=str_replace('_',' ',$w);
//   $w=str_replace("<eol>","\n\r",$w);
   $w=str_replace("<eol>","",$w);
} else {
   $w=str_replace("<eol>","<br>\n\r",$w);
}
$w=str_replace('twardaspacja','&nbsp;',$w);
$w='<font style="font-family: Courier">'.$w.'</font>';

return $w;

}
?>
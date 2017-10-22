<?php

set_time_limit(5*60);	// 3 min

session_start();

$ido=$_SESSION['osoba_id'];
$osu=$_SESSION['osoba_upr'];
$punkt=$_SESSION['osoba_pu'];
$fiskalna=$_SESSION['fiskalna'];

//include('skladuj_zmienne.php');

require('dbconnect.inc');

$w=mysql_query("select NAZWA from osoby where ID=$ido");
$w=mysql_fetch_row($w);
$oskr=$w[0];

//$idtab=$_POST['idtab'];
//$w=mysql_query("select NAZWA from tabele where ID=$idtab");
//$w=mysql_fetch_row($w);
//$tab_master=$w[0];

$tab_master=$_POST['batab'];

//********************************************************************
// zapamiêtaj stan tabeli dla zalogowanej osoby
// gdy nie suwanie po tabeli i zalogowany i przed chwil± by³ w tabeli

$warunek="";
$sortowanie="";
$opole=$_POST['opole'];
$ipole=$_POST['ipole'];
if (!$ipole) {
	$opole="S";		// niech nie zapisuje stanu tabeli
	$ipole=$_GET['ipole'];
}

if (($opole!="S")&&$osu&&$ipole) {

	$z='Select ID, WARUNKI, SORTOWANIE from tabeles where ID_OSOBY=';
	$z.=$ido;
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
			$z.=$ipole;
			$z.=' where ID=';
			$z.=$w['ID'];
		}
		else {
			$z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL) values (';
			$z.=$ido;
			$z.=',';
			$z.=$_POST['idtab'];
			$z.=',';
			$z.=$ipole;
			$z.=',';
			$z.=$_POST['strpole'];
			$z.=',';
			$z.=$_POST['rpole'];
			$z.=',';
			$z.=$_POST['cpole'];
			$z.=')';
		}
		$w=mysql_query($z);
	}
}

// zapamiêtaj stan tabeli dla zalogowanej osoby
//********************************************************************

//$fname="C:\Arrakis\Wydruki$punkt\Wydruk.htm";
$fname="Wydruk$ido.htm";

$zaznaczone=$_POST['zaznaczone']; if (!$zaznaczone) {$zaznaczone=$_GET['zaznaczone'];}
//$zaznaczonei=0;
//if ($zaznaczone) {
//	if (file_exists($fname)) {unlink($fname);}
//	$file=fopen($fname,"a");
//	$zaznaczone=explode(',',$zaznaczone);
//}
//else {
$file=fopen($fname,"w");
//	$zaznaczone=array();
//	$zaznaczone[0]=$ipole;
//}

fputs($file,'<html>'."\n");
fputs($file,'<head>'."\n");
fputs($file,'<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">'."\n");
fputs($file,'<meta http-equiv="Reply-to" content="AMoch@pro.onet.pl">'."\n");
fputs($file,'<meta name="Author" content="Arkadiusz Moch">'."\n");
fputs($file,'<title>Handel');
if ($osu) {
	fputs($file,': ');
	fputs($file,$osu);
	fputs($file,' (Nr ');
	fputs($file,$ido);
	fputs($file,')');
}
fputs($file,'</title>'."\n");
fputs($file,'<script type="text/javascript" language="JavaScript">'."\n");
fputs($file,'<!--'."\n");
fputs($file,'function escape(){'."\n");
fputs($file,'        if (event.keyCode==27) {'."\n");

if ($_GET['sio']=='tak') {
	fputs($file,'close();'."\n");
} elseif ($_GET['natab']) {
	fputs($file,'location.href="Tabela.php?tabela='.$_GET['natab'].'";'."\n");
} else {
	fputs($file,'location.href="Tabela.php?tabela='.$_POST['natab'].'";'."\n");
}

fputs($file,'        };'."\n");
fputs($file,'}'."\n");
fputs($file,'document.onkeypress=escape;'."\n");
fputs($file,'-->'."\n");
fputs($file,'</script>'."\n");

fputs($file,'</head>'."\n");

fputs($file,'<body bgcolor="#FFFFFF" onload="');

$laser=false;
if ($ido==9) { //$ido==2||szef, $ido==1autor, ||$ido==10||admin, ma³gosia
	fputs($file,'window.print()');
	$laser=true;
} else {
	fputs($file,"document.execCommand('SaveAs','','C:\\\Arrakis\\\Wydruki\\\Wydruk.htm')");
}
fputs($file,'">'."\n");

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

	$liczba='    '.$liczba;
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

//while ($zaznaczone[$zaznaczonei]) {

//	$ipole=$zaznaczone[$zaznaczonei];
//	$zaznaczonei++;

$q=array();
$qs=array();

$w=$_GET['wzor'];

$z="Select WZORWYDR from doktypy where TYP='$w'";
if ($z=mysql_query($z)) {
	if ($z=mysql_fetch_row($z)) {
		$w=$z[0];
	}
}

if ((($w=='DOK')||($w=='INW'))&&($_SESSION['osoba_dos']<>'T')) {
	$w.='bcz';
}

$z="Select TEKST, ID, SPACJE, WIERSZE from wzoryumow where NAZWA='$w'";
$w=mysql_query($z);
if (!$w||(mysql_num_rows($w)==0)) {
	$w=substr($_GET['wzor'],0,2);							//krótsza nazwa dokumentu
	$z="Select TEKST, ID, SPACJE, WIERSZE from wzoryumow where NAZWA='$w'";
	$w=mysql_query($z);
}

$w=mysql_fetch_row($w);
$z=$w[1];
$spacje=$w[2];			//if ($spacje!='N') {$w=str_replace(' ','&nbsp;',$w);
$blokmax=$w[3];			//max ile pozycji na stronie
$w=StripSlashes($w[0]);	//musi byæ na koñcu

$blokn='';	//nag³ówek
$bloks='';	//stopka
$blokstr=1;	//strona
$blokbreak=false;	//page break

$z="Select NAZWA, FORMAT, TEKST from wzoryumows where ID_WZORYUMOW=$z order by ID";

if (!$wynik=mysql_query($z)) {
	echo "<br>Query: $z<br>";
}

while ($wiersz=mysql_fetch_array($wynik)) {
	$ns=StripSlashes($wiersz['NAZWA']);
	$f=StripSlashes($wiersz['FORMAT']);
	$z=StripSlashes($wiersz['TEKST']);
	$z=str_replace('ID_master',$ipole,$z);
	$z=str_replace('tab_master',$tab_master,$z);
	$z=($z=='osoba_upr'?$z:str_replace('osoba_upr',$osu,$z));
	$z=($z=='osoba_skr'?$z:str_replace('osoba_skr',$oskr,$z));
	$z=str_replace('[fiskalna]',$fiskalna,$z);
	$z=str_replace('osoba_id',$ido,$z);
	$z=str_replace('osoba_pu',$punkt,$z);
	$z=str_replace('zaznaczone',$_POST['zaznaczone'],$z);
	if ($f=='+'||$f=='n'||$f=='s') {	//subtabela, np.: specyfikacja, nag³ówek, stopka
		$lps=0;					//l.p. w subtabeli
		$bloklp=0;				//l.p. w ramach bloku
		if (trim($z)==''||$f=='n'||$f=='s') {	//nie by³o ¿adnych zapytañ, wiêc jeden wiersz (pewnie nag³ówek lub stopka)
			$lpsmax=1;
		} else {
			$qq=explode(';',$z);	//mo¿e byæ kilka zapytañ ¿eby ustaliæ iloœæ wierszy
			$i=0;
			do {
				//fputs($file,$qq[$i]."\n");
				$qqs=mysql_query($qq[$i]);	//kolejne zapytanie
				if ($qqs) {
					//echo $qq[$i]."<br>";	//arek
				} else {
					echo "$qq[$i] <font color='red'>Error query</font><br><br>";
				}
				//echo mysql_num_rows($qqs)."<br>";
				if (strtoupper(substr(trim($qq[$i]),0,6))=='SELECT') {	//jeœli typu "SELECT"
					$qs=mysql_fetch_row($qqs);										//to coœ zwraca
					$i++;
					//echo $qs[0]."..<br>";
					if ($i<count($qq)) {				//jeœli s¹ nastêpne, to
						for ($j=0;$j<count($qs);$j++) {		//korzystaj¹ ze swoich wyników
							$qq[$i]=str_replace('['.$j.']',$qs[$j],$qq[$i]);
						}
					}
				} else {
					$i++;
				}
			} while ($i<count($qq));

			if (count($qs)>1) {	//wiêcej pól wyniku, to znaczy, ¿e s¹ tam pola dla specyfikacji w formacie [1], [2], itd.
				$lpsmax=-1;	//i nie wiadomo ile bêdzie wierszy
				$blok0=$qs[0];	//wartoœæ poprzedniego wiersza w ramach bloku
				$blok1=$qs[0];	//wartoœæ bie¿¹cego wiersza w ramach bloku
			} else {					//tylko jedno pole wyniku, to znaczy, ¿e
				$lpsmax=$qs[0];	//wynik ostatniego zapytania to iloœæ wierszy do obróbki
			}
		}	//if (trim($z)!='')

		$fvs=StripSlashes($wiersz['NAZWA']);	//s³owo: FVspec

		//if (substr($fvs,0,12)=='ListaSpecNag') {
		//	echo $fvs;
		//	echo $ws;
		//}
		while ($lpsmax==-1 ? (1==1) : ($lps<$lpsmax)) {

			if (($lpsmax==1)&&($f=='n'||$f=='s')) {
				$ws=$z;
				$zs='0';
			} else {
				$zs="Select TEKST, ID from wzoryumow where NAZWA='$fvs'";	// tekst FVspec
				$ws=mysql_query($zs);
				$ws=mysql_fetch_row($ws);
				$zs=$ws[1];									//ID definicji FVspec
				$ws=StripSlashes($ws[0]);				//linia z tekstami do zamiany
			}

			if (count(explode('[lp]',$ws))>0) {	//jeœli '[lp]' jest ju¿ w tekœcie g³ównym specyfikacji
				$ws=str_replace('[lp]',$lps+1,$ws);
			}

			if (($bufor=strpos($ws,'lp,'))>0) {
				$bufor--;
				$ws =substr($ws,0,$bufor)
				.sprintf("%' ".(substr($ws,$bufor+4)*1)."d",$lps+1)
				.substr($ws,$bufor+6);
			}

			if ($lpsmax<0) {		//wiêcej pól wyniku, to znaczy, ¿e s¹ tam pola dla specyfikacji w formacie [1], [2], itd.

				if ($lps==0) {	//PIERWSZY nag³ówek
					for ($j=0;$j<count($qs);$j++) {
						$w=str_replace('['.$j.']',strip_tags(stripslashes($qs[$j])),$w);
					}
				}

				for ($j=0;$j<count($qs);$j++) {
					$ws=str_replace('['.$j.']',$qs[$j],$ws);	//wiersz
				}
				$blok1=$qs[0];
				if ($blok0!=$blok1&&!$blokbreak) {	//zmieni³ siê numer bloku i nie ³ama³ strony ledwo co
					$bloklp=0;			//liczymy od nowa
					$blokstr++;			//next strona dla nowego bloku
					//					$ws=str_replace('[str]',$blokstr-1,$bloks).'<P~CLASS="breakhere">'.str_replace('[str]',$blokstr,$blokn).$ws;
					$blokss=$bloks;
					$bloknn=$blokn;
					for ($j=0;$j<count($qs);$j++) {
						$blokss=str_replace('['.$j.']',str_pad(number_format($sumy[$j],2),10,' ',STR_PAD_LEFT),$blokss);
						$bloknn=str_replace('['.$j.']',strip_tags(stripslashes($qs[$j])),$bloknn);
					}
					$ws=str_replace('[str]',$blokstr-1,$blokss).'<br>'.str_replace('[str]',$blokstr,$bloknn).'<br>'.$ws;	//nag³ówek
					for ($j=0;$j<count($qs);$j++) {
						$sumy[$j]=0;
					}
				}
				$blok0=$qs[0];
				if (count(explode('[bloklp]',$ws))>0) {//jeœli '[bloklp]' jest ju¿ w tekœcie g³ównym specyfikacji
					$ws=str_replace('[bloklp]',$bloklp+1,$ws);
				}
			}

			for ($j=0;$j<count($qs);$j++) {
				$sumy[$j]+=str_replace(',','',$qs[$j]);
				$tota[$j]+=str_replace(',','',$qs[$j]);
			}

			$blokbreak=false;

			$zss="Select NAZWA, FORMAT, TEKST from wzoryumows where ID_WZORYUMOW=$zs order by ID";
			if ($wyniks=mysql_query($zss)) {			//definicje zamian
				while ($wierszs=mysql_fetch_array($wyniks)) {	//lecimy po polach do zmiany
					$ns=StripSlashes($wierszs['NAZWA']);
					$fs=StripSlashes($wierszs['FORMAT']);
					$zs=StripSlashes($wierszs['TEKST']);
					$zs=str_replace('ID_master',$ipole,$zs);
					$zs=str_replace('[fiskalna]',$fiskalna,$zs);
					$zs=str_replace('osoba_id',$ido,$zs);
					$zs=str_replace('osoba_pu',$punkt,$zs);
					$zs=str_replace('[lp]',$lps,$zs);
					$zs=str_replace('[lp+1]',$lps+1,$zs);
					$zs=str_replace('[lp-1]',max($lps-1,0),$zs);
					if ($zs=='osoba_upr') {
						$qs[0]=$osu;}
						elseif ($zs=='osoba_skr') {
							$qs[0]=$oskr;}
							elseif ($zs=='lp') {
								$qs[0]=$lps+1;}
								else {
									if (count($qq=explode(';',$zs))>1) {		// kilka zapytañ
										$i=0;
										do {
											$qs=mysql_query($qq[$i]);
											if (strtoupper(substr(trim($qq[$i]),0,6))=='SELECT') {
												$qs=mysql_fetch_row($qs);
												//$zs=$qq[$i];
												//if ($qs) {
												//   echo "$zss <br> $ns: $zs <br><br>";
												//} else {
												//   echo "$zss <br> $ns: <font color='red'>Error fetch</font><br>".str_replace(';',';<br>',$zs)."<br><br>";
												//}
												$i++;
												if ($i<count($qq)) {
													for ($j=0;$j<count($qs);$j++) {		// korzystaj¹ ze swoich wyników
														$qq[$i]=str_replace('['.$j.']',$qs[$j],$qq[$i]);
													}
												}
											} else {
												$i++;
											}
										} while ($i<count($qq));
									} else {
										//echo "...$zs<br>";
										$qs=mysql_query($zs);
										if ($qs) {
											//echo "$zss <br> $ns: $zs <br><br>";	//arek
										} else {
											echo "$zss <br> $ns: <font color='red'>Error query</font><br>".str_replace(';',';<br>',$zs)."<br><br>";
										}
										$qs=mysql_fetch_row($qs);
										//echo $qs[0]."<br><br>";	//arek
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
								} else {
									$ws=str_replace($ns,$qs[0],$ws);
								}
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
						//						$ws=$ws.str_replace('[str]',$blokstr-1,$bloks).'<P~CLASS="breakhere">'.str_replace('[str]',$blokstr,$blokn);
						$ws=$ws.str_replace('[str]',$blokstr-1,$bloks).'<br>'.str_replace('[str]',$blokstr,$blokn);
					}
					if ($blokmax<>0) {
						$w=str_replace($fvs,$ws.$fvs,$w);			//kontynuacja FVspec
					} else {
						$w=str_replace($fvs,$ws.'<br>'.$fvs,$w);	//kontynuacja FVspec
					}
					//echo '<br>';
				} else {

					if (strpos($bloks,']')) {	//jeœli w ogóle jest coœ do podmiany
						$blokss=$bloks;
						for ($j=0;$j<20;$j++) {
							$blokss=str_replace('['.$j.']',str_pad(number_format($sumy[$j],2),10,' ',STR_PAD_LEFT),$blokss);
						}
						$ws=$ws.'<br>'.str_replace('[str]',$blokstr,$blokss);	//OSTATNIA stopka z prawdziwym numerem ostatniej strony

						$blokss=$bloks;
						for ($j=0;$j<20;$j++) {
							$blokss=str_replace('['.$j.']',str_pad(number_format($tota[$j],2),10,' ',STR_PAD_LEFT),$blokss);
						}
						$ws=$ws.'<br>'.str_replace('Razem','Total',$blokss);	//OSTATNIA stopka z prawdziwym numerem ostatniej strony
					}

					$w=str_replace($fvs,$ws,$w);		//koniec FVspec
					$lpsmax=-2;
				}
			} elseif ($lps<$lpsmax) {
				$w=str_replace($fvs,$ws.'<br>'.$fvs,$w);	//kontynuacja FVspec
			} else {
				if ($f!='s') {							//jak nie stopka (ona ma numer 1), to PIERWSZY nag³ówek
					$w=str_replace($fvs,$ws.$fvs,$w);	//kontynuacja FVspec
				}
			}
		}	//while ($lpsmax==-1 ? (1==1) : ($lps<$lpsmax))
		$w=str_replace($fvs,'',$w);		//koniec FVspec
		$w=str_replace('[str]',$blokstr,$w);	//numer strony na pierwszej stronie
		if ($f=='n') {$blokn=$ws;}			//mamy nag³ówek
		if ($f=='s') {$bloks=$ws;}			//mamy stopkê
	} else {
		if ($z=='osoba_upr') {
			$q[0]=$osu;}
			elseif ($z=='osoba_skr') {
				$q[0]=$oskr;}
				else {
					$qq=explode(';',$z);	// mo¿e byæ kilka zapytañ
					$i=0;
					do {
						//echo '...'.$qq[$i].'...<br>';		//pozycje nag³ówka
						if ((strtoupper(substr($x=trim($qq[$i]),0,5)))=='LINIA') {	//jeœli typu "LINIA"
							$n=substr($x,5)*1;
							$x=explode(chr(13).chr(10),$q[0]); // w "$q[0]" jest wynik ostatniego selecta
							$q[0]=$x[$n];                       // n-ta linia tekstu jako wynik
							$i++;
							if ($i<count($qq)) {
								$qq[$i]=str_replace('[0]',$q[0],$qq[$i]);
							}
						} else {
							if (!$q=mysql_query($qq[$i])) {
								echo "<br>$ns: $qq[$i]<br>";
							}
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

				$q[0]=StripSlashes($q[0]);			//	\"Zak³ad ...   ->	"Zak³ad ...

				if ($f) {	// format: "%' +30s"
					if (substr($f,3,1)=='+') {		//centrowanie
						$x=substr($f,4);				//30
						$q[0]=substr(trim($q[0]),0,$x);
						$q[0]=str_pad($q[0],$x,substr($f,2,1),STR_PAD_BOTH);
						$w=str_replace(StripSlashes($wiersz['NAZWA']),$q[0],$w);
					} elseif (substr($f,-1,1)=='z') {		//koszenie zer
						$xs=sprintf($f,$q[0]);				//12.3000z
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
						$w=str_replace(StripSlashes($wiersz['NAZWA']),$xs,$w);
					} else {
						$w=str_replace(StripSlashes($wiersz['NAZWA']),sprintf($f,$q[0]),$w);
					}
				}
				else {
					$w=str_replace(StripSlashes($wiersz['NAZWA']),$q[0],$w);
				}
	}
}

//$w=str_replace('"10CPI"','',$w);		//Chr(18).Chr(27).Chr(80),$w);	//1
//$w=str_replace('"12CPI"','',$w);		//Chr(27).'M',$w);					//1
//$w=str_replace('"15CPI"','',$w);		//Chr(27).'g',$w);					//1
//$w=str_replace('"17CPI"','',$w);		//Chr(15),$w);							//1
//$w=str_replace('"W0"','',$w);			//Chr(27).'W0',$w);					//1
//$w=str_replace('"W1"','',$w);			//Chr(27).'W1',$w);					//1

//$w=str_replace('"20CPI"',Chr(167),$w);		//Chr(27).'M'.Chr(27).Chr(15),$w);
//$w=str_replace('"E0"',Chr(168),$w);			//Chr(27).'F',$w);
//$w=str_replace('"E1"',Chr(147),$w);			//Chr(27).'E',$w);

//$w=str_replace('"EJE"',Chr(128),$w);		//Chr(12),$w);
//$w=str_replace('"INI"',Chr(138),$w);		//Chr(27).'@',$w);

if ($spacje!='N') {$w=str_replace(' ','&nbsp;',$w);$w=nl2br($w);}

//if (($zaznaczonei>1)&&($zaznaczonei==count($zaznaczone))) {$w=str_replace('"EJE"','',$w);}	//po ostatniej stronie nie rób EJECT
//else {$w=str_replace('"EJE"','<div_style="height:1px"></div><div_style="page-break-after:always;height:1px"></div>',$w);}

if ($laser) {
	$w=str_replace('"INI"','',$w);
	$w=str_replace('"20CPI"','<font style="font-size:06pt">',$w);
	$w=str_replace('"17CPI"','<font style="font-size:08pt">',$w);
	$w=str_replace('"15CPI"','<font style="font-size:10pt">',$w);
	$w=str_replace('"12CPI"','<font style="font-size:14pt">',$w);
	$w=str_replace('"10CPI"','</font>',$w);

	$w=str_replace('"W1"','<font style="font-size:16pt"><b>',$w);
	$w=str_replace('"W0"','</b></font>',$w);

	$w=str_replace('"EJECT"','<div_style="height:1px"></div><div_style="page-break-after:always;height:1px"></div>',$w);
	$w=str_replace('"EJE"','<div_style="height:1px"></div><div_style="page-break-after:always;height:1px"></div>',$w);
}

$w=str_replace('twardaspacja','&nbsp;',$w);
$w=str_replace('^','&nbsp;',$w);
$w=str_replace('~',' ',$w);
$w=str_replace('font_style','font style',$w);
$w=str_replace('div_style','div style',$w);

if (!$laser) {
	fputs($file,'<div style="width:1990">'."\n");
}

fputs($file,'<font style="font-family: Courier New">'."\n");

$s=explode('Fiskalna:',$w);
if (count($s)>1) {
	$w=$s[0];
	$s='Fiskalna:'.$s[1];
} else {
	$s='';
}

fputs($file,$w."\n");
fputs($file,'</font>'."\n");
if (!$laser) {
	fputs($file,'</div>'."\n");
}
fputs($file,'</font>'."\n");

//if ($spacje=='Z') {$zaznaczone[$zaznaczonei]='';};	//przerwij t± pêtlê

//}	//while ($zaznaczone[$zaznaczonei]) {

require('dbdisconnect.inc');

fputs($file,'</body>'."\n");
fputs($file,'</html>'."\n");

fclose($file);

if ($s) {
	$tmpfname='C:\\Wydruki\\Wydruk.htm';
	$file=fopen($tmpfname,"w");
	if (!$file) {
		echo "<p>Nie mo¿na otworzyæ pliku do zapisu.\n";
		exit;
	}
	fputs($file,$s);
	fclose($file);
}

include($fname);

?>

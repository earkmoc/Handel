<?php

if (!$ido) {
	session_start();
	$ido=$_SESSION['osoba_id'];
}

$raport='';
$raportn=0;

$z="Select ID from tabele where NAZWA='abonenci'";		//operacja tylko z tabeli "abonenci"
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $ida=$w[0];							//ostatnio u�ytego

$z='TRUNCATE TABLE opldodrob';	// analizujemy odci�cia i przy��czenia, �eby ustali� aktywnych
$w=mysql_query($z);
//echo $z.'<br>';

$z="insert into opldodrob select * from opldod where opldod.IDABONENTA=$ida and opldod.TYPOPER='a' order by DATAOPER desc";
$w=mysql_query($z);
//echo $z.'<br>';

$z='TRUNCATE TABLE opldodrob2';
$w=mysql_query($z);
//echo $z.'<br>';

$z='insert into opldodrob2 select * from opldodrob group by IDABONENTA, TYPOPER, INFO';
$w=mysql_query($z);
//echo $z.'<br>';

$z='TRUNCATE TABLE abonenciro';
$w=mysql_query($z);
//echo $z.'<br>';

//abonenci aktywni na dzi�
$z="insert into abonenciro 
select 0, abonenci.ID, abonenci.IDGRUPY, odcie.DATAOPER, przy.DATAOPER, 0 
from abonenci 
left join opldodrob2 as przy on (abonenci.ID=przy.IDABONENTA and przy.INFO='a') 
left join opldodrob2 as odcie on (abonenci.ID=odcie.IDABONENTA and (odcie.INFO='b' or odcie.INFO='c')) 
having ((abonenci.ID=$ida) and !(ISNULL(przy.DATAOPER) and !ISNULL(odcie.DATAOPER)) 
and (ISNULL(przy.DATAOPER) or ISNULL(odcie.DATAOPER) or przy.DATAOPER>=odcie.DATAOPER))";
$w=mysql_query($z);
//echo $z.'<br>';

$z="Select DATAREAL from abonenci where ID=$ida";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$dra=$w[0];										// od stycznia bie��cego roku ?

$z="Select MC1, MC2 from oplatyzak order by ID desc limit 1";
$w=mysql_query($z);
$w=mysql_fetch_row($w);

if (!$dtstart) {										//nie wiadomo od kiedy te op�aty, to ustala sam
	if (substr($dra,0,4)<date('Y')) {			//stara umowa
		$w[0]=date('Y.m');							//od bie��cego miesi�ca
	}
	else {												//nowe umowy
		$w[0]=substr($dra,0,7);						//od daty realizacji
	}
}
else {
	$w[0]=substr($dtstart,0,7);
}

$a=str_replace('-','.',$w[0])*1;
if(100*(round($a-floor($a),2))==12) {$a=$a+0.89;} else {$a=$a+0.01;}			//jednak od nast�pnego miesi�ca

$b=str_replace('-','.',$w[1])*1;

//2006.11+0.01=2006.12
//2006.12+0.89=2007.01

for($i=$a;$i<=$b;) {

//echo '<br><br><br>mc : '.$i.' ('.$a.'-'.$b.')<br><br><br>';

$z='TRUNCATE TABLE opldodrob';	// analizujemy op�aty dodatkowe "pocz" i "zako", �eby ustali� aktywne w danym miesi�cu
$w=mysql_query($z);
//echo $z.'<br>';

$zm=sprintf('%.2f',$i);
$d=$zm.'.15';

$z="insert into opldodrob select * from opldod where opldod.IDABONENTA=$ida and opldod.TYPOPER='o' and opldod.DATAOPER<='$d' order by DATAOPER desc";
$w=mysql_query($z);
//echo $z.'<br>';

$z='TRUNCATE TABLE opldodrob2';
$w=mysql_query($z);
//echo $z.'<br>';

// C2='p' lub 'z' ze s��w "pocz", "zako"
// wi�c w opldodrob2 s� zapisy o ostatnio wykonanym "pocz" i ostatnio wykonanym "zako"

$z='insert into opldodrob2 select * from opldodrob group by IDABONENTA, TYPOPER, C2';
$w=mysql_query($z);
//echo $z.'<br>';

$z='TRUNCATE TABLE typyopl1';	// ustalamy ostatnie zapisy na temat parametr�w op�at w grupach
$w=mysql_query($z);
//echo $z.'<br>';

$z="insert into typyopl1 select * from typyopl where typyopl.DATAOBOW<='$d' order by DATAOBOW desc";
$w=mysql_query($z);
//echo $z.'<br>';

$z='TRUNCATE TABLE typyopl2';	// parametry op�at w grupach
$w=mysql_query($z);
//echo $z.'<br>';

$z='insert into typyopl2 select * from typyopl1 group by IDGRUPY';
$w=mysql_query($z);
//echo $z.'<br>';

$z='TRUNCATE TABLE abonencir2';		// abonenci z op�atami dodatkowymi
$w=mysql_query($z);
//echo $z.'<br>';

//left join typyopl2 on typyopl2.IDGRUPY=abonenciro.IDGRUPY

$z="insert into abonencir2 
select 0, abonenciro.IDABONENTA, abonenciro.IDGRUPY, pocz.DATAOPER, zako.DATAOPER, pocz.C1 
from abonenciro 
left join opldodrob2 as pocz on (abonenciro.IDABONENTA=pocz.IDABONENTA and pocz.C2=112) 
left join opldodrob2 as zako on (abonenciro.IDABONENTA=zako.IDABONENTA and zako.C2=122) 
where !ISNULL(pocz.DATAOPER) and (ISNULL(zako.DATAOPER) or (pocz.DATAOPER>zako.DATAOPER))";
$w=mysql_query($z);
//echo $z.'<br>';

$z="Select count(*) from abonencir2";
$w=mysql_query($z); $w=mysql_fetch_row($w);
//echo $z."<br><br>($w[0])<br><br>";

//$z='TRUNCATE TABLE oplaty';		// miejsce na wyniki
//$w=mysql_query($z);
//echo $z.'<br>';

//$z='repair table `oplaty`';
//$w=mysql_query($z);
//echo $z.'<br>';

$j=0;

do {
$z="Select * from typyopl2 order by IDGRUPY limit $j,1";	// parametry op�at kolejnej grupy
$w=mysql_query($z);
if (!mysql_num_rows($w)) {			// nie ma kolejnej grupy
	$j=0;						// koniec
}
else {						// jest jaka� kolejna grupa
	$j++;										// mo�e b�dzie i nast�pna
	$g=mysql_fetch_array($w);			// parametry bie��cej grupy w tablicy
//echo '<br>Grupa '.$g['IDGRUPY'].'   <br>';
	$z="Select * from abonenciro where IDGRUPY=".$g['IDGRUPY'];
	$wa=mysql_query($z);					//abonenci aktywni
	while ($aa=mysql_fetch_array($wa)) {//kolejny abonent aktywny
		$do=0;								//nie wiemy czy i jak� ma dop�at�
		$od=false;							//na razie nie ma wypykanej op�aty dodatkowej
		$odj=false;							//na razie nie ma op�aty dodatkowej
		$odc=0;								//jaki z Towaru z abonenta w razie czego ?
		$odw=0;								//ile wynosi pierwsza lepsza ratunkowa w razie czego ?
		$odtt=0;								//jaki typ Towaru w razie czego ?
		$odzt=0;								//jaki z Towaru w razie czego ?
		for($n=0;$n<40;$n++) {
			$zt='ZTYTULU'.sprintf('%02d',$n);
			$tt='TYPTYTUL'.sprintf('%02d',$n);
			$op='OPLMSC'.sprintf('%02d',$n);
			if($g[$op]>0.009) {
				$ok=false;
				if($g[$tt]<>97) {			//op�ata dodatkowa
					if ($do>=0) {			//nie wiemy czy i jak� ma dop�at�
						if (!$odj) {							//jeszcze nie wiedzieli�my nic
							$z="Select C1 from abonencir2 where IDABONENTA=".$aa['IDABONENTA']." limit 1";
							$w=mysql_query($z);				// abonenci z dodatkowymi op�atami
							if (mysql_num_rows($w)) {
								$w=mysql_fetch_row($w);
								$odc=$w[0];							//jaki teoretycznie typ dop�aty ?
								$odw=$g[$op];						//ile w razie czego ?
								$odtt=$g[$tt];						//jaki typ Towaru w razie czego ?
								$odzt=$g[$zt];						//jaki z Towaru w razie czego ?
								$odj=true;							//to teraz ju� wiemy, �e jest jaka� dop�ata
							}
						}
//						echo $z;
						$z="Select DATAOPER from opldod where IDABONENTA=".$aa['IDABONENTA']." and TYPOPER='o' and DATAOPER<='$d' and C2=112 limit 1";
						$w=mysql_query($z);
//						echo $z.'<br>';
						if (!mysql_num_rows($w)) {				//wiemy, �e nie ma �adnej dop�aty
							$do=-1;}
						else {										//jest dop�ata
							$do=$g[$zt];						// ale jaka ? czy taka jakiej szukamy ?
							$z="Select DATAOPER from opldod where IDABONENTA=".$aa['IDABONENTA']." and TYPOPER='o' and DATAOPER<='$d' and (C1=$do or C1+1000=$do) and C2=112 order by DATAOPER desc";
							$w=mysql_query($z);				// ma pocz�tek takiej dop�aty do daty $d ?
//						echo $z.'<br>';
							if (mysql_num_rows($w)) {		// jest
								$w=mysql_fetch_row($w);		// jaka data ?
								$z="Select DATAOPER from opldod where IDABONENTA=".$aa['IDABONENTA']." and TYPOPER='o' and DATAOPER<='$d' and (C1=$do or C1+1000=$do) and C2=122 and DATAOPER>='$w[0]' limit 1";
								$w=mysql_query($z);			// ma koniec takiej dop�aty do daty $d ?
//						echo $z.'<br>';
								if (mysql_num_rows($w)) {	// dop�ata ma koniec wi�c nie interesuje nas
									$do=0;}
								else {							//nie ma ko�ca wi�c trwa
									$do=$g[$zt];				// jaka ?
								}
							}
							else {
								$do=0;				// nie ma takiej, ale mo�e ma inn�
							}
//							echo '========================='.$do;
						}
//						echo '<br>';
					}
					if (($do<>-1)&&(($do==$g[$zt])||(1000+$do==$g[$zt]))) {//i zgadza si� numer
						$ok=true;
						$od=true;	//wypykana op�ata dodatkowa
					}			//jak si� nie zgadza, to pomijaj op�at� dodatkow�
				}
				else {			//normalna op�ata
					$ok=true;
				}
				if ($ok) {			//dopisz op�at�: , IDABONENTA, TYPTYTULU, ZTYTULU, DODNIA,KWOTA, ZAMIESIAC 
					$z="select ID from oplaty where IDABONENTA=".$aa['IDABONENTA']." and TYPTYTULU=$g[$tt] and ZTYTULU=$g[$zt] and DODNIA='$d' and ZAMIESIAC='$zm' limit 1";
					$w=mysql_query($z);
//echo $z.'<br>';
					if(!mysql_num_rows($w)) {	//jak dot�d nie ma takiego zapisu to pisz
						$z="insert into oplaty values (0,".$aa['IDABONENTA'].",$g[$tt],$g[$zt],'$d',$g[$op],'','','$zm')";
						$w=mysql_query($z);
					}
//echo $z.'<br>';
				}
//echo $o.'='.$g[$o]."   ";
			}
		}
		if ($odj && !$od ) {	//abonent ma dop�at�, ale nie zgadza si� numer i nie zosta�a wypykana: IDABONENTA, TYPTYTULU, ZTYTULU, DODNIA,KWOTA, ZAMIESIAC
			$z="select ID from oplaty where IDABONENTA=".$aa['IDABONENTA']." and TYPTYTULU=$odtt and ZTYTULU=$odzt and DODNIA='$d' and ZAMIESIAC='$zm' limit 1";
			$w=mysql_query($z);
			$raportn++;
			$raport.="Abonent ".$aa['IDABONENTA']." ma niezgodny numer dop�aty ($odc) z parametrami swojej grupy (".$aa['IDGRUPY'].")\n";
//echo $z.'<br>';
			if(!mysql_num_rows($w)) {	//jak dot�d nie ma takiego zapisu to pisz
				$z="insert into oplaty values (0,".$aa['IDABONENTA'].",$odtt,$odzt,'$d',$odw,'','','$zm')";
				$w=mysql_query($z);
				$raport.="Wykonano domy�lny zapis na kwot� $odw z� z terminem wp�aty $d za m-c $zm\n\n";
			}
//echo $z.'<br>';
		}
	}
}
} while ($j>0);

if(100*(round($i-floor($i),2))==12) {$i=$i+0.89;} else {$i=$i+0.01;}

//echo 'floor : '.floor($i).'<br>';
//echo 'minus : '.(round(($i-floor($i)),2)).'<br>';
//echo 'razy100: '.(100*(round($i-floor($i),2))).'<br>';

}	//for($i=$a;$i<=$b;)

//echo '<br><br><br>end : '.$i.' ('.$a.'-'.$b.')<br><br><br>';

?>
<?

//require('skladuj_zmienne.php');

//obliczenie wszystkich pozycji specyfikacji bankowo/kasowej dokumentu

if ($ipole<>0) {

$all=false;
if ($ipole<0) {		//wymuszenie
	$ipole=-$ipole;
	$all=true;
}

$zz="select ID_D from dokumentbk where ID=$ipole"; $ww=mysql_query($zz); $ww=mysql_fetch_row($ww); $id_d=$ww[0];

$zz="select sum(PRZYCHOD), sum(ROZCHOD), sum(if(PRZYCHOD<>0,1,0)), sum(if(ROZCHOD<>0,1,0)) from dokumentbk where ID_D=$id_d"; $ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$ww3=$ww[3]*1;
$ww2=$ww[2]*1;
$www=$ww[1]*1;
$ww=$ww[0]*1;
$zz="update dokumenty SET PRZYCHODY=$ww, ROZCHODY=$www, ILEDOKPLUS=$ww2, ILEDOKMINU=$ww3, STANKONC=STANPOCZ+PRZYCHODY-ROZCHODY where ID=$id_d"; $ww=mysql_query($zz);

$zz="update dokumentbk SET KTO=$ido, CZAS=Now() where ID=$ipole"; $ww=mysql_query($zz);

}

?>
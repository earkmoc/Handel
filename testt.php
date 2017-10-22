<?php
@ $db=mysql_connect('','guest','123');

	if (!mysql_select_db('Arrakis')) {
		echo "Nie wyszlo: mysql_select_db('Arrakis')";
		exit;
	}

			$z="Select specopl.ID, specopl.IDABONENTA, specopl.TYPTYTULU, specopl.ZTYTULU, specopl.DODNIA, specopl.KWOTA, specopl.NRFAKTURY, specopl.NRPOZYCJI, specopl.ZAMIESIAC, specopl.WALUTA, specopl.NRRATY, specopl.Z_TABELI, specopl.ID_WTABELI, specopl.ID_OSOBYUPR, UPPER(stawkvat2.OPISSTVAT) as OPISSTAWKI from specopl left join stawkvat2 on stawkvat2.ZTYTULU=specopl.ZTYTULU where specopl.ID=25576";
			$z.=" limit 1";	// pierwsze zaznaczenie

echo '<br>'.$z.'<br>';

$w=mysql_query($z);
if (!$w) {
	echo '<br><br><br>problem<br><br><br>';
}

$o=mysql_fetch_array($w);		// mamy parê danych z "specopl"

	echo '1='.$o['NRFAKTURY'].'<br>';
	echo '2='.$o['DODNIA'].'<br>';
	echo '2='.date('Y-m').'<br>';
	echo '3='.$o[14].'<br>';

if (!$o['NRFAKTURY']&&(substr($o['DODNIA'],0,7)==date('Y-m'))&&(trim($o[14])=='BRAK')) {
	echo '1='.$o['NRFAKTURY'].'<br>';
	echo '2='.$o['DODNIA'].'<br>';
	echo '3='.$o[14].'<br>';
}

echo 'specopl.ID='.$o['specopl.ID'].'<br>';
echo 'ID='.$o['ID'].'<br>';
echo '0='.$o[0].'<br>';

echo 'UPPER(STAWKVAT2.OPISSTVAT)='.$o['UPPER(STAWKVAT2.OPISSTVAT)'].'<br>';
echo 'OPISSTAWKI='.$o['OPISSTAWKI'].'<br>';
echo 'opisstawki='.$o['opisstawki'].'<br>';
echo '14='.$o[14].'<br>';

mysql_close($db);

?>
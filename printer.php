<?php

session_start();

$file=fopen("dane.txt","w");
if (!$file) {
    echo "<p>Nie mo¿na otworzyæ pliku do zapisu.\n";
    exit;
}

require('dbconnect.inc');

$ok=true;

$sql="Select ID, DNIPO, PROGKWOTY, WYBRANE_T from zestzaop where ID_OSOBYUPR=osoba_id";
$sql=str_replace('osoba_id',$_SESSION['osoba_id'],$sql);

//		fputs($file,$sql."\n");

if (!$w=mysql_query($sql)) {$ok=false;};

if ($ok) {
	$w=mysql_fetch_row($w);
	$sql="Select concat(abonenci.ID,'-',abonenci.IDGRUPY,'-',abonenci.IDULICY,'-',abonenci.NRDOMU,' ',abonenci.NRMIESZK,' ',abonenci.NAZWISKO)";
	$sql.=" from abonenci ";
	$sql.=" left join oplaty on (abonenci.ID=oplaty.IDABONENTA and Date_Add(oplaty.DODNIA,interval [1] day)<=CurDate() and Find_In_Set(oplaty.ZTYTULU,'[3]')>0)";
	$sql.=" group by abonenci.ID";
	$sql.=" having sum(oplaty.KWOTA)>=[2]";
	$sql=str_replace('[1]',$w[1],$sql);
	$sql=str_replace('[2]',$w[2],$sql);
	$w[3]=str_replace('.',',',$w[3]);	//mog¹ byæ kropki zamiast przecinków
	$sql=str_replace('[3]',$w[3],$sql);

//		fputs($file,$sql."\n");

	if (!$w=mysql_query($sql)) {$ok=false;};
}

if ($ok) {
	$i=0;
	while ($r=mysql_fetch_row($w)) {
		$i++;
		fputs($file,$r[0]."\n");
	}
	fclose($file);

	echo '<a href="dane.txt">Pobierz plik wynikowy (ilo¶æ pozycji: '.$i.' )</a>';

}
else {
	echo 'Co¶ nie wysz³o: '.$sql;
}

require('dbdisconnect.inc');

?>

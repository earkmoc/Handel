<?php
session_start();
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">
<meta http-equiv="Reply-to" content="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<title></title>

<script type="text/javascript" language="JavaScript">
<!--
function escape(){
	if (event.keyCode==27) {
		location.href="Tabela.php?tabela=zestawy";
	}
}
document.onkeypress=escape;
-->
</script>
</head>

<body bgcolor="#FFFFFF">

<?php
require('dbconnect.inc');

$ok=true;

$sql="Select ID, DNIPO, PROGKWOTY, WYBRANE_T, WYBRANE_G from zestzaop where ID_OSOBYUPR=osoba_id";
$sql=str_replace('osoba_id',$_SESSION['osoba_id'],$sql);

if (!$w=mysql_query($sql)) {$ok=false;};

if ($ok) {
	$w=mysql_fetch_row($w);
	$sql="Select concat(if(isnull(abonenci.NRBLOKU) or abonenci.NRBLOKU='',concat('Indeks: ',abonenci.ID,'-',abonenci.IDGRUPY,'-',abonenci.IDULICY,'-',abonenci.NRDOMU),abonenci.NRBLOKU),' ',abonenci.NRMIESZK,' ',abonenci.NAZWISKO)";
	$sql.=" from abonenci ";
	$sql.=" left join oplaty on (abonenci.ID=oplaty.IDABONENTA and Date_Add(oplaty.DODNIA,interval [1] day)<=CurDate() and Find_In_Set(oplaty.ZTYTULU,'[3]')>0 and Find_In_Set(abonenci.IDGRUPY,'[4]')>0)";
	$sql.=" group by abonenci.ID";
	$sql.=" having sum(oplaty.KWOTA)>=[2]";
	$sql.=" order by abonenci.NRBLOKU*1";
	$sql=str_replace('[1]',$w[1],$sql);
	$sql=str_replace('[2]',$w[2],$sql);
	$w[3]=str_replace('.',',',$w[3]);	//mogš być kropki zamiast przecinków
	$sql=str_replace('[3]',$w[3],$sql);
	$w[4]=str_replace('.',',',$w[4]);	//mogš być kropki zamiast przecinków
	$sql=str_replace('[4]',$w[4],$sql);

	if (!$w=mysql_query($sql)) {$ok=false;};
}

if ($ok) {
	$i=0;
	$file=fopen("zest17.txt","w");
	if (!$file) {
	    echo "<p>Nie można otworzyć pliku do zapisu.\n";
	    exit;
	}
	while ($r=mysql_fetch_row($w)) {
		$i++;
		echo $r[0]."<br>";		//\n";
		fputs($file,$r[0]."\n");
	}
	fclose($file);
}
else {
	echo 'Coś nie wyszło: '.$sql;
}

require('dbdisconnect.inc');

?>

</body>
</html>



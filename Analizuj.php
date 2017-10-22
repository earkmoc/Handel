<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=iso-8859-2">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />
<title>Analizuj</title>
</head>
<body>

<?php

if ($_GET['tabela']) {
	$analizowane=$_GET['tabela'];
	$analizowane=str_replace('%20',' ',$analizowane);
	require('dbconnect.inc');
} else {
	$w=mysql_query("select NAZWA from tabele where ID=-$ipole");
	$w=mysql_fetch_row($w);
	$analizowane=$w[0];
}

echo "<h1>Analizowane: <font style=\"background-color:red; size:12pt\"><b>$analizowane</b></font></h1>";

$w=mysql_query("select TABELA, NAZWA, OPIS from tabele where TABELA like '%$analizowane%'");
while ($r=mysql_fetch_row($w)) {
	echo "<hr><h2>Tabela: ".$r[1].", ".$r[2]."</h2><br>";
	$r[0]=str_replace($analizowane,"<font style=\"background-color:red; size:12pt\"><b>$analizowane</b></font>",$r[0]);
	echo StripSlashes(nl2br($r[0])).'<br><br><br><br>';
}

$w=mysql_query("select FORMULARZ, NAZWA, OPIS from tabele where FORMULARZ like '%$analizowane%'");
while ($r=mysql_fetch_row($w)) {
	echo "<hr><h2>Formularz: ".$r[1].", ".$r[2]."</h2><br>";
	$r[0]=str_replace($analizowane,"<font style=\"background-color:red; size:12pt\"><b>$analizowane</b></font>",$r[0]);
	echo StripSlashes(nl2br($r[0])).'<br><br><br><br>';
}

$w=mysql_query("select FUNKCJE, NAZWA, OPIS from tabele where FUNKCJE like '%$analizowane%'");
while ($r=mysql_fetch_row($w)) {
	echo "<hr><h2>Funkcje: ".$r[1].", ".$r[2]."</h2><br>";
	$r[0]=str_replace($analizowane,"<font style=\"background-color:red; size:12pt\"><b>$analizowane</b></font>",$r[0]);
	echo StripSlashes(StripSlashes(nl2br($r[0]))).'<br><br><br><br>';
}

$w=mysql_query("select FUNKCJEF, NAZWA, OPIS from tabele where FUNKCJEF like '%$analizowane%'");
while ($r=mysql_fetch_row($w)) {
	echo "<hr><h2>FunkcjeF: ".$r[1].", ".$r[2]."</h2><br>";
	$r[0]=str_replace($analizowane,"<font style=\"background-color:red; size:12pt\"><b>$analizowane</b></font>",$r[0]);
	echo StripSlashes(StripSlashes(nl2br($r[0]))).'<br><br><br><br>';
}

$w=mysql_query("select ID, NAZWA, OPIS, TEKST from wzoryumow where TEKST like '%$analizowane%'");
while ($r=mysql_fetch_row($w)) {
	echo "<hr><h2>WzoryUmow: ".$r[1].", ".$r[2]."</h2><br>";
	$r[3]=str_replace($analizowane,"<font style=\"background-color:red; size:12pt\"><b>$analizowane</b></font>",$r[3]);
	echo StripSlashes(nl2br($r[3])).'<br><br><br><br>';
}

$w=mysql_query("select ID, NAZWA, ID_WZORYUMOW, TEKST from wzoryumows where TEKST like '%$analizowane%'");
while ($r=mysql_fetch_row($w)) {
	echo "<hr><h2>WzoryUmowS: ".$r[1].", ".$r[2]."</h2><br>";
	$r[3]=str_replace($analizowane,"<font style=\"background-color:red; size:12pt\"><b>$analizowane</b></font>",$r[3]);
	echo StripSlashes(nl2br($r[3])).'<br><br><br><br>';
}

$d = dir(".");
while (false !== ($entry = $d->read())) {
	if (substr($entry,-3,3)=='php') {
		$filename = "$entry";
		$jest=false;
		$row=0;
		if (file_exists($filename)) {
			$handle = fopen($filename, "r");
			while (!feof($handle)) {
				$data = fgets($handle,4096);
				$row++;
				if (count(explode("$analizowane",$data))>1) {
					if (!$jest) {
						$jest=true;
						echo "<hr><h2>PHP: ".$entry."</h2><br>";
					}
					$data=str_replace($analizowane,"<font style=\"color:red; size:12pt\"><b>$analizowane</b></font>",$data);
					echo $row.". ".nl2br($data);
				}
			}
			fclose($handle);
		}
		if ($jest) {echo '<br><br><br>';}
	}
}
$d->close();

$ok=false;
?>
</body>
</html>
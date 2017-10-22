<?php

session_start();

if ($_POST['natab']&&($_POST['natab']!=='osoby')) {
if (!$_SESSION['osoba_upr']) {
        echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
        echo "<title>OK</title></head><body bgcolor='#BFD2FF' ";
        echo "onload='";
        echo 'location.href="Tabela_End.php"';
        echo "'\'>";
//        echo '<h1 align="center"><br><br><br>Przetwarzanie danych w toku ...</h1>';
        echo '</body></html>';
        exit;
}};

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<title>"Abonenci"
<?php
if ($_SESSION['osoba_upr']) {
        echo ': ';
        echo $_SESSION['osoba_upr'];
}
?>
</title>

<style type="text/css">
<!--
#f00 {POSITION: absolute; VISIBILITY: visible; TOP:10px; LEFT: 10px; Z-INDEX:2;}
.nag {font: normal 15pt};
.nor {font: normal 20pt};
-->
</style>


<script type="text/javascript" language="JavaScript">
<!--
var tnag, cnag, twie, cwie, posx, posxx, r, c, str, okgoradol;

$okgoradol=true;

<?php

$natab=$_POST['natab'];                // definicja tabeli formularza
if (!$natab) {$natab='osoby';};

$idtab=$_POST['idtab'];                // gdzie ma otworzyć formularz
$ipole=$_POST['ipole'];                // id pozycji tabeli
$opole=$_POST['opole'];                // jaka operacja dla Tabela_Szukaj_Zapisz
if (!$opole) {$opole="_";};
$oopole=$opole;
$rrr=$_POST['rrrpole'];
$rr=$_POST['rrpole'];
$r=$_POST['rpole'];
$c=$_POST['cpole'];
$str=$_POST['strpole'];
?>
-->
</script>

</head>

<?php
echo '<body bgcolor="#BFD2FF">';
echo "\n";
echo '<form>';
echo "\n";
$s='';
for($i=0;$i<10;$i++) {
	if ($_POST['tx_'.$i.'_5']<>0) {
		for($j=1;$j<=5;$j++) {
			$s.=$_POST['tx_'.$i.'_'.$j];
			$s.=", ";
		}
		$s.="\n";
	}
}
echo '<a href="Tabela.php?tabela=stan_s">Powrót do zamówień</a>';
echo "<br>";
echo "<br>";
echo 'Zamówienie j.n. wysłane na skrzynkę "poczta@aziarko.pl":<br><br>';
echo nl2br($s);
echo "<br>";
echo "<br>";
echo '<a href="Tabela.php?tabela=stan_s">Powrót do zamówień</a>';
mail('AMoch@pro.onet.pl','Test zamówienia', $s);
mail('poczta@aziarko.pl','Test zamówienia', $s);
?>
</form>
</body>
</html>
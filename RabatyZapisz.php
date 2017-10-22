<?php
session_start();

$ido=$_SESSION['osoba_id'];
$rabat=$_POST['rabat'];
$dokum_tab=$_POST['dokum_tab'];
$spec_tab=$_POST['spec_tab'];

require('dbconnectr.inc');

$z="select ID from tabele where NAZWA='$dokum_tab'";
$w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];

$z="select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";
$w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];

$z="update spec set RABAT=$rabat where ID_D=$w and ILOSC>=0"; mysql_query($z);

$idd=$w;
$tabelaa=''; //dla spec_calc.php, ¿eby na koñcu nic nie kombinowa³
$tabelad=''; //dla spec_calc.php, ¿eby na koñcu nic nie kombinowa³
$ww=mysql_query("select ID from spec where spec.ID_D=$idd");
$r=mysql_fetch_row($ww);

$ipole=$r[0];
$all_spec=true;
require('spec_calc.php');

mysql_query("update dokum set TOWRABAT=$rabat, CZAS=Now() where ID=$idd limit 1");

require('dbdisconnect.inc');
?>
<html>
<head>
<title>Rabaty zapisz</title>

<script type="text/javascript" language="JavaScript">
<!--
function sio(){
opener.location.href='Tabela.php?tabela=<?php echo $spec_tab;?>';
close();
}
document.onkeypress=sio;
-->
</script>

</head>
<body bgcolor="#EFEFCF" onload='sio();' >
</body>
</html>
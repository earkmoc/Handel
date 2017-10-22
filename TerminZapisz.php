<?php
session_start();

$ido=$_SESSION['osoba_id'];
$termin=$_POST['termin'];
$dokum_tab=$_POST['dokum_tab'];
$spec_tab=$_POST['spec_tab'];

require('dbconnectr.inc');

$z="select ID from tabele where NAZWA='$dokum_tab'";
$w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];

$z="select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";
$w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];

$idd=$w;
$z="update dokum set DATAT=Date_Add(DATAS,interval '$termin' day), DNIZWLOKI=$termin, CZAS=Now() where ID=$idd limit 1";
$w=mysql_query($z);
require('dbdisconnect.inc');
?>
<html>
<head>
<title>Termin zapisz</title>

<script type="text/javascript" language="JavaScript">
<!--
function sio(){
<?php
echo "opener.location.href='Tabela.php?tabela=$spec_tab'";
//if (!$spec_tab) {
//echo "opener.location.href='Tabela_Formularz.php?tabela=$dokum_tab'";
//} else {
//echo "opener.location.href='Tabela.php?tabela=$spec_tab'";
//}
?>;
close();
}
document.onkeypress=sio;
-->
</script>

</head>
<body bgcolor="#EFEFCF" onload='sio();' >
</body>
</html>
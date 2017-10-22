<?php
session_start();

$ido=$_SESSION['osoba_id'];
$cennik=$_POST['cennik'];
$dokum_tab=$_POST['dokum_tab'];
$spec_tab=$_POST['spec_tab'];

require('dbconnectr.inc');

$z="select ID from tabele where NAZWA='$dokum_tab'";
$w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];

$z="select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";
$w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];

$cenik=$cennik;		               //oryginalnie to co poda³ operator jest w cenik
$cennik=(($cennik<2)?'':$cennik);   //a validacja zmienia cennik
if (strtoupper(substr($cenik,0,1))=='Z') {
   $marza=substr($cenik,1)*1;
   $z="update spec left join towary on towary.ID=spec.ID_T set spec.CENABEZR=TOWARY.CENA_Z*(100+$marza)/100 where spec.ID_D=$w";
//   $z="update spec left join towary on towary.ID=spec.ID_T set spec.CENABEZR=TOWARY.CENA_Z where spec.ID_D=$w";
   $mpodm_pre=1;  //wy³±czenie ograniczników netnet, czyli dzia³aj tak jakby do by³ dokument przychodowy, tj. = 1
} elseif (strpos($cenik,'.')>0) {   //cena podana np. grosz, czyli 0.00, a nie rabat
   $z="update spec set spec.CENABEZR=$cenik where spec.ID_D=$w";
   $mpodm_pre=1;  //wy³±czenie ograniczników netnet, czyli dzia³aj tak jakby do by³ dokument przychodowy, tj. = 1
   $cenik=-1;   //cennik dla dokumentu
} else {
   $z="update spec left join towary on towary.ID=spec.ID_T set spec.CENABEZR=TOWARY.CENA_S$cennik where spec.ID_D=$w";
}
mysql_query($z);

$idd=$w;
$tabelaa=''; //dla spec_calc.php, ¿eby na koñcu nic nie kombinowa³
$tabelad=''; //dla spec_calc.php, ¿eby na koñcu nic nie kombinowa³
$ww=mysql_query("select ID from spec where spec.ID_D=$idd");
$r=mysql_fetch_row($ww);

$z="update dokum set TOWCENNIK='$cenik', CZAS=Now() where ID=$idd limit 1";
$w=mysql_query($z);

$ipole=$r[0];
$all_spec=true;
require('spec_calc.php');

require('dbdisconnect.inc');
?>
<html>
<head>
<title>Cenniki zapisz</title>

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
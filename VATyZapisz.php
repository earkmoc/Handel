<?php
session_start();

$ido=$_SESSION['osoba_id'];
$vat=trim($_POST['vat']);
$dokum_tab=$_POST['dokum_tab'];
$spec_tab=$_POST['spec_tab'];

$komunikat='B³êdna stawka VAT';

if ( $vat=='0%'
   ||$vat=='zw.'
   ||$vat=='5%'
   ||$vat=='7%'
   ||$vat=='8%'
   ||$vat=='22%'
   ||$vat=='23%'
   ) {

   $komunikat='';

   require('dbconnectr.inc');
   
   $z="select ID from tabele where NAZWA='$dokum_tab'";
   $w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];
   
   $z="select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";
   $w=mysql_query($z);$w=mysql_fetch_row($w); $w=$w[0];
   
   $z="update spec left join towary on towary.ID=spec.ID_T set spec.STAWKAVAT='$vat' where spec.ID_D=$w";
   mysql_query($z);
   
   $idd=$w;
   $tabelaa=''; //dla spec_calc.php, ¿eby na koñcu nic nie kombinowa³
   $tabelad=''; //dla spec_calc.php, ¿eby na koñcu nic nie kombinowa³
   $ww=mysql_query("select ID from spec where spec.ID_D=$idd");
   $r=mysql_fetch_row($ww);
   
   $ipole=$r[0];
   $all_spec=true;
   require('spec_calc.php');
   require('dbdisconnect.inc');
}
?>
<html>
<head>
<title>VATy zapisz</title>

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
<body bgcolor="#EFEFCF" onload='sio();'>
<?php
//if ($komunikat) {       // 
//   echo '<script type="text/javascript" language="JavaScript">';
//   echo '<!--';
//   echo "alert('B³±d: $komunikat');";
//   echo '-->';
//   echo '</script>';
//}
?>
</body>
</html>
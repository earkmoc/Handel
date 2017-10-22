<?php
require('dbconnect.inc');
?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<title>Historia zmian</title>
<script type="text/javascript" language="JavaScript">
<!--
function escape(){
	if (event.keyCode==27) {
		location.href="Konserwuj.php";
	}
}
document.onkeypress=escape;
-->
</script>
</head>

<body bgcolor="#BFD2FF">

<a href="Konserwuj.php">Esc=powrót</a><br><br>

<!--
š=ą
=ś
ź=ź
-->
<?php
echo '<table><tr>
<td style="border-bottom: double #000000">Baza.tabela</td>
<td style="border-bottom: double #000000">Opis</td>
<td style="border-bottom: double #000000"> </td>
<td style="border-bottom: double #000000"> </td>
<td style="border-bottom: double #000000">ostatnie ID</td>
<td style="border-bottom: double #000000">count</td>
';
$w=mysql_query("select NAZWA from tabele"); 
while ($r=mysql_fetch_row($w)) {
	$tab=$r[0];
	$ww=mysql_query("ANALYZE TABLE $tab"); 
	while ($rr=mysql_fetch_row($ww)) {
if ((substr($rr[3],-13,13)<>"doesn't exist")
  &&(substr($rr[3],-16,16)<>"Operation failed")
  &&(substr($rr[3],-56,56)<>"The storage engine for the table doesn't support analyze")
  &&(substr($rr[3],-54,54)<>"The storage engine for the table doesn't support check")
   ) {
//		if (substr($rr[3],-13,13)<>"doesn't exist") {
			echo '<tr>';
			for($i=0;$i<count($rr);$i++) {
				echo '<td>';
				echo $rr[$i];
				echo '</td>';
			}

			$zzz="select format(ID,0) from $tab order by ID desc limit 1";
			$www=mysql_query($zzz); 
			@$rrr=mysql_fetch_row($www);
			echo '<td align="right">';
			echo $rrr[0];
			echo '</td>';

			$zzz="select format(count(*),0) from $tab";
			$www=mysql_query($zzz); 
			@$rrr=mysql_fetch_row($www);
			echo '<td align="right">';
			echo $rrr[0];
			echo '</td>';

			echo '</tr>';
		}
	}
}
echo '</table>';
require('dbdisconnect.inc');
?>
</body>
</html>

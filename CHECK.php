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
=ź
-->
<?php
echo '<table>';
$w=mysql_query("select NAZWA from tabele"); 
while ($r=mysql_fetch_row($w)) {
	$rr=$r[0];
	$ww=mysql_query("CHECK TABLE $rr"); 
	while ($rr=mysql_fetch_row($ww)) {
if ((substr($rr[3],-13,13)<>"doesn't exist")
  &&(substr($rr[3],-16,16)<>"Operation failed")
  &&(substr($rr[3],-54,54)<>"The storage engine for the table doesn't support check")
   ) {
	echo '<tr>';
		for($i=0;$i<count($rr);$i++) {
	echo '<td>';
	echo $rr[$i];
	echo '</td>';
		}
	echo '</tr>';
}
	}
}
echo '</table>';
require('dbdisconnect.inc');
?>
</body>
</html>

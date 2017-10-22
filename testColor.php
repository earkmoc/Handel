<?php
$pre=$_POST['pre'];
echo '<html>';echo "\n";
echo '<head>';echo "\n";
echo '<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';echo "\n";
echo '</head>';echo "\n";
echo '<body bgcolor="#BFD2FF" onload="FO.pole.focus()">';echo "\n";
echo '<table>';echo "\n";
$L='0123456789ABCDEF';
for($i=0;$i<16;$i++) {
	echo '<tr>';
	for($j=0;$j<16;$j++) {
		$s=$pre.'F';
		$s.=substr($L,$i,1).'F';
		$s.=substr($L,$j,1).'F';
		echo '<td bgcolor="#'.$s.'" title="'.$s.'" width="50px">&nbsp;</td>';
	}
	echo '</tr>';echo "\n";
}
echo '</table>';
echo '<form id="FO" method="POST" action="testColor.php">';
echo '<input id="pole" type="text" name="pre" value="'.$pre.'"/>';
echo '<input type="submit" value="OK"/>';
echo '</form>';
echo '</body></html>';
?>
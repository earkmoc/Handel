<?php
$pre1=$_POST['pre1'];
$pre2=$_POST['pre2'];
$pre3=$_POST['pre3'];
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
		$s=substr($L,$i,1).substr($L,$j,1);
		if (!$pre1) {$s=$s.$pre2.$pre3;}
		if (!$pre2) {$s=$pre1.$s.$pre3;}
		if (!$pre3) {$s=$pre1.$pre2.$s;}
		echo '<td bgcolor="#'.$s.'" title="'.$s.'" width="50px">&nbsp;</td>';
	}
	echo '</tr>';echo "\n";
}
echo '</table>';
echo '<form id="FO" method="POST" action="testColorr.php">';
echo 'R:<input type="text" name="pre1" value="'.$pre1.'" id="pole"/>';
echo 'G:<input type="text" name="pre2" value="'.$pre2.'"/>';
echo 'B:<input type="text" name="pre3" value="'.$pre3.'"/>';
echo '<input type="submit" value="OK"/>';
echo '</form>';
echo '</body></html>';
?>
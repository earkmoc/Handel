<?php

session_start();

$natab=$_POST['natab'];
if ($_GET['natab']) {$natab=$_GET['natab'];}

//info4mail
//uzytkownicy_id, nr_bl, nr_miesz, email1, email2, email3

$conn = pg_connect("host=195.13.38.7 port=5001 dbname=retsat1inet user=info4mail password=dupa12");

if (!$conn) {
  echo "An error occured.\n";
  exit;
}

$result = pg_query($conn, "SELECT uzytkownicy_id, nr_bl, nr_miesz, email1, email2, email3 FROM info4mail");
if (!$result) {
  echo "An error occured.\n";
  exit;
}

require('dbconnect.inc');

$z="truncate table abonencie";
$w=mysql_query($z);

$i=1;
while ($row = pg_fetch_row($result)) {
  echo $i++;echo ". ";
  echo $row[0];echo ", ";
  echo $row[1];echo ", ";
  echo $row[2];echo ", ";
  echo $row[3];echo ", ";
  echo $row[4];echo ", ";
  echo $row[5];echo "    ";

//ID int(11) NOT NULL auto_increment,
//IDGRUPY int(11) NOT NULL default 0,
//NR_BL char(7) default '',
//NR_MIESZ char(7) default '',
//EMAIL1 char(50) default '',
//EMAIL2 char(50) default '',
//EMAIL3 char(50) default '',

//for($i=1;$i<3;$i++) {
$i=2;
if ($i==1) {$szer='5';}
if ($i==2) {$szer='04';}
$znak=ord(substr(trim($row[$i]),-1));
if (48<=$znak && $znak<=57) {
	$row[$i]=substr('00000000'.trim($row[$i]),-$szer+1);
}
else {
	$row[$i]=substr('00000000'.trim($row[$i]),-$szer);
	$row[$i]=StrToUpper($row[$i]);
}
 
$z="insert into abonencie (IDGRUPY,NR_BL,NR_MIESZ,EMAIL1,EMAIL2,EMAIL3) values ('".$row[0]."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."')";
$w=mysql_query($z);

}

require('dbdisconnect.inc');

echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
echo "<title>Wykonanie SQL</title></head><body bgcolor='#0F4F9F' ";
echo "onload='";
echo 'location.href="Tabela.php?tabela='.$natab.'"';
echo '\'>';
echo '</body></html>';

?>

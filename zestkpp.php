<?php
session_start();
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">
<meta http-equiv="Reply-to" content="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<title></title>

<script type="text/javascript" language="JavaScript">
<!--
function escape(){
	if (event.keyCode==27) {
		location.href="Tabela.php?tabela=zestawy";
	}
}
document.onkeypress=escape;
-->
</script>
</head>

<body bgcolor="#FFFFFF">

<?php
require('dbconnect.inc');

$sql="Select DATA1, DATA2 from zestwpkp where ID_OSOBYUPR=osoba_id";
$sql=str_replace('osoba_id',$_SESSION['osoba_id'],$sql);

$ok=true;
if (!$w=mysql_query($sql)) {$ok=false;};

if ($ok) {

	$daty=mysql_fetch_row($w);

	$sql="Select ";
	$sql.=" DATAPRZYJ,";
	$sql.=" NRDOKUM,";
	$sql.=" Sum(zestwpkpw.WYSWPL),";
	$sql.=" concat(abonenci.IDGRUPY,'-',abonenci.IDULICY,'-',abonenci.NRDOMU,'-',abonenci.NRMIESZK),";
	$sql.=" osoby.OPIS,";
	$sql.=" IDOPERATOR,";
	$sql.=" abonenci.ZABLOK";
	$sql.=" from zestwpkpw ";
	$sql.=" left join abonenci on zestwpkpw.IDABONENTA=abonenci.ID left join osoby on osoby.ID=zestwpkpw.IDOPERATOR";
	$sql.=" group by zestwpkpw.NRDOKUM, zestwpkpw.DATAPRZYJ, zestwpkpw.IDOPERATOR";
	$sql.=" having if(osoba_pu=2,abonenci.ZABLOK='T',abonenci.ZABLOK!='T')";
	$sql.=" order by zestwpkpw.NRDOKUM*1";

	$sql=str_replace('osoba_pu',$_SESSION['osoba_pu'],$sql);
	if (!$w=mysql_query($sql)) {$ok=false;};
}

if ($ok) {

	$daty[0]=str_replace('-','.',$daty[0]);
	$daty[1]=str_replace('-','.',$daty[1]);
	$nag='';
	$nag.='Stowarzyszenie Telewizji Kablowej RET-SAT 1';
	$nag.='<br>94-044 Łódź, ul.Przełajowa 14 TEL.688-75-65';
	$nag.='<br>                                              DNIA '.date('Y.m.d').', '.'GODZINA '.date('H:i');
	$nag.='<br>    Zestawienie wpłat z KP (punkt Nr osoba_pu)';
	$nag.='<br>    od '.$daty[0].' do '.$daty[1];
	$nag.='<br>____________________________________________________________________________';
	$nag.='<br>|  LP |   Data   | Numer dokum. |    Kwota     |Indeks Abonenta| Przyjęła';
	$nag.='<br>|_____|__________|______________|______________|_______________|____________';

	echo '<font style="font-family: Courier">';
	$nag=str_replace('osoba_pu',$_SESSION['osoba_pu'],$nag);
	echo $nag=str_replace(' ','&nbsp',$nag);
//	echo $nag=str_replace('-','÷',$nag);

	$lp=1;
	$j=0;
	$rr2=0;
	while ($r=mysql_fetch_row($w)) {
		$line='<br>|';
		$line.=sprintf("%' 5d",$lp++).'|';
		$line.=sprintf("%' 10s",str_replace('-','.',$r[0])).'|';		//Data
		$line.=sprintf("%' 14s",$r[1]).'|';									//Nr
		$line.=sprintf("%' 14.2f",str_replace('-','÷',$r[2])).'|';	//Kwota
		$line.=sprintf("%-15s",str_replace('-','÷',$r[3])).'|';		//Indeks
		$line.=sprintf("%-22s",str_replace('-','÷',$r[4]));		//Operator

		$rr2+=$r[2];
		echo $line=str_replace(' ','&nbsp',$line);
	}
		$line='<br>============================================================================';
		$line.='<br>                            Razem: ';
		$line.=sprintf("%' 12.2f",$rr2);
		echo $line=str_replace(' ','&nbsp',$line);
	echo '</font>';
}
else {
	echo 'Coś nie wyszło: '.$sql;
}

require('dbdisconnect.inc');

?>

</body>
</html>
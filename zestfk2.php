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

<body bgcolor="#FFFFFF" onload="window.print()">

<?php
require('dbconnect.inc');

$sql="Select DATA1, DATA2 from zestfk2 where ID_OSOBYUPR=osoba_id";
$sql=str_replace('osoba_id',$_SESSION['osoba_id'],$sql);

$ok=true;
if (!$w=mysql_query($sql)) {$ok=false;};

if ($ok) {

	$daty=mysql_fetch_row($w);

	$sql="Select ";
	$sql.=" concat(faktury.NRDOKVAT,'/',faktury.MSCDOKVAT,'/',faktury.ROKDOKVAT),";
	$sql.=" faktury.DATAUSLUGI,";
	$sql.=" faktury.DATAWYSTAW,";
	$sql.=" faktury.NIPABONENT,";
	$sql.=" faktury.NAZWAABON,";
	$sql.=" concat(faktury.KODABON,' ',faktury.MIEJSCABON,' ',faktury.ULICAABON),";
	$sql.=" sum(BRUTTO22),";
	$sql.=" sum(BRUTTO22)-sum(VAT22),";
	$sql.=" sum(VAT22),";
	$sql.=" sum(BRUTTO7),";
	$sql.=" sum(BRUTTO7)-sum(VAT7),";
	$sql.=" sum(VAT7),";
	$sql.=" sum(BRUTTO0),";
	$sql.=" sum(BRUTTOZW),";
	$sql.=" sum(zestfk2w.WYSWPL),";
	$sql.=" sum(zestfk2w.WYSWPL)-sum(VAT22)-sum(VAT7),";
	$sql.=" sum(VAT22)+sum(VAT7),";
	$sql.=" zestfk2w.ID_OPLATY,";
	$sql.=" abonenci.ZABLOK";
	$sql.=" from zestfk2w ";
	$sql.=" left join abonenci on zestfk2w.IDABONENTA=abonenci.ID left join faktury on (faktury.NRFAKTURY=zestfk2w.NRFAKTURY and faktury.IDABONENTA=zestfk2w.IDABONENTA)";
	$sql.=" group by zestfk2w.NRFAKTURY, zestfk2w.IDABONENTA";
	$sql.=" having abonenci.ZABLOK='T' and zestfk2w.ID_OPLATY=2";
	$sql.=" order by faktury.DATAWYSTAW, zestfk2w.ID_OPLATY, faktury.NRDOKVAT*1";

	$sql=str_replace('osoba_pu',$_SESSION['osoba_pu'],$sql);
	if (!$w=mysql_query($sql)) {$ok=false;};
}

if ($ok) {

	$daty[0]=str_replace('-','.',$daty[0]);
	$daty[1]=str_replace('-','.',$daty[1]);
	$nag='';
	$nag.='Stowarzyszenie Telewizji Kablowej RET-SAT 1';
	$nag.='<br>94-044 Łódź, ul.Przełajowa 14 TEL.688-75-65';
	$nag.='<br>                                                 DNIA '.date('Y.m.d').', '.'GODZINA '.date('H:i');
	$nag.='<br>Zestawienie faktur korygujących VAT (punkt Nr 2)';
	$nag.='<br>wystawionych w okresie od '.$daty[0].' do '.$daty[1];
	$nag.='<br>"12CPI""17CPI"__________________________________________________________________________________________________________________________________________';
	$nag.='<br>|     | Numer doku |   Data   | Data wys |             |  Nazwisko i imię                       | Adres                                  |';
	$nag.='<br>|  Lp | mentu VAT  | wykonania| tawienia | NIP nabywcy |  lub nazwa nabywcy                     | nabywcy                                |';
	$nag.='<br>|     |____________|__________|__________|_____________|________________________________________|________________________________________|';
	$nag.='<br>|     | Brutto 22%  Netto 22%    VAT 22%     Brutto 7%   Netto 7%   VAT 7%  Brutto 0%  Brutto zw. Brutto razem   Netto razem   VAT razem |';
	$nag.='<br>|_____|____________|__________|__________|_____________|__________|_______|__________|__________|______________|_____________|___________|';

	echo '<font style="font-family: Courier">';
	$nag=str_replace('osoba_pu',$_SESSION['osoba_pu'],$nag);
	$nag=str_replace('"12CPI"',Chr(162),$nag);		//Chr(27).'M',$w);					//1
	$nag=str_replace('"17CPI"',Chr(136),$nag);		//Chr(15),$w);							//1

	echo $nag=str_replace(' ','&nbsp',$nag);
//	echo $nag=str_replace('-','÷',$nag);

	$lp=1;
	$j=0;
	$rr6=0;
	$rr7=0;
	$rr8=0;
	$rr9=0;
	$rr10=0;
	$rr11=0;
	$rr12=0;
	$rr13=0;
	$rr14=0;
	$rr15=0;
	$rr16=0;
	while ($r=mysql_fetch_row($w)) {
		$line='<br>|';
		$line.=sprintf("%' 5d",$lp++).'|';
		$line.=sprintf("%' 12s",$r[0]).'|';									//Nr
		$line.=sprintf("%' 10s",str_replace('-','.',$r[1])).'|';		//data
		$line.=sprintf("%' 10s",str_replace('-','.',$r[2])).'|';		//data
		$line.=sprintf("%' 13s",str_replace('-','÷',$r[3])).'|';		//NIP
		$line.=sprintf("%-40s",str_replace('-','÷',$r[4])).'|';		//Nazwa
		$line.=sprintf("%-40s",str_replace('-','÷',$r[5])).'|';		//Adres
		$line.='<br>|_____|';
		$line.=sprintf("%' 12.2f",$r[6]).'|';	//b22
		$line.=sprintf("%' 10.2f",$r[7]).'|';	//n22
		$line.=sprintf("%' 10.2f",$r[8]).'|';	//v22
		$line.=sprintf("%' 13.2f",$r[9]).'|';	//b7
		$line.=sprintf("%' 10.2f",$r[10]).'|';	//n7
		$line.=sprintf("%'  7.2f",$r[11]).'|';	//v7
		$line.=sprintf("%' 10.2f",$r[12]).'|';	//b0
		$line.=sprintf("%' 10.2f",$r[13]).'|';	//bzw
		$line.=sprintf("%' 14.2f",$r[14]).'|';	//br
		$line.=sprintf("%' 13.2f",$r[15]).'|';	//nr
		$line.=sprintf("%' 11.2f",$r[16]).'|';	//vr

		$rr6+=$r[6];
		$rr7+=$r[7];
		$rr8+=$r[8];
		$rr9+=$r[9];
		$rr10+=$r[10];
		$rr11+=$r[11];
		$rr12=$r[12];
		$rr13+=$r[13];
		$rr14+=$r[14];
		$rr15+=$r[15];
		$rr16+=$r[16];
		echo $line=str_replace(' ','&nbsp',$line);
	}
		$line='<br>==========================================================================================================================================';
		$line.='<br>Razem: ';
		$line.=sprintf("%' 12.2f",$rr6).'|';
		$line.=sprintf("%' 10.2f",$rr7).'|';
		$line.=sprintf("%' 10.2f",$rr8).'|';
		$line.=sprintf("%' 13.2f",$rr9).'|';
		$line.=sprintf("%' 10.2f",$rr10).'|';
		$line.=sprintf("%'  7.2f",$rr11).'|';
		$line.=sprintf("%' 10.2f",$rr12).'|';
		$line.=sprintf("%' 10.2f",$rr13).'|';
		$line.=sprintf("%' 14.2f",$rr14).'|';
		$line.=sprintf("%' 13.2f",$rr15).'|';
		$line.=sprintf("%' 11.2f",$rr16).'|';
		$line.=Chr(162);
		$line.=Chr(144);
		$line.='<br><br><br><br>.';
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
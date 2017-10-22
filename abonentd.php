<?php


$zbuttona=false;

if (!$ido) {
	$zbuttona=true;					// skrypt uruchamiany z buttona w podtabeli "d³ugi"
	session_start();
	$ido=$_SESSION['osoba_id'];
}

$raport='';
$ida=$ipole;							//ostatnio dopisany abonent

if ($zbuttona) {
	require('dbconnect.inc');
}

if ($ida==0) {							//po formularzu
	$z="Select ID from tabele where NAZWA='abonenci'";		//operacja tylko z tabeli "abonenci"
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
	$w=mysql_query($z); $w=mysql_fetch_row($w); $ida=$w[0];							//ostatnio u¿ytego
}

$z="select ID from abonenci order by ID desc limit 1";
$w=mysql_query($z); $w=mysql_fetch_row($w);

if ($ida==$w[0]) {	//œwie¿o dopisany abonent
	$z="update abonenci set ZABLOK=if($punkt=1,'','T'), STATUS='A' where ID=$ida limit 1";
	$z=str_replace('$punkt',$punkt,$z);
	$w=mysql_query($z);
}	//if ($ida==$w[0]) {	//œwie¿o dopisany abonent

$z="select * from abonenci where ID=$ida";							//obecne dane abonenta
$w=mysql_query($z); $w=mysql_fetch_array($w);
//$raport.=$z;

$z="select * from ulice where IDULICY='".$w['IDULICY']."'";
//$raport.=$z;
$u=mysql_query($z);
if ($u && mysql_num_rows($u)>0) {
	$u=mysql_fetch_array($u);						//ulica dok³adna
}
else {
	$z="select * from ulice where IDULICY like '".trim($w['IDULICY'])."%'";
//	$raport.=$z;
	if ($u=mysql_query($z)) {
		$u=mysql_fetch_array($u);						//ulica podobna
	
		$w['IDULICY']=$u['IDULICY'];					//lepszy IDULICY

		$z="update abonenci set IDULICY='".$u['IDULICY']." where ID=$ida";	//zapisz
		$z=str_replace('$punkt',$punkt,$z);
		$z=mysql_query($z);
	}
}

$z="select * from abonenci where ID<>$ida and IDULICY='".strtoupper($w['IDULICY'])."' and NRDOMU='".strtoupper($w['NRDOMU'])."' and length(KOD_F)=6 and length(NRBLOKU)>0";
//$raport.=$z;
$k=mysql_query($z);
if ($k && mysql_num_rows($k)>0) {
	$k=mysql_fetch_array($k);						//kod pocztowy w podobnym adresie
}
else {
$z="select * from abonenci where ID<>$ida and IDULICY like '".trim(strtoupper($w['IDULICY']))."%' and NRDOMU='".strtoupper($w['NRDOMU'])."' and length(KOD_F)=6 and length(NRBLOKU)>0";
//$raport.=$z;
	if ($k=mysql_query($z)) {
		$k=mysql_fetch_array($k);						//kod pocztowy w podobnym adresie
	}
}

function ZLTrim($s){
        $q='';
        $ok=true;
        for ($i=0;$i<strlen($s);$i++)
        {
            if ($ok && substr($s,$i,1)=='0') {
            }
            else {
                 $ok=false;
                 $q.=substr($s,$i,1);
            }
        }
        return $q;
}

$z="update abonenci set ";
$z.="IDULICY='".strtoupper($w['IDULICY'])."',";						//poprawki
$z.="NRDOMU='".strtoupper($w['NRDOMU'])."',";
$z.="NAZWISKO='".strtoupper($w['NAZWISKO'])."',";
$z.="IMIE='".strtoupper($w['IMIE'])."',";
$z.="RODZDOK='".strtoupper($w['RODZDOK'])."',";
$z.="SERIADOK='".strtoupper($w['SERIADOK'])."',";
$z.="NUMERDOK='".strtoupper($w['NUMERDOK'])."',";
$z.="TYPUMOWY='".strtoupper($w['TYPUMOWY'])."',";
if (!$w['NAZWA_F']) {	$z.="NAZWA_F='".trim(strtoupper($w['NAZWISKO'])).' '.strtoupper($w['IMIE'])."',";}
else {						$z.="NAZWA_F='".$w['NAZWA_F']."',";}
if (!$w['MIEJSC_F']) {	$z.="MIEJSC_F='".strtoupper($k['MIEJSC_F'])."',";}
else {						$z.="MIEJSC_F='".strtoupper($w['MIEJSC_F'])."',";}
if (!$w['KOD_F']) {		$z.="KOD_F='".strtoupper($k['KOD_F'])."',";}
else {						$z.="KOD_F='".strtoupper($w['KOD_F'])."',";}
if (!$w['NRBLOKU']) {	$z.="NRBLOKU='".strtoupper($k['NRBLOKU'])."',";}
else {						$z.="NRBLOKU='".strtoupper($w['NRBLOKU'])."',";}
if (!$w['ULICA_F']) {	$z.="ULICA_F='".trim(strtoupper($u['ULICA'])).' '.ZLTrim($w['NRDOMU']).'/'.ZLTrim($w['NRMIESZK'])."' ";}
else {						$z.="ULICA_F='".strtoupper($w['ULICA_F'])."' ";}
$z.=" where ID=$ida";
$w=mysql_query($z);
//$raport.=$z;

$z="Select count(*) from dlugi where IDABONENTA=$ida";	//s¹ d³ugi abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];		//ostatnio u¿ytego
//$raport.=$z;
if (!$w) {																//jak nie ma to dalej

$z="Select count(*) from oplaty where IDABONENTA=$ida";	//s¹ naniesione op³aty abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];		//ostatnio u¿ytego
//$raport.=$z.'='.$w;
if (!$w||$zbuttona) {												//jak nie ma lub z buttona to dalej

$z="Select * from abonenci where ID=$ida";					//s¹ dane abonenta
$w=mysql_query($z); $a=mysql_fetch_array($w);				//ostatnio u¿ytego
//$raport.=$z;

$tt=49;
$zt=5;
//if ($a['ZABLOK']=='T'||$a['ZABLOK']=='t') {					//niskie kody
//	$zt=100;
//}
//else {
//	$zt=1100;
//}

$dt=$a['DATAREAL'];

$z="Select * from typyumow where ZTYTULRAT='$zt' and TYPUMOWY='".$a['TYPUMOWY']."' and WALUTA='".$a['WALUTA']."' and DATAZAL<='$dt' order by DATAZAL desc limit 1";
$w=mysql_query($z); $w=mysql_fetch_array($w);
//$raport.=$z;

$rd=substr($dt,0,4)*1;		// '2006-04-11'  ->   '2006'*1 -> 2006
$md=substr($dt,5,2)*1;		// '2006-04-11'  ->   '04'*1   ->    4
$dd=substr($dt,8,2)*1;		// '2006-04-11'  ->   '11'*1   ->   11
$di=0;

if ($w['WYS1WPL']>0) {
	if ($w['TERM1WPL']==0) {$da=$dt;}
	else {$da=substr($dt,0,8).sprintf("%'02d",$w['TERM1WPL']);}		// '2006-04-11'  ->   '2006-04-'
$di++;
$zz='insert into `dlugi` set ';							// zapis w "dlugi", rata 1
$zz.="`IDABONENTA`='".$ida."',";
$zz.="`TYPTYTULU`='".$tt."',";
$zz.="`ZTYTULU`='".$zt."',";
$zz.="`DODNIA`='$da',";
$zz.="`KWOTA`='".$w['WYS1WPL']."',";
$zz.="`NRFAKTURY`='',";
$zz.="`NRPOZYCJI`='',";
$zz.="`WALUTA`='".$w['WALUTA']."',";
$zz.="`NRRATY`='$di'";
$zz=mysql_query($zz);
}

if ($w['WYS2WPL']>0) {
	$md++;							// 5, bo druga rata
	if ($md>12) {$md=1; $rd++;}
	if ($w['TERM2WPL']==0) {$dd=1;} else {$dd=$w['TERM2WPL'];}
	$da=sprintf("%'04d",$rd);
	$da.='-'.sprintf("%'02d",$md);
	$da.='-'.sprintf("%'02d",$dd);

$di++;
$zz='insert into `dlugi` set ';							// zapis w "dlugi", rata 2
$zz.="`IDABONENTA`='".$ida."',";
$zz.="`TYPTYTULU`='".$tt."',";
$zz.="`ZTYTULU`='".$zt."',";
$zz.="`DODNIA`='$da',";
$zz.="`KWOTA`='".$w['WYS2WPL']."',";
$zz.="`NRFAKTURY`='',";
$zz.="`NRPOZYCJI`='',";
$zz.="`WALUTA`='".$w['WALUTA']."',";
$zz.="`NRRATY`='$di'";
$zz=mysql_query($zz);
}

for($i=1;$i<=$w['ILOSCRAT'];$i++) {
	$md++;							// 5, bo druga rata
	if ($md>12) {$md=1; $rd++;}
	if ($w['PLRATDO']==0) {$dd=1;} else {$dd=$w['PLRATDO'];}
	$da=sprintf("%'04d",$rd);
	$da.='-'.sprintf("%'02d",$md);
	$da.='-'.sprintf("%'02d",$dd);

$di++;
$zz='insert into `dlugi` set ';							// zapis w "dlugi", rata 2
$zz.="`IDABONENTA`='".$ida."',";
$zz.="`TYPTYTULU`='".$tt."',";
$zz.="`ZTYTULU`='".$zt."',";
$zz.="`DODNIA`='$da',";
$zz.="`KWOTA`='".$w['WYSRATY']."',";
$zz.="`NRFAKTURY`='',";
$zz.="`NRPOZYCJI`='',";
$zz.="`WALUTA`='".$w['WALUTA']."',";
$zz.="`NRRATY`='$di'";
$zz=mysql_query($zz);
}

//CREATE TABLE dlugi (
//ID int(11) NOT NULL auto_increment,
//IDABONENTA int(11) default 0,
//TYPTYTULU int(11) default 0,
//ZTYTULU int(11) default 0,
//DODNIA date default NULL,
//KWOTA float(9,2) NOT NULL default 0,
//NRFAKTURY char(6) default '',
//NRPOZYCJI char(3) default '',
//WALUTA char(3) default '',
//NRRATY int(2) default 0,
//PRIMARY KEY (ID)
//) TYPE=MyISAM;
//GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON dlugi TO 'guest' IDENTIFIED BY '123';

$tabelaa='specopl';	// tu l¹duje po akcji

$z="Select count(*) from oplaty where IDABONENTA=$ida";	//s¹ naniesione op³aty abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];		//ostatnio u¿ytego
//$raport.=$z.'='.$w;

if (!$w) {																//jak nie ma to dalej
	include('oplatyzakaw.php');
}
}
}

if ($raport) {
	echo $raport;
	exit;
}

if ($zbuttona) {
	require('dbdisconnect.inc');
?>
</title>
</head>
<?php
echo "<body bgcolor='#0F4F9F' onload=";
echo '"location.href=';
echo "'Tabela.php?tabela=";
echo $_POST['natab'];
echo "'\">";
//echo $raport;
//echo "<br><hr><a href='Tabela.php?tabela=";
//echo $_POST['natab'];
//echo "'>Powrót</a>";
?>
</body>
</html>
<?php
}
?>

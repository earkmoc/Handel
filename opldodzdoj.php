<?php

$raport='';							//op³aty jednorazowe (jednorazowe us³ugi)

//opldodg
//C1|Typ op³aty|7|
//DATAOPERR|Nazwa typu op³aty|50|style="color:blue"|
//INFO|Typ i waluta|10|
//DATAOPER|Od dnia|+10|
//from opldodg
//where ID=

$z="Select ID from tabele where NAZWA='abonenci'";		//operacja tylko z tabeli "abonenci"
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $ida=$w[0];							//ostatnio u¿ytego

$z="update opldodg set IDABONENTA=$ida, TYPOPER='v', ID_OSOBYUPR=$ido, CZAS=Now() where ID=$ipole limit 1";
$w=mysql_query($z);

//$z="select ZABLOK from abonenci where ID=$ida limit 1";
//$w=mysql_query($z); $w=mysql_fetch_row($w); $nik=$w[0];							//Niskie kody ?

$z="Select * from opldodg where ID=$ipole";
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$raport.=$z;

//if ((!($nik==='T')&&($w['C1']*1)>999) {
if ($w['C1']>999) {
	$w['C1']=$w['C1']-1000;
}

if ($w['DATAOPER']<date('Y-m-d')) {
	$w['DATAOPER']=date('Y-m-d');
}

$wi=chr($w['C1']).' '.$w['INFO'];		// ê_1A
$dt=$w['DATAOPER'];

$z='insert into `opldod` set ';
$z.="`IDABONENTA`='$ida',";
$z.="`TYPOPER`='".$w['TYPOPER']."',";
if (chr($w['C1'])==="'") {
$z.='`INFO`="'.$wi.'",';}
else {
$z.="`INFO`='".$wi."',";}
$z.="`DATAOPER`='$dt',";
$z.="`C1`='".ord(substr($wi,0,1))."',";
$z.="`C2`='".ord(substr($wi,1,1))."',";
$z.="`C3`='".ord(substr($wi,2,1))."',";
$z.="`C4`='".ord(substr($wi,3,1))."',";
$z.="`C5`='".ord(substr($wi,4,1))."' ";
$ww=mysql_query($z);

$raport.=$z;

$ww=mysql_insert_id();

$z="Select ID from tabele where NAZWA='typoplatoj'";		//typ wybrany z tabeli
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID typu op³.
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytej

$z="Select TYPTYTULU, ID from typoplat where ID=$w";				//jest reszta
$w=mysql_query($z); $w=mysql_fetch_row($w); $tt=$w[0]; $zt=$w[1];

$z="Select ID from tabele where NAZWA='typyumowoj'";		//typ wybrany z tabeli
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID typu op³.
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];								//ostatnio u¿ytej

$z="Select * from typyumow where ID=$w";				//jest reszta
$w=mysql_query($z); $w=mysql_fetch_array($w);

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

$z="update opldodg set ID_OPLDOD=$ww where ID=$ipole limit 1";
$ww=mysql_query($z);

$tabelaa='opldodA';	// tu l¹duje po akcji

//echo $raport;
?>
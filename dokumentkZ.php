<?php

//zamkniêcie dokumentu i przepisanie pozycji do dziennika g³ównego

$ntab_master=$_SESSION['ntab_mast'];

$w=mysql_query("select ID from tabele where NAZWA='$ntab_master'"); $w=mysql_fetch_row($w); $w=$w[0];
$w=mysql_query("select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$osoba_id limit 1"); $w=mysql_fetch_row($w); 
$idd=$w[0];

$w=mysql_query("select * from dokumenty where ID=$idd"); $w=mysql_fetch_array($w); 

if ($w['GDZIE']=='bufor') {

$dk=$w['PK_DOK'];
$nr=$w['PK_NR'];
$dt=$w['DWPROWADZE'];
$lp=$w['LP'];
$do=($w['TYP']).' '.($w['NUMER']);
$tr=$w['PRZEDMIOT'];
$dt2=$w['DDOKUMENTU'];

if ($dt*1==0) {	//nie ma daty wprowadzenia
	$w=mysql_query("select CurDate()"); $w=mysql_fetch_row($w);
	$dt=$w[0];
}

if ($nr==0) {	//nastêpny numer tego typu
	$w=mysql_query("select NR from dnordpol where DOK like '$dk%'"); $w=mysql_fetch_row($w);
	$nr=$w[0]+1;
	$w=mysql_query("update dnordpol set NR='$nr', DATA='$dt' where DOK='$dk' limit 1");
	$nr=$nr-1;
}

$pz=0;
$w=mysql_query("select * from dokumentk where ID_D=$idd order by ID");
while ($r=mysql_fetch_array($w)) {
	$id=$r['ID'];
	$wa=$r['WINIEN'];
	$wn=$r['KONTOWN'];
	$ma=$r['KONTOMA'];
	$op=$r['PRZEDMIOT'];
	$pz++;
 	mysql_query("insert into nordpol values (0, '$dk', '$nr', '$dt', '$lp', '$pz', '$wa', '$wn', '$ma', '$do', '$tr', '$dt2', '$op', '', $osoba_id, Now())");
}

mysql_query("update dokumenty set GDZIE='ksiêgi', PK_NR='$nr', DWPROWADZE='$dt' where ID=$idd");

}

//CREATE TABLE nordpol (
//ID int(11) NOT NULL auto_increment,
//DOK char(8) not null default '',
//NR int(4) not null default 0,
//DATA date default NULL,
//LP int(4) not null default 0,
//PZ int(4) not null default 0,
//KWOTA float(15,2) not null default 0,
//WINIEN char(18) not null default '',
//MA char(18) not null default '',
//NAZ1 char(50) not null default '',
//NAZ2 char(50) not null default '',
//DATA2 date default NULL,
//OPIS char(50) not null default '',
//ZNAK char(1) not null default '',
//KTO int(11) not null default 0
//CZAS datetime

//CREATE TABLE dokumentk (
//ID int(11) NOT NULL auto_increment,
//ID_D int(11) not null default 0,
//KTO int(11) not null default 0,
//CZAS datetime not null default '',
//PRZEDMIOT char(30) not null default '',
//WINIEN numeric(11,2) not null default 0,
//MA numeric(11,2) not null default 0,
//KONTOWN char(18) not null default '',
//KONTOMA char(18) not null default '',
//PRIMARY KEY (ID)
//) TYPE=MyISAM;

?>
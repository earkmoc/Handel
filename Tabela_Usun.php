<?php
$r=$_GET['r'];
$c=$_GET['c'];
$str=$_GET['str'];
$baza=$_GET['baza'];
$tabela=$_GET['tabela'];

header("Location: Tabela.php?tabela=$tabela"); 

$tabsub=$_GET['tabsub'];
$tabpole=$_GET['tabpole'];

$id=$_GET['ID'];

$phpini=$_GET['phpini'];

require('dbconnect.inc');

//********************************************************************
// zapamiêtaj stan tabeli dla zalogowanej osoby

$ido=$_SESSION['osoba_id'];
$idt=$_POST['idtab'];
if ($ido) {
	if (!$idt) {
   	$w=mysql_query("select ID from tabele where NAZWA='$tabela'"); $w=mysql_fetch_row($w);
   	$idt=$w[0];
	}
	$ipole=$_POST['ipole'];
	$ipole=$id;
	$w=mysql_query("select count(*) from tabeles where ID_TABELE=$idt and ID_OSOBY=$ido"); $w=mysql_fetch_row($w);
	if ($w[0]>0) 	{
      $w=mysql_query(     "update tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c where ID_TABELE=$idt and ID_OSOBY=$ido limit 1");
	} else {
      $w=mysql_query("Insert into tabeles set ID_POZYCJI=$ipole,NR_STR=$str,NR_ROW=$r,NR_COL=$c,ID_TABELE=$idt,ID_OSOBY=$ido");
   }
}

// zapamiêtaj stan tabeli dla zalogowanej osoby
//********************************************************************

$z="delete from $baza where ID=$id";
$w=mysql_query($z);
if ($w) {
	if ($tabsub) {
		$z="delete from $tabsub where $tabpole=$id";
		$w=mysql_query($z);
	}

	if ($phpini&&$phpini<>'undefined') {
		$ipole=0;	 	//usuwanie !!!
		include($phpini);
	}
}
if (!$w) echo "$z<br  /><br  />niestety nie wysz³o !!!";

require('dbdisconnect.inc');
?>
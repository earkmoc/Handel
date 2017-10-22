<?php

//require('Tabela_Save_Stan.php');

$warunek="";
$sortowanie="";

if ($_SESSION['osoba_upr']&&$_POST['ipole']) {

$z='Select ID, WARUNKI, SORTOWANIE, MX_POZYCJI from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=abs($_POST['idtab']);
$z.=' limit 1';

$w=mysql_query($z);
if ($w) {
        if (mysql_num_rows($w)>0) {

		$w=mysql_fetch_array($w);

		if (!$_GET['maxrow']) {$_GET['maxrow']=$w['MX_POZYCJI'];}

		$warunek=StripSlashes($w['WARUNKI']);
		$sortowanie=StripSlashes($w['SORTOWANIE']);
		$idtabeles=$w['ID'];

                $z='Update tabeles';
                $z.=' set NR_STR=';
                $z.=$_POST['strpole'];
                $z.=', NR_ROW=';
                $z.=$_POST['rpole'];
                $z.=', NR_COL=';
                $z.=$_POST['cpole'];
                $z.=', ID_POZYCJI=';
                $z.=$_POST['ipole'];
                $z.=', OX_POZYCJI=';
                $z.=$_POST['offsetX'];
                $z.=', OY_POZYCJI=';
                $z.=$_POST['offsetY'];
                $z.=', MX_POZYCJI=';
                $z.=$_GET['maxrow'];
                $z.=' where ID=';
                $z.=$idtabeles;
	        $w=mysql_query($z);
        }
        else {

		if (!$_GET['maxrow']) {$_GET['maxrow']=0;}

                $z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL,OX_POZYCJI,OY_POZYCJI,MX_POZYCJI) values (';
                $z.=$_SESSION['osoba_id'];
                $z.=',';
                $z.=abs($_POST['idtab']);
                $z.=',';
                $z.=$_POST['ipole'];
                $z.=',';
                $z.=$_POST['strpole'];
                $z.=',';
                $z.=$_POST['rpole'];
                $z.=',';
                $z.=$_POST['cpole'];
                $z.=',';
                $z.=$_POST['offsetX'];
                $z.=',';
                $z.=$_POST['offsetY'];
                $z.=',';
                $z.=$_GET['maxrow'];
                $z.=')';
	        $w=mysql_query($z);
        	$idtabeles=mysql_insert_id();
        }
}}

?>
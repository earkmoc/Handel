<?php

//********************************************************************
// zapamiêtaj stan tabeli dla zalogowanej osoby
// gdy nie suwanie po tabeli i zalogowany i przed chwil± by³ w tabeli

if ($_SESSION['osoba_id']&&$_POST['ipole']) {

$z='Select ID from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$_POST['idtab'];

$w=mysql_query($z);
if (!$w) {exit;}
else {
        if (mysql_num_rows($w)>0) {

                $w=mysql_fetch_array($w);

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
                $z.=' where ID=';
                $z.=$w['ID'];
        }
        else {
                $z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL,OX_POZYCJI,OY_POZYCJI) values (';
                $z.=$_SESSION['osoba_id'];
                $z.=',';
                $z.=$_POST['idtab'];
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
                $z.=')';
        }
        $w=mysql_query($z);
}}

// zapamiêtaj stan tabeli dla zalogowanej osoby
//********************************************************************

?>
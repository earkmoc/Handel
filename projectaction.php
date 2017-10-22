<?php

$natab=($_GET['natab']?$_GET['natab']:$_POST['natab']);
session_start();
//require('skladuj_zmienne.php');exit;
$ido=$_SESSION['osoba_id'];
require('dbconnect.inc');
require($natab."_kod.php");

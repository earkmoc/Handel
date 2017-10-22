<?php

session_start();

//require('skladuj_zmienne.php');

//$zaznaczone=$_POST['zaznaczone'];
//if (!$zaznaczone) {
	$zaznaczone=$_POST['ipole'];
//}

if ($_GET['zaznaczone']) {$zaznaczone=$_GET['zaznaczone'];}

$natab=$_POST['natab'];
if ($_GET['natab']) {$natab=$_GET['natab'];}

if ($_GET['sure']) {$sure=$_GET['sure'];}
if ($_GET['dopisek']) {$dopisek=$_GET['dopisek'];}

require('dbconnect.inc');
require_once("class.phpmailer.php");
//require 'PHPMailer/PHPMailerAutoload.php';
require("WydrukWzorMail.php");

$z=("
   select 
	       dokum.ID
	      ,firmy.EMAIL
	      ,firmy.EMAIL2
	      ,firmy.EMAIL3
	      ,concat(doktypy.NAZWA, ' Nr ',dokum.INDEKS)
	      ,dokum.TYP
     from dokum 
left join firmy
	    on firmy.ID=dokum.NABYWCA	
left join doktypy
	    on doktypy.TYP=dokum.TYP	
    where dokum.ID=$zaznaczone
");

//echo $z;die;
//    where Find_In_Set(dokum.ID,'$zaznaczone')

$w=mysql_query($z); 

$sure=$sure*1;
$sure=($sure<1?0:$sure);
$sure=($sure>3?0:$sure);

if ($sure) {

   while ($r=mysql_fetch_row($w)) {
   
      $adr_mail=$r[$sure];
/*
//-------------------------------------------------------------------------

      $mail = new PHPMailer;
      
      $mail->isSMTP();  
      $mail->Host = 'mail.parrot-line.pl';  
      $mail->SMTPAuth = true;  
      $mail->Username = 'parrot@parrot-line.pl'; 
      $mail->Password = 'bomo6jemi'; 
      //$mail->SMTPSecure = 'tls';
      
      $mail->From = 'parrot@parrot-line.pl';
      $mail->FromName = 'Biuro Parrot-Line';
      $mail->addAddress('Arkadiusz.Moch@gmail.com');
      
      $mail->isHTML(true);
      
      $mail->Subject = 'Parrot test Arka 2';
      $mail->Body    = 'test Arka 2';
      
      if(!$mail->send()) {
         echo 'Message could not be sent.';
         echo 'Mailer Error: ' . $mail->ErrorInfo;
         exit;
      }
      
      echo 'Message has been sent';
      die;

//-------------------------------------------------------------------------
*/
   	$mail = new PHPMailer();
   	
   	$mail->SMTPAuth = true;

//   	$mail->Username = 'biuro@parrot-line.pl'; 
   	$mail->Username = 'parrot@parrot-line.pl'; 

//   	$mail->Password = 'VF8VlCF6Px0E';
   	$mail->Password = 'bomo6jemi';
   	
   	$mail->isSMTP();
   	$mail->isHTML(true);
   	$mail->CharSet 	= "iso-8859-2";
   	
   	$mail->Host 	= "mail.parrot-line.pl";
//   	$mail->Hostname = "Biuro Parrot-Line";

//   	$mail->From 	= "biuro@parrot-line.pl";
   	$mail->From 	= "parrot@parrot-line.pl";
   	$mail->FromName = "Biuro Parrot-Line";
   	
   	//$mail->Sender 	= "biuro@parrot-line.pl";
   	//$mail->AddReplyTo("biuro@parrot-line.pl","Biuro Parrot-Line");
   	
   	$mail->AddAddress('parrot@parrot-line.pl');
//   	$mail->AddAddress('Arkadiusz.Moch@gmail.com');
   	if ($adr_mail) {
   		$mail->AddAddress($adr_mail);
   	}
   
   	$mail->Subject = $r[4];

      $typ=$r[5];
      $ww=mysql_query("
         select WZORWYDR
           from doktypy
          where TYP='$typ'
      ");
      if ($rr=mysql_fetch_row($ww)) {
         $wzor=$rr[0];
      }

   	if ($typ=='ZAM') {
         $wzor='Mail';
      }

   	$mail->Body=WydrukWzorMail($r[0],$_SESSION['osoba_id'],$_SESSION['osoba_pu'],$_SESSION['osoba_upr'],$wzor);

      if ($dopisek) {
         $mail->Body=str_replace("Pozdrawiam","Dodatkowo:<br>$dopisek<br><br>--<br>Pozdrawiam",$mail->Body);
      }
   
      if ($adr_mail) {
      	$mail->send();
      	mysql_query("
      		update dokum
      		   set UWAGI=concat('mail: $adr_mail ".date('Y-m-d H:m:s').", ',trim(UWAGI))
      		 where ID=$zaznaczone
      	");
      }
   }
} else {
   $mail='';
   $n=mysql_num_rows($w);
   while ($r=mysql_fetch_array($w)) {
      $mail1=$r[1];
      $mail2=$r[2];
      $mail3=$r[3];

      $typ=$r[5];
      $ww=mysql_query("
         select WZORWYDR
           from doktypy
          where TYP='$typ'
      ");
      if ($rr=mysql_fetch_row($ww)) {
         $wzor=$rr[0];
      }

   	if ($typ=='ZAM') {
         $wzor='Mail';
      }

      $mail.=WydrukWzorMail($r[0],$_SESSION['osoba_id'],$_SESSION['osoba_pu'],$_SESSION['osoba_upr'],$wzor);
   }
}

require('dbdisconnect.inc');
?>
<html lang="pl" xml:lang="pl" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />

<style type="text/css">
div {
	font : 13px "Courier New" , Courier, mono;
	border : 1px dashed #D7C3C3;
	background: #f8f4f1;
	padding: 8px;
}
</style>

<script type="text/javascript" language="JavaScript">
<!--
function pytanie() {
   $odp=document.getElementById('odp').value;
   $dopisek=document.getElementById('dopisek').value;
   if ($odp!="") {
      <?php echo 'location.href="mail.php?natab='.$natab.'&zaznaczone='.$zaznaczone.'&sure="+$odp+"&dopisek="+$dopisek;';?>
   };
}
function klawisz($skok) {
   if (event.keyCode==13) {
      pytanie();
//      formularz.ok.click();
      return false;
   }
   if (event.keyCode==27) {
      formularz.sio.click();
      return false;
   }
}
document.onkeypress=klawisz;
document.onkeydown=klawisz;
-->
</script>

<?php
if ($sure) {
   echo "<title>Wykonanie SQL</title></head><body bgcolor='#0F4F9F' ";
   echo "onload='";
   echo 'location.href="Tabela.php?tabela='.$natab.'"';
   echo '\'>';
} else {
   echo "<title>Wysy³anie e-maila</title></head><body bgcolor='white' onload='formularz.odp.focus();'>";
   echo "<form id='formularz'>";
   echo "<font size=20>Wybierz adres e-mail wpisuj±c 1, 2 lub 3</font><hr>";
   $nbsp='&'.'nbsp;';
   echo "<br>$nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp 1. $mail1";
   echo "<br>$nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp 2. $mail2";
   echo "<br>$nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp $nbsp 3. $mail3";
   echo "<br>Wybór : <input id='odp' value='' size='1' />";
   echo "<input type='button' id='ok' value=' Enter = OK' onclick='pytanie()' />";
   echo "<input type='button' id='sio' value=' Esc = Anuluj ' onclick='location.href=\"Tabela.php?tabela=$natab\"' />";
   echo "<br><br>Dodatkowe informacje, np. towary, których nie ma w magazynie:<br>";
   echo "<textarea rows=5 cols=150 id='dopisek'>$dopisek</textarea>";
   echo "</form>";
   echo "<hr><font size=20>Tre¶æ e-maila:</font>";
   echo "<br><div>$mail</div>";
}
echo '</body></html>';
exit;
?>
<?php
session_start();
$ido=$_SESSION['osoba_id'];
$dokum_tab=$_GET['dokum_tab'];
$spec_tab=$_GET['spec_tab'];
?>
<html>
<head>

<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=ISO-8859-2">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />

<title>Termin<?php for($i=0;$i<80;$i++) {echo '&nbsp;';}?></title>

<script type="text/javascript" language="JavaScript">
<!--
function sio(){
	if (event.keyCode==27) {close();};
}
document.onkeypress=sio;
-->
</script>

</head>

<body bgcolor="#EFEFCF" onload="formularz.termin.select();formularz.termin.focus();" >

<form id="formularz" action="TerminZapisz.php" method="post">
Podaj ilo�� dni terminu: <input name="termin" type="text" size="2" value="14" />
<input name="dokum_tab" type="hidden" value="<?php echo $dokum_tab;?>" />
<input name="spec_tab" type="hidden" value="<?php echo $spec_tab;?>" />
<br><br>
<table width="100%" border=0>
<tr align="center">
<td>
<input id="nie" type="reset" value="Esc=Anuluj" onclick="close();">
</td>
<td>
<input type="submit" value="  OK=Enter ">
</td>
</tr>
</table>
</form>

</body>
</html>
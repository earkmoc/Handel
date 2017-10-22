<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" xml:lang="pl">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=iso-8859-2">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />

<title>Test</title>

<script type="text/javascript" src="advajax.js"></script>
<script type="text/javascript" language="JavaScript">
<!--
function AjaxGetData(ob) {
$w=ob.value;
advAJAX.get({
    url : "te3.php?ida="+$w,
    onSuccess : function(obj) {
                  s=obj.responseText;
                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("nazwisko").value = ss; 
                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("imie").value = ss;
                  f0.ida.select(); 
                }
});
}
-->
</script>

</head>

<body bgcolor="#0F4F9F" onload="f0.ida.focus()">

<?php
  $ida=58503;
  require('dbconnect.inc');
  $q="select NAZWISKO, IMIE from abonenci where ID=$ida";
  $w=mysql_query($q);
  $r=mysql_fetch_row($w);
  echo $ida.'='.$r[0].'|'.$r[1].'|';
?>

<form id="f0" action="te0.php" method="post">
<?php
	echo '<input name="ida" value="58506" onchange="AjaxGetData(this)"/><br>';
	echo '<input name="nazwisko" value="..." onfocus="f0.imie.focus()"/><br>';
	echo '<input name="imie" value="..." /><br><br><br>';
	echo '<input type="submit" value="OK"/>';
?>
</form>

</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=iso-8859-2">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />
<META name="page-topic" content="TEST" >
<META NAME="DESCRIPTION" CONTENT="OPIS">
<title>SLAVE</title>

<style type="text/css">
<!--
body {font: normal 34pt arial}
tr {font: normal 10pt arial}
-->
</style>

<script type="text/javascript" language="JavaScript">
<!--

function MasterScroll(){
var frames = window.parent.frames;
var framesets = window.parent.document.getElementsByTagName("frameset");
var slavetr = frames[1].document.getElementsByTagName("tr");
x=slavetr[0].clientHeight+7;
//frames[2].document.write(frames[1].document.body.scrollTop+', '+x+'. ');
if (frames[1].document.body.scrollTop<40 || frames[1].document.body.scrollTop==x) {
	framesets[0].setAttribute("rows",'0,*,30');
	frames[1].document.body.scrollTop=0;
}
else {
	framesets[0].setAttribute("rows",x+',*,30');
	if (frames[1].document.body.scrollTop==40) {frames[1].document.body.scrollTop=1.5*x;}
}
//frames[2].document.write(frames[1].document.body.scrollTop+', ');
//frames[2].document.write(x+', ');
frames[0].document.body.scrollLeft=document.body.scrollLeft;
frames[1].focus();
}

document.onscroll=MasterScroll;

function Akcja() {
var frames = window.parent.frames;
var framesets = window.parent.document.getElementsByTagName("frameset");
var slavetr = frames[1].document.getElementsByTagName("tr");
x=slavetr[0].clientHeight+7;
framesets[0].setAttribute("rows",x+',*,30');
MasterScroll();
}
-->
</script>

</head>
<body bgcolor="#0F4F9F" onload="Akcja();">
<table  bgcolor="white" border=1 cellpadding=5 cellspacing=0>

<?php
$tyt[0]='L.P.';
$tyt[1]='Masa próbki';
$tyt[2]='Nazwa';
$tyt[3]='Ilość';
$tyt[4]='Cena';
$tyt[5]='Wartość netto';
$tyt[6]='Podatek VAT';
$tyt[7]='Wartość brutto';
$tyt[8]='Marża procentowa';
$tyt[9]='Marża kwotowa';
$tyt[10]='Zysk procentowy';
$tyt[11]='Zysk kwotowy';
$tyt[12]='Nazwa';
//$tyt[13]='Ilość';
//$tyt[14]='Cena';
//$tyt[15]='Wartość netto';
//$tyt[16]='Podatek VAT';
//$tyt[17]='Wartość brutto';
//$tyt[18]='Marża procentowa';
//$tyt[19]='Marża kwotowa';
echo '<tr bgcolor="#EFEFDF">';
for ($i=0;$i<count($tyt);$i++) {echo "<td ".(($i==1)?'bgcolor="#FF6600"':'')."><b>".($tyt[$i])."</b></td>";}
echo "</tr>";
?>

<?php
for ($i=1;$i<=130;$i++) {
echo "<tr ".(($i==1)?'bgcolor="#FFCC66"':'').">";
echo "<td>$i</td>";
for ($j=1;$j<count($tyt);$j++) {echo "<td nowrap>".($tyt[$j])."</td>";}
echo "</tr>";
}
?>

</table>
</body>
</html>

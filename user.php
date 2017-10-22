<html><head>
<LINK href="images/style.css" type=text/css rel=StyleSheet>
<title></title>
<body bgcolor="#7b91ac">
<?php 
$x=0;
$myDirectory = dir("\usr\danuta6brukowa341\www\.");
while($entryName = $myDirectory->read())
{
	if(($entryName != ".htaccess") && ($entryName != "istat") && ($entryName != "admin1") && ($entryName != ".") && ($entryName != ".."))
		{
		$pliki=$entryName;
		$pliki2=$entryName;
		if((!eregi("\.[A-Za-z]{1,4}$",$pliki)) && (!eregi("\.[A-Za-z]{1,3}$",$pliki)) && (!eregi("^\.[A-Za-z]",$pliki2)))
			{
			$tablica[$x++]=strtolower($entryName);
			}
		}
}
if ($tablica)
	{
	sort($tablica);
	for ($index=0; $index <count($tablica); $index++)
		{
		$bez_spacji=ereg_replace(" ","%20",$tablica[$index]);
		print("<IMG height=7 src=images/dot.gif width=7>&nbsp;<a href=http://".$_SERVER["HTTP_HOST"]."/~".$bez_spacji."/ target=_blank>".ucfirst($tablica[$index])."</a><br>");
		}
	}
$myDirectory->close();
?></body></html>

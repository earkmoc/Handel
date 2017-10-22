<?php

$k=0;
$s='';
$ss='';
$sx='';
$sss='';
$id='';
$ipole=abs($ipole);
$z="Select NAZWA from tabele where ID=$ipole limit 1"; $w=mysql_query($z); $w=mysql_fetch_row($w); $nazwa=$w[0];
$s=$nazwa."<br>";
$sss=$nazwa."<br>";
if (!$w=mysql_query("show fields from $nazwa")) {
   $nazwa=substr($nazwa,0,strlen($nazwa)-1);
   $w=mysql_query("show fields from $nazwa");
}	
echo "create table $nazwa (<br>";
//nazwy pól w "Field", potem "Type (int(11))", "Null (YES)", "Key (PRI)", "Default", "Extra (auto_increment)"
while ($r=mysql_fetch_row($w)) {
	if (++$k>1) {
		echo ",<br>";
	}
	$n=count($r);
	for ($i=0;$i<$n;$i++) {
		if ($i==2) {
			echo (($r[$i]=='YES')?'':' not null');
		} else if ($i==3) {
			echo (($r[$i]=='PRI')?' primary key':'');
		} else if ($i==4 && !($r[1]=='text')) {
			echo ((($r[$i]==='')?" default ''":" default ".(($r[$i]==NULL)?'null':"'".($r[$i])."'")));
		} else {
			echo (($i==0)?'`':' ').$r[$i].(($i==0)?'`':'');
		}
	}
	if (!$id) {$id=$r[0];}
	$x=explode('(',$r[1]);
	$x=$x[1]*1;
	$s.=$r[0].'|'.$r[0].'|'.$x.'<br>';
	$sss.=$r[0].'|'.$r[0].'<br>';
	$ss.=$r[0].', ';
	$sx.="'".$r[0]."', ";
}
echo "<br>) ENGINE=MyISAM DEFAULT CHARSET=latin2;<br>";
echo "<br><br><br>$ss";
echo "<br><br><br>$sx";
$sss.='from';
echo "<br><br><br>$sss $nazwa";
$s.='from';
echo "<br><br><br>$s $nazwa<br>where $id=";
$ok=false;
?>

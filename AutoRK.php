<?php
//<2009-12-27 renumeracja numerów ewidencyjnych>

//automatyczne przeliczenie stanów pocz/koñcowych raportów kasowych

$ii=0;
$sp=0;
$sk=0;

//require('skladuj_zmienne.php');
//require('skladuj_zmienna.php');

if ($ipole<0) {$ipole=-$ipole;}

$zz="select TYP, left(OKRES,4) from dokumenty where ID=$ipole";$ww=mysql_query($zz);$ww=mysql_fetch_row($ww);
$typ=$ww[0];
$okr=$ww[1];

//$komunikat.="select ID, STANPOCZ, PRZYCHODY, ROZCHODY,STANKONC from dokumenty where TYP like 'RK%' order by 1*dokumenty.LP asc<br>";
//<2009-12-27
//$zz="select ID, STANPOCZ, PRZYCHODY, ROZCHODY, STANKONC     from dokumenty where TYP='$typ' and left(OKRES,4)='$okr' order by DDOKUMENTU, dokumenty.LP*1 asc";
  $zz="select ID, STANPOCZ, PRZYCHODY, ROZCHODY, STANKONC, LP from dokumenty where TYP='$typ' and left(OKRES,4)='$okr' order by DDOKUMENTU, dokumenty.LP*1, dokumenty.ID";
//2009-12-27>
$ww=mysql_query($zz);
while ($rr=mysql_fetch_array($ww)) {
	$ii++;
//<2009-12-27
//	if ($ii==1) {$sp=$rr['STANPOCZ'];}               else {$sp=$sk;}
	if ($ii==1) {$sp=$rr['STANPOCZ'];$lp=$rr['LP'];} else {$sp=$sk;$lp++;}
//2009-12-27>
	$sk=$sp+$rr['PRZYCHODY']-$rr['ROZCHODY'];
	$id_d=$rr['ID'];
//<2009-12-27
//	$zzz="select sum(PRZYCHOD), sum(ROZCHOD), sum(if(PRZYCHOD<>0,1,0)), sum(if(ROZCHOD<>0,1,0)) from dokumentbk where ID_D=$id_d"; 
	$zzz=("select sum(PRZYCHOD), 
	              sum(ROZCHOD), 
	              sum(if(PRZYCHOD<>0,1,0)), 
                 sum(if(ROZCHOD<>0,1,0)) 
            from dokumentbk 
           where ID_D=$id_d
         "); 
//2009-12-27>
	$www=mysql_query($zzz); $www=mysql_fetch_row($www);
	$ww3=$www[3]*1;
	$ww2=$www[2]*1;
//<2009-12-27
//	mysql_query("update dokumenty set           STANPOCZ='$sp', STANKONC='$sk', ILEDOKPLUS=$ww2, ILEDOKMINU=$ww3 where ID=$id_d limit 1;");
	mysql_query("update dokumenty set LP='$lp', STANPOCZ='$sp', STANKONC='$sk', ILEDOKPLUS=$ww2, ILEDOKMINU=$ww3 where ID=$id_d limit 1;");
//2009-12-27>
}
//$ok=false;
?>
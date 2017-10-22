<?php

$zz="select ID from tabele where NAZWA='typyoplAT'"; $ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$zz="select ID_POZYCJI from tabeles where ID_OSOBY=".$ido." and ID_TABELE=".$ww[0];
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);
$idt=$ww[0];

$ids_typoplat=explode(',',$zaznaczone);
for($i=0;$i<count($ids_typoplat);$i++) {
 	$zz="select ID, TYPTYTULU from typoplat where ID=".$ids_typoplat[$i];
	$ww=mysql_query($zz);
	$ww=mysql_fetch_row($ww);

	$zz="update typyopl set ZTYTULU0".$i."=".$ww[0].", TYPTYTUL0".$i."=".$ww[1]." where ID=".$idt. " limit 1";
	$ww=mysql_query($zz);
}
$j=$i;
for($i=$j;$i<9;$i++) {
	$zz="update typyopl set ZTYTULU0".$i."=0, TYPTYTUL0".$i."=0 where ID=".$idt. " limit 1";
	$ww=mysql_query($zz);
}
?>
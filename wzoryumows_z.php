<?php

if ($ipole<0) {
	$ipole=-$ipole;
}

$w=mysql_query("
	select ID_WZORYUMOW
	  from wzoryumows
	 where ID=$ipole
");

if ($r=mysql_fetch_row($w)) {
	$idw=$r[0];

	$w=mysql_query("
		update wzoryumows
	 left join wzoryumow 
		    on wzoryumow.ID=wzoryumows.ID_WZORYUMOW
		   set wzoryumows.NAZWA=concat('---',wzoryumows.NAZWA,'---')
		 where wzoryumows.ID_WZORYUMOW=$idw
		   and INSTR(wzoryumow.TEKST,wzoryumows.NAZWA)=0	
	");

}

?>
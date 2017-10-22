<?php
function myRound($value,$round=2){
	$minus=($value<0);
	$value=abs($value);
	$value*=pow(10.0,$round+1);            // 0,8349 --> 834,9
	//$value=floor(floatval($value+0.51));
	$value=floor(floatval($value));        // 834,9 --> 834
	if ((strpos($value,'E')>0)||(substr($value,-1,1)<5)) {                                // w dół
	   $value/=pow(10.0,1);                // 834 --> 83,4
	   $value=floor(floatval($value));     // 83,4 --> 83
	} else {                                                    // w górę
	   $value/=pow(10.0,1);                // 835 --> 83,5
	   $value=floor(floatval($value))+1;   // 83,5 --> 84
	}
	$value/=pow(10.0,$round);              // 84 --> 0,84
	if ($minus) {
		$value=-$value;
	}
return($value);
}

function Odsetki($value,$days) {
	if ($value==0||$days==0) {
		$odsetki=0;
	} else {
		$odsetki=myRound($value*$days*30*0.01/365);	//sztywna stopa 30%
	}
	if ($odsetki<0) {
		$odsetki=0;
	}
	return $odsetki;
}
?>
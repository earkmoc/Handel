<?php

$n=10;	//faktur
$k=1;	//korekt

for($i=0;$i<$k;$i++) {$j[$i]=$i;}
$j[$k-1]--;		//zaraz go zwi�kszy

$wariantuj=true;
while ($wariantuj) {
	$przesuwaj=true;
	while (($wariantuj)&&($przesuwaj)) {
		$x=$k-1;
		while (($wariantuj)&&($przesuwaj)&&($x>=0)&&(++$j[$x]>$n+$x-$k+1)) {
			$j[$x-1]++;
			for($i=$x;$i<$k;$i++) {$j[$i]=$j[$i-1]+1;}	//automatyczne ustawianie wska�nik�w
			$x--;
			if ($x<0) {			//za daleko
				$przesuwaj=false;	//koniec przesuwania
				$wariantuj=false;	//koniec tego wariantu indeks�w do faktur
			}
			else {
				$j[$x]--;		//zaraz go zn�w zwi�kszy w "while"
			}
		}
		$przesuwaj=false;	//poprzesuwane

		if ($wariantuj) {
			echo "<br>przetwarzane wska�niki n=$n, x=$x, k=$k, ".($n+$x-$k+1).': ';
			for($p=0;$p<$k;$p++) {
				echo $j[$p];
				echo ", ";
			}
		}
	}
}

?>
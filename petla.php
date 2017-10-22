<?php

$n=10;	//faktur
$k=1;	//korekt

for($i=0;$i<$k;$i++) {$j[$i]=$i;}
$j[$k-1]--;		//zaraz go zwiÍkszy

$wariantuj=true;
while ($wariantuj) {
	$przesuwaj=true;
	while (($wariantuj)&&($przesuwaj)) {
		$x=$k-1;
		while (($wariantuj)&&($przesuwaj)&&($x>=0)&&(++$j[$x]>$n+$x-$k+1)) {
			$j[$x-1]++;
			for($i=$x;$i<$k;$i++) {$j[$i]=$j[$i-1]+1;}	//automatyczne ustawianie wskaünikÛw
			$x--;
			if ($x<0) {			//za daleko
				$przesuwaj=false;	//koniec przesuwania
				$wariantuj=false;	//koniec tego wariantu indeksÛw do faktur
			}
			else {
				$j[$x]--;		//zaraz go znÛw zwiÍkszy w "while"
			}
		}
		$przesuwaj=false;	//poprzesuwane

		if ($wariantuj) {
			echo "<br>przetwarzane wskaüniki n=$n, x=$x, k=$k, ".($n+$x-$k+1).': ';
			for($p=0;$p<$k;$p++) {
				echo $j[$p];
				echo ", ";
			}
		}
	}
}

?>
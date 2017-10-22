<?php

$z="select ID from tabele where NAZWA='$tabela'"; $w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];
$z="select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$osoba_id limit 1"; $w=mysql_query($z); $w=mysql_fetch_row($w); 
$iddbk=$w[0];

$z="select PRZYCHOD, ROZCHOD, left(KONTOBK,2), PRZEDMIOT, NRKONT from dokumentbk where ID=$iddbk"; $w=mysql_query($z); $w=mysql_fetch_row($w); 
$przy=$w[0];
$roz=$w[1];
$przedmiot=$w[3];
$id_f=$w[4];

$z="select count(*) from dokumentz where ID_B=$iddbk"; $w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$max=false;
if ($w>0) {	//jest coï¿½ do roboty, bo jest choæ ªedna wybrana faktura do zapï¿½aty
	
	$z="select sum(KWOTA) from dokumentz where ID_B=$iddbk"; $w=mysql_query($z); $w=mysql_fetch_row($w); $wpl=$w[0];
	
	if ((($przy+$roz)==0)&&(($przedmiot=='')||(trim($przedmiot)=='za fakturê'))) {		//brak deklaracji kwoty pocz±tkowej i to nowy dokument na nieokre¶lone dokumenty
		$z="select ID_D, KWOTA from dokumentz where ID_B=$iddbk"; 
		$w=mysql_query($z); 
		while ($r=mysql_fetch_row($w)) {
			$idd=$r[0];
			$kwota=$r[1];
			$z="select if(TYP_F IN ('D','M'),2,1) from dokum where ID=$idd"; 
			$z=mysql_query($z); 
			if ($r=mysql_fetch_row($z)) {
				if ($r[0]==1) {
//					if ($kwota>0) {
						$przy+=$kwota;
//					} else {
//						$roz+=$kwota;
//					}
				} else {
//					if ($kwota>0) {
						$roz+=$kwota;
//					} else {
//						$przy+=$kwota;
//					}
				}
			}
		}
		$z="update dokumentz set KWOTA=0 where ID_B=$iddbk";
		$wpl=($przy+$roz);
		$max=true;
	}
	
	if (($wpl==($przy+$roz))&&!$max) {
	;				//dotychczasowe wpï¿½aty dokï¿½adnie pokrywajï¿½ PRZYCHOD+ROZCHOD, wiê£ nic nie rï¿½b
	} else {		//dotychczasowe wpï¿½aty sï¿½ mniejsze/wiê«³ze od PRZYCHOD+ROZCHOD, wiê£ trzeba podwyï¿½szyæ¯¯bniï¿½yæ ·pï¿½aty
		
		$z="select * from dokumentz where ID_B=$iddbk order by ID";	//and KWOTA<>0 
		$w=mysql_query($z); 
		while ($r=mysql_fetch_array($w)) {	//wycofanie dotychczasowych wpï¿½at z dokumentï¿½w

			$kwota=$r['KWOTA'];
//			mysql_query("update dokum set WPLACONO=WPLACONO-if(WARTOSC<0,-1,1)*$kwota where ID=".$r['ID_D']);

//--------------------------------------------------------------------------------

			mysql_query("delete from dokspl where abs(KWOTA)=abs($kwota) and ID_D=".$r['ID_D']." order by ID desc limit 1");

         $ww=mysql_query("select ifnull(sum(KWOTA),0) from dokspl where ID_D=".$r['ID_D']);
         $rr=mysql_fetch_row($ww);
         $rr=$rr[0];
			mysql_query("update dokum set WPLACONO=$rr where ID=".$r['ID_D']);

//--------------------------------------------------------------------------------

		}
		
		if ($wpl<=($przy+$roz)) {		//dotychczasowe wpï¿½aty sï¿½ mniejsze niï¿½ PRZYCHOD+ROZCHOD, wiê£ zwiê«³zamy na max
			$z=("
				update dokumentz 
	   		 left join dokum 
			        on dokum.ID=dokumentz.ID_D 
				   set dokumentz.KWOTA=(dokum.WARTOSC-dokum.WPLACONO) 
				     , dokumentz.PRZEDMIOT=concat(dokum.TYP,'-',dokum.INDEKS,' z dnia ',dokum.DATAW)
				 where dokumentz.ID_B=$iddbk
			"); 
			mysql_query($z);
			$z="select sum(KWOTA) from dokumentz where ID_B=$iddbk"; $w=mysql_query($z); $w=mysql_fetch_row($w); $wpl=$w[0];
		} else {						//dotychczasowe wpï¿½aty sï¿½ wiê«³ze niï¿½ PRZYCHOD+ROZCHOD, wiê£ zmniejszamy od koï¿½ca
			$zmiejsz_o=$wpl-($przy+$roz);
			do {	
				$z="update dokumentz set KWOTA=if(KWOTA>$zmiejsz_o,KWOTA-$zmiejsz_o,0) where ID_B=$iddbk and KWOTA>0 order by ID desc limit 1"; mysql_query($z);
				$z="select sum(KWOTA) from dokumentz where ID_B=$iddbk"; $w=mysql_query($z); $w=mysql_fetch_row($w); $wpl=$w[0];
				$zmiejsz_o=$wpl-($przy+$roz);
			} while ($wpl<>($przy+$roz));
		}
		
		$i=0;
		$s='';
		$s1='';
		$przy=0;
		$roz=0;
		$z="delete from dokumentz where ID_B=$iddbk and KWOTA=0"; mysql_query($z);	//zerï¿½wki sio
		$z="select dokumentz.ID_D, dokumentz.KWOTA, dokum.INDEKS, dokum.TYP, if(dokum.TYP_F IN ('D','M'),2,1) from dokumentz left join dokum on dokum.ID=dokumentz.ID_D where dokumentz.ID_B=$iddbk order by dokumentz.ID";	//echo '<br>'.
		$w=mysql_query($z); 
		while ($r=mysql_fetch_row($w)) {	//wpisanie obecnych wpï¿½at do dokumentï¿½w
			$i++;
			$kwota=$r[1];
			if ($r[4]==1) {
//				if ($kwota>0) {
					$przy+=$kwota;
//				} else {
//					$roz+=$kwota;
//				}
			} else {
//				if ($kwota>0) {
					$roz+=$kwota;
//				} else {
//					$przy+=$kwota;
//				}
			}
			mysql_query("update dokum set WPLACONO=WPLACONO+$kwota where ID=".$r[0]);

//--------------------------------------------------------------------------------

         $ww=mysql_query("select ID_X from dokspl where ID_D=".$r['0']." order by ID_X desc limit 1");
         $rr=0;
         if ($ww=mysql_fetch_row($ww)) {
            $rr=$ww[0];
         }
         $rr++;

         $ww=mysql_query("select NABYWCA, CurDate() from dokum where ID=".$r['0']);
         $ww=mysql_fetch_row($ww);

			mysql_query("insert into dokspl set ID_X=$rr, ID_F=".$ww[0].", DATAW='".$ww[1]."', KWOTA=$kwota, ID_D=".$r['0']);

//--------------------------------------------------------------------------------

			$s.=(($s=='')?'':', ').($r[3].'-'.$r[2]).' ('.($r[1]).'z³)';
			$s1.=(($s1=='')?        $r[3].'-'.$r[2]:'');
		}
		
		$s=(($i>1)?"za faktury: $s":"za fakturê $s1");
		$z="update dokumentbk set PRZYCHOD=$przy, ROZCHOD=$roz, PRZEDMIOT='$s' where ID=$iddbk"; mysql_query($z);
	}
}

$zz=("select sum(if(right(TYP,1)<>'K',WARTOSC,0))
           , sum(WPLACONO)
           , sum(if(UWAGI like '%korekta%',UWAGI*1,0))
           , sum(if(right(TYP,1)='K',WARTOSC,0)) 
        from dokum 
       where NABYWCA=$id_f 
	     and BLOKADA=''
         and TYP<>'INW' 
         and TYP<>'ZAM' 
         and TYP<>'ZNI' 
         and TYP<>'ZW' 
");
$ww=mysql_query($zz);
if ($ww=mysql_fetch_row($ww)) {
   $ww[0]=$ww[0]*1;
   $ww[1]=$ww[1]*1;
   $ww[2]=$ww[2]*1;
   $ww[3]=$ww[3]*1;
   $zz="update firmy 
           set NALEZNOSCI=$ww[0]-$ww[2]
             , ZALICZKI=$ww[1]
             , KOREKTY=$ww[2]-$ww[3] 
         where ID=$id_f";
   $ww=mysql_query($zz);
}

$w=true;

?>
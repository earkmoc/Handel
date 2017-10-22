<?php

//require('skladuj_zmienne.php');exit;
//require('spec_calc.php'); ze wszystkich spec_XXX.end

if (!$ipole) {
	$z="Select ID from tabele where NAZWA='spec'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID 
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$ipole=$w[0];
}

$w=mysql_query("
   select ID_T
     from spec 
    where ID=$ipole
");
$w=mysql_fetch_row($w);
$idt=$w[0];

if (!$idd) {
	$z="Select ID from tabele where NAZWA='dokum'";
	$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

	$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID 
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$idd=$w[0];
}

$z="Select TYP, Year(DATAW), Year(DATAO), Year(CurDate()) from dokum where ID=$idd";
$w=mysql_query($z); $w=mysql_fetch_row($w);
$doktyp=$w[0];

//-------------------------------------------------------------------------------------

$ydataw=$w[1];
$ydatao=$w[2];
$ydatac=$w[3];	//year of current data

$ydataw=($ydataw==0?$ydatac:$ydataw);
$ydatao=($ydatao==0?$ydatac:$ydatao);

$staryVAT=(($ydataw<2011)||($ydatao<2011));

//-------------------------------------------------------------------------------------

$mpodm=2;   
$odcen='Netto';
if ($w=mysql_query("select * from doktypy where TYP='$doktyp'")) {
	if ($w=mysql_fetch_array($w)) {
		$mpodm=$w['MAGAZYNP'];   
		$odcen=$w['ODCEN'];   
	}
}

if ($mpodm_pre) {$mpodm=$mpodm_pre;}   //mo¿na wy³±czyæ ograniczniki netnet

//------------------------------------------------------------------------------------

$towary_wpaczce='';
$dostawa_ilepaczek='';
$dostawa_posztukwpaczce='';
$dostawa_cenazapaczke='';

$z = '';
foreach($_POST as $zmienna => $wartosc) {
	if ($zmienna=='towary_W_PACZCE') {
		$towary_wpaczce=$wartosc;
	}
	if (substr($zmienna,0,7)=='towary_') {
		$zmienna=str_replace('towary_','',$zmienna);
		$z.= ($z?', ':'')."$zmienna='$wartosc'";
	}
	if ($zmienna=='(1_1)') {
		$dostawa_ilepaczek=$wartosc;
	}
	if ($zmienna=='(1_2)') {
		$dostawa_posztukwpaczce=$wartosc;
	}
	if ($zmienna=='(1_3)') {
		$dostawa_cenazapaczke=$wartosc;
	}
}

if ($z) {
	mysql_query("update towary set $z where ID=$idt");
}

//------------------------------------------------------------------------------------

require_once('funkcje.php');

$z="select spec.CENABEZR
         , spec.RABAT
         , TOWARY.VAT
         , spec.ILOSC
         , spec.ID_D
         , TOWARY.CENA_S3
         , TOWARY.CENA_B3
         , TOWARY.CENA_Z
         , TOWARY.STATUS
         , spec.ID
      from spec 
      left join towary on towary.ID=spec.ID_T 
";

if ($all_spec) {
	$z.=" where spec.ID_D=$idd";
} else {
	$z.=" where spec.ID=$ipole limit 1";
}

$w=mysql_query($z);
while ($r=mysql_fetch_row($w)) {

	$cena=$r[0]; 
	$rabat=$r[1]; 
	$svat=$r[2];
	$ilosc=$r[3];
//	$idd=$r[4];
	$cenanetnetn=$r[5]; 
	$cenanetnetb=$r[6]; 
	$cenazakupn=$r[7]; 
	$status=$r[8]; 
	$ipole=$r[9]; 

	$rabat=(($status=='U')?0:$rabat);
	$cena=myRound($cena*(100-$rabat)*0.01);

	if ($staryVAT) {
		if ($svat*1==23) {$svat='22%';}
		if ($svat*1== 8) {$svat=' 7%';}
		if ($svat*1== 5) {$svat=' 3%';}
	}

	if ($odcen=='Netto') {

		if (($mpodm==2)&&($cenanetnetn<>0)&&($cena<$cenanetnetn)) {
			$cena=$cenanetnetn;
		}

		$netto=myRound($cena*$ilosc);

//echo		"$netto=myRound($cena*$ilosc)";exit;

		$kwotaVAT=myRound($netto*($svat*1)*0.01);
		$brutto=$netto+$kwotaVAT;

		if ($ilosc<>0) {
			$cenabrutto=myRound($brutto/$ilosc);
			if (($mpodm==2)&&($cenanetnetb<>0)&&($cenabrutto<$cenanetnetb)) {
				$cenabrutto=$cenanetnetb;
			}
		} else {
			$cenabrutto=0;
		}

	} else {

		if (($mpodm==2)&&($cenanetnetb<>0)&&($cena<$cenanetnetb)) {
			$cena=$cenanetnetb;
		}

		$cenabrutto=$cena;

		$brutto=myRound($ilosc*$cenabrutto);
		$netto=myRound($brutto*100/(100+($svat*1)));
		$kwotaVAT=$brutto-$netto;

		if ($ilosc<>0) {
			$cena=myRound($netto/$ilosc);
			if (($mpodm==2)&&($cenanetnetn<>0)&&($cena<$cenanetnetn)) {
				$cena=$cenanetnetn;
			}
		} else {
			$cena=0;
		}

	}

	$z="update spec 
          set CENA=$cena 
             ,RABAT=$rabat
             ,NETTO=$netto
             ,KWOTAVAT=$kwotaVAT
             ,BRUTTO=$brutto
             ,CENABRUTTO=$cenabrutto
             ,STAWKAVAT='$svat'
        where ID=$ipole limit 1";
//echo $z;
	mysql_query($z);

}


//stopka faktury z sumami koñcowymi

if ($idd) {

   if ($odcen=='Brutto') {

		$z="select sum(BRUTTO) from spec where ID_D=$idd and STAWKAVAT='23%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b23=($w[0]?$w[0]:0);
		$n23=myRound($b23*100/123);
		$v23=$b23-$n23;
		
		$z="select sum(BRUTTO) from spec where ID_D=$idd and STAWKAVAT='22%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b22=($w[0]?$w[0]:0);
		$n22=myRound($b22*100/122);
		$v22=$b22-$n22;
		
		$z="select sum(BRUTTO) from spec where ID_D=$idd and STAWKAVAT='8%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b8=($w[0]?$w[0]:0);
		$n8=myRound($b8*100/108);
		$v8=$b8-$n8;
		
		$z="select sum(BRUTTO) from spec where ID_D=$idd and STAWKAVAT='7%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b7=($w[0]?$w[0]:0);
		$n7=myRound($b7*100/107);
		$v7=$b7-$n7;
		
		$z="select sum(BRUTTO) from spec where ID_D=$idd and STAWKAVAT='5%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b5=($w[0]?$w[0]:0);
		$n5=myRound($b5*100/105);
		$v5=$b5-$n5;
		
		$z="select sum(BRUTTO) from spec where ID_D=$idd and STAWKAVAT='0%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b0=($w[0]?$w[0]:0);
		$n0=$b0;
		
		$z="select sum(BRUTTO) from spec where ID_D=$idd and STAWKAVAT='zw.'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$bzw=($w[0]?$w[0]:0);
		$nzw=$bzw;
		
		$z="select sum(round(spec.ILOSC*towary.CENA_Z,2)) from spec left join towary on towary.ID=spec.ID_T where spec.ID_D=$idd";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$ncz=($w[0]?$w[0]:0);

   } else {

		$z="select sum(NETTO) from spec where ID_D=$idd and STAWKAVAT='23%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n23=($w[0]?$w[0]:0);
		$v23=myRound($n23*0.23);
		
		$z="select sum(NETTO) from spec where ID_D=$idd and STAWKAVAT='22%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n22=($w[0]?$w[0]:0);
		$v22=myRound($n22*0.22);
		
		$z="select sum(NETTO) from spec where ID_D=$idd and STAWKAVAT='8%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n8=($w[0]?$w[0]:0);
		$v8=myRound($n8*0.08);
		
		$z="select sum(NETTO) from spec where ID_D=$idd and STAWKAVAT='7%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n7=($w[0]?$w[0]:0);
		$v7=myRound($n7*0.07);
		
		$z="select sum(NETTO) from spec where ID_D=$idd and STAWKAVAT='5%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n5=($w[0]?$w[0]:0);
		$v5=myRound($n5*0.05);
		
		$z="select sum(NETTO) from spec where ID_D=$idd and STAWKAVAT='0%'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n0=($w[0]?$w[0]:0);
		
		$z="select sum(NETTO) from spec where ID_D=$idd and STAWKAVAT='zw.'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$nzw=($w[0]?$w[0]:0);
		
		$z="select sum(round(spec.ILOSC*towary.CENA_Z,2)) from spec left join towary on towary.ID=spec.ID_T where spec.ID_D=$idd";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$ncz=($w[0]?$w[0]:0);
   }

   $n23*=1;
   $n22*=1;
   $n8 *=1;
   $n7 *=1;
   $n5 *=1;
   $n0 *=1;
   $nzw*=1; 
   $ncz*=1;
   $v23*=1;
   $v22*=1;
   $v8 *=1;
   $v7 *=1;
   $v5 *=1;
      
	$z="update dokum 
          set NETTO23=$n23
            , NETTO22=$n22
            , NETTO8 =$n8
            , NETTO7 =$n7
            , NETTO5 =$n5
            , NETTO0 =$n0
            , NETTOZW=$nzw
            , NETTOCZ=$ncz
            , VAT23  =$v23
            , VAT22  =$v22
            , VAT8   =$v8
            , VAT7   =$v7
            , VAT5   =$v5
            , WARTOSC=($n23+$n22+$n8+$n7+$n5+$n0+$nzw)+($v23+$v22+$v8+$v7+$v5)
            , NETTODOS=if(left(TYP,2)='PZ',NETTODOS,$n23+$n22+$n8+$n7+$n5+$n0+$nzw)
            , BRUTTODOS=if(left(TYP,2)='PZ',BRUTTODOS,($n23+$n22+$n8+$n7+$n5+$n0+$nzw)+($v23+$v22+$v8+$v7+$v5))
            , CZAS=CurTime() 
        where ID=$idd 
        limit 1";
	$w=mysql_query($z);
}

//if (false&&$tabelaa) {  //1 wiersz dalej
//   $z="Select ID, MAXROWS from tabele where NAZWA='$tabelaa'";
//   $w=mysql_query($z); $w=mysql_fetch_row($w); $idt=$w[0]; $mr=$w[1];
//   
//   $z="update tabeles 
//          set ID_POZYCJI=$ipole
//            , NR_STR=if(NR_ROW+1>$mr,NR_STR+1,NR_STR)
//            , NR_ROW=if(NR_ROW+1>$mr,1,NR_ROW+1) 
//        where ID_TABELE=$idt 
//          and ID_OSOBY=$ido 
//        limit 1";
//   $w=mysql_query($z);
//}
?>
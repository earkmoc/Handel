<?php

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

$odcen='Netto';
if ($w=mysql_query("select * from doktypy where TYP='$doktyp'")) {
	if ($w=mysql_fetch_array($w)) {
		$odcen=$w['ODCEN'];   
	}
}

//------------------------------------------------------------------------------------

require_once('funkcje.php');

$z="select spec.CENA
         , spec.RABAT
         , towary.VAT
         , spec.ILOSC-spec.CENABEZR
         , spec.ID_D
         , towary.CENA_S3
         , towary.CENA_B3
         , towary.CENA_Z
         , towary.STATUS
         , spec.ID
         , towary.VAT
      from spec 
      left join towary on towary.ID=spec.ID_T 
	 where spec.ID_D=-$idd
";

$w=mysql_query($z);
while ($r=mysql_fetch_row($w)) {

	$cena=$r[0]; 
	$rabat=$r[1]; 
	$svat=(($r[10]<>'')?$r[10]:$r[2]);
	$ilosc=$r[3];
//	$idd=$r[4];
	$cenanetnetn=$r[5]; 
	$cenanetnetb=$r[6]; 
	$cenazakupn=$r[7]; 
	$status=$r[8]; 
	$ipole=$r[9]; 

	$rabat=(($status=='U')?0:$rabat);
	$cena=myRound($cena*(100-$rabat)*0.01);

//------------------------------------------------------------------------------------

	if ($staryVAT) {
		if ($svat*1==23) {$svat='22%';}
		if ($svat*1== 8) {$svat=' 7%';}
		if ($svat*1== 5) {$svat=' 3%';}
	}

	if ($odcen=='Netto') {

		$netto=myRound($cena*$ilosc);
		$kwotaVAT=myRound($netto*($svat*1)*0.01);
		$brutto=$netto+$kwotaVAT;

		if ($ilosc<>0) {
			$cenabrutto=myRound($brutto/$ilosc);
		} else {
			$cenabrutto=0;
		}

	} else {

		$cenabrutto=$cena;

		$brutto=myRound($ilosc*$cenabrutto);
		$netto=myRound($brutto*100/(100+($svat*1)));
		$kwotaVAT=$brutto-$netto;

		if ($ilosc<>0) {
			$cena=myRound($netto/$ilosc);
		} else {
			$cena=0;
		}

	}
}

//stopka faktury z sumami koñcowymi

if ($idd) {

   if ($odcen=='Brutto') {

		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=23";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b23=($w[0]?$w[0]:0);
		$n23=myRound($b23*100/123);
		$v23=$b23-$n23;
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=22";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b22=($w[0]?$w[0]:0);
		$n22=myRound($b22*100/122);
		$v22=$b22-$n22;
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=8";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b8=($w[0]?$w[0]:0);
		$n8=myRound($b8*100/108);
		$v8=$b8-$n8;
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=7";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b7=($w[0]?$w[0]:0);
		$n7=myRound($b7*100/107);
		$v7=$b7-$n7;
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=5";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b5=($w[0]?$w[0]:0);
		$n5=myRound($b5*100/105);
		$v5=$b5-$n5;
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and (STAWKAVAT='0%' or STAWKAVAT=' 0%')";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$b0=($w[0]?$w[0]:0);
		$n0=$b0;
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT='zw.'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$bzw=($w[0]?$w[0]:0);
		$nzw=$bzw;
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec left join towary on towary.ID=spec.ID_T where spec.ID_D=-$idd";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$ncz=($w[0]?$w[0]:0);

   } else {

		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=23";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n23=($w[0]?$w[0]:0);
		$v23=myRound($n23*0.23);
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=22";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n22=($w[0]?$w[0]:0);
		$v22=myRound($n22*0.22);
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=8";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n8=($w[0]?$w[0]:0);
		$v8=myRound($n8*0.08);
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=7";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n7=($w[0]?$w[0]:0);
		$v7=myRound($n7*0.07);
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT*1=5";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n5=($w[0]?$w[0]:0);
		$v5=myRound($n5*0.05);
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and (STAWKAVAT='0%' or STAWKAVAT=' 0%')";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$n0=($w[0]?$w[0]:0);
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec where ID_D=-$idd and STAWKAVAT='zw.'";
		$w=mysql_query($z); $w=mysql_fetch_row($w);
		$nzw=($w[0]?$w[0]:0);
		
		$z="select sum(round((spec.ILOSC-spec.CENABEZR)*spec.CENA,2)) from spec left join towary on towary.ID=spec.ID_T where spec.ID_D=-$idd";
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
        where ID=$idd 
        limit 1";
	$w=mysql_query($z);
}
?>
<?php

//test

$z="select NUMER, NAZWA, MASKA, Now(), Year(CurDate()), CZAS, TYP_F from doktypy where TYP='$dokumtyp' limit 1";
$w=mysql_query($z);
$r=mysql_fetch_row($w);
$wynik[2]=$dokumtyp;
$wynik[3]=$r[1];
$typf=$r[6];

//$wynik[4]='auto lub '.($r[0]+1).$r[2];
//$wynik[4]=(1*$r[0]+0).$r[2].' lub auto';
$r[2]=str_replace('rok',$r[4],$r[2]);
$r[2]=str_replace('rocznik',substr($r[4],-2,2),$r[2]);
$nrdok=$r[0]+1;
$nowyrok=false;
if (substr($r[4],0,4)<>substr($r[5],0,4)) {
   $nrdok=1;
   $nowyrok=true;
}
$maska=$r[2];
if ($maska*1>0) {
   $nrdok=substr('00000000000000000'.$nrdok,-($maska*1),$maska*1);
   $wynik[4]=$nrdok.substr($maska,1);
} else {
   $wynik[4]=$nrdok.$maska;
}

if (!$nowyrok) {
   $ok=false;
   $w=mysql_query("select INDEKS from dokum where TYP='$dokumtyp' order by DATAW desc, INDEKS desc limit 1");
   if ($w=mysql_fetch_row($w)) {
      $lastnr=$w[0]*1;
      $nrdok=$lastnr;
      do {
         $nrdok++;
         if ($maska*1>0) {
            $wynik[4]=substr('00000000000000000'.$nrdok,-($maska*1),$maska*1).substr($maska,1);
         } else {
            $wynik[4]=$nrdok.$maska;
         }
         
         $ileNum=0;  //ile jest dokumentów tego typu z takim numerem ?
         $w=mysql_query("select count(*) from dokum where TYP='$dokumtyp' and INDEKS='$wynik[4]'");
         if ($w=mysql_fetch_row($w)) {
            $ileNum=$w[0];
         }
      } while($ileNum>0);
   }
}
$wynik[5]=date('Y-m-d');
$wynik[6]=date('Y-m-d');

if (substr($dokumtyp,0,1)=='P') {
	if (substr($dokumtyp,0,2)=='PZ') {   
	   $wynik[24]='przelew';	//sposób zap³aty
	} else {					//paragon
	   $wynik[7]=100600;		//numer nabywcy
	   $wynik[8]='PARAGON';		//indeks nabywcy
	   $wynik[11]='PARAGON';	//nazwa nabywcy
	   $wynik[24]='gotówka';	//sposób zap³aty
	   $posx=24;   //rabat
   }
}

if ($dokumtyp=='INW') {
   $w=mysql_query("select ID, INDEKS, NIP, TYP, NAZWA, KOD, MIASTO, ADRES from firmy where ID=1");
   if ($rr=mysql_fetch_array($w)) {
		$wynik[7]=$rr[ID];
		$wynik[8]=$rr[INDEKS];
		$wynik[9]=$rr[NIP];
		$wynik[10]=$rr[TYP];
		$wynik[11]=$rr[NAZWA];
		$wynik[12]=$rr[KOD];
		$wynik[13]=$rr[MIASTO];
		$wynik[14]=$rr[ADRES];

		$wynik[24]='przelew';	//sposób zap³aty
		$posx=24;   //rabat
	}
}

$wynik[10]=$typf;		//typ firmy
$wynik[18]=date('Y-m-d');	//z dnia
$wynik[22]=($typf=='D'||$typf=='M'?0:1);	//cennik
$wynik[26]=date('Y-m-d');	//termin
$wynik[29]=$osoba_upr;		//wystawil
$wynik[20]=$r[3];			//czas

//magazyn i jego nazwa
$z="select NAZWA from firmy where ID=1 limit 1";
$w=mysql_query($z);
$r=mysql_fetch_row($w);
$wynik[15]=1;
$wynik[16]=$r[0];

?>
<?php

//session_start();
//$ido=$_SESSION['osoba_id'];

if ($kasabank=='BANK') {
  $typRK='WB';
  $typKP='BP';
  $typKW='BW';
  $konto='130';
} elseif ($kasabank=='ALLE') {
  $typRK='WBA';
  $typKP='AP';
  $typKW='AW';
  $konto='131';
} else {
  $typRK='RK';
  $typKP='KP';
  $typKW='KW';
  $konto='100';
}

if (!$odsetki) {
	$odsetki=0;
}

$z="Select * from dokum where ID=$id_d";
$w=mysql_query($z); $w=mysql_fetch_array($w);

$lp='';
$data=$dataw;
$okres=substr($data,0,7);
$numer='';
$stanpocz=0;
$przedmiot=$w['TYP'].'-'.$w['INDEKS'].' z '.$w['DATAW'].($w['NUMERFD']?' do faktury Nr '.$w['NUMERFD'].' z '.($w['DATAO']*1==0?$w['DATAW']:$w['DATAO']):'');
if (($w['TYP']=='PZ')||($w['TYP']=='PZK')||($w['TYP']=='FZ')||($w['TYP']=='FZK')) {
   if ($splata>0) {
      $numerKP=$typKW;
      $przych=0;
      $rozch=$splata+$odsetki;
      $ileplus=0;
      $ileminus=1;
   } else {
      $numerKP=$typKP;
      $przych=-($splata+$odsetki);
      $rozch=0;
      $ileplus=1;
      $ileminus=0;
   }
} else {
   if ($splata>0) {
      $numerKP=$typKP;
      $przych=$splata+$odsetki;
      $rozch=0;
      $ileplus=1;
      $ileminus=0;
   } else {
      $numerKP=$typKW;
      $przych=0;
      $rozch=-($splata+$odsetki);
      $ileplus=0;
      $ileminus=1;
   }
}
$nrkont=$w['NABYWCA'];
$pskont=$w['INDEKS_F'];
$nip=$w['NIP'];
$nazwa=$w['NAZWA'];
$adres=$w['KOD'].' '.$w['MIASTO'].', '.$w['ADRES'];

//require('dbconnect.inc');

$z=("
  select *
    from dokumenty
   where TYP='$typRK'
     and DWPROWADZE='$data'
order by ID desc
   limit 1 
");

$w=mysql_query($z);
if (!$r=mysql_fetch_row($w)) {   //brak RK z dzisiejsz? dat?
   $z=("
     select *
       from dokumenty
      where TYP='$typRK'
        and DWPROWADZE<'$data'
   order by ID desc
      limit 1 
   ");
   
   $w=mysql_query($z);
   if ($r=mysql_fetch_array($w)) {  //jest wcze?niejszy
      $lp=$r['LP'];
      $numer=$r['NUMER'];
      $stanpocz=$r['STANKONC'];
   }

   $lp=$lp*1+1;
   $numer=$numer*1+1;               //kontynuacja

   $z=("
     insert 
       into dokumenty
        set KTO=$ido
           ,CZAS=Now()
           ,GDZIE='bufor' 
           ,TYP='$typRK'
           ,LP='$lp'
           ,OKRES='$okres'
           ,DWPROWADZE='$data'
           ,DDOKUMENTU='$data'
           ,DOPERACJI='$data'
           ,DWPLYWU='$data'
           ,NUMER='$numer'
           ,SCHEMAT='rk'
           ,STANPOCZ=$stanpocz
           ,PRZYCHODY=$przych
           ,ROZCHODY=$rozch
           ,STANKONC=$stanpocz+$przych-$rozch
           ,ILEDOKPLUS=$ileplus
           ,ILEDOKMINU=$ileminus
   ");
   
   $w=mysql_query($z);     //teraz jest dzisiejszy
   $idrk=mysql_insert_id(); 

   $lp=1;      //dla dokumentbk
   
} else {

   $idrk=$r[0]; 

   $z=("
     update dokumenty
        set KTO=$ido
           ,CZAS=Now()
           ,PRZYCHODY=PRZYCHODY+$przych
           ,ROZCHODY=ROZCHODY+$rozch
           ,STANKONC=STANKONC+$przych-$rozch
           ,ILEDOKPLUS=ILEDOKPLUS+$ileplus
           ,ILEDOKMINU=ILEDOKMINU+$ileminus
      where ID=$idrk
   ");
   
   $w=mysql_query($z);     //jest dzisiejszy
}

$z=("
  select LP
    from dokumentbk
   where ID_D=$idrk
order by ID desc
   limit 1 
");

$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {  //jest wcze?niejszy
   $lp=$r[0]*1+1;
} else {
   $lp=1;
}

$z=("
  select NUMER
    from dokumentbk
   where left(NUMER,2)='$numerKP'
order by ID desc
   limit 1 
");

$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {  //jest wcze?niejszy
   $numerKP.=' '.substr('0000'.(substr($r[0],3)*1+1),-4,4).'-'.Date('y');
} else {
   $numerKP.=' 0001-'.Date('y');
}

$z=("
  insert 
    into dokumentbk
     set ID_D=$idrk
        ,KTO=$ido
        ,CZAS=Now()
        ,TYP='$typRK'
        ,LP='$lp'
        ,NUMER='$numerKP'
        ,DATA='$data'
        ,PRZEDMIOT=concat('$przedmiot',if($odsetki=0,'',' z odsetkami'))
        ,NRKONT='$nrkont'
        ,PSKONT='$pskont'
        ,NIP='$nip'
        ,NAZWA='$nazwa'
        ,ADRES='$adres'
        ,PRZYCHOD=$przych
        ,ROZCHOD=$rozch
        ,KONTOBK='$konto'
        ,OKRES='$okres'
");

$w=mysql_query($z);
$idb=mysql_insert_id(); 

$z=("
  insert 
    into dokumentz
     set ID_D=$id_d
        ,ID_B=$idb
        ,KTO=$ido
        ,CZAS=Now()
        ,PRZEDMIOT='$przedmiot'
        ,KWOTA=$przych+$rozch-$odsetki
");

$w=mysql_query($z);

if ($odsetki<>0) {
	$splata=number_format($splata,2,'.',',');
	$z=("
	  insert 
	    into dokumentz
	     set ID_D=-$id_d
	        ,ID_B=$idb
	        ,KTO=$ido
	        ,CZAS=Now()
	        ,PRZEDMIOT='odsetki 30% za $dniodsetek dni od kwoty $odsetkiodkwoty z³'
	        ,KWOTA=$odsetki
	");
	$w=mysql_query($z);
}

$tabelaa='dokumentzy';

//require('dbdisconnect.inc');

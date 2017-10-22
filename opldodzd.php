<?php

$raport='';							//zmiana grupy

$z="Select ID from tabele where NAZWA='abonenci'";		//zmiana grupy tylko z tabeli "abonenci"
$w=mysql_query($z); $w=mysql_fetch_row($w); $w=$w[0];

$z="Select ID_POZYCJI from tabeles where ID_TABELE=$w and ID_OSOBY=$ido";	//jest ID abonenta
$w=mysql_query($z); $w=mysql_fetch_row($w); $ida=$w[0];								//ostatnio u¿ytego

$z="update opldodg set IDABONENTA=$ida, TYPOPER='p', ID_OSOBYUPR=$ido, CZAS=Now() where ID=$ipole limit 1";
$w=mysql_query($z);

$z="Select * from opldodg where ID=$ipole";
$w=mysql_query($z);
$w=mysql_fetch_array($w);

$dtstart=$w['DATAOPER'];

$raport.=$z;

$z="select ID, IDGRUPY, ZABLOK from abonenci where ID=$ida limit 1";
$a=mysql_fetch_row(mysql_query($z));

$raport.=$z;

if ($a[1]*1==$w['INFO']*1) {	//nic siê nie zmienia
	$z="delete from opldodg where ID=$ipole limit 1";
	$w=mysql_query($z);
}
else {			//faktycznie grupa siê zmienia

$z='update `abonenci` set ';
$z.="`IDGRUPY`='".$w['INFO']."'";
$z.=" where `ID`='".$w['IDABONENTA']."' limit 1";
$ww=mysql_query($z);

$raport.=$z;

$z='insert into `opldod` set ';
$z.="`IDABONENTA`='".$w['IDABONENTA']."',";
$z.="`TYPOPER`='".$w['TYPOPER']."',";
$z.="`DATAOPER`='".$w['DATAOPER']."',";
$z.="`INFO`='".$a[1]."',";
$z.="`IDGRUPY`='".$a[1]."',";
$z.="`C1`='".ord(substr($a[1],0,1))."',";
$z.="`C2`='".ord(substr($a[1],1,1))."',";
$z.="`C3`='".ord(substr($a[1],2,1))."',";
$z.="`C4`='".ord(substr($a[1],3,1))."',";
$z.="`C5`='".ord(substr($a[1],4,1))."' ";
$ww=mysql_query($z);

$raport.=$z;

$ww=mysql_insert_id();

$z="update opldodg set ID_OPLDOD=$ww where ID=$ipole limit 1";
$ww=mysql_query($z);

$dt=$dtstart;
$rd=substr($dt,0,4)*1;		// '2006-04-11'  ->   '2006'*1 -> 2006
$md=substr($dt,5,2)*1;		// '2006-04-11'  ->   '04'*1   ->    4
$dd=1;							//pierwszy dzieñ
$md++;							//przysz³y miesi¹c: 4 -> 5
if ($md>12) {$md=1; $rd++;}//12.2006 -> 1.2007
$da=sprintf("%'04d",$rd);
$da.='-'.sprintf("%'02d",$md);
$da.='-'.sprintf("%'02d",$dd);	//'2006-05-01'

//usuniêcie op³at jeszcze nie fakturowanych
$z="delete from oplaty where IDABONENTA=$ida and DODNIA>='".$da."' and NRFAKTURY=''";
$ww=mysql_query($z);

$dane['DATAWPLATY']=$dtstart;		//ze wzglêdu na zgodnoœæ dalszego kodu
$dane['DATAPRZYJ']=$dtstart;		//ze wzglêdu na zgodnoœæ dalszego kodu
$dane['IDOPERATOR']=$ido;

//przesuniêcie wp³at do nadp³at
$z="select * from wplaty where IDABONENTA=$ida and DODNIA>='".$da."'";
$ww=mysql_query($z);
while ($wp=mysql_fetch_array($ww)) {	//kolejne pozycje wp³at

	$dane['KWOTA']=$wp['WYSWPL'];

					$z="Select * from typydok where LITERA='/' limit 1";
					$w=mysql_fetch_array(mysql_query($z));		// mamy ostatni NUMER RN z "typydok"

					$dane['NUMERRN']=trim($w['NUMER']);
					$dane['MASKARN']=trim($w['MASKA']);

					$dane['NUMERRN']=($dane['NUMERRN']+1);

					$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer RN
					$z.=$dane['NUMERRN'];
					$z.="', DATA='";
					$z.=$dane['DATAWPLATY'];
					$z.="' where LITERA='/' limit 1";
					$w=mysql_query($z);

					$z="INSERT INTO dokwplat values (0,'";		// zapis danych do "dokwplat"
					$z.='/';
					$z.="','";
					$z.=$dane['NUMERRN'].$dane['MASKARN'];
					$z.="','";
					$z.=$dane['DATAPRZYJ'];
					$z.="','";
					$z.=$dane['IDOPERATOR'];
					$z.="','1','','','";
					$z.=$wp['IDABONENTA'];
					$z.="','";
					$z.=$dane['KWOTA'];
					$z.="','',Now())";
					$w=mysql_query($z);

						$z="INSERT INTO nadplaty values (0,'";
						$z.=$wp['IDGRUPY'];
						$z.="','";
						$z.=$wp['IDABONENTA'];
						$z.="','";
						$z.=$wp['TYPINST'];
						$z.="','";
						$z.=$wp['RODZADM'];
						$z.="','";
						$z.=95;				// nadp³ata
						$z.="','";
//				if ($a[2]=='T'||$a[2]=='t') {			//niskie kody
//						$z.=102;				// nadp³ata
//				}
//				else {
//						$z.=1102;			// nadp³ata
//				}
						$z.=26;			// nadp³ata
						$z.="','";
						$z.=$dane['KWOTA'];
						$z.="','";
						$z.=$dane['NUMERRN'].$dane['MASKARN'];
						$z.="','/','";
						$z.=$dane['DATAWPLATY'];
						$z.="','";
						$z.=$dane['DATAPRZYJ'];
						$z.="','";
						$z.=$wp['DODNIA'];
						$z.="',";
						$z.=$dane['IDOPERATOR'];
						$z.=",'";
						$z.='';						//$wp['NRFAKTURY'];
						$z.="','";
						$z.='';						//$wp['NRPOZYCJI'];
						$z.="')";
						$w=mysql_query($z);					// zapis danych do "nadplaty"

						$z="INSERT INTO wplaty values (0,'";
						$z.=$wp['IDGRUPY'];
						$z.="','";
						$z.=$wp['IDABONENTA'];
						$z.="','";
						$z.=$wp['TYPINST'];
						$z.="','";
						$z.=$wp['RODZADM'];
						$z.="','";
						$z.=$wp['TYPTYTULU'];
						$z.="','";
						$z.=$wp['ZTYTULU'];
						$z.="','";
						$z.=(-$dane['KWOTA']);
						$z.="','";
						$z.=$dane['NUMERRN'].$dane['MASKARN'];
						$z.="','/','";
						$z.=$dane['DATAWPLATY'];
						$z.="','";
						$z.=$dane['DATAPRZYJ'];
						$z.="','";
						$z.=$wp['DODNIA'];
						$z.="',";
						$z.=$dane['IDOPERATOR'];
						$z.=",'";
						$z.='';			//minusa nie fakturujemy $wp['NRFAKTURY'];
						$z.="','";
						$z.='';			//minusa nie fakturujemy $wp['NRPOZYCJI'];
						$z.="','";
						$z.=$wp['ZAMIESIAC'];
						$z.="',";
						$z.=$wp['ID_OPLATY'];
						$z.=")";
						$w=mysql_query($z);					// zapis danych do "nadplaty"
}

//przesuniêcie op³at fakturowanych do nadp³at
$z="select * from oplaty where IDABONENTA=$ida and DODNIA>='".$da."'";
$ww=mysql_query($z);
while ($wp=mysql_fetch_array($ww)) {	//kolejne pozycje op³at

	$dane['KWOTA']=$wp['KWOTA'];

					$z="Select * from typydok where LITERA='/' limit 1";
					$w=mysql_fetch_array(mysql_query($z));		// mamy ostatni NUMER RN z "typydok"

					$dane['NUMERRN']=trim($w['NUMER']);
					$dane['MASKARN']=trim($w['MASKA']);

					$dane['NUMERRN']=($dane['NUMERRN']+1);

					$z="update typydok set NUMER='";				// zapisz ¿e zwiêkszono numer RN
					$z.=$dane['NUMERRN'];
					$z.="', DATA='";
					$z.=$dane['DATAWPLATY'];
					$z.="' where LITERA='/' limit 1";
					$w=mysql_query($z);

					$z="INSERT INTO dokwplat values (0,'";		// zapis danych do "dokwplat"
					$z.='/';
					$z.="','";
					$z.=$dane['NUMERRN'].$dane['MASKARN'];
					$z.="','";
					$z.=$dane['DATAPRZYJ'];
					$z.="','";
					$z.=$dane['IDOPERATOR'];
					$z.="','1','','','";
					$z.=$wp['IDABONENTA'];
					$z.="','";
					$z.=$dane['KWOTA'];
					$z.="','',Now())";
					$w=mysql_query($z);

						$z="INSERT INTO nadplaty values (0,'";
						$z.=$wp['IDGRUPY'];
						$z.="','";
						$z.=$wp['IDABONENTA'];
						$z.="','";
						$z.=$wp['TYPINST'];
						$z.="','";
						$z.=$wp['RODZADM'];
						$z.="','";
						$z.=$wp['TYPTYTULU'];
						$z.="','";
						$z.=$wp['ZTYTULU'];
						$z.="','";
						$z.=$dane['KWOTA'];
						$z.="','";
						$z.=$dane['NUMERRN'].$dane['MASKARN'];
						$z.="','/','";
						$z.=$dane['DATAWPLATY'];
						$z.="','";
						$z.=$dane['DATAPRZYJ'];
						$z.="','";
						$z.=$wp['DODNIA'];
						$z.="',";
						$z.=$dane['IDOPERATOR'];
						$z.=",'";
						$z.=$wp['NRFAKTURY'];
						$z.="','";
						$z.=$wp['NRPOZYCJI'];
						$z.="')";
						$w=mysql_query($z);					// zapis danych do "nadplaty"

						$z="INSERT INTO nadplaty values (0,'";
						$z.=$wp['IDGRUPY'];
						$z.="','";
						$z.=$wp['IDABONENTA'];
						$z.="','";
						$z.=$wp['TYPINST'];
						$z.="','";
						$z.=$wp['RODZADM'];
						$z.="','";
						$z.=$wp['TYPTYTULU'];
						$z.="','";
						$z.=$wp['ZTYTULU'];
						$z.="','";
						$z.=(-$dane['KWOTA']);
						$z.="','";
						$z.=$dane['NUMERRN'].$dane['MASKARN'];
						$z.="','/','";
						$z.=$dane['DATAWPLATY'];
						$z.="','";
						$z.=$dane['DATAPRZYJ'];
						$z.="','";
						$z.=$wp['DODNIA'];
						$z.="',";
						$z.=$dane['IDOPERATOR'];
						$z.=",'";
						$z.='';								//$wp['NRFAKTURY'];
						$z.="','";
						$z.='';								//$wp['NRPOZYCJI'];
						$z.="')";
						$w=mysql_query($z);				// zapis danych do "nadplaty"

					$z="delete from oplaty where ID=".$wp['ID']." limit 1";
					$w=mysql_query($z);		// kasujemy pozycjê fakturowan¹ z oplat
}

$zz="Select sum(nadplaty.WYSWPL) from nadplaty where IDABONENTA=".$ida;
$ww=mysql_query($zz); $ww=mysql_fetch_row($ww);

$zz="update abonenci set NADPLATA=".$ww[0]." where ID=".$ida." limit 1";
$ww=mysql_query($zz);

include('oplatyzakaw.php');		//zak³adanie op³at abonenta na nowych warunkach (inna grupa)

}	//if ($a[1]*1<>$w['INFO']*1) {	//faktycznie grupa siê zmienia

$tabelaa='opldodA';	// tu l¹duje po akcji

//echo $raport;
?>
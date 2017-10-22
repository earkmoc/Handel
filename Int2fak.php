<?php

//synchroniacja szybka

//if ($_GET['automat']) {
//  header('Location: index.php');
//}

set_time_limit(3*60*60);	// 3h

require('funkcje.php');
require('dbconnect.inc');

$ido=$_SESSION['osoba_id'];

//T|Test|PlikPHP('Tabela_SQL.php','Test stanu w internecie ?','Int_test.php')
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<title>Parrot - Internet synchronizacja</title>

<script type="text/javascript" language="JavaScript">
<!--

function start() {
<?php
  if ($_GET['automat']) {
    echo 'window.close();';
  }
?>

}
-->
</script>

</head>
<body onload="start();">
<?php
echo $x='Synchronizacja szybka: start='.$start=date('Y.m.d / H.i.s');
mysql_query("insert into todo set IDABONENTA=0, IDOPERATOR=$ido, CZAS=Now(), TYTUL='$x', OPIS='$x', DATA=CurDate()");
echo "<br>";
flush();

$raport='';

//---------------------------------------------------------------------------------
//Czas ostatniej synchronizacji internet-->faktury

$czas='';

//---------------------------------------------------------------------------------
//Czas jakiejkolwiek ostatniej synchronizacji

$czas_syn='';
$z=("
	 SELECT WARTOSC
      from parametry
     where NAZWA='int2fak'
        or NAZWA='int_akt' 
  order by WARTOSC desc
     limit 1
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$czas_syn=$r[0];				//ostatni czas
}

$czas='';
$z=("
	SELECT WARTOSC
      from parametry
     where NAZWA='int2fak'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$czas=$r[0];				//ostatni czas
	mysql_query("
		update parametry
		   set WARTOSC=Now()
		 where NAZWA='int2fak'
	");							//nowy czas
} else {
	mysql_query("
		insert 
		  into parametry
		   set NAZWA='int2fak'
		     , WARTOSC=Now()
			 , OPIS='Czas ostatniej synchronizacji internet-->faktury'
	");							//pierwszy czas jest pusty, więc weźmie wszystkie dokumenty
}

require('dbdisconnect.inc');

//-----------------------------------------------------------------------------------

require('Int_dbconnect.php');

mysql_query("
	update orders
	   set orders_status=2
	 where date_purchased >= '$czas'
");

$orders=mysql_query("
	select *
	  from orders
	 where date_purchased >= '$czas'
");

$orders_products=mysql_query("
	select orders_products.products_model 	  as indeks
	     , orders_products.final_price		  as cena
		  , orders_products.products_tax		  as vat
		  , orders_products.products_quantity as ilosc
		  , orders_products.orders_id 		  as idzam
	     , orders_products.final_price*(100+orders_products.products_tax*1)*0.01		 as cenab
	     , orders.customers_nip  as nip
	  from orders_products
 left join orders
 		on orders.orders_id=orders_products.orders_id
	 where orders.date_purchased >= '$czas'
  order by orders_products.orders_id
");

//mysql_query("
//	update orders
//     set orders_status=3
//	 where orders.date_purchased >= '$czas'
//     and orders_status=2
//");

require('dbdisconnect.inc');

//---------------------------------------------------------------------------------

require('dbconnect.inc');

$lp=0;
$idorder=array();
while ($order=mysql_fetch_array($orders)) {

  $typ=(((trim($order['customers_nip']))=='')?'PI':'FI');

	$w=mysql_query("
		insert 
		  into dokum
		   set BLOKADA='O'
		     , TYP='$typ'
		     , INDEKS=''
		     , NABYWCA='' 
		     , WARTOSC='' 
		     , DATAW='".($order['date_purchased'])."'
		     , DATAS='".($order['date_purchased'])."'
		     , DATAO=''
		     , DATAT=''
		     , SPOSOB='".($order['payment_method'])."'
		     , WPLACONO=''
		     , NUMERFD='' 
		     , UWAGI='tel.: ".($order['customers_telephone']).", e-mail: ".($order['customers_email_address']).", dostawa na: ".($order['delivery_name']).", firma: ".($order['delivery_company']).", NIP: ".($order['delivery_nip']).", ul.: ".($order['delivery_street_address']).", miasto: ".($order['delivery_city']).", kod: ".($order['delivery_postcode']).", woj.: ".($order['delivery_state']).", kraj: ".($order['delivery_country'])."'
		     , CZAS='".($order['date_purchased'])."'
		     , VAT22=''
		     , VAT7=''
		     , NETTO22='' 
		     , NETTO7=''
		     , NETTO0=''
		     , NETTOZW =''
		     , NETTOCZ =''
		     , INDEKS_F='".($order['customers_name'])."'
		     , TYP_F='N'
		     , NAZWA='".($order['customers_company'])."'
		     , KOD='".($order['customers_postcode'])."'
		     , MIASTO='".($order['customers_city'])."'
		     , ADRES='".($order['customers_street_address'])."'
		     , NIP='".($order['customers_nip'])."'
		     , MAGAZYN='1' 
		     , WYSTAWIL=''
		     , ODEBRAL =''
		     , TOWCENNIK='1'
		     , TOWRABAT=''
		     , NETTO23 =''
		     , VAT23=''
		     , NETTO8=''
		     , VAT8=''
		     , NETTO5=''
		     , VAT5=''
		     , DRUKOWANO=''
		     , DNIZWLOKI=''
		     , NETTODOS=''
		     , BRUTTODOS=''
		     , WYDAL=''
		     , PRZYG=''
	");

	$idd=mysql_insert_id();
	$idorder[$lp++]=($order['orders_id'].','.$idd);
		
	$raport.="<br><br>$lp. ".($order['customers_name']).', '.($order['customers_company']).", tel.: ".($order['customers_telephone']).", e-mail: ".($order['customers_email_address']).", dostawa na: ".($order['delivery_name']).", firma: ".($order['delivery_company']).", NIP: ".($order['delivery_nip']).", ul.: ".($order['delivery_street_address']).", miasto: ".($order['delivery_city']).", kod: ".($order['delivery_postcode']).", woj.: ".($order['delivery_state']).", kraj: ".($order['delivery_country']);

	$z="select NUMER, MASKA, Year(CurDate()), CZAS from doktypy where TYP='$typ' limit 1";
	$w=mysql_query($z); $w=mysql_fetch_row($w);
	$nrdok=$w[0];
	$maska=$w[1];
	$rok=$w[2];
	$czas=$w[3];
	$maska=str_replace('rok',$rok,$maska);
	$maska=str_replace('rocznik',substr($rok,-2,2),$maska);
	$z=1;
	while ($z>0) {
		$nrdok=$nrdok+1;
		if (substr($czas,0,4)<>$rok) {
			$nrdok=1;
		}
		if ($maska*1>0) {
			$nrdok=substr('00000000000000000'.$nrdok,-$maska*1,$maska*1);
			$maska=substr($maska,1);
		}
		$z="select count(*) from dokum where TYP='$typ' and INDEKS='$nrdok$maska'";
		$z=mysql_query($z);
		$z=mysql_fetch_row($z);
		$z=$z[0];
	}
	$z="update doktypy set NUMER=$nrdok where TYP='$typ' limit 1";$w=mysql_query($z);
	$z="update dokum set INDEKS='$nrdok$maska' where ID=$idd limit 1";$w=mysql_query($z);

}

while ($orders_product=mysql_fetch_array($orders_products)) {

//ustalenie ID dokumentu FI w handlu dla tego zamówienia

	$idd=0;
	for ($i=0;$i<$lp;$i++) {
		$w=explode(',',$idorder[$i]);	//($order['orders_id'].','.$idd);
		if ((1*$w[0])==(1*$orders_product['idzam'])) {
			$idd=1*$w[1];
		}
	}

//ustalenie ID towaru w handlu dla kolejnego towaru z zamówienia

	$idt=($orders_product['indeks']);	//indeks w formacie '9999-9999'

	$w=mysql_query("
		select ID
		  from towary
		 where INDEKS='$idt'
		   and STATUS='T'
	");

	$idt=0;
	if ($r=mysql_fetch_row($w)) {
		$idt=$r[0];
	}
	
//wpisanie w handlu co się da

  $typ=(((trim($orders_product['nip']))=='')?'PI':'FI');
                       
	if ($idd&&$idt) {
		$w=mysql_query("
			insert 
			  into spec
			   set ID_D='$idd'
			     , ID_T='$idt'
			     , CENA='".($orders_product['cena'])."'
			     , ILOSC='".($orders_product['ilosc'])."'
			     , RABAT=''
			     , CENABEZR='".($orders_product[(($typ=='FI')?'cena':'cenab')])."'
			     , NETTO=''
			     , KWOTAVAT=''
			     , BRUTTO=''
			     , CENABRUTTO='".($orders_product[(($typ=='FI')?'cenab':'cenab')])."'
			     , STAWKAVAT='".($orders_product['vat']*1)."%'
		");
	} else {
		$raport.="<br>Nieznaleziony towar w magazynie: indeks ".($orders_product['indeks']).", cena netto ".($orders_product['cena']).", cena brutto ".($orders_product['cenab']).", ilość ".($orders_product['ilosc']).", vat ".($orders_product['vat']);
	}
}

//---------------------------------------------------------------------------------
//---------------------------------------------------------------------------------
//teraz towary do sklepu

$czas=$czas_syn;

$z=("SELECT 
           100000000+replace(towary.INDEKS,'-','')
         , towary.INDEKS
         , towary.NAZWA
         , towary.CENA_S
         , if(towary.VAT='23%',1,if(towary.VAT='8%',2,if(towary.VAT='5%',3,4)))
         , towary.STAN 
         , left(towary.KATEGORIA,32)
         , towary.OPIS
         , towary.ZDJECIE
         , left(upper(towary.PRODUCENT),32)
         , towary.DOSTAWCA
         , firmy.INDEKS
         , towary.INDEKS2
      from towary
 left join firmy 
        on firmy.ID=towary.DOSTAWCA
     where towary.STATUS='T'
       and towary.CZAS_OZ>='$czas'
  order by towary.INDEKS
");

$ww=mysql_query($z);

//require('Int2fak_kategorie_pre.php');
//require('Int2fak_producenci_pre.php');

require('dbdisconnect.inc');  //handel off

require('Int_dbconnect.php'); //sklep on

//require('Int2fak_kategorie.php');
//require('Int2fak_producenci.php');

//---------------------------------------------------------------------------------

$i=0;
while ($rr=mysql_fetch_row($ww)) {

  $i++;
  
  $problem='';
  $id=$rr[0];
  $indeks=$rr[1];
  $nazwa=AddSlashes(trim(StripSlashes($rr[2])));
  $cena=$rr[3];
  $cena2=myRound($rr[3]*1.15);
  $vat=$rr[4];
  $stan=$rr[5];
  $kategoria=AddSlashes($rr[6]);
  $kategoria=($kategoria?$kategoria:'inne');
  $opis=AddSlashes(trim(StripSlashes($rr[7])));
  $zdjecie=$rr[8];
  $producent=trim($rr[9]);
  $dostawca=$rr[10];
  $dostawcaIndeks=$rr[11];
  $indeks2=$rr[12];
             
   $z=("
		select manufacturers_id
	     from manufacturers
		 where manufacturers_name=upper('$producent')
	");
   $w=mysql_query($z);
	$id_man=0;
	if (($r=mysql_fetch_row($w))&&($r[0]>0)) {
		$id_man=$r[0];
	}

   $z=("
  		select count(*)
          from `products`
		 where `products_id`=$id
   ");
   $w=mysql_query($z);
   if (!$r=mysql_fetch_row($w)) {
	   echo "<br>$z<br>";
   }

   if ($r[0]==0) {

     $w=mysql_query("
		 insert 
			into `products` (
				  `products_id`
				 ,`products_quantity`
				 ,`products_model`
				 ,`products_image`
				 ,`products_price_2`
				 ,`products_status`
				 ,`products_tax_class_id`
				 ,`manufacturers_id`
				 ,`products_date_added`
				 ,`products_last_modified`
				 ,`products_availability_id`
				 ,`products_price`
				 ,`dostawca_id`
				 ,`kod_dost`
				 ,`INDEKS2`
				 ) 
		 values (
		 		  $id
				 ,$stan
				 ,'$indeks'
				 ,'$zdjecie'
				 ,'$cena'
				 ,1
				 ,$vat
				 ,$id_man
				 ,Now()
				 ,Now()
				 ,'1'
				 ,'$cena2'
				 ,'$dostawca'
				 ,'$dostawcaIndeks'
				 ,'$indeks2'
				)
    on duplicate key update products_id=products_id
    ");

     $w=mysql_query("
		  INSERT 
		    INTO `products_description` (
		         `products_id`
			    , `language_id`
			    , `products_name`
			    , `products_description`
			   ) 
		VALUES (
				  $id
				 ,1
				 ,'$nazwa'
				 ,'$opis'
				)
    on duplicate key update products_id=products_id
    ");
   
    $z=("
		select categories_id
	     from categories_description
		 where categories_name='$kategoria'
	 ");
    $w=mysql_query($z);
	 if (($r=mysql_fetch_row($w))&&($r[0]>0)) {
		 $id_cat=$r[0];
	 } else {
	   $w=mysql_query("INSERT INTO `categories` (`categories_id`, `date_added`) 
						VALUES (0, Now())");
		$id_cat=mysql_insert_id();
	    $w=mysql_query("INSERT INTO `categories_description` (`categories_id`,`language_id`,`categories_name`) 
						VALUES ($id_cat,1,'$kategoria')");
	 }
    $w=mysql_query("INSERT INTO `products_to_categories` (`products_id`, `categories_id`) 
					VALUES ($id,$id_cat)");

  } else {

    $w=mysql_query("select `products_model`
                          ,`products_quantity`
                          ,`products_price_2`
                          ,`products_tax_class_id`
                          ,`products_price`
                 				  ,`dostawca_id`
                				  ,`kod_dost`
                				  ,`INDEKS2`
                      from `products`
                     where `products_id`=$id");
    $r=mysql_fetch_row($w);
    if ($r[0]<>$indeks) {
      $problem.="INDEKS:   <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[0]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $indeks </font><br>";
    }

    if ($r[1]<>$stan) {
      $w=mysql_query("update `products`
                         set `products_quantity` = $stan
                       where `products_id`=$id");
    }

    if ($r[2]<>$cena) {
      $w=mysql_query("update `products`
                         set `products_price_2` = $cena
                       where `products_id`=$id");
      $problem.="CENA <u><b>poprawiona</b></u> (hurtowa):  <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[2]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $cena </font><br>";
    }

    if ($r[4]<>$cena2) {
      $w=mysql_query("update `products`
                         set `products_price` = $cena2
                       where `products_id`=$id");
      $problem.="CENA <u><b>poprawiona</b></u> (wyższa o 15% od hurtowej)  <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[4]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $cena2 </font><br>";
    }

    if ($r[3]<>$vat) {
      $problem.="VAT: <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[3]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $vat </font><br>";
    }
    
    if ($r[5]<>$dostawca) {
      $w=mysql_query("update `products`
                         set `dostawca_id` = '$dostawca'
                       where `products_id`=$id");
      $problem.="DOSTAWCA <u><b>poprawiony</b></u> <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[5]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $dostawca </font><br>";
    }

    if ($r[6]<>$dostawcaIndeks) {
      $w=mysql_query("update `products`
                         set `dostawca_id` = '$dostawcaIndeks'
                       where `products_id`=$id");
      $problem.="Indeks (KOD) dostawcy <u><b>poprawiony</b></u> <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[6]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $dostawcaIndeks </font><br>";
    }

    if ($r[7]<>$indeks2) {
      $w=mysql_query("update `products`
                         set `INDEKS2` = '$indeks2'
                       where `products_id`=$id");
      $problem.="Indeks katalogowy towaru <u><b>poprawiony</b></u> <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[7]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $indeks2 </font><br>";
    }





    $w=mysql_query("select `products_name`
						  ,`products_description`
                      from `products_description`
                     where `products_id`=$id");
    $r=mysql_fetch_row($w);
    if (
          (trim(StripSlashes($nazwa))<>StripSlashes($r[0]))
        &&(trim(StripSlashes($nazwa))<>'')
      ) {
		//$nazwa=trim(StripSlashes($nazwa));
	    $w=mysql_query("update `products_description`
						   set `products_name`='$nazwa'
	                     where `products_id`=$id
	                     limit 1
		");
		if (trim(substr(trim($r[0]),0,strlen($nazwa)))<>$nazwa) {
      $problem.="NAZWA <u><b>poprawiona</b></u>: <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[0]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $nazwa</font><br>";
		}
    }

    if (
          (trim(StripSlashes($opis))<>trim(StripSlashes($r[1])))
        &&(trim(StripSlashes($opis))<>'')
      ) {
		//$opis=trim(StripSlashes($opis));
	    $w=mysql_query("update `products_description`
						   set `products_description`='$opis'
	                     where `products_id`=$id
	                     limit 1
		");
      $problem.="OPIS <u><b>poprawiony</b></u>: <font style='font-family: Courier New'><br>
&nbsp;&nbsp;&nbsp;Internet (było): $r[1]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Handel (jest): $opis</font><br>";
    }

   if ($problem) {
      $problem="Indeks: Internet: $id, Magazyn: ".substr($id,-8,4).'-'.substr($id,-4,4)."<br>".$problem;
      $problem='----------------------------------------------------------------<br>'.$problem;
      echo $problem;
      $raport.=$problem;
   }  
//    $w=mysql_query("select `categories_id`
//                      from `products_to_categories`
//                     where `products_id`=$id");
//    $r=mysql_fetch_row($w);
//    if ($r[0]<>2) {
//      echo "Kategoria: $r[0]<>2 where `products_id`=$id<br>";
//    }
  }
}

//usunięcie zdublowanych ostatnio automatycznie dodanych powiązań: towar-kategoria

$w=mysql_query("  
CREATE temporary TABLE `ptc` (
  `products_id` int(11) NOT NULL default '0',
  `categories_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`products_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin2;
");

$w=mysql_query("  
insert into ptc 
SELECT * FROM products_to_categories group by products_id having count(*)>1;
");

$w=mysql_query("  
   update products_to_categories 
left join ptc
       on products_to_categories.products_id=ptc.products_id
      and products_to_categories.categories_id=ptc.categories_id
      set products_to_categories.categories_id=-2 
    where ptc.categories_id=2;
");

$w=mysql_query("  
   delete 
     from products_to_categories 
    where products_to_categories.categories_id=-2;
");

//---------------------------------------------------------------------------------

require('dbdisconnect.inc');  //sklep off


echo "<br>Start: ".$start;
echo "<br> Stop: ".date('Y.m.d / H.i.s');
echo "<br><br><a href='index.php'>Powrót</a>";
echo "<br><br>$raport<br><br>";

require('dbconnect.inc');     //handel on

mysql_query("insert into todo set IDABONENTA=0, IDOPERATOR=$ido, CZAS=Now(), TYTUL='Raport', OPIS='$raport', DATA=CurDate()");

//---------------------------------------------------------------------------------

$opispop='';
$sumpop=0;

$w=mysql_query("
    select WARTOSC
          ,if(OPIS='',WARTOSC,OPIS) as kat_main
      from parametry
     where NAZWA='Kategorie'
  order by if(OPIS='',WARTOSC,OPIS), if(OPIS='','',WARTOSC)
"); 
while ($r=mysql_fetch_array($w)) {
  $naz=$r[WARTOSC];
  $ww=mysql_query("
    select count(*)
      from towary
     where STATUS='T'
       and KATEGORIA='$naz'
  ");
  if ($opispop<>$r[kat_main]) {
    $opispop=$r[kat_main];
    if ($sumpop<>0) {
      echo "Razem: $sumpop<br>";
      $sumpop=0;
    }
    echo "<br>";
  }
  if ($rr=mysql_fetch_row($ww)) {
    echo "$naz = $rr[0]<br>";
    $sumpop+=$rr[0];
  }
}
         

//---------------------------------------------------------------------------------
exit;

?>
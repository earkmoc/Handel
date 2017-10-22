<?php

//synchronizacja pełna

set_time_limit(3*60*60);	// 3h

require('funkcje.php');
require('dbconnect.inc');

$ido=$_SESSION['osoba_id'];

//T|Test|PlikPHP('Tabela_SQL.php','Test stanu w internecie ?','Int_test.php')

echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Parrot - Internet synchronizacja</title></head><body>';
echo $x='Synchronizacja: start='.$start=date('Y.m.d / H.i.s');
mysql_query("insert into todo set IDABONENTA=0, IDOPERATOR=$ido, CZAS=Now(), TYTUL='$x', OPIS='$x', DATA=CurDate()");
echo "<br>";
flush();

//---------------------------------------------------------------------------------
// nie zmieniamy pola towary.CZAS_OZ

mysql_query("ALTER TABLE towary CHANGE CZAS_OZ CZAS_OZ TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

//---------------------------------------------------------------------------------
// pole DOSTEPNY domyślnie przed synchronizacją = 1

mysql_query("update towary set DOSTEPNY=1");

//---------------------------------------------------------------------------------
//Czas ostatniej synchronizacji Handel-->internet

$czas='';
$z=("
	SELECT WARTOSC
      from parametry
     where NAZWA='int_akt'
");
$w=mysql_query($z);
if ($r=mysql_fetch_row($w)) {
	$czas=$r[0];				//ostatni czas
	mysql_query("
		update parametry
		   set WARTOSC=Now()
			 , OPIS='Czas ostatniej synchronizacji Handel-->internet'
		 where NAZWA='int_akt'
	");							//nowy czas
} else {
	mysql_query("
		insert 
		  into parametry
		   set NAZWA='int_akt'
		     , WARTOSC=Now()
			 , OPIS='Czas ostatniej synchronizacji Handel-->internet'
	");							//pierwszy czas jest pusty, wiŠc wejdĽ wszystkie dokumenty
}

//-----------------------------------------------------------------------------------
//pobranie ostatnich zmian po stronie sklepu (zdjęcia, opisy, dost=0) 

require('dbdisconnect.inc');  //handel off

require('Int_dbconnect.php'); //sklep on

$products=mysql_query("
	select products.products_model as indeks
	     , products_description.products_description as opis
	     , if(isnull(products.products_image),'',products.products_image) as zdjecie
	     , if(isnull(products.dost),1,products.dost) as dost
	  from products_description
 left join products 
	    on products.products_id=products_description.products_id
	 where substr(products.products_model,5,1)='-'
	   and   (  (      !isnull(products_description.products_description)
	   		      and products_description.products_description<>''
               )
			      or
			      (		!isnull(products.products_image)
	   			   and products.products_image<>''
			      )
			      or
			      (		!isnull(products.dost)
	   			   and products.dost<>1
			      )
		       )
");

require('dbdisconnect.inc');  //sklep off

//---------------------------------------------------------------------------------
//zapis zdjęć, opisów i dost po stronie handlu

require('dbconnect.inc');     //handel on

$lp=0;
$raport='';
while ($product=mysql_fetch_array($products)) {

	$indeks=$product['indeks'];
	$opis=AddSlashes(strip_tags($product['opis']));
	$zdjecie=AddSlashes(trim(StripSlashes($product['zdjecie'])));
	$dost=$product['dost'];

	$z=("
		update towary
		   set ZDJECIE='$zdjecie'
          ,OPIS='$opis' 
          ,DOSTEPNY='$dost' 
		 where INDEKS='$indeks'
	");

	$w=mysql_query($z);
  if(mysql_error($w)) {
    $raport.="<br>$lp. $indeks, Error: ".mysql_error($w);
  }

	$lp++;
	//$raport.="<br><br>$lp. $indeks<br>Opis: $opis";	//<br><br>Query: $z
}

//---------------------------------------------------------------------------------
//teraz wszystkie towary do sklepu

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
         , towary.DOSTEPNY
      from towary
 left join firmy 
        on firmy.ID=towary.DOSTAWCA
     where towary.STATUS='T'
  order by towary.ID
");
//       and CZAS_OZ>='$czas'

$ww=mysql_query($z);

//---------------------------------------------------------------------------------
//teraz wszystkie kategorie do sklepu

$z=("
    SELECT *
      from parametry
     where NAZWA='Kategorie'
  order by LP, ID
");

$ww_kat=mysql_query($z);

//---------------------------------------------------------------------------------
//teraz wszyscy producenci do sklepu

$z=("
    SELECT upper(WARTOSC)
      from parametry
     where NAZWA='Producenci'
  order by WARTOSC
");

$ww_pro=mysql_query($z);

require('dbdisconnect.inc');  //handel off

require('Int_dbconnect.php'); //sklep on

//---------------------------------------------------------------------------------
//teraz wszystkie kategorie do sklepu

mysql_query("truncate categories");
mysql_query("truncate categories_description");

$katestru=array();
while ($r=mysql_fetch_array($ww_kat)) {
   $kategoria=$r['WARTOSC'];
   if ($kateparent=$r['OPIS']) {
      $parent_id=$katestru[$kateparent];
      $w=mysql_query("
      	INSERT INTO `categories` (`categories_id`, `parent_id`, `date_added`) VALUES (0, $parent_id, Now())
      ");
   } else {
      $w=mysql_query("
      	INSERT INTO `categories` (`categories_id`, `date_added`) VALUES (0, Now())
      ");
   }
   $id_cat=mysql_insert_id();
   $w=mysql_query("
   	INSERT INTO `categories_description` (`categories_id`,`language_id`,`categories_name`) VALUES ($id_cat,1,'$kategoria')
   ");
   $katestru[$kategoria]=$id_cat;
}

mysql_query("truncate products_to_categories");

//usunięcie ostatnio automatycznie dodanych powiązań: towar-kategoria
//mysql_query("
//      update products_to_categories
//   left join products_description
//          on (products_description.products_id = products_to_categories.products_id )
//         set products_to_categories.categories_id = 9999
//       where isnull(products_description.products_description) 
//         and products_to_categories.categories_id = 2
//");

//mysql_query("
// delete from products_to_categories
//       where products_to_categories.categories_id = 9999
//");

//usunięcie ostatnio automatycznie dodanych opisów towarów

mysql_query("
  delete 
	 from products_description 
   where products_id between 100000000 and 200000000
");

//mysql_query("
//      update products_description
//   left join products
//          on products_description.products_id = products.products_id 
//         set products_description.language_id = 9999
//       where isnull(products_description.products_description) 
//         and isnull(products.products_image)
//         and isnull(products.products_last_modified)
//");

//mysql_query("
// delete from products_description
//       where products_description.language_id = 9999
//");

//usunięcie ostatnio automatycznie dodanych towarów

mysql_query("
  delete 
	 from products 
   where products_id between 100000000 and 200000000
");

//mysql_query("
//      update products
//   left join products_description
//          on products_description.products_id = products.products_id 
//         set products.manufacturers_id = 9999
//       where isnull(products_description.products_description) 
//         and isnull(products.products_image)
//         and isnull(products.products_last_modified)
//");

//mysql_query("
// delete from products
//       where products.manufacturers_id = 9999
//");

//---------------------------------------------------------------------------------
//teraz wszyscy producenci do sklepu

mysql_query("truncate manufacturers");
mysql_query("truncate manufacturers_info");

while ($r=mysql_fetch_row($ww_pro)) {
   $producent=$r[0];
	mysql_query("INSERT INTO `manufacturers` (`manufacturers_id`, `manufacturers_name`, `date_added`) 
      VALUES (0, '$producent', Now())");
	$id_man=mysql_insert_id();
   mysql_query("INSERT INTO `manufacturers_info` (`manufacturers_id`,`languages_id`,`manufacturers_htc_title_tag`, `manufacturers_htc_desc_tag`, `manufacturers_htc_keywords_tag` ) 
		VALUES ($id_man,1, '$producent', '$producent', '$producent')");
}

//mysql_query("delete from `manufacturers` where isnull(manufacturers_image)");
//mysql_query("delete from `manufacturers_info` where isnull(manufacturers_htc_description)");

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
  $dost=$rr[13];
             
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
				 ,`dost`
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
				 ,'$dost'
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
                				  ,`dost`
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

    if ($r[8]<>$dost) {
      $w=mysql_query("update `products`
                         set `dost` = '$dost'
                       where `products_id`=$id
      ");
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

require('dbconnect.inc');     //handel on

mysql_query("insert into todo set IDABONENTA=0, IDOPERATOR=$ido, CZAS=Now(), TYTUL='Raport', OPIS='$raport', DATA=CurDate()");

//---------------------------------------------------------------------------------
// znów zmieniamy pole towary.CZAS_OZ
mysql_query("ALTER TABLE towary CHANGE CZAS_OZ CZAS_OZ TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

echo "<br>Start: ".$start;
echo "<br> Stop: ".date('Y.m.d / H.i.s');
echo "<br><br><a href='index.php'>Powr˘t</a>";
flush();
exit;

?>
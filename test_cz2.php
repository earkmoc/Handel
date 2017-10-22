<?php

set_time_limit(10*60);	// 10 min

require('dbconnect.inc');

echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
echo '<title>Pobranie opisów towarów z internetu</title></head><body>';
echo 'Pobranie opisów towarów z internetu: start='.$start=date('Y.m.d / H.i.s');
echo "<br>";

require('dbdisconnect.inc');

//-----------------------------------------------------------------------------------

require('Int_dbconnect.php');

$products=mysql_query("
	select products.products_model as indeks
	     , if(isnull(products_description.products_description),'',products_description.products_description) as opis
	     , if(isnull(products.products_image),'',products.products_image) as zdjecie
	  from products_description
 left join products 
	    on products.products_id=products_description.products_id
	 where substr(products.products_model,5,1)='-'
	   and ((		!isnull(products_description.products_description)
	   			and products_description.products_description<>''
			)
			or
			(		!isnull(products.products_image)
	   			and products.products_image<>''
			)
		   )
");

require('dbdisconnect.inc');

//---------------------------------------------------------------------------------

require('dbconnect.inc');

$lp=0;
$raport='';
while ($product=mysql_fetch_array($products)) {

	$opis=AddSlashes(strip_tags($product['opis']));
	$indeks=$product['indeks'];
	$zdjecie=AddSlashes($product['zdjecie']);

	$z=("
		update towary
		   set OPIS='$opis'
		     , ZDJECIE='$zdjecie'
		 where INDEKS='$indeks'
	");

	$w=mysql_query($z);

	$lp++;
	$raport.="<br><br>$lp. $indeks<br>Opis: $opis";	//<br><br>Query: $z
}

//---------------------------------------------------------------------------------

echo "<br>Start: ".$start;
echo "<br> Stop: ".date('Y.m.d / H.i.s');
echo "<br><br><a href='index.php'>Powrót</a>";
echo "<br><br>$raport";
exit;

?>
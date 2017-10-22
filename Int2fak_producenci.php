<?php

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

?>

<?php

//---------------------------------------------------------------------------------
//teraz wszystkie kategorie do sklepu

mysql_query("truncate categories");
mysql_query("truncate categories_description");

$katestru=array();
while($r=mysql_fetch_array($ww_kat)) {
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

?>

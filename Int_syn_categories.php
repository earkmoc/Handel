<?php

require('dbdisconnect.inc');

require('Int_dbconnect.php');
$w=mysql_query("  
               select `categories_name`
                    , `categories_heading_title`
                    , `categories_id`
                 from categories_description 
");
require('dbdisconnect.inc');

require('dbconnect.inc');
mysql_query("delete from parametry where NAZWA='Kategorie'");
while ($r=mysql_fetch_array($w)) {
   mysql_query("  insert into parametry
               select 0
                    , 'Kategorie'
                    , '".$r['categories_name']."'
                    , '".$r['categories_heading_title']."'
                    , '".$r['categories_id']."'
   ");
}

require('dbdisconnect.inc');
?> 
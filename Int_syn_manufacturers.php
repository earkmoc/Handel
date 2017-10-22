<?php
require('Int_dbconnect.php');
$w=mysql_query("  
               select `manufacturers_name`
                    , `manufacturers_id`
                 from manufacturers
");
require('dbdisconnect.inc');

require('dbconnect.inc');
mysql_query("delete from parametry where NAZWA='Producenci'");
while ($r=mysql_fetch_array($w)) {
   mysql_query("
          insert into parametry
               select 0
                    , 'Producenci'
                    , '".$r['manufacturers_name']."'
                    , ''
                    , '".$r['manufacturers_id']."'
   ");
}
?> 
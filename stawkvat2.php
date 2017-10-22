<?php

$z='truncate table stawkvat3'; $w=mysql_query($z);
$z='insert into stawkvat3 select * from stawkvat order by DATASTVAT desc'; $w=mysql_query($z);
$z='truncate table stawkvat2'; $w=mysql_query($z);
$z='insert into stawkvat2 select * from stawkvat3 group by ZTYTULU'; $w=mysql_query($z);

?>
<?php
//$ipole=$_POST['ID'];Q|Q|PlikPHP('Tabela_SQL.php','','update todo set IDOPERATOR=2 where ID=id_master')
$z="update todo set IDOPERATOR=$ido, CZASZ=Now() where ID=$ipole";
$w=mysql_query($z);
?>
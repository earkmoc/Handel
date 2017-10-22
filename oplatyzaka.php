<?php

require('dbconnect.inc');
include('oplatyzakaw.php');
require('dbdisconnect.inc');

?>
</title>
</head>
<?php
echo "<body bgcolor='#0F4F9F' onload=";
echo '"location.href=';
echo "'Tabela.php?tabela=";
echo $_POST['natab'];
echo "'\">";
//echo "\n";
//echo $sql;
//echo '<hr><font style="font-size:100">';
//echo 'KP cofniête';
//echo "</font>";
//echo "<br><hr><a href='Tabela.php?tabela=";
//echo $_POST['natab'];
//echo "'>Powrót</a>";
?>
</body>
</html>
<?php

echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
echo "<title>Wykonanie SQL</title></head><body bgcolor='red' ";
echo "onload='";
echo 'document.getElementById("pole").focus()';
echo '\'>';
echo "<h1>$komunikat</h1>";
echo '<a id="pole" href="Tabela.php?tabela='.$natab.'">Wciśnij klawisz ENTER</a>';
echo $raport_sql;
echo '<bgsound src="ringin.wav" loop="1">';
echo '</body></html>';
<?php

$sf="spec_calc";
$sp="../test/";
$se=".txt";

$df=$sf;
$dp="./";
$de=".php";

$z="$sp$sf$se";

if (!$q=file_get_contents($z)) {
    die('<font color="red"><b>PHP error: </b></font>'."file_get_contents('$z')<br>");
}

echo "<form name='dokument' method='post' action='test_zapisz.php'>";

echo "<input name='sourpath' value='$sp' size='40'/>";
echo "<input name='sourfile' value='$sf' />";
echo "<input name='sourext' value='$se' /> sour ...";

echo '<br>';

echo "<input name='destpath' value='$dp' size='40' />";
echo "<input name='destfile' value='$df' />";
echo "<input name='destext' value='$de' /> dest ...";

echo '<br>';

echo "<textarea id='pole1' rows=30 cols=160 name='content'>";
//echo AddSlashes($q);
echo str_replace('textarea','text_area',$q);
//echo ($q);
echo "</textarea>";

echo "<br>";

echo "<input type='submit' value=' Zapisz ' />";
echo "<input type='reset' value=' Anuluj ' />";

echo "</form>";
exit;
?>

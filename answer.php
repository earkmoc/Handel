<?php
$s='';
$row=0;
$filename = "/Wydruki/Answer.txt";
$handle = fopen($filename, "r");
while (($data = fgetcsv($handle, 99, ",")) !== FALSE) {
    $num = count($data);
    $row++;
    $s=$s.(($row==1?"":",").trim($data[0]));
}
fclose($handle);

echo $s;

//unlink($filename);
?>

<?php

date_default_timezone_set('Europe/Paris'); 

echo ("<br>".'ZMIENNE GET :'."<br>");
echo (skladuj_tablice($_GET));
echo ("<br>".'ZMIENNE POST :'."<br>");
echo (skladuj_tablice($_POST));
echo ("<br>".'ZMIENNE SESJI :'."<br>");
echo (skladuj_tablice($_SESSION));
echo ("<br>".'ZMIENNE COOKIE :'."<br>");
echo (skladuj_tablice($_COOKIE));

function skladuj_tablice($tablica)
{
  if(is_array($tablica)) {
    $wielkosc = count($tablica);
    if($wielkosc) {
      $ciag = '<table>';
      $licznik = 0;
      // dodanie klucza i wartoœci ka¿dego elementu do ci¹gu
      foreach($tablica as $zmienna => $wartosc) {
          $ciag .= "<tr><td align='right'>$zmienna </td><td>= $wartosc</td></tr>";
        if($licznik++ < ($wielkosc-1)) {
//          $ciag .= '<br>';
        }
      }
      $ciag.='</table>';
    }
    return $ciag;
  }
  else
  {
    // je¿eli nie jest to tablica, po prostu zwrócenie
    return $tablica;
  }
}
?>
<?php

//require('skladuj_echozmienne.php');

echo ('<-- ROZPOCZÊCIE SK£ADOWANIA ZMIENNYCH --> <br>'."\n");
echo ('<-- ---------------------------------------------------------- --> <br>'."\n");
echo ('<-- ZMIENNE GET --> <br>'."\n");
echo ('<-- '.skladuj_tablice($_GET).' --> <br>'."\n");
echo ('<-- ---------------------------------------------------------- --> <br>'."\n");
echo ('<-- ZMIENNE POST --> <br>'."\n");
echo ('<-- '.skladuj_tablice($_POST).' --> <br>'."\n");
echo ('<-- ---------------------------------------------------------- --> <br>'."\n");
echo ('<-- ZMIENNE SESJI --> <br>'."\n");
echo ('<-- '.skladuj_tablice($_SESSION).' --> <br>'."\n");
echo ('<-- ---------------------------------------------------------- --> <br>'."\n");
echo ('<-- ZMIENNE COOKIE --> <br>'."\n");
echo ('<-- '.skladuj_tablice($_COOKIE).' --> <br>'."\n");
echo ('<-- ---------------------------------------------------------- --> <br>'."\n");
echo ('<-- ZAKOÑCZENIE SK£ADOWANIA ZMIENNYCH --> <br>'."\n");

function skladuj_tablice($tablica) {
  if (is_array($tablica)) {
    $wielkosc = count($tablica);
    $ciag = '';
    if ($wielkosc) {
      $licznik = 0;
      $ciag .= '{<br>';
      // dodanie klucza i warto&micro;ci ka¿dego elementu do ci¹gu
      foreach($tablica as $zmienna => $wartosc) {
        $ciag .= "$zmienna='$wartosc'";
        if($licznik++ < ($wielkosc-1))
        {
          $ciag .= ";<br>\n";
        }
      }
      $ciag .= ";<br>\n}";
    }
    return $ciag;
  } else {
    // je¿eli nie jest to tablica, po prostu zwrócenie
    return $tablica;
  }
}
?>
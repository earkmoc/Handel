<?php

$tmpfname=date('Y.m.d_H.i.s_').(1*microtime()).'.txt';
$file=fopen($tmpfname,"w");
if (!$file) {
    echo "<p>Nie mo�na otworzy� pliku do zapisu.\n";
    exit;
}

  // poni�szy kod formatuje wyniki jako komentarze HTML
  // i wywo�uje regularnie funkcj� skladuj_tablice()

fputs($file,'<!-- ROZPOCZ�CIE SK�ADOWANIA ZMIENNYCH -->'."\n");
fputs($file,'<!-- ZMIENNE GET -->'."\n");
fputs($file,'<!�'.skladuj_tablice($_GET).' -->'."\n");
fputs($file,'<!-- ZMIENNE POST -->'."\n");
fputs($file,'<!�'.skladuj_tablice($_POST).' -->'."\n");
fputs($file,'<!-- ZMIENNE SESJI -->'."\n");
fputs($file,'<!�'.skladuj_tablice($_SESSION).' -->'."\n");
fputs($file,'<!-- ZMIENNE COOKIE -->'."\n");
fputs($file,'<!�'.skladuj_tablice($HTTP_COOKIE_VARS).' -->'."\n");
fputs($file,'<!-- ZAKO�CZENIE SK�ADOWANIA ZMIENNYCH -->'."\n");

fclose($file);

// skladuj_tablice() pobiera jako parametr tablic�
// Dokonuje iteracji tablicy w celu stworzenia ci�gu
// aby przedstawi� tablic� jako zbi�r

function skladuj_tablice($tablica)
{
  if(is_array($tablica))
  {
    $wielkosc = count($tablica);
    $ciag = '';
    if($wielkosc)
    {
      $licznik = 0;
      $ciag .= '{ ';
      // dodanie klucza i warto�ci ka�dego elementu do ci�gu
      foreach($tablica as $zmienna => $wartosc)
      {
        $ciag .= "$zmienna=$wartosc";
        if($licznik++ < ($wielkosc-1))
        {
          $ciag .= ', ';
        }
      }
      $ciag .= ' }';
    }
    return $ciag;
  }
  else
  {
    // je�eli nie jest to tablica, po prostu zwr�cenie
    return $tablica;
  }
}
?>

<?php
  // funkcja obs�uguj�ca b��dy
  function mojaObslugaBledow($numerbl, $ciagbl, $plikbl, $liniabl)
  {
    echo "<br /><table bgcolor='#cccccc'><tr><td>
          <p><b>B��D:</b> $ciagbl</p>
          <p>Prosz� spr�bowa� ponownie lub skontaktowa� si� z administratorem i
          przekaza�, �e b��d wyst�pi� w linii $liniabl pliku '$plikbl'</p>";
    if ($numerbl == E_USER_ERROR||$numerbl == E_ERROR)
    {
      echo '<p>B��d krytyczny, zako�czenie programu</p>';
      echo '</td></tr></table>';
      // zamkni�cie otwartych zasob�w, do��czenie stopki strony itp.
      exit;
    }
    echo '</td></tr></table>';
  }
  // ustawienie obs�ugi b��d�w
  set_error_handler('mojaObslugaBledow');

  // wyzwolenie r�nych poziom�w b��d�w
  trigger_error('Wywo�ana funkcja wyzwalaj�ca', E_USER_NOTICE);
  fopen('zadenplik', 'r');
  trigger_error('Ten komputer jest be�owy', E_USER_WARNING);
  include('zadenplik');
  trigger_error('Ten komputer ulegnie samoznisczeniu za 15 sekund', E_USER_ERROR);
?>

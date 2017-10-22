<?php
  // funkcja obs³uguj¹ca b³êdy
  function mojaObslugaBledow($numerbl, $ciagbl, $plikbl, $liniabl)
  {
    echo "<br /><table bgcolor='#cccccc'><tr><td>
          <p><b>B£¥D:</b> $ciagbl</p>
          <p>Proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem i
          przekazaæ, ¿e b³¹d wyst¹pi³ w linii $liniabl pliku '$plikbl'</p>";
    if ($numerbl == E_USER_ERROR||$numerbl == E_ERROR)
    {
      echo '<p>B³¹d krytyczny, zakoñczenie programu</p>';
      echo '</td></tr></table>';
      // zamkniêcie otwartych zasobów, do³¹czenie stopki strony itp.
      exit;
    }
    echo '</td></tr></table>';
  }
  // ustawienie obs³ugi b³êdów
  set_error_handler('mojaObslugaBledow');

  // wyzwolenie ró¿nych poziomów b³êdów
  trigger_error('Wywo³ana funkcja wyzwalaj¹ca', E_USER_NOTICE);
  fopen('zadenplik', 'r');
  trigger_error('Ten komputer jest be¿owy', E_USER_WARNING);
  include('zadenplik');
  trigger_error('Ten komputer ulegnie samoznisczeniu za 15 sekund', E_USER_ERROR);
?>

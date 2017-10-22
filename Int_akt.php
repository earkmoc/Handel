<?php

session_start();

if ($_POST[y]) {
   $_SESSION[CZAS_SYN_PELNA]="$_POST[y].$_POST[m].$_POST[d]/$_POST[h].$_POST[i].$_POST[s]";
}

if ($_GET[zmiana]) {
   $_SESSION[CZAS_SYN_PELNA]='';
}

if ($_SESSION[CZAS_SYN_PELNA]) {
   if (($_GET['teraz'])||(date('Y.m.d/H.i.s')>=$_SESSION[CZAS_SYN_PELNA])) {
      echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Parrot - Synchronizacja</title></head>';
      echo '<body onload="location.href=';
      echo "'Int_test.php'";
      echo '">';
      echo 'Synchronizacja: start='.$start=date('Y.m.d/H.i.s');
   } else {
      echo '<html><head><meta http-equiv="refresh" content="5" ><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Czekanie na start: Parrot - Synchronizacja</title></head>';
      echo '<body>';
      echo 'Czekanie na start synchronizacji:<br><br>';
      echo date('Y.m.d/H.i.s').' - ostatnie odwieżenie tej strony<br>';
      echo $_SESSION[CZAS_SYN_PELNA].' - oczekiwany czas startu synchronizacji<br><br>';
      echo 'Jeśli klikniesz <a href="Int_akt.php?teraz=1">tu</a>, to pełna synchronizacja wystartuje teraz.<br>';
      echo 'Możesz także powrócić <a href="index.php">do menu głównego</a> ';
      echo 'lub <a href="Int_akt.php?zmiana=1">zmienić czas</a> startu synchronizacji.<br>';
   }
} else {
   echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" /><title>Parrot - Internet synchronizacja</title></head>';
   echo '<body onload="document.getElementById(\'fokus\').focus();document.getElementById(\'fokus\').select()">';
   echo 'Synchronizacja:<br><br>';
   echo '<form action="Int_akt.php" method="POST">';
   echo 'Podaj czas startu (rok.miesic.dzień / godzina.minuta.sekunda): ';
   echo '<input name="y" value="'.(date('Y')).'" maxlength="4" size="1" /> . ';
   echo '<input name="m" value="'.(date('m')).'" maxlength="2" size="1" /> . ';
   echo '<input name="d" value="'.(date('d')+1).'" maxlength="2" size="1" /> / ';
   echo '<input id="fokus" name="h" value="02" maxlength="2" size="1" /> . ';
   echo '<input name="i" value="00" maxlength="2" size="1" /> . ';
   echo '<input name="s" value="00" maxlength="2" size="1" /><br><br>';
   echo '<input type="reset" value=" Anuluj " onclick="location=\'index.php\'" />&nbsp;';
   echo '<input type="submit" value="&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;" />';
   echo '</form>';
}

?>
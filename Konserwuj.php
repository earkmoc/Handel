<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<title>Konserwacja danych</title>
<script type="text/javascript" language="JavaScript">
<!--
function escape(){
	if (event.keyCode==27) {
		location.href="index.php";
	}
}
document.onkeypress=escape;
-->
</script>
</head>

<body bgcolor="#BFD2FF">

<a href="index.php">Esc=powr�t</a><br><br>

<!--
�=�
�=�
�=�
-->
<a href="CHECK.php">Sprawdzenie</a> - sprawdza czy w tabelach danych s� jakie� b��dy<br><br>
<a href="OPTIMIZE.php">Optymalizacja</a> - fizycznie usuwa zaznaczone do skasowania pozycje (niewidoczne w systemie), zmniejszaj�c rozmiar tabel i w ten spos�b przyspieszaj�c ich przetwarzanie. Powinna by� u�ywana je�li zosta�y skasowane du�e fragmenty tabel danych lub je�li by�o robione wiele zmian w tabelach z polami o zmiennej d�ugo�ci (np. pola tekstowe typu "uwagi"). Mo�na u�y� tej operacji �eby odzyska� nieu�ywane obszary tabel i zdefragmentowa� dane. Nie ma potrzeby wykonywa� tej operacji cz�ciej ni� raz na tydzie� lub miesi�c.<br><br>
<a href="REPAIR.php">Naprawienie</a> - naprawia podejrzane o uszkodzenie tabele danych. Normalnie nie powinno by� konieczno�ci u�ywania tej operacji, jednak je�li zda�y si� katastrofa, ta operacja odzyska wszystkie dane. Je�li tabele cz�sto ulegaj� uszkodzeniu, nale�y znale�� przyczyn� tego stanu rzeczy i w ten spos�b wyeliminowa� konieczno�� stosowania tej operacji. Przed napraw� tabeli najlepiej wykona� jej kopi� bezpiecze�stwa, poniewa� w pewnych okoliczno�ciach mo�e doj�� do utraty cz�ci danych. Je�li podczas operacji naprawiania danych serwer przestanie dzia�a� (zawieszenie, reset, wy��czenie), to po jego ponownym uruchomieniu jest niezwykle wa�n� spraw�, �eby pierwsz� operacj� by�o tak�e naprawianie danych, zanim zostanie wykonana jakakolwiek inna operacja.<br><br>
<a href="ANALYZE.php">Analizowanie</a> - analizuje i zapisuje klucz tabeli. Podczas analizy tabela jest zablokowana dla odczytu. MySQL u�ywa klucza tabeli do decyzji w jakim porz�dku tabele powinny zosta� po��czone.<br>
</body>
</html>
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

<a href="index.php">Esc=powrót</a><br><br>

<!--
š=ą
=ś
=ź
-->
<a href="CHECK.php">Sprawdzenie</a> - sprawdza czy w tabelach danych są jakieś błędy<br><br>
<a href="OPTIMIZE.php">Optymalizacja</a> - fizycznie usuwa zaznaczone do skasowania pozycje (niewidoczne w systemie), zmniejszając rozmiar tabel i w ten sposób przyspieszając ich przetwarzanie. Powinna być używana jeśli zostały skasowane duże fragmenty tabel danych lub jeśli było robione wiele zmian w tabelach z polami o zmiennej długości (np. pola tekstowe typu "uwagi"). Można użyć tej operacji żeby odzyskać nieużywane obszary tabel i zdefragmentować dane. Nie ma potrzeby wykonywać tej operacji częściej niż raz na tydzień lub miesiąc.<br><br>
<a href="REPAIR.php">Naprawienie</a> - naprawia podejrzane o uszkodzenie tabele danych. Normalnie nie powinno być konieczności używania tej operacji, jednak jeśli zdaży się katastrofa, ta operacja odzyska wszystkie dane. Jeśli tabele często ulegają uszkodzeniu, należy znaleźć przyczynę tego stanu rzeczy i w ten sposób wyeliminować konieczność stosowania tej operacji. Przed naprawą tabeli najlepiej wykonać jej kopię bezpieczeństwa, ponieważ w pewnych okolicznościach może dojść do utraty części danych. Jeśli podczas operacji naprawiania danych serwer przestanie działać (zawieszenie, reset, wyłączenie), to po jego ponownym uruchomieniu jest niezwykle ważną sprawą, żeby pierwszą operacją było także naprawianie danych, zanim zostanie wykonana jakakolwiek inna operacja.<br><br>
<a href="ANALYZE.php">Analizowanie</a> - analizuje i zapisuje klucz tabeli. Podczas analizy tabela jest zablokowana dla odczytu. MySQL używa klucza tabeli do decyzji w jakim porządku tabele powinny zostać połączone.<br>
</body>
</html>
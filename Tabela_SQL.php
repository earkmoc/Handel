<?php

session_start();

//require('skladuj_zmienne.php');

$r=$_POST['rpole'];
$c=$_POST['cpole'];
$str=$_POST['strpole'];
$natab=$_POST['natab'];
if ($_GET['natab']) {$natab=$_GET['natab'];}
$batab=$_POST['batab'];
$ipole=$_POST['ipole'];
if ($_GET['ipole']) {$ipole=$_GET['ipole'];} //używane przy testowaniu INW
$sql=$_POST['phpini'];
if ($_GET['phpini']) {$sql=$_GET['phpini'];}	//u�ywane przy ci�gu skrypt�w importu danych
if ($_GET['parametr']) {
	$parametr=$_GET['parametr'];	//nazwa dodatkowego przes�anego parametru pobranego przez prompt
	$wparametr=$_GET[$parametr];	//warto�栤odatkowego przes�anego parametru pobranego przez prompt
}
$ido=$_SESSION['osoba_id'];
$osoba_id=$ido;

$ok=true;
$raport='';
$komunikat='';

require('dbconnect.inc');
require('tabela_zapam.inc');

$sql=str_replace($parametr,$wparametr,$sql);	//np. parametr=dataoperacji & dataoperacji=2008-02-05
$sql=str_replace('r_master',$r,$sql);
$sql=str_replace('c_master',$c,$sql);
$sql=str_replace('s_master',$str,$sql);
$sql=str_replace('id_master',$ipole,$sql);
$sql=str_replace('tab_master',$batab,$sql);
$sql=str_replace('osoba_id',$_SESSION['osoba_id'],$sql);
$sql=str_replace('osoba_pu',$_SESSION['osoba_pu'],$sql);
$sql=str_replace('zaznaczone',$_POST['zaznaczone'],$sql);
$sql=str_replace(':',"|",$sql);
$sql=str_replace('`',"'",$sql);
$sql=str_replace("'''",'"',$sql);

$sql=str_replace('ID_POZYCJI=, NR_STR=, NR_ROW=, NR_COL=,','',$sql);	//zabezpiecza przed brakiem z poziomu wydruku

$id_inserted=0;	//ostatnio dodany
$id_insertep=0;	//poprzednio dodany
$id_insertef=0;	//first dodany

$sql=explode(';',$sql);		//tablica zapyta�
$krytyczne=false;			//zapytanie krytyczne
$sio=false;
$iii=0;
do {
	if (!$ok) {
		$sio=true;			//sio, bo co� nie wysz�o
	} else {
		$z=$sql[$iii];		//nast갮e zapytanie
		if ((count(explode('.php',$z))>1)||(count(explode('.end',$z))>1)) {	//do wykonania jest skrypt, a nie zapytania SQL
			$iii++;
			if ($id_inserted>0) {			//�e niby stoimy na ostatnio dodanym
				$ipole=$id_inserted;
			}
			$ipole=-$ipole;		//na znak, �e to jest wywo�ywane z "Tabela_SQL", a nie na zako�czenie D_Formularza
			include($z);
		} else {
			if ($krytyczne=(substr($z,0,1)=='?')) {	//by校lbo nie by栤la pozosta�ych zapyta�
				$z=substr($z,1);
			}
			$z=str_replace('id_insertef',$id_insertef,$z);		//ID pierwszego dodanego rekordu
			$z=str_replace('id_insertep',$id_insertep,$z);		//ID poprzednio dodanego rekordu
			$z=str_replace('id_inserted',$id_inserted,$z);		//ID ostatnio dodanego rekordu
			if (count($buf_n=explode('(*)',$z))>1) {	//sam zdob�d� list꠰�l
				//			$raport.="z=$z<br>";
				$buf_n=$buf_n[1];			//od n-tego pola
				//			$raport.="buf_n[1]=$buf_n<br>";
				$buf_n=$buf_n*1;			//od n-tego pola liczbowo
				//			$raport.="buf_n=$buf_n<br>";
				$buf=explode('from ',$z);
				$buf=explode(' ',$buf[1]);
				$tab=$buf[0];			//tabela
				$buf="show fields from $tab";	//nazwy p�l w "Field", potem "Type (int(11))", "Null (YES)", "Key (PRI)", "Default", "Extra (auto_increment)"
				$buf=mysql_query($buf);
				$buf_s='';
				$buf_i=0;
				while ($buf_r=mysql_fetch_row($buf)) {
					$buf_i++;
					if ($buf_i>=$buf_n) {		//lista p�l od n-tego
						$buf_s.=",$tab.".$buf_r[0];
					}
				}
				$z=str_replace(',(*)'.trim($buf_n),$buf_s,$z);
			}
			$raport.=$z.'<br>';
//      if ($z) {
  			$w=mysql_query($z);					//wykonaj zapytanie
  			$z=strtoupper(trim($z));
  			if (!$w) {
  				$ok=false;
          $komunikat.="Error: ".mysql_error()."\n<br>";
  
  			}
//      }
			if ($w&&(substr($z,0,6)=='INSERT')) {	//je�li to INSERT
				$id_insertep=$id_inserted;			//poprzednio dodany rekord
				$id_inserted=mysql_insert_id();		//nowo dodany rekord
				if ($id_insertef==0) {				//first dodany rekord
					$id_insertef=$id_inserted;
				}
				$iii++;
			} elseif ($w&&(substr($z,0,6)=='SELECT')) {		//je�li to SELECT
				$w=mysql_fetch_row($w);						//to da si꠰obra样yniki
				if ($krytyczne&&!$w[0]) {					//sio, bo co� nie wysz�o
					$sio=true;
				}
				if (  (substr($z,0,15)=='SELECT COUNT(*)')
				||(substr($z,0,10)=='SELECT SUM')
				||(substr($z,0,8)=='SELECT (')) {		//je�li to SUM, COUNT lub "("
					$komunikat='';
					if (!$krytyczne||$sio) {				//bo je�li krytyczne i OK, to bez komunikatu
						for ($j=0;$j<count($w);$j++) {		//to da si꠰obra样yniki
							$komunikat.=$w[$j];
						}
						//						$komunikat=str_replace('<br>',Chr(13).Chr(10),$komunikat);
					}
				}
				$iii++;									//i wtedy nast갮e zapytanie
				if ($iii<count($sql)) {					//o ile jest nast갮e
					for ($j=0;$j<count($w);$j++) {		//mo�e korzysta栺 wynik�w poprzednika
						$sql[$iii]=str_replace('['.$j.']',$w[$j],$sql[$iii]);
					}
				}
			} else {
				$iii++;
			}
		}
	}
} while (!$sio&&($iii<count($sql)));

//$ok=false;
if ($ok) {
	echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
	echo "<title>Wykonanie SQL</title></head><body bgcolor='#0F4F9F' ";

	if (count(explode('php',$natab))<=1) {		//je�li w "$natab" nie ma "php"
		$natab='Tabela.php?tabela='.$natab;	//to przej�cie do tabeli, a inaczej mo�e by栤o "Tabela_Formularz.php?..."
	}

	echo "onload='";
	echo 'location.href="'.$natab.'&r='.$r.'&c='.$c.'&str='.$str.'"';
	echo '\'>';
	if ($komunikat) {
		echo "\n";
		echo '<SCRIPT LANGUAGE="JavaScript">';
		echo "\n";
		echo '<!-- ';
		echo "\n";
		echo "alert('$komunikat.');";
		echo "\n";
		echo '-->';
		echo "\n";
		echo '</SCRIPT>';
		echo "\n";
	}
	echo '</body></html>';
} else {
	echo "<br><br>r=$r<br>";
	echo "c=$c<br>";
	echo "str=$str<br>";
	echo "natab=$natab<br>";
	echo "batab=$batab<br>";
	echo "ipole=$ipole<br>";
	echo "zaznaczone=".($_POST['zaznaczone'])."<br>";
	echo "raport:<br>$raport<br>";
	echo "komunikat:<br>$komunikat<br>";
	echo "<br><br>niestety nie wysz�o !!!";
	echo '<a href="Tabela.php?tabela='.$tabela.'&r='.$r.'&c='.$c.'&str='.$str.'">powr�t</a>';
}
require('dbdisconnect.inc');
?>

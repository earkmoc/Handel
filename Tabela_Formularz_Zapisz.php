<?php

session_start();

//require('funkcje_sql.php');
//require('skladuj_zmienne.php');exit;
//require('skladuj_echozmienne.php');die;

$tabela=$_POST['tabela'];          // zapisz tu
$tabelaa=$_POST['tabelaa'];        // i id¿ tu
$zaznaczone=$_POST['zaznaczone'];        // zawartosc pola w ktorym stal gdy wcisnal POMOC

$szukane=$_POST['szukane'];        // zawartosc pola w ktorym stal gdy wcisnal POMOC
$szukane=str_replace(' ','%20',$szukane);
$szukane=str_replace('"','%22',$szukane);

$punkt=$_SESSION['osoba_pu'];

$idtab=$_POST['idtab'];                        // ID tabeli gdzie dzia³a formularz
$id=$_POST['ID'];                                        // ID pozycji w tabeli j.w.

if (false&&($idtab==2)) {//
	$id=8;
	if($punkt==3) {$id=8;}
	if($punkt==2) {$id=11;}
	if($punkt==1) {$id=8;}
	$_SESSION['osoba_id']=$id;
//require('skladuj_echozmienne.php');
}

$op=$_POST['opole'];
$opp=$op;                                                        // stan zmiennej $op przed zmian±
$ipole=$id;
$ido=$_SESSION['osoba_id'];
$osoba_id=$ido;

require('dbconnect.inc');

//if ($op=='d'||$op=='f') {                // wariant help do tabeli z formularza
   $z=("Select ID 
          from tabeles 
         where ID_OSOBY=$ido
           and ID_TABELE=$idtab
   ");
   $w=mysql_query($z);
   if ($w) {                                                        // jest namiar na konkretny stan
      $w=mysql_fetch_array($w);
      $idtabeles=$w[0];                                // ID konkretnego stanu
      if ($op=='d'||$op=='f') {                // wariant help do tabeli z formularza
          $z='Update tabeles';
          $z.=' set NR_COL=';                        // zapisz pole w którym sta³ focus
          $z.=$_POST['posx']+1;
          $z.=' where ID=';
          $z.=$idtabeles;
          $w=mysql_query($z);
      }
      $sqla=$z;
   }
   if ($op=='d') {$op="D";};
   if ($op=='f') {$op="";};
   if ($op=="") {$idtabeles="";};        // niech nie d³ubie w tabeles na koñcu skryptu
//}

$pole='';
$phpend='';

$z="select * from tabele where NAZWA='$tabela'";        // pobierz definicjê formularza
$w=mysql_query($z);
if ($w){
        $w=mysql_fetch_array($w);
        $phpend=StripSlashes($w['PHPFORMEND']);
        $sql=StripSlashes($w['FORMULARZ']);
        
        if (substr($sql,0,1)=='#') {   //definicja jest gdzie indziej
           $sql=substr($sql,1);
           $z="select * from tabele where NAZWA='$sql' limit 1";
           $w=mysql_query($z);
           $w=mysql_fetch_array($w);
           $sql=StripSlashes($w['FORMULARZ']);
        }
        
         if (!$sql) { exit;}
         else {
            $mc=-1;
            $w=explode("\n",$sql);
            $baza=trim($w[0]);
   
      		$zd="insert into ";
                $zd.=$w[0];
                $zd.=" ( ";

                $za="update ";
                $za.=$w[0];
                $za.=" set ";

                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
                        if (substr($w[$i],0,4)=='from') {;}
                        elseif (substr($w[$i],0,1)=='(') {;}
                        elseif (substr($w[$i],0,5)=='group') {;}
                        elseif (substr($w[$i],0,6)=='having') {
                                $l=explode(" ", $w[$i]);
                                $za.=' where '.($l[count($l)-1]).$id;
//                                $pole=$l[count($l)-1];
                        }
                        elseif (substr($w[$i],0,5)=='where') {
                                $l=explode(" ", $w[$i]);
                                $za.=' '.$l[0].' '.($l[count($l)-1]).$id;
//                                $pole=$l[count($l)-1];
                                if ($id=='0') {
                                    $buf=$l[count($l)-1];
                                    $buf=explode('.',$buf);
                                    $buf=$buf[0];
                                    $za.=" or $buf".".ID_OSOBYUPR=$ido";
                                }
                        }
                        else {
                                $mc++;
                                $l=explode("|",$w[$i]);
                                $pola[$mc]=trim($l[0]);
                                $szer[$mc]=trim($l[2]);
                                $wart[$mc]=$_POST[$pola[$mc]];
                                if ((count(explode(".",$pola[$mc]))<2) 
                                 && (count(explode("blue",$l[3]))<2)) {
                                        if (substr($za,-4,4)<>'set ') {$za.=",";};
                                        $za.=$pola[$mc];
                                        if (count(explode("option:",$szer[$mc]))>1)        {      // pole select/option
                                                $za.="='";
                                                $za.=$wart[$mc];
                                                 $za.="'";
                                        }
                                        elseif (count(explode("*",$szer[$mc]))>1)        {        // gwiazdka w polu szeroko¶ci
                                                $za.="=password('";
                                                $za.=$wart[$mc];
                                                 $za.="')";
                                        }
                                        elseif (count(explode("t",$szer[$mc]))>1)        {        // datetime w polu szeroko¶ci
                                                $za.="='";
                                                $za.=date('Y-m-d H:i:s');
                                                $za.="'";
                                        }
                                        elseif (count(explode("d",$szer[$mc]))>1)        {        // datetime w polu szeroko¶ci
                                                $za.="='";
                                                $za.=date('Y-m-d');
                                                $za.="'";
                                        }
                                        else {
						$za.="='"; 
//w polu liczbowym mo¿e byæ przecinek
                                                $znak=ord(substr(trim($wart[$mc]),0,1));
// wiêc jeœli pierwsza litera z lewej to minus lub cyfra
                                                if ($znak==45 || (48<=$znak && $znak<=57)) {
                                                   if (strpos($wart[$mc],'.')) {	//jest gdzieœ dalej kropka dziesiêtna
                                                        $wart[$mc]=str_replace(',','',$wart[$mc]);	//przecinki tysiêcy s¹ zbêdne
                                                   } else {							//nie ma kropki
                                                        $wart[$mc]=str_replace(',','.',$wart[$mc]);	//to ktoœ siê pomyli³ i wpisa³ przecinek zamiast kropki
                                                   }
//							if (count(explode(".",$wart[$mc]))<3) {	//jedna lub zero kropek
//								if (count(explode("-",$wart[$mc]))<3) {	//jeden lub zero minusów
//									if (count(explode("/",$wart[$mc]))<2) {	//zero ³amañców
//			                                                        $wart[$mc]=1*$wart[$mc];	//zamieñ na liczbê
//									}
//								}
//							}
						}
						if (substr($szer[$mc],0,1)==='0') {
							$znak=ord(substr(trim($wart[$mc]),-1));
							if (48<=$znak && $znak<=57) {
								$wart[$mc]=substr('00000000'.trim($wart[$mc]),-$szer[$mc]+1);
							}
							else {
								$wart[$mc]=substr('00000000'.trim($wart[$mc]),-$szer[$mc]);
								$wart[$mc]=StrToUpper($wart[$mc]);
							}
						}
            $wart[$mc]=str_replace('<//textarea>','</textarea>',$wart[$mc]);
            $za.=addslashes($wart[$mc]);
						$za.="'";
                                        }
                                }
                        }
                }
        }
}

if ($op=='D') {
	$ok=true;
        $n=0;
        for($i=0;$i<=$mc;$i++) {
                if (count(explode(".",$pola[$i]))<2) {
                        if ($n++>0) {$zd.=",";}
                        $zd.=$pola[$i];
                }
        }
        $zd.=") ";

        $zd.=" values ( ";			//dopisanie lub koñczenie po F1 do s³ownika przy dopisywaniu
        $n=0;
        for($i=0;$i<=$mc;$i++) {
                if (count(explode(".",$pola[$i]))<2) {
                        if ($n++>0) {$zd.=",";}
                        if (count(explode("option:",$szer[$i]))>1)        {      // pole select/option
                                $zd.="'";
                                $zd.=$wart[$i];
                                $zd.="'";
                        }
                        elseif (count(explode("*",$szer[$i]))>1)        {        // gwiazdka w polu szeroko¶ci
                                $zd.="password('";
                                $zd.=$wart[$i];
                                $zd.="')";
                        }
                        elseif (count(explode("t",$szer[$i]))>1)        {        // datetime w polu szeroko¶ci
                                $zd.="'";
                                $zd.=date('Y-m-d H:i:s');
                                $zd.="'";
                        }
                        elseif (count(explode("d",$szer[$i]))>1)        {        // datetime w polu szeroko¶ci
                                $zd.="'";
                                $zd.=date('Y-m-d');
                                $zd.="'";
                        }
                        else {
                                $zd.="'";
                                $zd.=AddSlashes($wart[$i]);
                                $zd.="'";
                        }
                }
        }
        $zd.=") ";
        $za=$zd;
} else {
   $za.=' limit 1';	// przy zapisie formularza niech nie "KAMILUJE" abonentów
}

if (!$ok) {
	$ok=(!$op&&!$opp);						// startuj procedury PHP na "do widzenia", gdy oba puste
}

//------------------------------------------------------------------------------
function Raportuj($tyt, $baza, $pole, $id, $ido, $before) {
//echoo("Raportuj($tyt,$baza,$pole,$id,$ido)");
  if (($baza=='towary')
    ||($baza=='firmy')
    ||($baza=='magazyny')
    ||($baza=='parametry')
    ||($baza=='osoby')
    ||($baza=='doktypy')
    ||($baza=='wzoryumow')
    ||($baza=='wzoryumows')
    ||($baza=='tab_obl')
    ||($baza=='dok_spl')
    ||($baza=='dokumenty')
    ||($baza=='dokumentz')
    ||($baza=='dokumentbk')
    ) {
  
     if (!$pole) {$pole='ID';}
     $ida=0;
     $tyt.=" from $baza where $pole=$id";
     $raport='';
     
     //nazwy pól w "Field", potem "Type (int(11))", "Null (YES)", "Key (PRI)", "Default", "Extra (auto_increment)"
     $w=mysql_query("show fields from $baza");
     
     $i=0;
     while ($r=mysql_fetch_row($w)) {  //stawiamy tabelê do pionu
         $rr[$i++]=$r[0];
     }
   //  echo "select * from $baza where $pole $id";
     @$w=mysql_query("select * from $baza where $pole=$id");
     if ($r=mysql_fetch_row($w)) {
        for ($i=0;$i<count($r);$i++) {
           if ($rr[$i]=='ID') {$ida=$r[$i];}
           $raportowac=true;
           if (substr($tyt,0,5)=='after') {
              $raportowac=($r[$i]!=$before[$i]);
           }
           if ($raportowac) {
              $raport.=$rr[$i].' = '.AddSlashes($r[$i]).", ";  
           }
        }
     }

     $raport=strip_tags($raport);     
     $w=mysql_query("insert into todo set IDABONENTA=$ida, IDOPERATOR=$ido, CZAS=Now(), TYTUL='$tyt', OPIS='$raport', DATA=CurDate()");
     return $r;
   }
}
//------------------------------------------------------------------------------
if ($op!="D") {
   $before=Raportuj('before', $baza, $pole, $id, $ido, '' );
}

//echo $za;

$w=mysql_query($za);                // zmiana lub dopisanie pozycji w tabeli
if (!$w) {
   echo "<br  /><br  />$za<br  /><br  />niestety nie wysz³o !!!";
} else {
   $ipole=mysql_insert_id();			// identyfikator nowego wiersza w tabeli

   if ($op=="D") {						// dopisanie
      Raportuj('insert', $baza, $pole, $ipole, $ido, '' );
   } else {
      Raportuj('after', $baza, $pole, $id, $ido, $before );
   }

   if ($op=="D") {						// dopisanie
//	if (!$ok) {$ok=!$szukane;}
      $_POST['r']=$_POST['rr'];
      $_POST['str']=999999;			//skok na koniec tabeli
      if ($idtabeles) {					//jest namiar na konkretny stan
//	$w=mysql_query("select ID_TABELE from tabeles where ID=$idtabeles"); $w=mysql_fetch_row($w); $w=$w[0];
//	$w=mysql_query("select MAXROWS, NAZWA from tabele where ID=$w"); $w=mysql_fetch_row($w); $mr=$w[0]; $nt=$w[1];
//	$w=mysql_query("select count(*)/$mr from $nt"); $w=mysql_fetch_row($w); $w=$w[0];
	$z='Update tabeles';
	$z.=" set ID_POZYCJI=$ipole";		// zapisz ID POZYCJI, która zosta³a zmieniona lub dopisana
	$z.=", WARUNKI=''";
	$z.=", SORTOWANIE=''";			//wed³ug ID na koniec, to
	$z.=", NR_ROW='1'";		//skok na pocz±tek wierszy
	$z.=", NR_STR='1'";		//skok na pocz±tek stron
//	$z.=$_POST['str'];		//skok na koniec ostatniej strony
	$z.=' where ID=';
	$z.=$idtabeles;
	$w=mysql_query($z);
      }
   }
   if ($op=="L") {                //login => sprawdzamy has³a
      $za=("select logi.haslo
                  ,osoby.haslo
                  ,osoby.opis
                  ,logi.id_osoby
                  ,osoby.id_grupy
                  ,logi.hasloo
                  ,logi.haslooo 
                  ,osoby.DOSTAWCY
              from logi
                  ,osoby 
            where logi.id=$id 
              and logi.id_osoby=osoby.id
      ");
      $w=mysql_query($za);
      if ($w) {
         $w=mysql_fetch_row($w);
//         if ($w[0] && $w[1] && $w[0]===$w[1]) {                        // has³a ?
         if (($w[0]===$w[1])) {                        // has³a ?
            $_SESSION['osoba_dos']=$w[7];
            $_SESSION['osoba_gr']=$w[4];
            $_SESSION['osoba_id']=$w[3];
            $_SESSION['osoba_upr']=$w[2];
	        if ($w[5] && $w[6] && $w[5]===$w[6]) {                        // zmiana has³a ?
		      $za="update osoby set haslo='".$w[6]."' where ID=".$w[3];
		      $w=mysql_query($za);
	        }
//require('skladuj_echozmienne.php');die;
         }

	      $za=("select CZAS
	              from logi
	          order by ID desc
	             limit 1,1
	      ");
	      $w=mysql_query($za);
	      if ($r=mysql_fetch_row($w)) {
	      	if (substr($r[0],0,10)<>date('Y-m-d')) {	//pierwsza osoba w danym dniu
	      		require('firmy_aktualizacja.php');
	      	}
	      }
      }
   }

//	echo '$szukane='.$szukane.'<br>'.'<br>';
//	echo '$ok='.$ok.'<br>'.'<br>';
//	echo '$op='.$op.'<br>'.'<br>';
//	echo 'strlen($op)='.strlen($op).'<br>'.'<br>';
//	echo '$opp='.$opp;

	$komunikat='';
	if ($ok&&$phpend) {include($phpend);}

	$powrot='';
	if ($szukane) {	

		$w=mysql_query("select ID from tabele where NAZWA='$tabelaa'");
		if ($r=mysql_fetch_row($w)) {
			$idt=$r[0];
			mysql_query("update tabeles set WARUNKI='' where ID_TABELE=$idt and ID_OSOBY=$ido");
		}

      $powrot='Tabela.php?tabela='.$tabelaa.'&szukane='.$szukane;
   } else {
		if (count(explode('php',$tabelaa))>1) {$powrot=$tabelaa;}  // np. WydrukWzor.php?natab=abonenci&wzor=KP&ipole='.$dane['ID_DOKWPLAT']
		else {$powrot='Tabela.php?tabela='.$tabelaa;};
	}

   echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
   echo "<title>OK</title></head><body text=black bgcolor='#0F4F9F' ";
   
   if ($komunikat<>'') {
   	echo 'onload="ok.focus();">';	//koniec <body ...
   	echo '<table width="100%" height="90%" align="center" valign="center"><tr><td align="center">'.nl2br($komunikat).'</td></tr>';
   	echo '<tr><td align="center">';
   	echo '<input id="ok" type="button" value="&nbsp;&nbsp;&nbsp;&nbsp;OK=Enter&nbsp;&nbsp;&nbsp;&nbsp;" ';
   	echo 'onclick="';
   	echo "location.href='$powrot'";
   	echo '"></td></tr></table>';	//koniec <body ...
   }
   else {
   	echo 'onload="';
   	echo "location.href='$powrot'";
   	echo '">';	//koniec <body ...
   }
	echo '</body></html>';
}
//mysql_free_result($w);
require('dbdisconnect.inc');
?>

<?php

require('towary_kolumny.php');

$indeks=$_GET['projectKod'];
$ipole=$_POST['ipole'];
$znak=substr(trim($indeks),0,1);
$zkod=ord($znak);

$update=false;
if (!(48<=$zkod && $zkod<=57)) { 					//litera
	if ($znak=='N'||$znak=='n') {					//N lub n nadaje nowy kod
		$indeks=substr($indeks,1);
		$znak=substr(trim($indeks),0,1);
		$zkod=ord($znak);
		if ((48<=$zkod && $zkod<=57)||$znak==' '||$znak=='') { //cyfra lub spacja
			if ((strlen($indeks)> 9)||$znak==' '||$znak=='') {	//kod paskowy lub jego wyczyszczenie
				$update=true;
			}
		}
	}
} elseif (strlen($indeks)>9) {	//kod paskowy
   $indeks=substr($indeks,0,13);
   $_POST['KODPAS']=$indeks;
   $c=$kolumna_kodpas;

   $w=mysql_query("select count(*) from towary where KODPAS='$indeks' and STATUS='T' and STAN<>0");
   if ($r=mysql_fetch_row($w)) {
      if ($r[0]==0) {
         $w=mysql_query("select megais.INDEKS 
                           from megais 
                      left join towary 
      			                 on towary.ID=megais.ID_TOWARY 
                          where INDEKSS='$indeks' 
                            and towary.STATUS='T'
                            and towary.STAN<>0
         ");
         if ($r=mysql_fetch_row($w)) {
            $indeks=$r[0];
            $_POST['INDEKS']=$indeks;
            $c=$kolumna_indeks;
         } else {
           $w=mysql_query("select megais.INDEKS 
                             from megais 
                        left join towary 
        			                 on towary.ID=megais.ID_TOWARY 
                            where INDEKSS='$indeks' 
                              and towary.STATUS='T'
           ");
           if ($r=mysql_fetch_row($w)) {
              $indeks=$r[0];
              $_POST['INDEKS']=$indeks;
              $c=$kolumna_indeks;
           }   
         }  
      }
   }   
   
} elseif ((strlen($indeks)==8)||(strlen($indeks)==9)) {		//indeks z kreské lub bez kreski
   if (substr($indeks,4,1)<>'-') {							//indeks bez kreski
      $indeks=substr($indeks,0,4).'-'.substr($indeks,4,4);	//indeks teraz z kreské
   }
   $_POST['INDEKS']=$indeks;
	$c=$kolumna_indeks;
} else {
   $_POST['INDEKS']=$indeks;
	$c=$kolumna_indeks;
}

if ($update) {
	mysql_query("
		update towary
		   set KODPAS='$indeks'
		 where ID=$ipole
	");
	$str=$_POST['strpole'];
	$r=$_POST['rpole'];

	header("location:Tabela.php?tabela=$natab");

} else {
	$_POST['opole']='S';
	$_POST['tabela']=$natab;
	$_POST['tabelaa']=$natab;
	$_POST['c']=$c;

	require("Tabela_Szukaj_Zapisz.php");
}

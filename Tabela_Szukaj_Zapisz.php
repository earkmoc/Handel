<?php

session_start();

$ido=$_SESSION['osoba_id'];

//require('skladuj_zmienne.php');exit;

$tabela=$_POST['tabela'];          // zapisz tu
$tabelaa=$_POST['tabelaa'];        // i id¿ tu
$szukane=$_POST['szukane'];        // zawartosc pola w ktorym stal gdy wcisnal POMOC

$idtab=$_POST['idtab'];                        // ID tabeli gdzie dzia³a formularz
$id=$_POST['ID'];                                        // ID pozycji w tabeli j.w.
$op=$_POST['opole'];
$c=$_POST['c'];
$cc=$_POST['cc'];
$ss=$_POST['ss'];

//require_once('skladuj_zmienne.php');
require('dbconnect.inc');

$z="select * from tabele where NAZWA='$tabela' limit 1";        // pobierz definicjê formularza

if ($_POST['zmieniony']=='on'&&$_POST['edytor']) {

	$z="Select ID from tabeles where ID_OSOBY='$ido' and ID_TABELE='$idtab' limit 1";
	$w=mysql_query($z);
	$w=mysql_fetch_row($w);
	$w=$w[0];

	$z="update tabeles set WARUNKI='".$_POST['edytor']."' where ID=$w";
	$w=mysql_query($z);

} else {

   $baza='';
   $w=mysql_query($z);
   $sqla=$z;$sqla.='<br>';
   if ($w){
        $w=mysql_fetch_array($w);
        $rr=$w['MAXROWS']; //do nowego szukania

        $sql=StripSlashes($w['TABELA']);
        $z=explode("\n",$sql);		// na linie
        $z=trim($z[0]);
        $z=explode(",",$z);
        $baza=trim($z[3]);
        if (!$baza) {
           $baza=trim($z[0]);
        }
         
        $tabdef=StripSlashes($w['TABELA']);

        if (!$tabdef) {
				echo "$sqla<br  /><br  />niestety nie wysz³o !!!";
				exit;
		  } else {
            if ($baza=='spec'||$baza=='towary') {
   
               $z=("select ID from tabele where NAZWA='dokum'");
               $w=mysql_query($z);
               $w=mysql_fetch_row($w);
               $w=$w[0];
   
               $z=("Select ID_POZYCJI from tabeles where ID_OSOBY='$ido' and ID_TABELE='$w' limit 1");
               $w=mysql_query($z);
               $w=mysql_fetch_row($w);
               $w=$w[0];
   
               $tabdef=str_replace("ID_D=[0]","ID_D=$w",$tabdef);

               $z=("Select TYP, NABYWCA from dokum where ID='$w' limit 1");
               $w=mysql_query($z);
               $w=mysql_fetch_row($w);
               $typ=$w[0];
               $nab=$w[1];
   
               $tabdef=str_replace("[4]","$typ",$tabdef);
               $tabdef=str_replace("[4]","$typ",$tabdef);
               $tabdef=str_replace("DOSTAWCA=[1]","DOSTAWCA=$nab",$tabdef);
               $tabdef=str_replace("DOSTAWCA=[7]","DOSTAWCA=$nab",$tabdef);
            }
            if ($baza=='dokum'||$baza=='magazyny') {
   
               $z=("select ID from tabele where NAZWA='firmy'");
               $w=mysql_query($z);
               $w=mysql_fetch_row($w);
               $w=$w[0];
   
               $z=("Select ID_POZYCJI from tabeles where ID_OSOBY='$ido' and ID_TABELE='$w' limit 1");
               $w=mysql_query($z);
               $w=mysql_fetch_row($w);
               $w=$w[0];
   
               $tabdef=str_replace("ID_F=[0]","ID_F=$w",$tabdef);
            }
			$w=explode("\n",$tabdef);		// na linie
      		if (!$baza) {
      			$z=explode(",",$w[0]);
      			$baza=trim($z[3]);
      			if (!$baza) {
                  $baza=trim($z[0]);
               }
      		}

			if ($cc) {
	      		$l=explode("|",$w[$cc]);		// liniê numer "$cc" na pola
	      		if (!(!$l[4]||$l[4]=="\r")) {
	               $l[0]=$l[4];
	            }
	      		$pola[0]=trim($l[0]);		// nazwa pola z tej linii
	      		$pola[0]=str_replace(" ","_",$pola[0]);
	      		$pola[0]=str_replace(".","krooopka",$pola[0]);
	      		$pola[0]=AddSlashes($pola[0]);
	      //$sqla.='$pola[0]='.$pola[0];$sqla.='<br>';
	      		$wart[1]=$_POST[$pola[0]];	// wartoœæ pola
	//			if ($zkod) {
	//				$wart[0]=str_replace('_',' ',$wart[0]);
	//			}
	            if ((count(explode(',',$wart[1]))==2)&&(!$zkod)) {	//jest jeden przecinek
	            	$wart[1]=str_replace(',','.',$wart[1]);
	            }
	
	      //$sqla.='$wart[0]='.$wart[0];$sqla.='<br>';
	      		$pola[1]=trim($l[0]);		// nazwa pola z tej linii
			}

      		$l=explode("|",$w[$c]);		// liniê numer "$c" na pola
      		if (!(!$l[4]||$l[4]=="\r")) {
               $l[0]=$l[4];
            }
      		$pola[0]=trim($l[0]);		// nazwa pola z tej linii
      		$pola[0]=str_replace(" ","_",$pola[0]);
      		$pola[0]=str_replace(".","krooopka",$pola[0]);
      		$pola[0]=AddSlashes($pola[0]);
      //$sqla.='$pola[0]='.$pola[0];$sqla.='<br>';
      		$wart[0]=$_POST[$pola[0]];	// wartoœæ pola
//			if ($zkod) {
//				$wart[0]=str_replace('_',' ',$wart[0]);
//			}
            if ((count(explode(',',$wart[0]))==2)&&(!$zkod)) {	//jest jeden przecinek
            	$wart[0]=str_replace(',','.',$wart[0]);
            }

      //$sqla.='$wart[0]='.$wart[0];$sqla.='<br>';
      		$pola[0]=trim($l[0]);		// nazwa pola z tej linii

        }
   }

   $z="Select ID from tabeles where ID_OSOBY='$ido' and ID_TABELE='$idtab' limit 1";
   
   $w=mysql_query($z);
   $sqla.=$z;$sqla.='<br>';
   $sqla.=mysql_num_rows($w);$sqla.='<br>';

	if (mysql_num_rows($w)==0) {
		echo $z=("insert into tabeles set ID_OSOBY='$ido', ID_TABELE='$idtab'");
		mysql_query($z);
		$idtabeles=mysql_insert_id();
	}
   

   $sqla.=$pola[0];$sqla.='<br>';
   $sqla.=$wart[0];$sqla.='<br>';
   if (!$w) {                                                        // jest namiar na konkretny stan
   	echo "$sqla<br  /><br  />niestety nie wysz³o !!!";
   	exit;
   } else {                                                        // jest namiar na konkretny stan
   	$w=mysql_fetch_row($w);
   	$idtabeles=$w[0];                                // ID konkretnego stanu
   	if ($wart[0]) {

   		$z="Update tabeles set `NR_STR`='1',`NR_ROW`='1',`WARUNKI`='";

   		$w="select WARUNKI from tabeles where ID=$idtabeles";
   		$w=mysql_query($w); $w=mysql_fetch_row($w); $w=$w[0]; $w=AddSlashes($w);    //obecny

         $zz='';
         $dodany=false;
   		if ((substr($wart[0],0,1)=='+')||($_POST['dodany_lub']=="on")) {	//dodanie do obecnego "lub"
   			if (substr($wart[0],0,1)=='+') {
               $wart[0]=substr($wart[0],1);
            }
            if ($w) {
   			   $zz.='( '.$w.' ) or ';
   			}
            $dodany=true;
   		}
   		if ((substr($wart[0],0,1)=='*')||($_POST['dodany_i']=="on")) {	//dodanie do obecnego "i"
   			if (substr($wart[0],0,1)=='*') {
               $wart[0]=substr($wart[0],1);
            }
            if ($w) {
      			$zz.='( '.$w.' ) and ';
   			}
            $dodany=true;
   		}
		if (strpos($pola[0],'.')) {  // jest kropka w nazwie pola, np. "grupy.NAZWAGR"
   			$zz.='';
   		} else {
   			$zz.=$baza.'.';
   		}
   		$zz.=AddSlashes($pola[0]).' ';
   		if (substr($wart[0],0,1)=='>') {
   			$zz.=$wart[0];
   		} elseif (substr($wart[0],0,1)=='<') {
   			$zz.=$wart[0];
   		} elseif (substr($wart[0],0,1)=='=') {
			if (strpos($wart[0],'.')||strpos($wart[0],'"')||strpos($wart[0],"'")) {	//je¶li jest kropka, tj. dokumenty.TYP lub cudzys³ów
	   			$zz.=$wart[0];										//, to bez  kombinacji
			} else {
	   			$zz.=substr($wart[0],0,1).'"'.substr($wart[0],1).'"';								//, a kombinacje to objêcie zapominalskiego cudzys³owami
			}
   		} elseif (count($buf=explode('::',$wart[0]))>1) {
        	   $zz.=' between '.$buf[0].' and '.$buf[1];
   		} elseif (  $dodany
                  ||(substr($wart[0],0,1)=='%')
                  ||(strpos(substr($tabdef,strpos($tabdef,'where')),'['))
                  ) {
//echo $tabdef;exit;
				$zz.=' like "'.AddSlashes($wart[0]).'%"';
   		} else {

            $wljm=99999999;
		      $wlj=strpos($tabdef,'left join');
            if ($wlj) {
   		      $slj=substr($tabdef,$wlj);
            }

            $w=strpos($tabdef,'where');   //dodatkowe ograniczenie where, np. STATUS<>'S'
            if ($w) {
     		      $wljm=(($wljm>$w)?$w:$wljm);
               $s=substr($tabdef,$w+5);   //bez "where"
               $w=strpos($s,"order");
               if ($w) {
                  $s=substr($s,0,$w);     //bez "order"
               }
               $w=strpos($s,"group by");
               if ($w) {
                  $sgb=substr($s,$w);      //"group by" osobno
                  $s=substr($s,0,$w);     //bez "group by"
               }
               $s=" and ( $s )";          //za to z "and"
            }

            if ($wlj) {
               $w=strpos($tabdef,'order');   //dodatkowe ograniczenie where, np. STATUS<>'S'
               if ($w) {
        		      $wljm=(($wljm>$w)?$w:$wljm);
        		   }
   		      $slj=substr($tabdef,$wlj,$wljm-$wlj);
            }
            
			$dod_sort=", $baza.ID desc";

            if ($baza=='firmy') {
				$dod_sort=", $baza.INDEKS";
            	if (substr($zz,0,strlen("$baza.INDEKS"))=="$baza.INDEKS") {
	            	$dod_sort="";
            	}
				if ($_SESSION['osoba_dos']<>'T') {
					$s.=' and (firmy.TYP<>"D")';
				}
	            if (!(strpos($zz,'firmy.NIP')===false)) {
	               $zz=str_replace('firmy.NIP','replace(firmy.NIP,"-","")',$zz);
	               $wart[0]=str_replace('-','',$wart[0]);
	            }
            }

            if ($baza=='dokum') {
				if ($_SESSION['osoba_dos']<>'T') {
					$s.=' and (dokum.TYP_F<>"D")';
				}
            }

            if ($baza=='towary') {
				$dod_sort=", $baza.NAZWA";
            	if (substr($zz,0,strlen("$baza.NAZWA"))=="$baza.NAZWA") {
	            	$dod_sort="";
            	}
            }

			if ($_SESSION['doktyp']=='ALL') {
				$s=str_replace('$doktyp',"' or ''='",$s);
			} else {
				$s=str_replace('$doktyp',$_SESSION['doktyp'],$s);
			}

            $s=str_replace('$osoba_gr',$_SESSION['osoba_gr'],$s);

//--------------------------------------------------------------------------------------------

			if ($ss) {
				$sss=" and ($ss)";
			}

            $zzz=("
                  select count(*)
                    from $baza $slj
                   where left($zz,length('$wart[0]')) = '$wart[0]'
                         $s $sss
            ");
//echo $zzz; exit;
      		if (!$w=mysql_query($zzz)) {
            echo $zzz.'<br>';
            echo mysql_error();
            //die;
          }
      		$r=mysql_fetch_row($w);

			$brak=($r[0]==0);
			
//--------------------------------------------------------------------------------------------

			if ($cc) {
				$zc=AddSlashes($baza.'.'.$pola[1]).' ';
	            $zzz=("
	                  select count(*)
	                    from $baza $slj
	                   where (isnull($zz) or $zz <='$wart[0]')
	                     and (isnull($zz) or if($zz <'$wart[0]',1,$zc < '$wart[1]'))
	                         $s $sss
	            ");
			} else {
	            $zzz=("
	                  select count(*)
	                    from $baza $slj
	                   where (isnull($zz) or $zz < '$wart[0]')
	                         $s $sss
	            ");
			}
//echo $zzz;exit;
      		$w=mysql_query($zzz);
      		$r=mysql_fetch_row($w);

			$r=$r[0];              //32604
//echo "<br>$str=floor($r/$rr)<br>";
      		$str=floor($r/$rr);    //1552
//echo "<br>";
      		$r=$r-$str*$rr+1;      //12
//echo "<br>";
      		$str++;
//echo "<br>";
//
            $zzz=("
                  update tabeles
                     set SORTOWANIE='$zz $dod_sort'
                        ,WARUNKI='$ss'
                        ,NR_STR=$str
                        ,NR_ROW=$r
                   where ID=$idtabeles
                   limit 1
            ");
      		$w=mysql_query($zzz);
            $zz='';  //bez warunku wiêc poka¿e wszystko
   		}
//echo "<br>".
//exit;
         if ($zz) {
      		$z.="$zz' where ID=$idtabeles limit 1";
      		$w=mysql_query($z);
      		$sqla.=$z;$sqla.='<br>';
   		}
   		if (!$w) {                                                        // jest namiar na konkretny stan
   			echo "$sqla<br  /><br  />niestety nie wysz³o !!!";
   			exit;
   		}
   	} else {
   		$z="Update tabeles set `NR_STR`='1',`NR_ROW`='1',`WARUNKI`='' where `ID`=$idtabeles limit 1";
   		$w=mysql_query($z);
   		$sqla.=$z;$sqla.='<br>';
   		if (!$w) {                                                        // jest namiar na konkretny stan
   			echo "$sqla<br  /><br  />niestety nie wysz³o !!!";
   			exit;
   		}
   	}
   }
} //if ($_POST['zmieniony']&&$_POST['edytor'])
//exit;
if ($w) {
   echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
   echo '<title>Zapis udany</title></head><body bgcolor="#0F4F9F" ';
   echo 'onload="';
   echo "location.href='Tabela.php?tabela=".$tabelaa;

	if ($brak) {
		echo "&kolor=red";
	}

	echo "'";
   echo '"></body></html>';
} else {
	echo "$z<br  /><br  />niestety nie wysz³o !!!";
}
require('dbdisconnect.inc');
?>
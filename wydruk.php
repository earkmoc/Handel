<?php

session_start();
$ido=$_SESSION['osoba_id'];

//require('skladuj_zmienne.php');

if ($_GET['doktyp']) {$_SESSION['doktyp']=$_GET['doktyp'];}
$doktyp=$_SESSION['doktyp'];

if ($_GET['doktypnazwa']) {$_SESSION['doktypnazwa']=$_GET['doktypnazwa'];}
$doktypnazwa=$_SESSION['doktypnazwa'];

$zaznaczone=$_POST['zaznaczone'];

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">
<meta http-equiv="Reply-to" content="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<title>"Handel"
<?php
if ($_SESSION['osoba_upr']) {
	echo ': ';
	echo $_SESSION['osoba_upr'];
	$osoba_gr=$_SESSION['osoba_gr'];
	$osoba_pu=$_SESSION['osoba_pu'];
//echo ' (';
//echo $_SESSION['osoba_id'];
//echo ')';
}
?>
</title>

<style type="text/css">
<!--
@media screen {.bez {display: none;}}
@media print {.bez {display: none;}}
.nag {font: bold 10pt times};
.nor {font: normal 10pt arial};
.zaz {font-size: 22pt; background-color: red};

#f0 {POSITION: absolute; VISIBILITY: hidden; TOP:0px; LEFT: 0px; Z-INDEX:1;}
#f1 {POSITION: absolute; VISIBILITY: hidden; TOP:0px; LEFT: 10px; Z-INDEX:2;}
#glowka2 {POSITION: absolute; VISIBILITY: visible; TOP:10px; right: 10px; Z-INDEX:3;}
#glowka3 {POSITION: absolute; VISIBILITY: visible; TOP:80px; right: 10px; Z-INDEX:4;}

<?php														// jeli to nie Raport, to ukryj mastera
if (!($_GET['wydruk']=='Raport')) {
	echo '#master {POSITION: absolute; VISIBILITY: hidden; TOP:10px; LEFT: 10px; Z-INDEX:5;}';
}
?>

-->
</style>

<script type="text/javascript" language="JavaScript">
<!--
var r, rr, rrr, c, cc, str, tnag, cnag, twie, cwie;

<?php

require('dbconnect.inc');

if ($_GET['natab']) {								//druk z tabeli obcej (powyższej)
	$_POST['natab']=$_GET['natab'];
	$_POST['batab']=$_GET['natab'];
	$_POST['sutabmid']='';
	$_POST['cpole']=2;
	$_POST['rrpole']=1;
	$_POST['rrrpole']=21;

	$z="Select ID from tabele where NAZWA='";
	$z.=$_POST['batab'];
	$z.="'";
	$w=mysql_query($z);
	$w=mysql_fetch_row($w);
	$_POST['idtab']=$w[0];							//mamy ID obcej

	$z='Select ID_POZYCJI from tabeles where ID_OSOBY=';
	$z.=$_SESSION['osoba_id'];
	$z.=' and ID_TABELE=';
	$z.=$_POST['idtab'];
	$w=mysql_query($z);
	$w=mysql_fetch_row($w);
	$_POST['ipole']=$w[0];							//mamy ostatnio użyty ID obcej

}

//********************************************************************

if ($_POST['idtab']) {
	$z='Select NAZWA from tabele where ID='.($_POST['idtab']); $w=mysql_query($z); $w=mysql_fetch_row($w); $ntab_master=$w[0];
	if (($ntab_master)==='dokum')       {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumenty')   {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentFV') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentKB') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentZA') {$_SESSION['ntab_mast']=$ntab_master;};
}
if ($_GET['tabela']) {
	$ntab_master=$_GET['tabela'];
	if (($ntab_master)==='dokum')       {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumenty')   {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentFV') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentKB') {$_SESSION['ntab_mast']=$ntab_master;};
	if (($ntab_master)==='dokumentZA') {$_SESSION['ntab_mast']=$ntab_master;};
	if ($ntab_master==='tab_master') {
		$_GET['tabela']=$_SESSION['ntab_mast'];
		$z="Select ID from tabele where NAZWA='".($_GET['tabela'])."'"; $w=mysql_query($z); $w=mysql_fetch_row($w); $_POST['idtab']=$w[0];
	};
}
$ntab_master=$_SESSION['ntab_mast'];
if (!$ntab_master) {$ntab_master=' '; $_SESSION['idtab_master']='';}

$gdzie='';
if ($ntab_master&&$_SESSION['idtab_master']) {
	$z="Select left(GDZIE,1) from dokumenty where ID=".$_SESSION['idtab_master']; $w=mysql_query($z); 
	$w=mysql_fetch_row($w); $gdzie=$w[0];
}

echo '$ntab_master="'.$ntab_master.'";';
echo "\n";

//********************************************************************
// zapamiętaj stan tabeli dla zalogowanej osoby
// gdy nie suwanie po tabeli i zalogowany i przed chwilą był w tabeli

$warunek="";
$sortowanie="";
$opole=$_POST['opole'];
$ipole=$_POST['ipole'];
if (($opole!="S")&&$_SESSION['osoba_upr']&&$ipole) {

$z='Select ID, WARUNKI, SORTOWANIE from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$_POST['idtab'];

$w=mysql_query($z);
if (!$w) {exit;}
else {
        if (mysql_num_rows($w)>0) {

                $w=mysql_fetch_array($w);

                $warunek=StripSlashes($w['WARUNKI']);
                $sortowanie=StripSlashes($w['SORTOWANIE']);

                $z='Update tabeles';
                $z.=' set NR_STR=';
                $z.=$_POST['strpole'];
                $z.=', NR_ROW=';
                $z.=$_POST['rpole'];
                $z.=', NR_COL=';
                $z.=$_POST['cpole'];
                $z.=', ID_POZYCJI=';
                $z.=$_POST['ipole'];
                $z.=', OX_POZYCJI=';
                $z.=$_POST['offsetX'];
                $z.=', OY_POZYCJI=';
                $z.=$_POST['offsetY'];
                $z.=' where ID=';
                $z.=$w['ID'];
        }
        else {
                $z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL,OX_POZYCJI,OY_POZYCJI) values (';
                $z.=$_SESSION['osoba_id'];
                $z.=',';
                $z.=$_POST['idtab'];
                $z.=',';
                $z.=$_POST['ipole'];
                $z.=',';
                $z.=$_POST['strpole'];
                $z.=',';
                $z.=$_POST['rpole'];
                $z.=',';
                $z.=$_POST['cpole'];
                $z.=',';
                $z.=$_POST['offsetX'];
                $z.=',';
                $z.=$_POST['offsetY'];
                $z.=')';
        }
//        $sql0=$z;
        $w=mysql_query($z);
}}

// zapamiętaj stan tabeli dla zalogowanej osoby
//********************************************************************

//********************************************************************
//zmienne PHP i Java Script sterujące dalszym zachowaniem

if ($_POST['sutab']) {                                         // tryb Master/Slave (SubTab=podtabela)
        if ($opole=="S") {                                                                // suwanie się po tej samej tabeli
                $tabela=$_POST['natab'];                        // tabela slave aktywna
                $tabelaa=$_POST['sutab'];                        // tabela master nieaktywna
                $tabelap=$_POST['sutabpol'];                // pole łącznik do tabeli slave
                $tabelai=$_POST['sutabmid'];                 // identyfikator pozycji w master
        }
        else {
                $tabela=$_POST['sutab'];                        // tabela slave aktywna
                $tabelaa=$_POST['natab'];                        // tabela master nieaktywna
                $tabelap=$_POST['sutabpol'];                // pole łącznik do tabeli slave
                $tabelai=$_POST['ipole'];                         // identyfikator pozycji w master
        }
}
else {
        $tabela=$_GET['tabela'];
        if (!$tabela) {$tabela=$_POST['natab'];};
}
if (!$tabela) {                // brak podanej tabeli
        $tabela='tabele'; // => ląduj w tabeli głównej
        $tabelaa="";                // tabela master nieaktywna
        $tabelap="";                // pole łącznik do tabeli slave
        $tabelai="";                 // identyfikator pozycji w master
};

if (!$_SESSION['osoba_upr']) {$tabela='osoby';}; // niezalogowany ląduje w osoby

//********************************************************************
// może tryb Master/Slave jest określony w definicji tabeli ?

if ($opole!="S") {                                        // nie suwanie się
//$tabela może być liczbą ID tabeli lub nazwą tabeli
$z=ord(substr($tabela,0,1));
if (48<=$z && $z<=57) {
        $z="select * from tabele where ID='";
        $z.=$tabela;
        $z.="'";
}
else {
	if (count($w=explode(",",$tabela))>1) {	// jest przecinek
		$tabela=$w[0];
	}
        $z="select * from tabele where NAZWA='";                // WYKAZYSPE
        $z.=$tabela;
        $z.="'";
}
$w=mysql_query($z);
if ($w){
        $w=mysql_fetch_array($w);
        $sql=StripSlashes($w['TABELA']);
        if (!$sql) { exit;}
        else {
                $w=explode("\n",$sql);
                $z=trim($w[0]);
                if (count($w=explode(",",$z))>1) {  // jest przecinek
                        $tabela=$w[0];              // tabela slave aktywna
                        $tabelaa=$w[1];             // tabela master nieaktywna
                        $tabelap=trim($w[2]);       // pole łącznik do tabeli slave
                        $tabelai='';                // ID pozycji w Master za chwilę ...
                  }
        }
}
}
// może tryb Master/Slave jest określony w definicji tabeli ?
//********************************************************************

if ($tabelaa=='tab_master') {$tabelaa=$ntab_master;}

echo '$tabela="'.$tabela.'";';
echo "\n";

$tnag='"#FFFFFF"';              //'"#FFCC33"';
echo '$tnag='.$tnag.';';
echo "\n";

$cnag='"#FFFFFF"';                                        //'"#FF6600"';
echo '$cnag='.$cnag.';';
echo "\n";

$twie='"#FFFFFF"';                                        //'"#FFFFCC"';
echo '$twie='.$twie.';';
echo "\n";

$cwie='"#FFFFFF"';                                        //'"#FFCC66"';
echo '$cwie='.$cwie.';';
echo "\n";

//zmienne Java Script
//********************************************************************

if ($tabelaa) {
//********************************************************************
// wariant z tabelą MASTER (nieaktywną)

$zprequery=0;
$prequery=array();
$tna=array();
$tp=array();
$mca=$cca;
$ca=1;

$z=ord(substr($tabelaa,0,1));
if (48<=$z && $z<=57) {
        $z="select * from tabele where ID='";
        $z.=$tabelaa;
        $z.="'";
}
else {
        $z="select * from tabele where NAZWA='";
        $z.=$tabelaa;
        $z.="'";
}
//echo $z;
$wa=mysql_query($z);
if ($wa){
        $wa=mysql_fetch_array($wa);
        $idtaba=$wa['ID'];
        $tabelaa=StripSlashes($wa['NAZWA']);
        echo '$tabelaa="'.($wa['NAZWA']).'";';
        echo "\n";

        $tyta=str_replace('$doktypnazwa',$doktypnazwa,StripSlashes($wa['OPIS']));
        $sqla=StripSlashes($wa['TABELA']);
        $funa=StripSlashes($wa['FUNKCJE']);
        if (!$sqla) { exit;}
        else {
                $mca=0;
                $wa=explode("\n",$sqla);
                $sqla='';

                if (count($bazaa=explode(",",$wa[0]))>1) {        // jest przecinek
   	             if (count($bazaa=explode(",",$wa[0]))>3) {   // sš nawet 3: abonenciG,grupy,[1].[2],abonenci
   	                $bazaa=trim($bazaa[3]);
         		    } else {
                      $bazaa=$bazaa[0];
         		    }
                } else {
                   $bazaa=trim($wa[0]);
                }
                $z='Select';
                $cca=Count($wa);
                for($i=1;$i<$cca;$i++) {
			               $wa[$i]=trim($wa[$i]);
                        if     (!$wa[$i]) {;}
                        elseif (substr($wa[$i],0,4)=='from')  {$z.=' '.$wa[$i];}
                        elseif (substr($wa[$i],0,5)=='order') {$zorder=' '.$wa[$i];}
                        elseif (substr($wa[$i],0,5)=='where') {
                           if ($doktyp=='ALL') {
                              $zwhere=' '.str_replace('$doktyp',"' or ''='",$wa[$i]);  //SQL injection
                           } else {
                              $zwhere=' '.str_replace('$doktyp',$doktyp,$wa[$i]);
                           }
                        }
                        elseif (substr($wa[$i],0,5)=='group') {$zgroup=' '.$wa[$i];}
                        elseif (substr($wa[$i],0,8)=='prequery'){$prequery[$zprequery]=substr($wa[$i],9);$zprequery++;}
                        elseif (substr($wa[$i],0,6)=='having') {$zhaving=' '.$wa[$i];}
                        else {
                                if($i==1) {$z.=' ';} else {$z.=',';};
                                $la=explode("|",$wa[$i]);
                                if (!($bazaa=='Select')&&(count(explode(".",$la[0]))<2)&&(count(explode("(",$la[0]))<2)) {
                                        $z.=$bazaa;
                                        $z.=".";
                                }
                                $z.=$la[0];
                                if(!$la[1]) {$tna[$i-1]=trim($la[0]);} else {$tna[$i-1]=trim($la[1]);};
                                $szera[$mca]=$la[2];                //szerokość
                                if (substr($szera[$mca],0,1)=='+') {$ca=$mca+1;};
                                $styla[$mca]=$la[3];                //style="font-size: 70pt; color: red; font-weight: normal"
                                $styna[$mca]=$la[4];                //font-family: serif; font-size: 18pt; text-align: center
                                $mca++;
                        }
                }
                $cca=$mca;
        }
}
if (!$tabelai) {                                                        // nie ma ID pozycji w Master
        $za='Select ID, ID_POZYCJI from tabeles where ID_OSOBY=';
        $za.=$_SESSION['osoba_id'];
        $za.=' and ID_TABELE=';
        $za.=$idtaba;
        $wa=mysql_query($za);
        $wa=mysql_fetch_array($wa);
        $tabelai=$wa['ID_POZYCJI'];                                          // ID pozycji w Master
}
if ($zgroup) {
        $z.=' '.$zgroup;                 // "group by" zamiast "where"
        if ($zhaving) {                // "having" za "group by"
                $z.="$zhaving";
                if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
                        $tr=explode('.',$tabelap);                // [1],[2]
                        for($i=0;$i<count($tr);$i++) {
                                $j=substr($tr[$i],1)*1;	//może mieć 2 cyfry i więcej
                                $z=str_replace($tr[$i],$tra[$j],$z);
                        }
                }
                else {$z.=" and $bazaa.ID=$tabelai";};
        }
        else {                                          // nie ma "having", więc ma być
                $z.=" having $bazaa.ID=$tabelai";
        }
}
else {
        if ($zwhere) {                                                                                // jest "where"
                if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
                	$z.=" where $bazaa.ID=$tabelai";	//master nie odwołuje się do mastera tylko polega na ID
                }
                else {$z.="$zwhere and $bazaa.ID=$tabelai";};
        }
        else {
                $z.=" where $bazaa.ID=$tabelai";                 // nie ma "where"
        }
}
if ($zorder) {$z.=' '.$zorder;}                                  // "order by" za "where"
$z.=' limit 1';
$z=str_replace('ID_master',$tabelai,$z);
$z=str_replace('osoba_id',$_SESSION['osoba_id'],$z);
$sqla=$z.';';
if (1==2&&$zprequery) {
	for($k=0;$k<count($prequery);$k++) {
		$prequery[$k]=str_replace('ID_master',$tabelai,$prequery[$k]);
		$prequery[$k]=str_replace('osoba_id',$_SESSION['osoba_id'],$prequery[$k]);
		$sql.='   '.$prequery[$k].';';
		if (substr($prequery[$k],0,1)=='?') {
			$prequery[$k]=substr($prequery[$k],1);
			$wa=mysql_query($prequery[$k]);
			$wa=mysql_fetch_row($wa);
			$sql.='   '.$wa[0].';';
			if ($wa[0]) {$k=count($prequery);};	//finito prequerys
		}
		else {
			$wa=mysql_query($prequery[$k]);
		}
	}
}
@$wa=mysql_query($z);
@$na=mysql_num_rows($wa);
@$tra=mysql_fetch_row($wa);
for($j=0;$j<Count($tra);$j++) {$tra[$j]=StripSlashes($tra[$j]);}

// wariant z tabelą MASTER (nieaktywną)
//********************************************************************
}

//********************************************************************
// tabela Slave (aktywna)

$zwhere="";                // zerowanie zmiennych, które za chwilę znów będą użyte
$zorder="";
$zgroup="";
$zhaving="";
$zunion=0;
$uniony=array();
$zprequery=0;
$prequery=array();
$pola=array();
$tn=array();
$sumy=array();
$sumyp=array();
$sumyok=false;
$mc=$cc;
$sql='';

//$tabela może być liczbą ID tabeli lub nazwą tabeli
$z=ord(substr($tabela,0,1));
if (48<=$z && $z<=57) {
        $z="select * from tabele where ID='";
        $z.=$tabela;
        $z.="'";
}
else {
        $z="select * from tabele where NAZWA='";
        $z.=$tabela;
        $z.="'";
}
$w=mysql_query($z);
if (!$w) {
	echo "Nie wyszlo: $z\r";
	exit;}
else {
        $w=mysql_fetch_array($w);
        $idtab=$w['ID'];
        echo '$idtab='.$idtab.';';
        echo "\n";

        $tabela=$w['NAZWA'];
        echo '$tabela="'.($w['NAZWA']).'";';
        echo "\n";

        $tyt=str_replace('$doktypnazwa',$doktypnazwa,StripSlashes($w['OPIS']));
        $sql=StripSlashes($w['TABELA']);
        $fun=StripSlashes($w['FUNKCJE']);
//wydruk nie ma ogranicznika na ilość wierszy na stronie
        $rr=99999999;
//        $rr=$w['MAXROWS'];
//        if ($rr==0) {$rr=20;}
        $rrr=$rr;

        $z='Select NR_STR, NR_ROW, NR_COL, WARUNKI, SORTOWANIE from tabeles where ID_OSOBY=';
        $z.=$_SESSION['osoba_id'];
        $z.=' and ID_TABELE=';
        $z.=$idtab;
        $ww=mysql_query($z);
        if ($ww and mysql_num_rows($ww)>0) {
                $ww=mysql_fetch_array($ww);
        };

        $warunek=StripSlashes($ww['WARUNKI']);
        $sortowanie=StripSlashes($ww['SORTOWANIE']);

        $r=$ww['NR_ROW'];
        if (!$r) {$r=1;};

        $str=$ww['NR_STR'];
        $str=1;
        if (!$str) {$str=1;};
        if ($_POST['opole']=="S") {
                $str=$_POST['strpole'];
                if ($str>0) {$r=1;};        //jak dodaje strony, to najpierw staje na pierwszym wierszu
        }
        else {
                if ($tabelaa) {                // po wejściu do Slave w trybie Maste/Slave stoi na szczycie
//                        $r=1;
                        $str=1;
                }
        };
        if ($str<0) {$str=-$str; $r=$rr;};        //jak cofa strony, to najpierw staje na ostatnim wierszu
        echo '$str='.$str.';';
        echo "\n";

        if ($rr<$r) {$r=$rr;};

        echo '$r='.$r.';';
        echo "\n";

        echo '$rr='.$rr.';';
        echo "\n";

        echo '$rrr='.$rrr.';';
        echo "\n";

        $cc=11;

        $c=$ww['NR_COL'];
        if (!$c) {$c=2;};
        if (!$sql) { exit;}
        else {
                $mc=0;
                $w=explode("\n",$sql);
                $z='Select';

                if (count($baza=explode(",",$w[0]))>1) {        // jest przecinek
	             if (count($baza=explode(",",$w[0]))>3) {   // sš nawet 3: abonenciG,grupy,[1].[2],abonenci
                        $baza=trim($baza[3]);
		     }
		     else {
                        $baza=$baza[0];
		     }
                }
                else {
                        $baza=trim($w[0]);
                }
                echo '$baza="'.$baza.'";';
                echo "\n";

                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
			               $w[$i]=trim($w[$i]);
//			               $w[$i]=str_replace('CENA_Z','CENA_Z*0',trim($w[$i]));
                        if     (!$w[$i]) {;}
                        elseif (substr($w[$i],0,4)=='from')   {     $z.=' '.$w[$i];}
                        elseif (substr($w[$i],0,5)=='where')  {
                           if ($doktyp=='ALL') {
                              $zwhere=' '.str_replace("'".'$doktyp'."'",'dokum.TYP',$w[$i]);  //SQL injection
                           } else {
                              $zwhere=' '.str_replace('$doktyp',$doktyp,$w[$i]);
                           }
                        }
                        elseif (substr($w[$i],0,5)=='order')  { $zorder=' '.$w[$i];}
                        elseif (substr($w[$i],0,5)=='group')  { $zgroup=' '.$w[$i];}
                        elseif (substr($w[$i],0,5)=='union')  { $uniony[$zunion]=$w[$i];$zunion++;}
                        elseif (substr($w[$i],0,8)=='prequery'){$prequery[$zprequery]=substr($w[$i],9);$zprequery++;}
                        elseif (substr($w[$i],0,6)=='having') {$zhaving=' '.$w[$i];}
                        else {
                                if($i==1) {$z.=' ';} else {$z.=',';};
                                $l=explode("|",$w[$i]);
                                if (!($baza=='Select')&&(count(explode(".",$l[0]))<2)&&(count(explode("(",$l[0]))<2)) {
                                        $z.=$baza;
                                        $z.=".";
                                }
                                $z.=$l[0];
				$pola[$i-1]=$l[0];
                                if(!$l[1]) {
					$tn[$i-1]=trim($l[0]);
				}
				else {
					$tn[$i-1]=trim($l[1]);
					if (count(explode("[",$tn[$i-1]))>1) {	//sš jakie odwołania w nazwie kolumny
						$zz=explode('.',$tabelap);	//[1].[2]
						for($ii=0;$ii<count($zz);$ii++) {
							$jj=substr($zz[$ii],1)*1;
							$tn[$i-1]=str_replace($zz[$ii],$tra[$jj],$tn[$i-1]);
						}
					}
				}
                                $szer[$mc]=$l[2];                //szerokość
                                if (substr($szer[$mc],0,1)=='+') {$c=$mc+1;};
                                $sumy[$mc]='';
                                if ((strpos($szer[$mc],'+')>0)||($szer[$mc]=='+')) {        //"+" z prawej
                                        $sumy[$mc]='0';
                                        $sumyok=true;
                                };
                                $styl[$mc]=$l[3];                //style="font-size: 70pt; color: red; font-weight: normal"
                                $styn[$mc]=$l[4];                //font-family: serif; font-size: 18pt; text-align: center
                                $mc++;
                        }
                }
                $cc=$mc;
//                $sql=$z.';';
        }
}

if ($tabelaa) {                                // tryb Master/Slave
   if ($zgroup) {
      if ($zwhere) {                        // jest "where", więc "and"
         $z.="$zwhere";
      }
      $z.=' '.$zgroup;                 // "group by" zamiast "where"
      if ($zhaving) {                // "having" za "group by"
         $z.="$zhaving";
         if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
            $tr=explode('.',$tabelap);                // [1].[2]
            for($i=0;$i<count($tr);$i++) {
               $j=substr($tr[$i],1)*1;
               $z=str_replace($tr[$i],$tra[$j],$z);
            }
         } else {
            $z.=" and ($baza.$tabelap=$tabelai)";
         }
      } else {                                          // nie ma "having", więc ma być
      //                        $z.=" having $baza...$tabelap=$tabelai";
      }
      if ($warunek) {
      	$warunek="($warunek)";
      	if ($zhaving) {$z.=" and $warunek";} else {$z.=" having $warunek";}
      }
      if ($sortowanie) {
      	$z.=" order by $sortowanie";
      	$zorder='';
      }
   } else {
          if ($zwhere) {                        // jest "where", więc "and"
               $z.="$zwhere";
               if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
                 $tr=explode('.',$tabelap);                // [1].[2]
                 for($i=0;$i<count($tr);$i++) {
      					$j=substr($tr[$i],1)*1;
      					$z=str_replace($tr[$i],$tra[$j],$z);
                 }
               } else {
                  $z.=" and ($baza.$tabelap=$tabelai)";
               }
   			if ($warunek) {
   				$warunek="($warunek)";
   				$z.=" and $warunek";
   			}
   			if ($sortowanie) {
   				$z.=" order by $sortowanie";
   				$zorder='';
   			}
         } else {                                          // nie ma "where", więc ma być
      		if ($warunek) {
      			$warunek="($warunek)";
      			$z.=" where ($baza.$tabelap=$tabelai) and $warunek";
      		} else {
               $z.=" where $baza.$tabelap=$tabelai";
      		}
      		if ($sortowanie) {
      			$z.=" order by $sortowanie";
      			$zorder='';
      		}
         }
   }
} else {                                                // tryb Slave
   if ($zgroup) {
      $z.="$zwhere ";                        // trzeba w końcu uwzględnić warunek "where"
      $z.=' '.$zgroup;                                                                          // "group by" zamiast "where"
      if ($zhaving) {                // "having" za "group by"
         $z.="$zhaving";
         if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
             $tr=explode('.',$tabelap);                // [1].[2]
             for($i=0;$i<count($tr);$i++) {
                $j=substr($tr[$i],1)*1;
                $z=str_replace($tr[$i],$tra[$j],$z);
             }
         } elseif ($tabelap) {
            $z.=" and $baza.$tabelap=$tabelai";
         }
      } else {                                          // nie ma "having", więc ma być
      //                        $z.=" ...having $baza.$tabelap=$tabelai";
      }
      if ($warunek) {
      	$warunek="($warunek)";
      	if ($zhaving) {
            $z.=" and $warunek";
         } else {
            $z.=" having $warunek";
         }
      }
      if ($sortowanie) {
      	$z.=" order by $sortowanie";
      	$zorder='';
      }
   } else {
      if ($_GET['szukane']) {
         $zwhere=str_replace('[1]',$_GET['szukane'],$zwhere);
      } else {                                        // nic ne szukamy
         if (count($w=explode("[1]",$zwhere))>1) {  // definicja SQL jest przeznaczona do szukania
            $zwhere=''; // trzeba zrezygnować z ograniczeń
            $zorder=''; // trzeba zrezygnować z uporzšdkowania "po nazwie" na rzecz "po ID", bo po "Dopisz" by się na nim nie ustawiał
         }
      }
      $z.="$zwhere ";                        // trzeba w końcu uwzględnić warunek "where"

      if ($warunek) {
         $warunek="($warunek)";
         if ($zwhere) {
            $z.=" and $warunek";
         } else {
            $z.=" where $warunek";
         }
	  }

	 if ($zaznaczone) {
         $zaznaczone="($baza.ID in ($zaznaczone))";
         if ($zwhere) {
            $zaznaczone=" and $zaznaczone";
         }
	 }

	  $z.=$zaznaczone;

      if ($sortowanie) {
         $z.=" order by $sortowanie";
         $zorder='';
      }
   }
}
if ($zorder) {$z.=' '.$zorder;}                // "order by" za "where"
if ($zunion) {
	$z='('.$z.')';
	for($i=0;$i<$zunion;$i++) {
		$z.=' '.$uniony[$i];
	}
}
if (substr($tabelap,0,1)=='[') {        // odwołanie do pól mastera
	$tr=explode('.',$tabelap);                // [1].[2]
	for($i=0;$i<count($tr);$i++) {
		$j=substr($tr[$i],1)*1;				// 1 lub 2, a nawet 25 i większe
		$z=str_replace($tr[$i],$tra[$j],$z);
		if ($zprequery) {
			for($k=0;$k<count($prequery);$k++) {
				$prequery[$k]=str_replace($tr[$i],$tra[$j],$prequery[$k]);
			}
		}
	}
}
$z.=" limit ";                                                                // "limit" na końcu
$z.=sprintf("%d",($str-1)*$rr).",";
$z.=sprintf("%d",$rr);

if ($zprequery) {
	for($k=0;$k<count($prequery);$k++) {
		$prequery[$k]=str_replace('ID_master',$tabelai,$prequery[$k]);
		$prequery[$k]=str_replace('osoba_id',$_SESSION['osoba_id'],$prequery[$k]);
		$sql.='   '.$prequery[$k].';';
		if (substr($prequery[$k],0,1)=='?') {
			$prequery[$k]=substr($prequery[$k],1);
			$w=mysql_query($prequery[$k]);
			$w=mysql_fetch_row($w);
			$sql.='   '.$w[0].';';
			if ($w[0]) {$k=count($prequery);};	//finito prequerys
		}
		else {
			$w=mysql_query($prequery[$k]);
			if (strtoupper(substr(trim($prequery[$k]),0,6))=='SELECT') {	//jeli typu "SELECT"
				$qs=mysql_fetch_row($w);										//to co zwraca
				$i=$k+1;					//następne prequery
				if ($i<count($prequery)) {			//jeli sš następne, to
					for ($j=0;$j<count($qs);$j++) {		//korzystajš ze swoich wyników
						$prequery[$i]=str_replace('{'.$j.'}',$qs[$j],$prequery[$i]);
					}
				}
			}
		}
	}
}
if (strpos($z,'where')) {
   if (($baza=='firmy')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('where','where firmy.TYP not in ("D","M") and ',$z);
   }
   if (($baza=='dokum')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('where','where dokum.TYP_F not in ("D","M") and ',$z);
   }
} else {
   if (($baza=='firmy')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('from firmy','from firmy where firmy.TYP not in ("D","M") ',$z);
   }
   if (($baza=='dokum')&&($_SESSION['osoba_dos']<>'T')) {
      $z=str_replace('from dokum','from dokum where dokum.TYP_F not in ("D","M") ',$z);
   }
}

$z=str_replace('$osoba_gr',$osoba_gr,$z);  // wrażliwość na grupę usera
$z=str_replace('$osoba_pu',$osoba_pu,$z);  // wrażliwość na punkt usera
$z=str_replace('osoba_id',$_SESSION['osoba_id'],$z);  // wrażliwość na ID usera

$w=mysql_query($z);
if ($w) {$n=mysql_num_rows($w);} else {$n=0;};
if (!$n) {
	$r=1;
	echo '$r='.$r.';';
	echo "\n";
}
else {
	if ($n<$r) {
		$r=$n;
		echo '$r='.$r.';';
		echo "\n";
	}
}

// tabela Slave (aktywna)
//********************************************************************

if ($cc<$c) {$c=$cc;};
echo '$c='.$c.';';
echo "\n";

echo '$cc='.$cc.';';
echo "\n";

//********************************************************************
?>

function Odswiez($kierunek){};
function klawisz() {}
document.onkeydown=klawisz;

function PlikPHP($ko,$h,$pa,$f02){
	$ok=(1==2);
	if ($h=='') {$ok=(1==1);}
	else {
		if (confirm($h)) {$ok=(1==1);};
	}
	if ($ok) {
		f0.action=$ko;
		f0.phpini.value=$pa;
		f0.odswiez.click();
	}
}
function enter(){
        if (event.keyCode==27) {
<?php
        $ok=false;
        if (!$fun) { exit;}
        else {
                $f=explode("\n",$fun);
                $cc=Count($f);
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if ($l[0]=='Esc') {
                                echo $l[2].';';
                                $ok=true;
                        }
                }
        }
?>
        };
}
document.onkeypress=enter;

function mysza($x,$y){}
function mysza2($x,$y){}
function tab_czysc(){}
function tab_kolor(){}
function Adres($ko){
        f0.sutab.value="";                                        //czyść, bo to koniec chodzenia po subtabeli slave
if (isNaN($ko)) {                                                        // nazwa tabeli
        f0.natab.value=$ko;
        f0.action="Tabela.php";
        f0.odswiez.click();
}
else { // $ko=1 => numer kolumny zawierającej id tabeli
        f0.natab.value=f0.ipole.value;
        f0.action="Tabela.php";
        f0.odswiez.click();
}}
function Start(){
<?php
        include($_GET['wydruk']."_p.html");
?>
};
-->
</script>

</head>

<body bgcolor="#FFFFFF" onload="Start()">

<?php
        include($_GET['wydruk']."_n.html");
?>

<br style="font-size: 12pt">

<form id="f0" action="Tabela.php?tabela=<?php echo $tabela?>" method="post">
<input type="hidden" id="natab" name="natab" value=""/>
<input type="hidden" id="batab" name="batab" value=""/>
<?php
echo '<input id="sutab"    type="hidden" name="sutab"    value="'.$tabelaa.'"/>';echo "\n";
echo '<input id="sutabpol" type="hidden" name="sutabpol" value="'.$tabelap.'"/>';echo "\n";
echo '<input id="sutabmid" type="hidden" name="sutabmid" value="'.$tabelai.'"/>';echo "\n";
?>
<input type="hidden" id="idtab" name="idtab" value=""/>
<input type="hidden" id="ipole" name="ipole" value=""/>
<?php
//echo '<input id="fpole" value=""/>';
?>
<input type="hidden" id="opole" name="opole" value=""/>
<input type="hidden" id="strpole" name="strpole" value=""/>
<input type="hidden" id="rpole" name="rpole" value=""/>
<input type="hidden" id="cpole" name="cpole" value=""/>
<input type="hidden" id="kpole" name="kpole" value=""/>
<input type="hidden" id="rrpole" name="rrpole" value=""/>
<input type="hidden" id="phpini" name="phpini" value=""/>
<input               id="odswiez" type="submit" value=""/>
</form>

<form id="f1">
<?php
        if ($fun) {
                $f=explode("\n",$fun);
                $cc=Count($f);
                $ok_esc=false;
                for($i=0;$i<$cc;$i++) {
                        $l=explode("|",$f[$i]);
                        if ($l[0]=="Esc") {
                                $ok_esc=true;
                        }
                }
                if (!$ok_esc) {
                        echo '<input type="button" value="Esc=wyjście" onclick="window.close()"/>';echo "\n";
                }
        }
?>
</form>

<div id="master">

<?php

$mr=$n;
if ($n==0) {
        if ($r<2&&$str<2) {
                $n=1;
//                echo '<h1 align="center"><br><br><br>BRAK DANYCH<br><br><br></h1>';
        }
        else {
                echo '<h1 align="center"><br>TO JEST OSTATNIA POZYCJA</h1>';
        }
};

$startkol=1;
if ($_GET['wydruk']=='Raport') {
	$startkol=0;
}

if ($tabelaa) { // tabela master nieaktywna
        $startkol=2;
        echo '<table align="center" border="1" cellpadding="2" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080" title="'.$tyta.'"> '; echo "\n";
        echo '<caption align="left">'.$tyta;
//        echo '  (';
//        echo $sqla;
        echo '</caption>';
        echo "\n";
        echo '<tr bordercolor="black">';
        echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
        for($j=1;$j<=$mca-1;$j++) {
                echo '<td ';
                echo 'align="center" ';
//                if (!$styna[$j]||$styna[$j]=="\r") {;} else {echo $styna[$j];};
		if (($szera[$j]==='0')||($szera[$j]=='.')||(count(explode("@s",$szera[$j]))>1)) {
			echo ' CLASS="bez" ';	//	echo 'width=0 style="font-size:0"';
		}
		else {
			echo ' CLASS="nag" ';	//	echo 'width=0 style="font-size:0"';
		}
                echo ' bgcolor='.$tnag.'>';
               if (($szera[$j]==='0')||($szera[$j]=='.')) {echo '.';}
					else {echo $tna[$j];}
                echo '</td>';
                echo "\n";
        }
        echo '</tr>';
        for($i=0;$i<1;$i++){
//                $tra=mysql_fetch_row($wa);
                echo "\n";
                echo '<tr height=1 bgcolor='.$twie.'>';
                echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
                for($j=1;$j<$mca;$j++){
// wydruk nie tnie treści kolumn tabeli  "nowrap "
                        echo '<td id="taba_'.$i.'_'.$j.'" ';
                if ($szera[$j]==='0')    {echo ' class="bez" ';}
                elseif ($szera[$j]=='.') {echo ' class="bez" ';}                    
                elseif (count(explode("@s",$szera[$j]))>1) {echo ' class="bez" ';}                    
                else {                    echo ' class="nor" width='.($szera[$j]*12);}
                        echo ' align="center" ';
                        if (!$styla[$j]||$styla[$j]=="\r") {;} else {echo $styla[$j];};
			               if (($szera[$j]==='0')||($szera[$j]=='.')) {
										echo 'width=0 style="font-size:0"';
								}
                        echo ' >';
                        if (count($z=explode(":",$szera[$j]))>1) {                        //obrazek
                                if (!$z[0]) echo '<img src="'.$tra[$j].'" alt="" height='.$z[1].'>';
                                if (!$z[1]) echo '<img src="'.$tra[$j].'" alt="" width='.$z[0].' >';
                                if ($z[0]&&$z[1]) echo '<img src="'.$tra[$j].'" alt="" width='.$z[0].' height='.$z[1].'>';
                        }
                        else {                                                                                                                  //tekst

					    $buf=$szera[$j];
	                if (count($z=explode("@Z",$buf))>1) {		// bez zer
								$buf=str_replace('@Z','',$szera[$j]);
								if ($tra[$j]*1==0) {
									$buf='';
									$tra[$j]='';
								}
	                }
                   if (!$buf) {echo $tra[$j];}
                   elseif ($buf==='0') {echo $tra[$j];}
                   elseif ($buf=='.') {echo '.';}
                   elseif ($buf==='i') {echo number_format($tra[$j],0,'.',',');}
                   elseif ($buf==='w') {echo number_format($tra[$j],2,'.',',');}
                   elseif (substr($buf,0,1)=='%') {printf($buf,$tra[$j]);}
//                   elseif (strlen($tra[$j])>$buf) {echo substr($tra[$j],0,$buf).'...';}
//                   else {echo substr($tra[$j],0,$buf);};
                   else {echo $tra[$j];}
                        }
                        echo '</td>';
                        echo "\n";
                }
                echo '</tr>';
        }
	echo '</table>';
}
echo '</div>';
echo "\n";
if ($_GET['wydruk']=='Raport') {
	echo '<br style="font-size: 12pt">';
	echo "\n";
}

echo '<div id="slave">';
echo "\n";
echo '<table border="1" align="center" id="tab" summary="'.$n.'"  cellpadding="2" cellspacing="0" bordercolorlight="#C0C0C0" bordercolordark="#808080" title="'.$tyt.'"> '; echo "\n";

if ($_GET['wydruk']=='Raport') {
	echo '<caption align="left">'.$tyt;
}

//echo '  (';
//echo $sqla;
echo '</caption>';

echo "\n";
echo '<tr bordercolor="black">';
echo "\n";
echo '<td align="center" CLASS="nag">LP</td>';	//LP
echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
for($j=$startkol;$j<=$mc-1;$j++) {
        if ($szer[$j]==='0')    {echo '<td id="tab_0'.($j+1).'" nowrap class="bez" ';}
        elseif ($szer[$j]=='.') {echo '<td id="tab_0'.($j+1).'" nowrap class="bez" ';}	//width=0 style="font-size:0"
        elseif (count(explode("@s",$szer[$j]))>1) {echo '<td id="tab_0'.($j+1).'" class="bez" ';}
        else {                   echo '<td id="tab_0'.($j+1).'" class="nag" ';}
        echo ' align="center" ';
//        if (!$styn[$j]||$styn[$j]=="\r") {;} else {echo $styn[$j];};
        echo ' bgcolor='.$tnag.'>';
        if ($szer[$j]=='.') {echo '.';}
        else {echo $tn[$j];};
        echo '</td>';
        echo "\n";
}
echo '</tr>';

if ($mr==0) {                // brak specyfikacji
        $mr=1;
for($i=0;$i<1;$i++){
        $tr=mysql_fetch_row($w);
        echo "\n";
        echo '<tr id="tab_'.($i+1).'" height=1 bgcolor='.$twie.'>';
        echo "\n";
// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
        for($j=$startkol;$j<$mc;$j++){
// wydruk nie tnie treści kolumn tabeli  "nowrap "
                if ($szer[$j]==='0')    {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
                elseif ($szer[$j]=='.') {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
         		 elseif (count(explode("@s",$szer[$j]))>1) {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
                else {                   echo '<td id="tab_'.$i.'_'.$j.'" class="nor" width='.($szer[$j]*12);}
                echo ' nowrap align="center" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' >';
                echo '...';
                echo '</td>';
                echo "\n";
        }
        echo '</tr>';
}
}
else {
for($i=0;$i<$mr;$i++){
        $tr=mysql_fetch_row($w);
        echo "\n";
        echo '<tr id="tab_'.($i+1).'" height=1 bgcolor='.$twie.'>';
        echo "\n";
        echo '<td align="right">&nbsp'.($i+1).'&nbsp</td>';	//LP
        echo "\n";// wydruk ma nagłówki bez pierwszej kolumny (Nr 0) z ID tabeli
        for($j=$startkol;$j<$mc;$j++){// wydruk nie tnie treści kolumn tabeli  "nowrap "
                if ($szer[$j]==='0')    {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
                elseif ($szer[$j]=='.') {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
         		 elseif (count(explode("@s",$szer[$j]))>1) {echo '<td id="tab_'.$i.'_'.$j.'" class="bez" ';}
                else {                   echo '<td id="tab_'.$i.'_'.$j.'" class="nor" width='.($szer[$j]*12);}
                echo ' align="center" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' >';
                if (count($z=explode(":",$szer[$j]))>1) {                        //obrazek
                        if (!$z[0]) echo '<img src="'.$tr[$j].'" alt="" height='.$z[1].'>';
                        if (!$z[1]) echo '<img src="'.$tr[$j].'" alt="" width='.$z[0].' >';
                        if ($z[0]&&$z[1]) echo '<img src="'.$tr[$j].'" alt="" width='.$z[0].' height='.$z[1].'>';
                }
                else {                                                                                                                  //tekst

	                if (($sumyok)&&(!($sumy[$j]===''))) {             // wiersz sum: ile max miejsc po przecinku ?
	         			$sumy[$j]+=str_replace(',','',$tr[$j]);
	         			$buf=explode('.',str_replace('&nbsp;','#',$tr[$j]));
	         			$sumyp[$j]=Max(strlen($buf[1]),$sumyp[$j]);
	         			$buf=explode('&nbsp;',$tr[$j]);
	         			$sumyp[$j]=Max(count($buf)-2,$sumyp[$j]);
	                }

// wydruk nie tnie treści kolumn tabeli  "nowrap "
			$buf=$szer[$j];

           	$jw=((count(explode("j",$buf))>1)&&($tr[$j]==$tp[$j]));	//&&($tr[$j]==$tp[$j])
			$buf=str_replace('j','',$buf);			//j.w.

			$buf=str_replace('@s','',$buf);		//tylko na ekranie
         if (count($z=explode("@Z",$buf))>1) {		// bez zer
				$buf=str_replace('@Z','',$szer[$j]);
				$buf=str_replace('+','',$buf);
				$buf=str_replace('%','',$buf);
				if (strip_tags($tr[$j])*1==0) {
					$buf='';
					$tr[$j]='';
				} elseif (count($z=explode("@z",$buf))>1) {		//zera po kropce ucinamy
					$buf=str_replace('@z','',$buf);
				        if (count($z=explode(".",$tr[$j]))>1) {		// bez zer po kropce
						$tr[$j]=$z[0];
						if (count(explode("i",$buf))>1) {		// format ilości
							$buf=str_replace('i','',$buf);
							$tr[$j]=number_format($tr[$j],0,'.',',');
						}
						$z[0]='';
						if ($z[1]*1>0) {
							$tr[$j]=$tr[$j].'.';
						} else {
							$tr[$j]=$tr[$j].'&nbsp;';
							if ($buf<>'') {$buf=$buf*1+5;}	//twarde spacje zajmujš więcej
						}
						while (substr($z[1],-1,1)==='0') {
							$z[1]=substr($z[1],0,strlen($z[1])-1);
							$z[0]=$z[0].'&nbsp;';
							if ($buf<>'') {$buf=$buf*1+5;}
						}
						$tr[$j]=$tr[$j].$z[1].$z[0];
					}
				}
	                }
                   	if ($jw) 		   {echo "";}
					elseif (!$buf) 	   {echo $tr[$j];}
                   elseif ($buf==='0') {echo $tr[$j];}
                   elseif ($buf==='+') {echo $tr[$j];}
                   elseif ($buf==='w') {echo number_format($tr[$j],2,'.',',');}
                   elseif ($buf==='i') {echo number_format($tr[$j],0,'.',',');}
				   elseif ($buf=='.') {echo '.';}
                   elseif (substr($buf,0,1)=='%') {printf($buf,$tr[$j]);}
                   elseif (strlen(str_replace('&nbsp;','#',$tr[$j]))>$buf) {echo substr($tr[$j],0,$buf).'...';}
                   else {echo substr($tr[$j],0,$buf);};
//                   if ($buf=='.') {echo '.';}
//                   elseif (substr($buf,0,1)=='%') {printf($buf,$tr[$j]);}
//                   else {echo $tr[$j];};
                }
				$tp[$j]=$tr[$j];
                echo '</td>';
                echo "\n";
        }
        echo '</tr>';
}
if ($sumyok) {                // wiersz sum
        echo "\n";
        echo '<tr bgcolor='.$twie.'>';
        echo "\n";
        echo "<td></td>";	//LP
        echo "\n";
        $sumyok=true;
        for($j=$startkol;$j<$mc;$j++){
                if ($sumyok&&!$sumy[$j+1]=='') {
                        $sumy[$j]='Suma:';
                        $sumyok=false;
                }
                echo '<td nowrap ';
                if ($szer[$j]==='0')    {echo ' class="bez" ';}
                elseif ($szer[$j]=='.') {echo ' class="bez" ';}
                else {                   echo ' class="nor" ';
		}
                if (count($z=explode("@Z",$szer[$j]))>1) {		// bez zer
			$szer[$j]=str_replace('@Z','',$szer[$j]);
			$szer[$j]=str_replace('+','',$szer[$j]);
			$szer[$j]=str_replace('w','',$szer[$j]);
//			$szer[$j]=str_replace('i','',$szer[$j]);
	                if (count($z=explode("@z",$szer[$j]))>1) {		// bez zer po kropce
				$szer[$j]=str_replace('@z','',$szer[$j]);
      			if ((count($z=explode("i",$szer[$j]))>1)||($sumyp[$j]>0 && $sumy[$j] && $sumy[$j]<>'Suma:')) {
					$sumy[$j]=number_format($sumy[$j]*1,$sumyp[$j],'.',',');
				}
				if (count($z=explode(".",$sumy[$j]))>1) {		// bez zer po kropce
					$sumy[$j]=$z[0];
					$z[0]='';
					if ($z[1]*1>0) {$sumy[$j]=$sumy[$j].'.';}
					else {
						$sumy[$j]=$sumy[$j].'&nbsp;';
					}
					while (substr($z[1],-1,1)==='0') {
						$z[1]=substr($z[1],0,strlen($z[1])-1);
						$z[0]=$z[0].'&nbsp;';
					}
					$sumy[$j]=$sumy[$j].$z[1].$z[0];
					$sumyp[$j]=0;
					$szer[$j]=strlen(str_replace('&nbsp;','#',$sumy[$j]));
				} else {
					for ($x=0;$x<$sumyp[$j];$x++) {
						$sumy[$j]=$sumy[$j].'&nbsp;';
					}
				}
			}
                }
                if (substr($szer[$j],0,1)=='%') {$szer[$j]=substr($szer[$j],2);}
                echo ' width='.($szer[$j]*12);
                if (!$sumy[$j]=='') {
                        echo ' style="border-top: double #000000" ';
                }
                echo ' align="center" ';
                if (!$styl[$j]||$styl[$j]=="\r") {;} else {echo $styl[$j];};
                echo ' >';
		if ($sumyp[$j]>0 && $sumy[$j] && $sumy[$j]<>'Suma:') {$sumy[$j]=number_format($sumy[$j]*1,$sumyp[$j],'.',',');}
                if (!$szer[$j]) {echo $sumy[$j];}	// kolumny bez okrelonej szerokoci
                elseif ($szer[$j]==='+') {echo substr($sumy[$j],0,strpos($sumy[$j],'.')+3);}
                elseif (strlen(str_replace('&nbsp;','#',$sumy[$j]))>$szer[$j]) {echo substr($sumy[$j],0,$szer[$j]).'...';}
                elseif (!$sumy[$j]||$sumy[$j]=='Suma:') {echo substr($sumy[$j],0,$szer[$j]);}
                elseif ($sumyp[$j]>0 && $sumyp[$j]<>2) {printf("%.".($sumyp[$j])."f",$sumy[$j]);}
		else {
			echo $sumy[$j];
		}
                echo '</td>';
                echo "\n";
        }
        echo '</tr>';
   }
}
mysql_free_result($w);
//mysql_free_result($f);
require('dbdisconnect.inc');

echo '</table>';
echo '</div>';
echo "\n";

include($_GET['wydruk']."_s.html");

?>

</body>
</html>
<?php

session_start();
$_SESSION['stop_import_test']='index.php';
//http://localhost/Handel/import_test.php
$detale=false;

?>
<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=iso-8859-2">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />
<title>import_test</title>
</head>
<body 
<?php

echo " onload='";
if ($_SESSION['stop_import_test']) {
   echo 'location.href="'.$_SESSION['stop_import_test'].'"'."'>";
   $_SESSION['stop_import_test']='';
   die;
} else {
   echo 'location.href="import_test.php"';
   echo "'>";
}

$start_time=time();

if (!$_SESSION['start_process']) {
   $_SESSION['cur_table']='';
   $_SESSION['start_rec']=0;
   $_SESSION['start_process']=date('Y.m.d / H.i.s');
   echo "<br>Start process: ".$_SESSION['start_process'];
   echo "<br>Max execution time: ".($max_time=ini_get('max_execution_time'));
} else {
   echo "<br>Start process: ".$_SESSION['start_process'];
   echo "<br>Start this time: ".date('Y.m.d / H.i.s');
   echo "<br>Max execution time: ".($max_time=ini_get('max_execution_time'));
   echo "<br>Table: ".$_SESSION['cur_table'];
   echo "<br>Start record: ".$_SESSION['start_rec'];
   echo "<br>Last record: ".$_SESSION['lastrec'];
}

flush();

//-----------------------------------------------------------------------------

function Konwert($fb,$fs,$delta,$detale) {

   if (false&&$detale) {
      for($i=0;$i<$fs;$i++) {
         $kod=ord($fb[$i]);
         echo $kod+$delta;
         echo ".";
         echo chr($kod+$delta);
         echo "<br>";
      }
   }
   
   $s='';
   for($i=0;$i<$fs;$i++) {

      $kod=ord($fb[$i]);

      if       ($kod+$delta== 13) {$s.="\n";
      } elseif ($kod+$delta== 10) {
      } elseif ($kod+$delta== 15) {    //eof
      } elseif ($kod+$delta==134) {$s.='ą';
      } elseif ($kod+$delta==141) {$s.='ć';
      } elseif ($kod+$delta==143) {$s.='Ą';
      } elseif ($kod+$delta==144) {$s.='Ę';
      } elseif ($kod+$delta==145) {$s.='ę';
      } elseif ($kod+$delta==146) {$s.='ł';
      } elseif ($kod+$delta==149) {$s.='Ć';
      } elseif ($kod+$delta==152) {$s.='Ś';
      } elseif ($kod+$delta==156) {$s.='Ł';
      } elseif ($kod+$delta==158) {$s.='ś';
      } elseif ($kod+$delta==160) {$s.='Ź';
      } elseif ($kod+$delta==161) {$s.='Ż';
      } elseif ($kod+$delta==162) {$s.='ó';
      } elseif ($kod+$delta==163) {$s.='Ó';
      } elseif ($kod+$delta==164) {$s.='ń';
      } elseif ($kod+$delta==165) {$s.='Ń';
      } elseif ($kod+$delta==166) {$s.='ź';
      } elseif ($kod+$delta==167) {$s.='ż';
      } else {                     $s.=chr($kod+$delta);
      }
   }
   return $s;
}

//-----------------------------------------------------------------------------

function DBF($katalog,$detale,$dbf,$tabela,$tab_pold,$tab_pols,$start_time,$max_time,$start_rec,$cur_tab) {

   if ($cur_tab&&($tabela<>$cur_tab)) {
      return;
   }
   
   if (!$dbf) {
      $dbf="$tabela.dbf";
   }

   $plik="$katalog\\$dbf";
   $db=dbase_open($plik,2);
   dbase_pack($db);
   $fn=dbase_numfields($db);
   $lastrec=dbase_numrecords($db);
   $dbh=dbase_get_header_info($db);
   
   $_SESSION['cur_table']=$tabela;
   $_SESSION['lastrec']=$lastrec;

   if (!$tab_pold&&!$tab_pols) {
      for($n=0;$n<$fn;$n++) {
         $tab_pols[$n]=$dbh[$n]['name'];
         $tab_pold[$n]=$dbh[$n]['name'];
      }
   }
      
   if ($detale) {
   
      echo "<table border=1 cellpadding=5 cellspacing=0>";
      echo "<caption align=left><font size=5>$plik</font></caption>";
      
      for($i=0;$i<$fn;$i++) {
         echo "<td>";
         echo '<b>';
         echo $dbh[$i]['name'];
         echo '</b><br>';
         echo '<font color="#CCCCCC">';
         echo $dbh[$i]['type'].','.$dbh[$i]['length'].','.$dbh[$i]['precision'];
         echo '</font>';
         echo "</td>";
      }
   }  

   if (!$start_rec) {
      $start_rec=1;
      if (!mysql_query("truncate $tabela")) {
	  	$q="
			CREATE TABLE `$tabela` (
			  `ID` int(11) NOT NULL auto_increment,
		";
		for($i=0;$i<$fn;$i++) {
			$name=$dbh[$i]['name'];
			$type=$dbh[$i]['type'];
			$len =$dbh[$i]['length'];
			$prec=$dbh[$i]['precision'];
			if ($type=='date') {
			}
			if ($type=='memo') {
				$type='text';
			}
			if ($type=='character') {
				$type="char($len)";
			}
			if ($type=='number') {
				$type="decimal($len,$prec)";
			}
			$q.=("`$name` $type,");
		}
		$q.="PRIMARY KEY  (`ID`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin2
		";
		if (!mysql_query($q)) {
		  	echo "Error: $q";
			$_SESSION['stop_import_test']='Tabela.php?tabela=firmy';
			exit;
		}
	  }
   }
   
   for($rn=$start_rec;$rn<=$lastrec;$rn++) {
      if ($detale) {echo "<tr>";}
      $rec=dbase_get_record($db,$rn);
      for($i=0;$i<$fn;$i++) {
         if ($detale) {echo "<td nowrap align='".(substr($dbh[$i]['format'],0,2)=='%-'?'left':'right')."' >";}
         if ($rec[$i]) {
            $s=sprintf($dbh[$i]['format'],$rec[$i]);
            if (($dbh[$i]['type']=='character')&&($dbh[$i]['length']*1>1)) {
               $s=Konwert($s,strlen($s),0,$detale);
            }
         } else {
            $s="";
         }
         if ($detale) {echo "$s</td>";}

         for($n=0;$n<count($tab_pols);$n++) {
            if ($tab_pols[$n]==$dbh[$i]['name']) {
               if ($dbh[$i]['type']=='date') {
                  $tab_wart[$n]=substr($s,0,4).'-'.substr($s,4,2).'-'.substr($s,6,2);
               } elseif ($dbh[$i]['type']=='memo') {
                  $tab_wart[$n]='';
               } else {
                  $tab_wart[$n]=$s;
               }
            }
         }

      }

      $w=("insert into $tabela set ");
      for ($n=0;$n<count($tab_pold);$n++) {
         $w.=($n==0?'':',').$tabela.'.'.$tab_pold[$n]."='".$tab_wart[$n]."'";
      }

      if ($detale) {echo "</tr>$w";}

      $w=mysql_query($w);

      if ((time()-$start_time+3)>$max_time) {   //zbli?a siŕ koniec czasu
         $_SESSION['start_rec']=$rn+1;
         die;
      }

   }

   if ($detale) {echo "</table>";}

   $_SESSION['start_rec']=0;  //dla nastŕpnego importu
   $_SESSION['cur_table']='';

   mysql_query("CHECK TABLE $tabela");

}

//-----------------------------------------------------------------------------
// dane firmy

//$katalog=$_GET['katalog'];
//$katalog='c:\PL_1106';
$katalog='c:\Parrotli';

//-----------------------------------------------------------------------------

require('dbconnect.inc');

$last_table=$_SESSION['cur_table'];

DBF($katalog,$detale,'','bank',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

//-----------------------------------------------------------------------------
//Bankowe inne (koszty)

mysql_query("
	delete
	  from dokumentbk
	 where ID_D=0
	    or right(CZAS,8)='00:00:00'
");

mysql_query("
	insert 
	  into dokumentbk
	select 0
		 , 0
		 , 0
		 , 0
		 , DATA
		 , 'WB'
		 , NUMER
		 , DOWOD
		 , DATA
		 , ZA_CO
		 , 0
		 , TRESC
		 , ''
		 , ''
		 , ''
		 , PRZYCHOD
		 , ROZCHOD
		 , '130'
		 , ANULOWANA
		 , left(DATA,7)
	  from bank
  order by DATA, DOWOD
");

mysql_query("
	delete
	  from dokumenty
	 where TYP IN ('WB','WBK','WBA')
");

$w=mysql_query("
	select ID, ID_D, ID_P, KTO, CZAS, TYP, LP, NUMER, DATA, PRZEDMIOT, NRKONT, PSKONT, NIP, NAZWA, ADRES
		 , sum(PRZYCHOD) as przy
		 , sum(ROZCHOD) as roz
		 , KONTOBK, KONTOP, OKRES
		 , sum(if(PRZYCHOD>0,1,0)) as ilep
		 , sum(if(ROZCHOD>0,1,0)) as ilem
	  from dokumentbk
	 where ID_D=0 or (TYP IN ('WB','WBK','WBA')) or TYP='RB'
  group by DATA
  order by DATA
");

while ($r=mysql_fetch_array($w)) {
	mysql_query("
		insert
		  into dokumenty
		   set CZAS='".$r['CZAS']."'
		      ,GDZIE='bufor'
			  ,TYP=if(right(CZAS,8)='00:00:00','WBK',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'WBA','WB'))
			  ,OKRES='".$r['OKRES']."'
			  ,DWPROWADZE='".$r['DATA']."'
			  ,DDOKUMENTU='".$r['DATA']."'
			  ,DOPERACJI='".$r['DATA']."'
			  ,DWPLYWU='".$r['DATA']."'
			  ,NUMER='".$r['LP']."'
			  ,PRZYCHODY='".$r['przy']."'
			  ,ROZCHODY='".$r['roz']."'
			  ,ILEDOKPLUS='".$r['ilep']."'
			  ,ILEDOKMINU='".$r['ilem']."'
	");
}

$w=mysql_query("
	select ID, DWPROWADZE
	  from dokumenty
	 where TYP IN ('WB','WBK','WBA') or TYP='RB'
  order by DWPROWADZE
");

while ($r=mysql_fetch_row($w)) {
	mysql_query("
		update dokumentbk
		   set ID_D='".$r[0]."'
			  ,TYP=    if(right(CZAS,8)='00:00:00','WBK',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'WBA','WB'))
			  ,KONTOBK=if(right(CZAS,8)='00:00:00','132',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'131','130'))
		 where ((TYP IN ('WB','WBK','WBA')) or TYP='RB')
		   and DATA='".$r[1]."'
	");
}

//-----------------------------------------------------------------------------

$_SESSION['stop_import_test']="Tabela.php?tabela=dokumentBA";
exit;

//-----------------------------------------------------------------------------

DBF($katalog,$detale,'','megaza',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
DBF($katalog,$detale,'','megazb',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

if (!$_SESSION['cur_table']) {
   $_SESSION['cur_table']='dalej';  //kontynuacja w nastŕpnym rzucie tego skryptu
   exit;
}

//-----------------------------------------------------------------------------
//Zam˘wienia

mysql_query("update spec
		  left join dokum
		         on dokum.ID=spec.ID_D
				set spec.CENA=-99999
			  where dokum.TYP in ('ZAM')
");

mysql_query("delete 
               from spec 
			  where CENA=-99999");

mysql_query("delete from dokum where TYP='ZAM'");

mysql_query("
        insert into dokum 
             select 0
                  , '', 'ZAM', megaza.NR_ZA, megaza.DOSTAWCA+10000, megaza.OGOLEM
                  , megaza.DATA_DOST, megaza.DATA_DOST, '', megaza.DATA_DOST, ''
                  , 0, '', megaza.NR_FK, replace(megaza.TIMESTAMP,',','')
				  , 0, 0, 0, 0
				  , 0, 0, megaza.OGOLEM
                  , firmy.INDEKS, firmy.TYP, firmy.NAZWA, firmy.KOD, firmy.MIASTO, firmy.ADRES, firmy.NIP
                  , 1, '', ''
				  , 0, 0
				  , 0, 0
				  , 0, 0
				  , 0, 0
				  , '', 0
				  , megaza.OGOLEM, megaza.OGOLEM, '', '' 
               from megaza
          left join firmy 
                 on firmy.ID=megaza.DOSTAWCA+10000
           order by megaza.DATA_DOST
                  , megaza.NR_ZA
");

//-----------------------------------------------------------------------------

mysql_query("
	insert 
	  into spec
	select 0
	     , dokum.ID
		 , towary.ID
		 , megazb.CENA_ZAKUP
		 , megazb.ILOSC
		 , dokum.TOWRABAT
		 , megazb.CENA_ZAKUP
		 , megazb.CENA_ZAKUP*megazb.ILOSC
		 , megazb.CENA_ZAKUP*megazb.ILOSC*towary.VAT*0.01
		 , megazb.CENA_ZAKUP*megazb.ILOSC+megazb.CENA_ZAKUP*megazb.ILOSC*towary.VAT*0.01
		 , if(megazb.ILOSC=0,0,(megazb.CENA_ZAKUP*megazb.ILOSC+megazb.CENA_ZAKUP*megazb.ILOSC*towary.VAT*0.01)/megazb.ILOSC)
		 , towary.VAT
	  from megazb
 left join dokum
        on dokum.INDEKS=megazb.NR_ZA
	   and dokum.TYP in ('ZAM')
 left join towary
        on towary.INDEKS=megazb.INDEKS
	   and towary.CENA_Z=megazb.CENA_ZAKUP
	   and towary.DOSTAWCA=megazb.DOSTAWCA+10000
	 where !isnull(dokum.ID)
	   and !isnull(towary.ID)
");

//-----------------------------------------------------------------------------

mysql_query("truncate megaza");
mysql_query("truncate megazb");

$_SESSION['stop_import_test']="Tabela.php?tabela=dokum&doktyp=$last_table&doktypnazwa=WZki";
exit;

DBF($katalog,$detale,'','tab_obl',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

DBF($katalog,$detale,'','stermask',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

DBF($katalog,$detale,'','megapn',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
//DBF($katalog,$detale,'','megapw',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

DBF($katalog,$detale,'','megatn',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
DBF($katalog,$detale,'','megatw',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

DBF($katalog,$detale,'','megafn',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
DBF($katalog,$detale,'','megafw',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

DBF($katalog,$detale,'','megazn',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
DBF($katalog,$detale,'','megazw',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

DBF($katalog,$detale,'','me_wzzn',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
DBF($katalog,$detale,'','me_wzzw',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

//-----------------------------------------------------------------------------

$w=mysql_query("select * from stermask where ID=1");

if ($r=mysql_fetch_array($w)) {
	$w=mysql_query("update doktypy set NUMER='".$r['NR_KP']."' where TYP='KP'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_KW']."' where TYP='KW'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_BP']."' where TYP='BP'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_BW']."' where TYP='BW'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_FK']."' where TYP='FM'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_NT']."' where TYP='FMK'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_PZ']."' where TYP='PZ'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_ZA']."' where TYP='ZAM'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_NP']."' where TYP='PZK'");
	$w=mysql_query("update doktypy set NUMER='".$r['NR_WZZ']."' where TYP='WZ'");
}

//-----------------------------------------------------------------------------
//PZki

mysql_query("
	update dokum
 left join megapn
        on megapn.NR_PZ=dokum.INDEKS
	   and dokum.TYP in ('PZ')
	   set dokum.DATAO=megapn.DATA_WYST
	     , dokum.CZAS=replace(megapn.TIMESTAMP,', ',' ')
	     , dokum.DNIZWLOKI=DateDiff(megapn.TERMIN,megapn.DATA_WYST)
	     , dokum.NETTODOS=megapn.OGOLEM
	     , dokum.BRUTTODOS=megapn.WART_FKPZ
	 where !isnull(megapn.ID)
");

//-----------------------------------------------------------------------------

mysql_query("truncate megapn");
//mysql_query("truncate megapw");

//-----------------------------------------------------------------------------
//Korekty PZ

mysql_query("update spec
		  left join dokum
		         on dokum.ID=spec.ID_D
				set spec.CENA=-99999
			  where dokum.TYP in ('PZK')
");

mysql_query("delete 
               from spec 
			  where CENA=-99999");

//-----------------------------------------------------------------------------

mysql_query("delete from dokum where TYP='PZK'");

mysql_query("
        insert into dokum 
             select 0
                  , '', 'PZK', megatn.NR_NPZ, megatn.DOSTAWCA+10000, megatn.ROZNICA_FK
                  , megatn.DATA_NOTY, megatn.DATA_NOTY, '', megatn.DATA_NOTY, megatn.ZAPLATA
                  , 0, megatn.NR_PZ, '', replace(megatn.TIMESTAMP,',','')
				  , 0, 0, 0, 0
				  , 0, 0, megatn.ROZNICA
                  , firmy.INDEKS, firmy.TYP, firmy.NAZWA, firmy.KOD, firmy.MIASTO, firmy.ADRES, firmy.NIP
                  , 1, '', ''
				  , 0, 0
				  , 0, 0
				  , 0, 0
				  , 0, 0
				  , '', 0
				  , megatn.ROZNICA, megatn.ROZNICA_FK, '', '' 
               from megatn
          left join firmy 
                 on firmy.ID=megatn.DOSTAWCA+10000
           order by megatn.DATA_NOTY
                  , megatn.NR_NPZ
");

//-----------------------------------------------------------------------------
//Byo

mysql_query("
	insert 
	  into spec
	select 0
	     , dokum.ID
		 , towary.ID
		 , megatw.CENA1
		 , (-megatw.ILOSC1)
		 , 0
		 , megatw.CENA1
		 , megatw.CENA1*(-megatw.ILOSC1)
		 , megatw.CENA1*(-megatw.ILOSC1)*megatw.VAT1*0.01
		 , megatw.CENA1*(-megatw.ILOSC1)+megatw.CENA1*(-megatw.ILOSC1)*megatw.VAT1*0.01
		 , if((-megatw.ILOSC1)=0,0,(megatw.CENA1*(-megatw.ILOSC1)+megatw.CENA1*(-megatw.ILOSC1)*megatw.VAT1*0.01)/(-megatw.ILOSC1))
		 , megatw.VAT1
	  from megatw
 left join dokum
        on dokum.INDEKS=megatw.NR_NPZ
	   and dokum.TYP in ('PZK')
 left join megatn
        on megatn.NR_NPZ=megatw.NR_NPZ
 left join towary
        on towary.INDEKS=megatw.INDEKS1
	   and towary.CENA_Z=megatw.CENA1
	   and towary.DOSTAWCA=megatw.DOSTAWCA+10000
	 where !isnull(dokum.ID)
	   and !isnull(towary.ID)
	   and !isnull(megatn.ID)
");

//-----------------------------------------------------------------------------
//Ma by

mysql_query("
	insert 
	  into spec
	select 0
	     , dokum.ID
		 , towary.ID
		 , megatw.CENA2
		 , megatw.ILOSC2
		 , 0
		 , megatw.CENA2
		 , megatw.CENA2*megatw.ILOSC2
		 , megatw.CENA2*megatw.ILOSC2*megatw.VAT2*0.01
		 , megatw.CENA2*megatw.ILOSC2+megatw.CENA2*megatw.ILOSC2*megatw.VAT2*0.01
		 , if(megatw.ILOSC2=0,0,(megatw.CENA2*megatw.ILOSC2+megatw.CENA2*megatw.ILOSC2*megatw.VAT2*0.01)/megatw.ILOSC2)
		 , megatw.VAT2
	  from megatw
 left join dokum
        on dokum.INDEKS=megatw.NR_NPZ
	   and dokum.TYP in ('PZK')
 left join megatn
        on megatn.NR_NPZ=megatw.NR_NPZ
 left join towary
        on towary.INDEKS=megatw.INDEKS2
	   and towary.CENA_Z=megatw.CENA2
	   and towary.DOSTAWCA=megatw.DOSTAWCA+10000
	 where !isnull(dokum.ID)
	   and !isnull(towary.ID)
	   and !isnull(megatn.ID)
");

//-----------------------------------------------------------------------------
//faktury

mysql_query("
	update dokum
 left join megafn
        on megafn.NR_FK=dokum.INDEKS
	   and dokum.TYP in ('FM','PM')
	   set dokum.SPOSOB=megafn.ZAPLATA
	     , dokum.WYSTAWIL=megafn.WYSTAWIL
	     , dokum.WYDAL=megafn.WYDAL
	     , dokum.DNIZWLOKI=megafn.TERM_ZAPL
	     , dokum.TOWRABAT=megafn.UPUST
	     , dokum.TOWCENNIK=1
	     , dokum.WARTOSC=megafn.OGOLEM
	     , dokum.NETTO0=megafn.WART_NET0
	     , dokum.NETTO8=megafn.WART_NET7
	     , dokum.VAT8=megafn.WART_VAT7
	     , dokum.NETTO23=megafn.WART_NET22
	     , dokum.VAT23=megafn.WART_VAT22
	     , dokum.NETTOZW=megafn.WART_NETZW
	     , dokum.NETTO5=megafn.WART_NET3
	     , dokum.VAT5=megafn.WART_VAT3
	     , dokum.VAT5=megafn.WART_VAT3
	     , dokum.CZAS=replace(megafn.TIMESTAMP,', ',' ')
	     , dokum.NETTODOS=megafn.WART_NET22+megafn.WART_NET7+megafn.WART_NET3+megafn.WART_NET0+megafn.WART_NETZW
	     , dokum.BRUTTODOS=megafn.OGOLEM
	 where !isnull(megafn.ID)
");

//-----------------------------------------------------------------------------

mysql_query("update spec
		  left join dokum
		         on dokum.ID=spec.ID_D
				set spec.CENA=-99999
			  where dokum.TYP in ('FM','PM')
");

mysql_query("delete 
               from spec 
			  where CENA=-99999");

mysql_query("
	insert 
	  into spec
	select 0
	     , dokum.ID
		 , towary.ID
		 , round(megafw.CENA*(1-0.01*dokum.TOWRABAT),2)
		 , megafw.ILOSC
		 , dokum.TOWRABAT
		 , megafw.CENA
		 , round(megafw.CENA*(1-0.01*dokum.TOWRABAT),2)*megafw.ILOSC
		 , round(megafw.CENA*(1-0.01*dokum.TOWRABAT),2)*megafw.ILOSC*towary.VAT*0.01
		 , round((megafw.CENA*(1-0.01*dokum.TOWRABAT)),2)*megafw.ILOSC+round((megafw.CENA*(1-0.01*dokum.TOWRABAT)),2)*megafw.ILOSC*towary.VAT*0.01
		 , if(megafw.ILOSC=0,0,(round((megafw.CENA*(1-0.01*dokum.TOWRABAT)),2)*megafw.ILOSC+round((megafw.CENA*(1-0.01*dokum.TOWRABAT)),2)*megafw.ILOSC*towary.VAT*0.01)/megafw.ILOSC)
		 , towary.VAT
	  from megafw
 left join dokum
        on dokum.INDEKS=megafw.NR_FK
	   and dokum.TYP in ('FM','PM')
 left join towary
        on towary.INDEKS=megafw.INDEKS
	   and towary.CENA_Z=megafw.CENA_ZAKUP
	   and towary.DOSTAWCA=megafw.DOSTAWCA+10000
	 where !isnull(dokum.ID)
	   and !isnull(towary.ID)
");

//-----------------------------------------------------------------------------

mysql_query("truncate megafn");
mysql_query("truncate megafw");

//-----------------------------------------------------------------------------
//Korekty faktur

mysql_query("update spec
		  left join dokum
		         on dokum.ID=spec.ID_D
				set spec.CENA=-99999
			  where dokum.TYP in ('FMK','PMK')
");

mysql_query("delete 
               from spec 
			  where CENA=-99999");

//-----------------------------------------------------------------------------

mysql_query("delete from dokum where TYP='FMK'");

mysql_query("
        insert into dokum 
             select 0
                  , '', 'FMK', megazn.NR_NFK, megazn.ODBIORCA+100000, megazn.ROZNICA
                  , megazn.DATA_NOTY, megazn.DATA_NOTY, megazn.DATA_FK, megazn.DATA_NOTY, megazn.ZAPLATA
                  , 0, megazn.NR_FK, megazn.TYTUL_KOR, replace(megazn.TIMESTAMP,',','')
				  , 0, 0, 0, 0
				  , megazn.WART_NET0, megazn.WART_NETZW, megazn.WRT_ZAKUP
                  , firmy.INDEKS, firmy.TYP, firmy.NAZWA, firmy.KOD, firmy.MIASTO, firmy.ADRES, firmy.NIP
                  , 1, '', ''
				  , 1, megazn.UPUST
				  , megazn.WART_NET22, megazn.WART_VAT22
				  , megazn.WART_NET7, megazn.WART_VAT7
				  , megazn.WART_NET3, megazn.WART_VAT3
				  , '', 0
				  , (megazn.WART_NET22+megazn.WART_NET7+megazn.WART_NET3+megazn.WART_NET0+megazn.WART_NETZW), megazn.ROZNICA, '', '' 
               from megazn
          left join firmy 
                 on firmy.ID=megazn.ODBIORCA+100000
           order by megazn.DATA_NOTY
                  , megazn.NR_NFK
");

//-----------------------------------------------------------------------------
//Byo

mysql_query("
	insert 
	  into spec
	select 0
	     , dokum.ID
		 , towary.ID
		 , round(megazw.CENA1*(1-0.01*megazn.UPUST),2)
		 , (-megazw.ILOSC1)
		 , megazn.UPUST
		 , megazw.CENA1
		 , round(megazw.CENA1*(1-0.01*megazn.UPUST),2)*(-megazw.ILOSC1)
		 , round(megazw.CENA1*(1-0.01*megazn.UPUST),2)*(-megazw.ILOSC1)*megazw.VAT1*0.01
		 , round((megazw.CENA1*(1-0.01*megazn.UPUST)),2)*(-megazw.ILOSC1)+round((megazw.CENA1*(1-0.01*megazn.UPUST)),2)*(-megazw.ILOSC1)*megazw.VAT1*0.01
		 , if((-megazw.ILOSC1)=0,0,(round((megazw.CENA1*(1-0.01*megazn.UPUST)),2)*(-megazw.ILOSC1)+round((megazw.CENA1*(1-0.01*megazn.UPUST)),2)*(-megazw.ILOSC1)*megazw.VAT1*0.01)/(-megazw.ILOSC1))
		 , megazw.VAT1
	  from megazw
 left join dokum
        on dokum.INDEKS=megazw.NR_NFK
	   and dokum.TYP in ('FMK')
 left join megazn
        on megazn.NR_NFK=megazw.NR_NFK
 left join towary
        on towary.INDEKS=megazw.INDEKS1
	   and towary.CENA_Z=megazw.CENA1_ZAK
	   and towary.DOSTAWCA=megazw.DOST1+10000
	 where !isnull(dokum.ID)
	   and !isnull(towary.ID)
	   and !isnull(megazn.ID)
");

//-----------------------------------------------------------------------------
//Ma by

mysql_query("
	insert 
	  into spec
	select 0
	     , dokum.ID
		 , towary.ID
		 , round(megazw.CENA2*(1-0.01*megazn.UPUST2),2)
		 , megazw.ILOSC2
		 , megazn.UPUST2
		 , megazw.CENA2
		 , round(megazw.CENA2*(1-0.01*megazn.UPUST2),2)*megazw.ILOSC2
		 , round(megazw.CENA2*(1-0.01*megazn.UPUST2),2)*megazw.ILOSC2*megazw.VAT2*0.01
		 , round((megazw.CENA2*(1-0.01*megazn.UPUST2)),2)*megazw.ILOSC2+round((megazw.CENA2*(1-0.01*megazn.UPUST2)),2)*megazw.ILOSC2*megazw.VAT2*0.01
		 , if(megazw.ILOSC2=0,0,(round((megazw.CENA2*(1-0.01*megazn.UPUST2)),2)*megazw.ILOSC2+round((megazw.CENA2*(1-0.01*megazn.UPUST2)),2)*megazw.ILOSC2*megazw.VAT2*0.01)/megazw.ILOSC2)
		 , megazw.VAT2
	  from megazw
 left join dokum
        on dokum.INDEKS=megazw.NR_NFK
	   and dokum.TYP in ('FMK')
 left join megazn
        on megazn.NR_NFK=megazw.NR_NFK
 left join towary
        on towary.INDEKS=megazw.INDEKS2
	   and towary.CENA_Z=megazw.CENA2_ZAK
	   and towary.DOSTAWCA=megazw.DOST2+10000
	 where !isnull(dokum.ID)
	   and !isnull(towary.ID)
	   and !isnull(megazn.ID)
");

//-----------------------------------------------------------------------------

mysql_query("truncate megazn");
mysql_query("truncate megazw");

//-----------------------------------------------------------------------------
//WZki

mysql_query("update spec
		  left join dokum
		         on dokum.ID=spec.ID_D
				set spec.CENA=-99999
			  where dokum.TYP in ('WZ')
");

mysql_query("delete 
               from spec 
			  where CENA=-99999");

mysql_query("delete from dokum where TYP='WZ'");

mysql_query("
        insert into dokum 
             select 0
                  , '', 'WZ', me_wzzn.NR_WZZ, me_wzzn.ODBIORCA+100000
				  , (me_wzzn.WART_NET22+me_wzzn.WART_NET7+me_wzzn.WART_NET3+me_wzzn.WART_NET0+me_wzzn.WART_NETZW)+
				    (me_wzzn.WART_VAT22+me_wzzn.WART_VAT7+me_wzzn.WART_VAT3)
                  , me_wzzn.DATA_FAKT, me_wzzn.DATA_FAKT, me_wzzn.DATA_FAKT, me_wzzn.DATA_FAKT, me_wzzn.ZAPLATA
                  , me_wzzn.WPLACONO, '', '', replace(me_wzzn.TIMESTAMP,',','')
				  , 0, 0, 0, 0
				  , me_wzzn.WART_NET0, me_wzzn.WART_NETZW, me_wzzn.WRT_ZAKUP
                  , firmy.INDEKS, firmy.TYP, firmy.NAZWA, firmy.KOD, firmy.MIASTO, firmy.ADRES, firmy.NIP
                  , 1, me_wzzn.WYSTAWIL, ''
				  , 0, me_wzzn.UPUST
				  , me_wzzn.WART_NET22, me_wzzn.WART_VAT22
				  , me_wzzn.WART_NET7, me_wzzn.WART_VAT7
				  , me_wzzn.WART_NET3, me_wzzn.WART_VAT3
				  , '', me_wzzn.TERM_ZAPL
				  , (me_wzzn.WART_NET22+me_wzzn.WART_NET7+me_wzzn.WART_NET3+me_wzzn.WART_NET0+me_wzzn.WART_NETZW)
				  , (me_wzzn.WART_NET22+me_wzzn.WART_NET7+me_wzzn.WART_NET3+me_wzzn.WART_NET0+me_wzzn.WART_NETZW)+
				    (me_wzzn.WART_VAT22+me_wzzn.WART_VAT7+me_wzzn.WART_VAT3)
				  , me_wzzn.WYDAL, '' 
               from me_wzzn
          left join firmy 
                 on firmy.ID=me_wzzn.ODBIORCA+100000
           order by me_wzzn.DATA_FAKT
                  , me_wzzn.NR_WZZ
");

//-----------------------------------------------------------------------------

mysql_query("
	insert 
	  into spec
	select 0
	     , dokum.ID
		 , towary.ID
		 , round(me_wzzw.CENA*(1-0.01*dokum.TOWRABAT),2)
		 , me_wzzw.ILOSC
		 , dokum.TOWRABAT
		 , me_wzzw.CENA
		 , round(me_wzzw.CENA*(1-0.01*dokum.TOWRABAT),2)*me_wzzw.ILOSC
		 , round(me_wzzw.CENA*(1-0.01*dokum.TOWRABAT),2)*me_wzzw.ILOSC*towary.VAT*0.01
		 , round((me_wzzw.CENA*(1-0.01*dokum.TOWRABAT)),2)*me_wzzw.ILOSC+round((me_wzzw.CENA*(1-0.01*dokum.TOWRABAT)),2)*me_wzzw.ILOSC*towary.VAT*0.01
		 , if(me_wzzw.ILOSC=0,0,(round((me_wzzw.CENA*(1-0.01*dokum.TOWRABAT)),2)*me_wzzw.ILOSC+round((me_wzzw.CENA*(1-0.01*dokum.TOWRABAT)),2)*me_wzzw.ILOSC*towary.VAT*0.01)/me_wzzw.ILOSC)
		 , towary.VAT
	  from me_wzzw
 left join dokum
        on dokum.INDEKS=me_wzzw.NR_WZZ
	   and dokum.TYP in ('WZ')
 left join towary
        on towary.INDEKS=me_wzzw.INDEKS
	   and towary.CENA_Z=me_wzzw.CENA_ZAKUP
	   and towary.DOSTAWCA=me_wzzw.DOSTAWCA+10000
	 where !isnull(dokum.ID)
	   and !isnull(towary.ID)
");

//-----------------------------------------------------------------------------

mysql_query("truncate me_wzzn");
mysql_query("truncate me_wzzw");

//-----------------------------------------------------------------------------

require('dbdisconnect.inc');

//-----------------------------------------------------------------------------

$_SESSION['start_process']='';
$_SESSION['start_rec']=0;
//$_SESSION['cur_table']=$last_table;
$_SESSION['stop_import_test']='Tabela.php?tabela=dokum&doktyp=WZ&doktypnazwa=WZki';

?>
</body>
</html>
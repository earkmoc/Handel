<?php

require('funkcje_sql.php');

session_start();
//$_SESSION['stop_import_test']='index.php';
//http://localhost/Handel/import_BW.php
$detale=false;
//$detale=true;

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

<?php

//echo "<body";
echo "<body onload='";
if ($_SESSION['stop_import_test']) {         //skończone, więc wyjście do wskazanego miejsca
   echo 'location.href="'.$_SESSION['stop_import_test'].'"'."'>";
   $_SESSION['stop_import_test']='';
   die;
} else {
   echo 'location.href="import_BW.php"';     //wracaj tu tak długo aż skończysz
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

$katalog=$_POST['katalog'];
//$katalog='c:\PL_1012';
//$katalog='c:\Parrotli';

//-----------------------------------------------------------------------------

require('dbconnect.inc');

$last_table=$_SESSION['cur_table'];

DBF($katalog,$detale,'','megaba',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
DBF($katalog,$detale,'','megabb',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);
DBF($katalog,$detale,'','megakw',$tab_pold,$tab_pols,$start_time,$max_time,$_SESSION['start_rec'],$_SESSION['cur_table']);

//-----------------------------------------------------------------------------
//czyszczenie tmp

//mysql_query("delete from dokumentbk where ID>8400");
//mysql_query("delete from dokumenty where ID>420");
//mysql_query("delete from dokumentz where ID>7996");
//mysql_query("delete from dokspl where ID>8179");

//-----------------------------------------------------------------------------
//Bankowe BP

mysql_query("
  insert 
	 into dokumentbk
  select 0
		 , 0
		 , 0
		 , 0
		 , ''
		 , 'WB'
		 , 0
		 , concat('BP ',NR_BP)
		 , DATA_BP
		 , concat('FM ',NR_FK, ' z dnia ', DATA_FK)
		 , ODBIORCA+100000
		 , ''
		 , ''
		 , ''
		 , ''
		 , WARTOSC
		 , 0
		 , '130'
		 , 'import'
		 , left(DATA_BP,7)
	 from megaba
 order by DATA_BP, NR_BP
");

//jakie ID dostały te dokumenty ?
$w=mysql_query("
	select *
	  from dokumentbk
 left join dokum
	    on (dokum.TYP=left(dokumentbk.PRZEDMIOT,3) 
       and dokum.INDEKS=substr(dokumentbk.PRZEDMIOT,4,7) 
       and dokum.NABYWCA=dokumentbk.NRKONT
       and dokum.DATAW=substr(dokumentbk.PRZEDMIOT,19,10)
          )
	 where dokumentbk.KONTOP='import'
");

//wpisz historię spłat
while ($r=mysql_fetch_row($w)) {
	mysql_query("
		insert
		  into dokumentz
		   set ID_D='".$r[20]."'
		      ,ID_B='".$r[0]."'
		      ,PRZEDMIOT='".$r[9]."'
		      ,KWOTA='".$r[16]."'
   ");
	mysql_query("
		insert
		  into dokspl
		   set ID_F='".$r[10]."'
		      ,ID_X=1
		      ,ID_D='".$r[20]."'
		      ,DATAW='".$r[8]."'
		      ,KWOTA='".$r[16]."'
		      ,KASABANK='Bank'
   ");
}

$w=mysql_query("
	select ID, ID_D, ID_P, KTO, CZAS, TYP, LP, NUMER, DATA, PRZEDMIOT, NRKONT, PSKONT, NIP, NAZWA, ADRES
		 , sum(PRZYCHOD) as przy
		 , sum(ROZCHOD) as roz
		 , KONTOBK, KONTOP, OKRES
		 , sum(if(PRZYCHOD>0,1,0)) as ilep
		 , sum(if(ROZCHOD>0,1,0)) as ilem
	  from dokumentbk
	 where KONTOP='import'
  group by DATA
  order by DATA
");

while ($r=mysql_fetch_array($w)) {
	mysql_query("
		insert
		  into dokumenty
		   set CZAS='".$r['CZAS']."'
		      ,GDZIE='bufor'
			  ,TYP='".$r['TYP']."'
			  ,OKRES='".$r['OKRES']."'
			  ,DWPROWADZE='".$r['DATA']."'
			  ,DDOKUMENTU='".$r['DATA']."'
			  ,DOPERACJI='".$r['DATA']."'
			  ,DWPLYWU='".$r['DATA']."'
			  ,NUMER='".$r['LP']."'
			  ,PRZEDMIOT='import'
			  ,PRZYCHODY='".$r['przy']."'
			  ,ROZCHODY='".$r['roz']."'
			  ,ILEDOKPLUS='".$r['ilep']."'
			  ,ILEDOKMINU='".$r['ilem']."'
	");
}


//jakie ID dostały te dokumenty ?
$w=mysql_query("
	select ID
         ,DWPROWADZE
	  from dokumenty
	 where PRZEDMIOT='import'
 order by DWPROWADZE
");

//wpisz te ID i inne dane do specyfikacji oraz usuń znaczniki importu
while ($r=mysql_fetch_row($w)) {
	mysql_query("
		update dokumentbk
	left join firmy
   		 on firmy.ID=dokumentbk.NRKONT
		   set dokumentbk.ID_D='".$r[0]."'
		      ,dokumentbk.PSKONT=firmy.INDEKS
		      ,dokumentbk.NIP=firmy.NIP
		      ,dokumentbk.NAZWA=firmy.NAZWA
		      ,dokumentbk.ADRES=concat(firmy.KOD,' ',firmy.MIASTO,', ',firmy.ADRES)
            ,dokumentbk.KONTOP=''
		 where dokumentbk.KONTOP='import'
		   and dokumentbk.DATA='".$r[1]."'
	");
}

//usuń znaczniki importu
mysql_query("
	update dokumenty
	   set PRZEDMIOT=''
	 where PRZEDMIOT='import'
");

//			  ,TYP=    if(right(CZAS,8)='00:00:00','WBK',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'WBA','WB'))
//			  ,KONTOBK=if(right(CZAS,8)='00:00:00','132',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'131','130'))

//-----------------------------------------------------------------------------

$_SESSION['stop_import_test']="Tabela.php?tabela=dokumentBA";

//-----------------------------------------------------------------------------

//-----------------------------------------------------------------------------
//Bankowe BW

mysql_query("
  insert 
	 into dokumentbk
  select 0
		 , 0
		 , 0
		 , 0
		 , ''
		 , 'WB'
		 , 0
		 , concat('BW ',NR_BW)
		 , DATA_BW
		 , concat('PZ ',NR_PZ, ' z dnia ', DATA_PZ)
		 , DOSTAWCA+10000
		 , ''
		 , ''
		 , ''
		 , ''
		 , 0
		 , WARTOSC
		 , '130'
		 , 'import'
		 , left(DATA_BW,7)
	 from megabb
 order by DATA_BW, NR_BW
");

//jakie ID dostały te dokumenty ?
$w=mysql_query("
	select *
	  from dokumentbk
 left join dokum
	    on (dokum.TYP=left(dokumentbk.PRZEDMIOT,3) 
       and dokum.INDEKS=substr(dokumentbk.PRZEDMIOT,4,7) 
       and dokum.NABYWCA=dokumentbk.NRKONT
       and dokum.DATAW=substr(dokumentbk.PRZEDMIOT,19,10)
          )
	 where dokumentbk.KONTOP='import'
");

//wpisz historię spłat
while ($r=mysql_fetch_row($w)) {
	mysql_query("
		insert
		  into dokumentz
		   set ID_D='".$r[20]."'
		      ,ID_B='".$r[0]."'
		      ,PRZEDMIOT='".$r[9]."'
		      ,KWOTA='".$r[16]."'
   ");
	mysql_query("
		insert
		  into dokspl
		   set ID_F='".$r[10]."'
		      ,ID_X=1
		      ,ID_D='".$r[20]."'
		      ,DATAW='".$r[8]."'
		      ,KWOTA='".$r[16]."'
		      ,KASABANK='Bank'
   ");
}

$w=mysql_query("
	select ID, ID_D, ID_P, KTO, CZAS, TYP, LP, NUMER, DATA, PRZEDMIOT, NRKONT, PSKONT, NIP, NAZWA, ADRES
		 , sum(PRZYCHOD) as przy
		 , sum(ROZCHOD) as roz
		 , KONTOBK, KONTOP, OKRES
		 , sum(if(PRZYCHOD>0,1,0)) as ilep
		 , sum(if(ROZCHOD>0,1,0)) as ilem
	  from dokumentbk
	 where KONTOP='import'
  group by DATA
  order by DATA
");

while ($r=mysql_fetch_array($w)) {
	mysql_query("
		insert
		  into dokumenty
		   set CZAS='".$r['CZAS']."'
		      ,GDZIE='bufor'
			  ,TYP='".$r['TYP']."'
			  ,OKRES='".$r['OKRES']."'
			  ,DWPROWADZE='".$r['DATA']."'
			  ,DDOKUMENTU='".$r['DATA']."'
			  ,DOPERACJI='".$r['DATA']."'
			  ,DWPLYWU='".$r['DATA']."'
			  ,NUMER='".$r['LP']."'
			  ,PRZEDMIOT='import'
			  ,PRZYCHODY='".$r['przy']."'
			  ,ROZCHODY='".$r['roz']."'
			  ,ILEDOKPLUS='".$r['ilep']."'
			  ,ILEDOKMINU='".$r['ilem']."'
	");
}


//jakie ID dostały te dokumenty ?
$w=mysql_query("
	select ID
         ,DWPROWADZE
	  from dokumenty
	 where PRZEDMIOT='import'
 order by DWPROWADZE
");

//wpisz te ID i inne dane do specyfikacji oraz usuń znaczniki importu
while ($r=mysql_fetch_row($w)) {
	mysql_query("
		update dokumentbk
	left join firmy
   		 on firmy.ID=dokumentbk.NRKONT
		   set dokumentbk.ID_D='".$r[0]."'
		      ,dokumentbk.PSKONT=firmy.INDEKS
		      ,dokumentbk.NIP=firmy.NIP
		      ,dokumentbk.NAZWA=firmy.NAZWA
		      ,dokumentbk.ADRES=concat(firmy.KOD,' ',firmy.MIASTO,', ',firmy.ADRES)
            ,dokumentbk.KONTOP=''
		 where dokumentbk.KONTOP='import'
		   and dokumentbk.DATA='".$r[1]."'
	");
}

//usuń znaczniki importu
mysql_query("
	update dokumenty
	   set PRZEDMIOT=''
	 where PRZEDMIOT='import'
");

//			  ,TYP=    if(right(CZAS,8)='00:00:00','WBK',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'WBA','WB'))
//			  ,KONTOBK=if(right(CZAS,8)='00:00:00','132',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'131','130'))

//-----------------------------------------------------------------------------

$_SESSION['stop_import_test']="Tabela.php?tabela=dokumentBA";

//-----------------------------------------------------------------------------

//-----------------------------------------------------------------------------
//Bankowe KW

mysql_query("
  insert 
	 into dokumentbk
  select 0
		 , 0
		 , 0
		 , 0
		 , ''
		 , 'RK'
		 , 0
		 , concat('KW ',NR_KW)
		 , DATA_KW
		 , concat('PZ ',NR_PZ, ' z dnia ', DATA_PZ)
		 , DOSTAWCA+10000
		 , ''
		 , ''
		 , ''
		 , ''
		 , 0
		 , WARTOSC
		 , '100'
		 , 'import'
		 , left(DATA_KW,7)
	 from megakw
 order by DATA_KW, NR_KW
");

//jakie ID dostały te dokumenty ?
$w=mysql_query("
	select *
	  from dokumentbk
 left join dokum
	    on (dokum.TYP=left(dokumentbk.PRZEDMIOT,3) 
       and dokum.INDEKS=substr(dokumentbk.PRZEDMIOT,4,7) 
       and dokum.NABYWCA=dokumentbk.NRKONT
       and dokum.DATAW=substr(dokumentbk.PRZEDMIOT,19,10)
          )
	 where dokumentbk.KONTOP='import'
");

//wpisz historię spłat
while ($r=mysql_fetch_row($w)) {
	mysql_query("
		insert
		  into dokumentz
		   set ID_D='".$r[20]."'
		      ,ID_B='".$r[0]."'
		      ,PRZEDMIOT='".$r[9]."'
		      ,KWOTA='".$r[16]."'
   ");
	mysql_query("
		insert
		  into dokspl
		   set ID_F='".$r[10]."'
		      ,ID_X=1
		      ,ID_D='".$r[20]."'
		      ,DATAW='".$r[8]."'
		      ,KWOTA='".$r[16]."'
		      ,KASABANK='Kasa'
   ");
}

$w=mysql_query("
	select ID, ID_D, ID_P, KTO, CZAS, TYP, LP, NUMER, DATA, PRZEDMIOT, NRKONT, PSKONT, NIP, NAZWA, ADRES
		 , sum(PRZYCHOD) as przy
		 , sum(ROZCHOD) as roz
		 , KONTOBK, KONTOP, OKRES
		 , sum(if(PRZYCHOD>0,1,0)) as ilep
		 , sum(if(ROZCHOD>0,1,0)) as ilem
	  from dokumentbk
	 where KONTOP='import'
  group by DATA
  order by DATA
");

while ($r=mysql_fetch_array($w)) {
	mysql_query("
		insert
		  into dokumenty
		   set CZAS='".$r['CZAS']."'
		      ,GDZIE='bufor'
			  ,TYP='".$r['TYP']."'
			  ,OKRES='".$r['OKRES']."'
			  ,DWPROWADZE='".$r['DATA']."'
			  ,DDOKUMENTU='".$r['DATA']."'
			  ,DOPERACJI='".$r['DATA']."'
			  ,DWPLYWU='".$r['DATA']."'
			  ,NUMER='".$r['LP']."'
			  ,PRZEDMIOT='import'
			  ,PRZYCHODY='".$r['przy']."'
			  ,ROZCHODY='".$r['roz']."'
			  ,ILEDOKPLUS='".$r['ilep']."'
			  ,ILEDOKMINU='".$r['ilem']."'
	");
}


//jakie ID dostały te dokumenty ?
$w=mysql_query("
	select ID
         ,DWPROWADZE
	  from dokumenty
	 where PRZEDMIOT='import'
 order by DWPROWADZE
");

//wpisz te ID i inne dane do specyfikacji oraz usuń znaczniki importu
while ($r=mysql_fetch_row($w)) {
	mysql_query("
		update dokumentbk
	left join firmy
   		 on firmy.ID=dokumentbk.NRKONT
		   set dokumentbk.ID_D='".$r[0]."'
		      ,dokumentbk.PSKONT=firmy.INDEKS
		      ,dokumentbk.NIP=firmy.NIP
		      ,dokumentbk.NAZWA=firmy.NAZWA
		      ,dokumentbk.ADRES=concat(firmy.KOD,' ',firmy.MIASTO,', ',firmy.ADRES)
            ,dokumentbk.KONTOP=''
		 where dokumentbk.KONTOP='import'
		   and dokumentbk.DATA='".$r[1]."'
	");
}

//usuń znaczniki importu
mysql_query("
	update dokumenty
	   set PRZEDMIOT=''
	 where PRZEDMIOT='import'
");

//			  ,TYP=    if(right(CZAS,8)='00:00:00','WBK',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'WBA','WB'))
//			  ,KONTOBK=if(right(CZAS,8)='00:00:00','132',if(left('".$r['PRZEDMIOT']."',2) IN ('FA','PA'),'131','130'))

//-----------------------------------------------------------------------------

$_SESSION['stop_import_test']="Tabela.php?tabela=dokumentBA";

//-----------------------------------------------------------------------------

require('dbdisconnect.inc');

//-----------------------------------------------------------------------------

$_SESSION['start_process']='';
$_SESSION['start_rec']=0;
//$_SESSION['cur_table']=$last_table;
$_SESSION['stop_import_test']='index.php';

?>
</body>
</html>
<?php
/*
-Parrot/INW:
   pozycje ID_D<0: 
   w ILOSC    jest ilo¶æ     po INW obliczona przez skrypt
   w NETTO    jest ID_F (dostawca) z MAGAZYNY
   w KWOTAVAT jest ID_D (PZ) z dokumenty
   w CENABEZR jest ILOSC sprzed INW, na ogó³ to samo co w BRUTTO, chyba ¿e co¶ namieszali 
   w BRUTTO   jest ZAKUP sprzed INW
   w CENABRUTTOjest STAN sprzed INW

   pozycje ID_D>0: 
   w ILOSC    jest ilo¶æ     po INW wprowadzona z rêki
   w CENABEZR jest cena zakupu

regu³a 1: sum(ILOSC) where ID_D>0 = sum(ILOSC) where ID_D<0, tj. po INWentaryzacji analityka z MAGAZYNY zgodna z syntetyk± z TOWARY 
regu³a 2: CENABRUTTO=CENABEZR where ID_D<0, tj. analityka z MAGAZYNY zgodna z syntetyk± z TOWARY 
regu³a 3: ILOSC-CENABEZR where ID_D<0 daje ró¿nicê inwentaryzacyjn± na wydruku i w warto¶ci INW
*/

@session_start();
$ido=$_SESSION['osoba_id'];

//require('skladuj_zmienne.php');exit;
//require('funkcje_sql.php');
require('dbconnect.inc');

$komunikat='';

function buttonPopraw($id, $idd, $ile, $space) {
   $wynik="<button onclick='location.href=\"test_INW_popraw.php?ID=$id&IDD=$idd&ile=$ile\"'>";
   for ($i=0;$i<$space;$i++) {$wynik.="&nbsp;";}
   $wynik.="$ile";
   for ($i=0;$i<$space;$i++) {$wynik.="&nbsp;";}
   $wynik.="</button>";
   return $wynik;
}

$dalej=($_GET['dalej']==1);

if (!$zaznaczone) {
   $zaznaczone=$_GET['zaznaczone'];
}

if (!$zaznaczone) {
   $zaznaczone=$_POST['zaznaczone'];
}

if (!$zaznaczone) {
   $zaznaczone=abs($ipole);
}

$idds=explode(',',$zaznaczone);

foreach($idds as $idd) {

   $problemy=0;

   $w=mysql_query("
      select *
        from dokum
       where ID=$idd
   ");
   $rd=mysql_fetch_array($w);

   if (($rd['TYP']=='INW')&&($rd['BLOKADA']<>'O')) { 

      $w=mysql_query("
         select sum(ILOSC)
           from spec
          where ID_D=$idd
      ");
      $r=mysql_fetch_row($w);
      $silidd=$r[0];
   
      $w=mysql_query("
         select sum(ILOSC)
           from spec
          where ID_D=-$idd
      ");
      $r=mysql_fetch_row($w);
      $sil_idd=$r[0];
      
      if ($silidd<>$sil_idd) {
         $komunikat.="<br>Ró¿nica ilo¶ci wed³ug inwentaryzacji miêdzy analityk± a syntetyk±: $sil_idd - $silidd = ".($sil_idd - $silidd);
         $problemy++;
      }

      if ($dalej) {
      
         $ww=mysql_query("
            select ID_T
                  ,ILOSC
              from spec_sheet
             where ID_D=$idd
               and ID_OSOBY=$ido
          group by ID_T
         ");
      
      } else {
      
         $ww=mysql_query("
            select ID_T
                  ,sum(ILOSC)
              from spec
             where ID_D=-$idd
          group by ID_T
         ");
      }

      while ($rr=mysql_fetch_row($ww)) {  //zsumowana analityka grupowana po ID_T

         $idt=$rr[0];
         $sil=$rr[1];
         $w=mysql_query("
            select ILOSC
            from spec
            where ID_D=$idd
              and ID_T=$idt
         ");
         while ($r=mysql_fetch_row($w)) {    //niesumowana syntetyka
            if ($r[0]<>$sil) {

               $wt=mysql_query("
                select *
                  from towary
                 where ID=$idt
               ");
               $rt=mysql_fetch_array($wt);
      
               $komunikat.="<br>$rt[INDEKS] $rt[NAZWA]: ró¿nica sum ilo¶ci wed³ug inwentaryzacji miêdzy analityk± a syntetyk±: $sil<>$r[0]";
            }
         }
      }

if ($dalej) {

   $ww=mysql_query("
      select ID_T
        from spec_sheet
       where ID_D=$idd
         and ID_OSOBY=$ido
    group by ID_T
   ");

} else {

   mysql_query("
      truncate spec_sheet
   ");

   $ww=mysql_query("
      select ID_T
        from spec
       where ID_D=-$idd
    group by ID_T
   ");
//      and CENABRUTTO<>CENABEZR
}

$problemySub=0;
$komunikatSub='';
while ($rr=mysql_fetch_row($ww)) {

      $idt=$rr[0];

      $wt=mysql_query("
        select *
          from towary
         where ID=$idt
      ");
      $rt=mysql_fetch_array($wt);
      
      $komunikatSub.="<br><hr><br><font style=\"background-color:lightblue\"><b>$rt[INDEKS]</b> $rt[NAZWA]</font>";
      $komunikatSub.=" sprawd¼ w&nbsp;&nbsp;<button onclick='window.open(\"Tabela_SQL.php?phpini=a3_gen.php&ipole=$rt[ID]\", \"_new\")'>historii tego towaru</button>";

      $ilePrzed=0;
      $_GET['ipole']=$idt;
      $tabelaaa='dokum';  //$natab;	// tu l¹duje po akcji
      require('a3_gen.php');  //po tym w analiza3 jest historia towaru
      $natab=$tabelaaa;	// tu l¹duje po akcji
      $w=mysql_query("
         select *
           from analiza3
          where ID_OSOBYUPR=$ido 
       order by ID desc
      ");
      while ($r=mysql_fetch_array($w)) {
         if ($r['ID_D']==$idd) {
            $r['STAN']=$r['STAN']*1;
            $ilepo=$r['STAN'];
            
            $r=mysql_fetch_array($w);
            $r['STAN']=$r['STAN']*1;
            $ilePrzed=$r['STAN'];
            break;
         }
      }

      $komunikatSub.=" i rozstrzygnij problem ze stanem <b>PRZED T¡ INWENTARYZACJ¡</b> (wed³ug historii towaru = <font style=\"background-color:green\"><b>&nbsp;$ilePrzed&nbsp;</b></font>)";

      $sumaPrzedWgPz=0;
      $sumaPrzedWgMg=0;
      $w=mysql_query("
         select sum(CENABRUTTO)
               ,sum(CENABEZR)
           from spec
          where ID_D=-$idd
            and ID_T=$idt
      ");
      
      if ($r=mysql_fetch_row($w)) {
         $sumaPrzedWgPz=$r[0];
         $sumaPrzedWgMg=$r[1];
      }
      
      $w=mysql_query("
         select spec.ID
               ,spec.CENABRUTTO
               ,spec.CENABEZR
               ,spec.ID_T
               ,spec.CENA
               ,spec.BRUTTO
               ,spec.KWOTAVAT
           from spec
      left join dokum 
             on dokum.ID=spec.KWOTAVAT
          where ID_D=-$idd
            and ID_T=$idt
       order by dokum.DATAW desc
      ");
      
//            and (CENABRUTTO<>0
//              or CENABEZR<>0
//                )

      $komunikatSub.="<table border=1 cellpadding=5 cellspacing=0>";

      $komunikatSub.="<tr>";
      $komunikatSub.="<td>Cena</td>";
      $komunikatSub.="<td>Dokument</td>";
      $komunikatSub.="<td>Data</td>";
      $komunikatSub.="<td>Przychód</td>";
      $komunikatSub.="<td>Stan przed wed³ug PZ</td>";
      $komunikatSub.="<td>Stan przed wed³ug MG</td>";
      $komunikatSub.="<td>Stan zerowy</td>";
      $komunikatSub.="<td>Max ile mo¿e byæ przed inwentaryzacj±</td>";
      $komunikatSub.="</tr>";

      while ($r=mysql_fetch_row($w)) {

         $r[1]=$r[1]*1;
         $r[2]=$r[2]*1;
         $r[5]=$r[5]*1;
         $r[6]=$r[6]*1;

         $przych=$r[5];
         $stanpz=$r[1];
         $stanmg=$r[2];
         
         $www=mysql_query("
            select TYP
                  ,INDEKS
                  ,DATAW 
              from dokum
             where ID=$r[6]
         ");
         if ($rrr=mysql_fetch_row($www)) {
            $dokum_typ=$rrr[0];
            $dokum_indeks=$rrr[1];
            $dokum_dataw=$rrr[2];
         }
         
         $komunikatSub.="<tr>";
         $komunikatSub.="<td>$r[4]</td>";
         $komunikatSub.="<td>$dokum_typ $dokum_indeks</td>";
         $komunikatSub.="<td>$dokum_dataw</td>";
         $komunikatSub.="<td>$r[5]</td>";
         $komunikatSub.="<td>".buttonPopraw($r[0], $idd, $r[1], 3)."</td>";
         $komunikatSub.="<td>".buttonPopraw($r[0], $idd, $r[2], 3)."</td>";

         if ($r[1]==$r[2]) {
           //$komunikatSub.=" <-- tu nie ma po co klikaæ, bo s± równe";
         } else {
           $problemySub++;
         }
         $komunikatSub.="<td>".buttonPopraw($r[0], $idd, 0, 3)."</td>";
         
         $ileMax=$ilePrzed;

         if ($ilePrzed-$sumaPrzedWgPz>0) {
            if ($ileMax>($ilePrzed-$sumaPrzedWgPz)) {
               $ileMax=$ilePrzed-$sumaPrzedWgPz;
            }
         } else {
            if ($ileMax<($ilePrzed-$sumaPrzedWgPz)) {
               $ileMax=($ilePrzed-$sumaPrzedWgPz);
            }
         }
         
         if ($stanpz==$sumaPrzedWgPz) {
            $ileMax=$ilePrzed;
         }

         if (($przych)>0) {     //-$stanpz
            if ($ileMax>($przych)) {
               $ileMax=($przych);
            }
         } else {
            if ($ileMax<($przych)) {
               $ileMax=($przych);
            }
         }
         
         if (0==$sumaPrzedWgPz) {
            $ileMax=$ilePrzed;
         }

         if ($ileMax==$ilePrzed) {
            $komunikatSub.="<td>".buttonPopraw($r[0], $idd, $ileMax, 3)."</td>";
         } else {
            $komunikatSub.="<td>".buttonPopraw($r[0], $idd, $ileMax, 3)."&nbsp;&nbsp;&nbsp;lub&nbsp;&nbsp;&nbsp;".buttonPopraw($r[0], $idd, $ilePrzed, 3)."</td>";
         }

//         $komunikatSub.="<br><br>lub"; 
//         for($i=0;$i<=100;$i++) {
//            $komunikatSub.=", <button onclick='location.href=\"test_INW_popraw.php?ID=$r[0]&IDD=$idd&ile=$i\"'>$i</button> ";
//         }
//         $komunikatSub.="<br>lub"; 
//         for($i=0;$i>=-100;$i--) {
//            $komunikatSub.=", <button onclick='location.href=\"test_INW_popraw.php?ID=$r[0]&IDD=$idd&ile=$i\"'>$i</button> ";
//         }

         $komunikatSub.="</tr>";
      }

      $komunikatSub.="<tr>";
      $komunikatSub.="<td></td>";
      $komunikatSub.="<td></td>";
      $komunikatSub.="<td></td>";
      $komunikatSub.="<td>Razem:</td>";
      $komunikatSub.="<td>".(($ilePrzed<>$sumaPrzedWgPz)?"<font style=\"background-color:red\"><b>$sumaPrzedWgPz (ró¿nica o ".($ilePrzed-$sumaPrzedWgPz).")</b></font>":$sumaPrzedWgPz)."</td>";
      $komunikatSub.="<td>".(($ilePrzed<>$sumaPrzedWgMg)?"<font style=\"background-color:red\"><b>$sumaPrzedWgMg (ró¿nica o ".($ilePrzed-$sumaPrzedWgMg).")</b></font>":$sumaPrzedWgMg)."</td>";
      $komunikatSub.="<td></td>";
      $komunikatSub.="<td></td>";
      $komunikatSub.="</tr>";

      $komunikatSub.="</table>";
      
//      $komunikatSub.="Wed³ug historii obrotów tego towaru:";
//      $komunikatSub.="<br>$ilepo = Stan wed³ug tej inwentaryzacji";
//      $komunikatSub.="<br>$ilePrzed = Stan przed t± inwentaryzacj±";

      if ($ilePrzed<>$sumaPrzedWgPz) {
         $problemySub++;
//         $komunikatSub.=" <font style=\"background-color:red\"><b><-- ró¿nica o ".($ilePrzed-$sumaPrzedWgPz). ' do sumy wg PZ</b></font>';
      }
      
      if ($ilePrzed<>$sumaPrzedWgMg) {
         $problemySub++;
//         $komunikatSub.=" <font style=\"background-color:red\"><b><-- ró¿nica o ".($ilePrzed-$sumaPrzedWgMg). ' do sumy wg MG</b></font>';
      }
      
      if ($problemySub) {
         $problemy+=$problemySub;
         $komunikat.=$komunikatSub;
      }


//         if (!$dalej&&(count($idds)==1)) {    //pierwszy przebieg dla testu jednego dokumentu notuje problematyczne towary
      if (!$dalej&&$problemySub) {    //pierwszy przebieg notuje problematyczne towary

         mysql_query("
       insert into spec_sheet
            select *, $ido
              from spec
             where ID_D=$idd
               and ID_T=$idt
             limit 1
         ");

      }

      if ($dalej&&!$problemySub) {    //z tym towarem ju¿ nie ma problemów

         mysql_query("
            delete 
              from spec_sheet
             where ID_D=$idd
               and ID_T=$idt
             limit 1
         ");

      }

      $problemySub=0;
      $komunikatSub='';
}   

      $w=mysql_query("
         select sum((ILOSC-CENABEZR)*CENA)
           from spec
          where ID_D=-$idd
      ");
      $r=mysql_fetch_row($w);
      $wINWspec=$r[0];
      $wINWdokum=$rd['NETTOCZ'];
   
      if ($wINWdokum<>$wINWspec) {
         $komunikat.="<br>Dokument=$idd, $rd[TYP] $rd[INDEKS], jest ró¿nica warto¶ci netto: dokument=$wINWdokum, obecna specyfikacja=$wINWspec";
         require('spec_calc_inw.php');
         $komunikat.=" - poprawione";
      }

      if ($problemy) {
         $komunikat="<br><br>Dokument: $rd[TYP] $rd[INDEKS] $komunikat";

         mysql_query("
            update dokum
               set WPLACONO=WARTOSC
                  ,UWAGI='<font style=\"background-color:red\"><b>Problemy ( $problemy )</b></font>'
             where ID=$idd
         ");
      } else {
         mysql_query("
            update dokum
               set WPLACONO=WARTOSC
                  ,UWAGI=''
             where ID=$idd
         ");
      }
   }
}
if ($komunikat) {?>
<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=iso-8859-2">
<META HTTP-EQUIV="Reply-to" CONTENT="AMoch@pro.onet.pl">
<meta name="Author" content="Arkadiusz Moch">
<meta http-equiv="Content-Language" content="pl">
<meta content="pl" name="Language" />
<meta http-equiv="refresh" content="1200" >
</head>
<body onload="document.getElementById('start').focus()">
<?php
   $komunikat="<a id='start' href=Tabela.php?tabela=dokum>Powrót = Enter</a>".$komunikat;
   echo $komunikat;
   die;
}
?>

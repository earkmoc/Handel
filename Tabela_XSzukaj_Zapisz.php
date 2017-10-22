<?php

session_start();

$tabela=$_POST['tabela'];          // zapisz tu
$tabelaa=$_POST['tabelaa'];        // i id¿ tu
$szukane=$_POST['szukane'];        // zawartosc pola w ktorym stal gdy wcisnal POMOC

$idtab=$_POST['idtab'];                        // ID tabeli gdzie dzia³a formularz
$id=$_POST['ID'];                                        // ID pozycji w tabeli j.w.
$op=$_POST['opole'];
$c=$_POST['c'];

require('dbconnect.inc');

$z="select * from tabele where NAZWA='";        // pobierz definicjê formularza
$z.=$tabela;
$z.="'";
$z.=' limit 1';

$polaszukane=explode(",",$szukane);

function PoleSzukane($linia,$ps) {
	$l=explode("|",$linia);
	for ($i=0;$i<count($ps);$i++) {
		if ($l[0]==$ps[$i]) {
			return true;
		}
	}
	return false;
}

$baza='';
$w=mysql_query($z);
if (!$w) {exit;}
else {
        $w=mysql_fetch_array($w);
        $sql=StripSlashes($w['FORMULARZ']);
        if (!$sql) { exit;}
        else {
					$w=explode("\n",$sql);		// na linie
		         $baza=trim($w[0]);

                $mc=-1;
                $cc=Count($w);
                for($i=1;$i<$cc;$i++) {
                        if (substr($w[$i],0,4)=='from') {;}
                        elseif (substr($w[$i],0,5)=='where') {;}
                        elseif (!PoleSzukane($w[$i],$polaszukane)) {$z.='';}
                        else {
                                $mc++;
                                $l=explode("|",$w[$i]);
                                $pola[$mc]=trim($l[0]);
                                $szer[$mc]=trim($l[2]);
                                $wart[$mc]=$_POST[$pola[$mc]];
											if ($wart[$mc]) {
												if ($pola[$mc]=='NRBLOKU' && substr($wart[$mc],0,1)<>'=') {
													$wart[$mc]="='".$wart[$mc]."'";
												}
												if ($pola[$mc]=='NRMIESZK') {
													if (count($buf=explode('::',$wart[$mc]))>1) {
                                          $znak=ord(substr(trim($buf[0]),-1));
                                          if (48<=$znak && $znak<=57) {	//0-9
															$buf[0]=substr('00000000'.trim($buf[0]),-$szer[$mc]+1);
														}
														else {								//inne, np.: 1A
															$buf[0]=substr('00000000'.trim($buf[0]),-$szer[$mc]);
															$buf[0]=StrToUpper($buf[0]);
														}
                                          $znak=ord(substr(trim($buf[1]),-1));
                                          if (48<=$znak && $znak<=57) {	//0-9
															$buf[1]=substr('00000000'.trim($buf[1]),-$szer[$mc]+1);
														}
														else {								//inne, np.: 1A
															$buf[1]=substr('00000000'.trim($buf[1]),-$szer[$mc]);
															$buf[1]=StrToUpper($buf[1]);
														}
														$wart[$mc]='"'.$buf[0].'"::"'.$buf[1].'"';
													}
													else {
														$buf[0]=Max(1,$wart[$mc]*1-8);	//1
														$buf[1]=$buf[0]+16;	//999
														$buf[0]=substr('00000000'.trim($buf[0]),-$szer[$mc]+1);
														$buf[1]=substr('00000000'.trim($buf[1]),-$szer[$mc]+1);
                                          $znak=ord(substr(trim($wart[$mc]),-1));
                                          if (48<=$znak && $znak<=57) {	//0-9
															$wart[$mc]=substr('00000000'.trim($wart[$mc]),-$szer[$mc]+1);
														}
														else {								//inne, np.: 1A
															$wart[$mc]=substr('00000000'.trim($wart[$mc]),-$szer[$mc]);
															$wart[$mc]=StrToUpper($wart[$mc]);
														}
														$wart[$mc]='"'.$buf[0].'"::"'.$buf[1].'" or abonenci.NRMIESZK="'.$wart[$mc].'"';
													}
												}
												else {
													if (substr($szer[$mc],0,1)==='0') {
                                          $znak=ord(substr(trim($wart[$mc]),-1));
                                          if (48<=$znak && $znak<=57) {	//0-9
															$wart[$mc]=substr('00000000'.trim($wart[$mc]),-$szer[$mc]+1);
														}
														else {								//inne, np.: 1A
															$wart[$mc]=substr('00000000'.trim($wart[$mc]),-$szer[$mc]);
															$wart[$mc]=StrToUpper($wart[$mc]);
														}
													}
												}
											}
                        }
                }
        }
}

$z='Select ID from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$idtab;
$z.=' limit 1';
$w=mysql_query($z);

if (!$w) {exit;}
else {                                                        // jest namiar na konkretny stan
	$w=mysql_fetch_array($w);
	$idtabeles=$w[0];                                // ID konkretnego stanu
	$z='Update tabeles';
	$z.=" set `NR_STR`='1',`NR_ROW`='1',";
	$z.="`SORTOWANIE`='abonenci.IDULICY asc, abonenci.NRDOMU asc, abonenci.NRMIESZK asc',";
	$z.="`WARUNKI`=";
	$z.="'(";

	$mc=-1;
	for ($i=0;$i<count($wart);$i++) {
		if ($wart[$i]) {

			$mc++;

			if ($mc>0) {
				$z.=') and (';
			}

			$znak=ord(substr(trim($wart[$i]),0,1));        //w polu liczbowym
   	   if (48<=$znak && $znak<=57) {                   // mo¿e byæ przecinek
      		$wart[$i]=str_replace(',','.',$wart[$i]);  // zamieniamy go na kropkê
			}

			if (count(explode(".",$pola[$i]))>1) {  // jest kropka w nazwie pola, np. "grupy.NAZWAGR"
   	      $z.='';
			}
			else {
				$z.=$baza.'.';
			}

	      $z.=$pola[$i];

			if (substr($wart[$i],0,1)=='>') {
      	          $z.=addslashes($wart[$i]);
			}
			elseif (substr($wart[$i],0,1)=='<') {
      	          $z.=addslashes($wart[$i]);
			}
			elseif (substr($wart[$i],0,1)=='=') {
      	          $z.=addslashes($wart[$i]);
			}
			elseif (count($buf=explode('::',$wart[$i]))>1) {
      	          $z.=' between '.$buf[0].' and '.$buf[1];
			}
			else {
      	          $z.=' like "';
         	       $z.=addslashes($wart[$i]);
	                $z.='%"';
			}
		}//if ($wart[$i])
	}//for ($i=0;$i<count($wart);$i++)
}//if (!$w) {exit;}

if ($mc==-1 ) {
	$z='Update tabeles';
	$z.=" set `NR_STR`='1',`NR_ROW`='1',`WARUNKI`=''";
   $z.=' where `ID`=';
   $z.="'";
   $z.=$idtabeles;
   $z.="'";
   $z.=" limit 1";
   $w=mysql_query($z);
   $sqla=$z;
}
else {
	$z.=")'";
	$z.=' where `ID`=';
	$z.="'";
	$z.=$idtabeles;
	$z.="'";
	$z.=" limit 1";
	$w=mysql_query($z);
	$sqla=$z;
}

//$w=false;

if ($w) {
        echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
        echo '<title>Zapis udany</title></head><body bgcolor="#BFD2FF" ';
        echo 'onload="';
        echo "location.href='Tabela.php?tabela=".$tabelaa."'";
        echo '">';
        echo '</body></html>';
}
if (!$w) {
	echo "$z<br  /><br  />niestety nie wysz³o !!!";
}
//mysql_free_result($w);
require('dbdisconnect.inc');
?>
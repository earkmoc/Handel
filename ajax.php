<script type="text/javascript" src="advajax.js"></script>
<script type="text/javascript" language="JavaScript">
<!--

function Ajax_indeks(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_indeks.php?indeks="+$w
                     +"&ilosc="+document.getElementById("towar_"+$i+"_6").value
                     +"&rabat="+document.getElementById("towar_"+$i+"_9").value,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_3").value = ss; //indeks 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_4").innerHTML = ss;   //nazwa

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_8").value = ss;       //cenabezr

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_9").value = ss;       //rabat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_10").innerHTML = ss;   //cena

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_5").innerHTML = ss;   //stan

                  document.getElementById("towar_"+$i+"_6").value = 1;        //ilo¶æ

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_7").innerHTML = ss;  //jm

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_12").innerHTML = ss;  //%VAT



                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto


                  if (document.getElementById("towar_"+$i+"_5").innerHTML*1 < document.getElementById("towar_"+$i+"_6").value*1) {
                     document.getElementById("towar_"+$i+"_6").style.background='red';
//	                  document.getElementById("towar_"+$i+"_6").value = 0;        //ilo¶æ
                  } else {
                     document.getElementById("towar_"+$i+"_6").style.background='white';
                  }

                  tab_czysc();
                  $r=$i+2;
                  $r=($r>$rr?$rr:$r);
                  tab_kolor();
                  
                }
});
}
function Ajax_cena(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_cena.php?cena="+$w
                     +"&rabat="+document.getElementById("towar_"+$i+"_9").value
                     +"&ilosc="+document.getElementById("towar_"+$i+"_6").value
                     +"&vat="+document.getElementById("towar_"+$i+"_12").innerHTML,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_7").innerHTML = ss;  //cena 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto
                }
});
}
function Ajax_rabat(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_cena.php?rabat="+$w
                     +"&cena="+document.getElementById("towar_"+$i+"_8").value
                     +"&ilosc="+document.getElementById("towar_"+$i+"_6").value
                     +"&vat="+document.getElementById("towar_"+$i+"_12").innerHTML,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_9").innerHTML = ss;  //cena 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto
                }
});
}
function Ajax_ilosc(ob,$i,$j) {
$w=ob.value;
advAJAX.get({
    url : "sheet_ilosc.php?ilosc="+$w
                     +"&cena="+document.getElementById("towar_"+$i+"_10").innerHTML
                     +"&vat="+document.getElementById("towar_"+$i+"_12").innerHTML,
    onSuccess : function(obj) {

                  s=obj.responseText;

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_11").innerHTML = ss;  //netto 

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_13").innerHTML = ss;  //vat

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_14").innerHTML = ss;  //brutto

                  xx=s.indexOf('|',s);ss=s.substring(0,xx);s=s.substring(xx+1); 
                  document.getElementById("towar_"+$i+"_15").innerHTML = ss;  //cenabrutto

                  if (document.getElementById("towar_"+$i+"_5").innerHTML*1 < document.getElementById("towar_"+$i+"_6").value*1) {
                     document.getElementById("towar_"+$i+"_6").style.background='red';
                  } else {
                     document.getElementById("towar_"+$i+"_6").style.background='white';
                  }

                  tab_czysc();
                  $r=$i+2;
                  $r=($r>$rr?$rr:$r);
                  tab_kolor();
                }
});
}
-->
</script>

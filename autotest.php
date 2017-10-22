<?php
$z=("
   select
          towary.ID
         ,towary.STAN
     from towary 
left join magazyny 
       on (magazyny.ID_T=towary.ID)
    where towary.STATUS='T'
 group by towary.ID
   having sum(magazyny.STAN)=towary.STAN 
      and towary.STAN=0 
      and sum(abs(magazyny.STAN))<>0
");

$w=mysql_query($z);
while ($r=mysql_fetch_row($w)) {
   $idt=$r[0];
   mysql_query("
      update magazyny
         set STAN=0
       where magazyny.ID_T=$idt
   ");
}
?>
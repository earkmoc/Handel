
//exec('lp phpinfo.php');
//exec('lp truncate.sql');

//`lp phpinfo.php`;
//`lp truncate.sql`;

//system('lp phpinfo.php');
//system('lp truncate.sql');

//function lpr($STR,$PRN) {
//	$prn=(isset($PRN) && strlen($PRN))?"$PRN":C_DEFAULTPRN ;
//  $CMDLINE="lpr -P $prn ";
//  $pipe=popen("$CMDLINE" , 'w' );
//  if (!$pipe) {print "pipe failed."; return ""; }
//  fputs($pipe,$STR);
//  pclose($pipe);
//} // lpr()

//lpr('test','epson');
//lpr('test','Epson');
//lpr('test','EPSON');
//lpr('test','Epson FX-1050');

//function getPrinter($SharedPrinterName) {
//   global $REMOTE_ADDR;
//   $host=getHostByAddr($REMOTE_ADDR);
//   return "\\\\".$host."\\".$SharedPrinterName;
//   return "\\\\84.40.204.49\\".$SharedPrinterName;
//}

//echo getPrinter("EPSON");
//$handle=printer_open(getPrinter("EPSON"));
//printer_write($handle, " Test of printer "); 
//$handle=printer_close(); 

//$print = printer_open(); 
//$print = printer_open("LPT1:"); 
//printer_write($print, " Test of printer "); 
//printer_write($print, " Test of printer "); 
//printer_write($print, " Test of printer "); 
//$print = printer_close(); 


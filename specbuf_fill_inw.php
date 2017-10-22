<?php

//SELECT * , count( * ) 
//FROM spec
//LEFT JOIN dokum ON dokum.ID = spec.ID_D
//WHERE dokum.DATAS > '2011.06.30'
//GROUP BY spec.ID_D, spec.ID_T
//HAVING count( * ) >1

//mysql_query("
//	drop table specbuf
//");

mysql_query("
	create temporary table IF NOT EXISTS specbuf (
	`ID` int(11) NOT NULL auto_increment,
	`ID_D` int(11) not null default '0' ,
	`ID_T` int(11) not null default '0' ,
	`CENA` decimal(12,2) not null default '0.00' ,
	`ILOSC` decimal(12,3) not null default '0.000' ,
	`RABAT` decimal(3,0) not null default '0' ,
	`CENABEZR` decimal(12,2) not null default '0.00' ,
	`NETTO` decimal(12,2) not null default '0.00' ,
	`KWOTAVAT` decimal(12,2) not null default '0.00' ,
	`BRUTTO` decimal(12,2) not null default '0.00' ,
	`CENABRUTTO` decimal(12,2) not null default '0.00' ,
	`STAWKAVAT` char(3) not null default '', 
	  PRIMARY KEY  (`ID`),
	  KEY `ID_T` (`ID_T`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin2
");

mysql_query("
	truncate specbuf
");

mysql_query("
	insert 
	  into specbuf
	select 0, ID_D, ID_T, CENA, sum(ILOSC), RABAT, sum(CENABEZR), NETTO, KWOTAVAT, BRUTTO, sum(CENABRUTTO), STAWKAVAT 
	  from spec
	 where ID_D=-$idd
  group by ID_T
");

mysql_query("
	check table specbuf
");

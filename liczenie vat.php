abonenci
ID|ID ABO|||style="font-size:10"|
IDGRUPY|ID GRU|||style="font-size:10"|
IDULICY|ID ULICY|||style="font-size:10"|
NRDOMU|DOM|||style="font-size:10"|
NRMIESZK|MIESZ|||style="font-size:10"|
NAZWISKO|Nazwisko||
IMIE|ImiÍ||
Sum(nadplaty.WYSWPL)|Nad p≥aty||style="font-size:10"|
grupy.NAZWAGR|Grupa||
RODZDOK|RODZ DOK|2||style="font-size:10"|
SERIADOK|SERIA DOK|3||style="font-size:10"|
NUMERDOK|Numer dokumentu|||style="font-size:16"|
DATAUMOWY|DATA UMOWY|||style="font-size:16"|
DATAREAL|DATA REAL|||style="font-size:16"|
TYPUMOWY|TYP UMOWY|1||style="font-size:10"|
WALUTA|Waluta|||style="font-size:10"|
KONSPOMSC|KONSPOMSC|2||style="font-size:10"|
PLOPLDOD|PLOPLDOD|1||style="font-size:10"|
UWAGIAB|Uwagi|||style="font-size:10"|
STATUS|STATUS|1||style="font-size:10"|
ZABLOK|ZABLOK|1||style="font-size:10"|
NAZWA_F|NAZWA||
MIEJSC_F|MIEJSCOWOSC||
KOD_F|KOD||
ULICA_F|ULICA||
NIPABONENT|NIP||
NRKSIAZECZ|NR KSIAZECZ|||style="font-size:10"|
IDP|IDP|6|
DATAMODYF|DATA MODYF|||style="font-size:10"|
from abonenci left join grupy on abonenci.IDGRUPY=grupy.id left join nadplaty on abonenci.ID=nadplaty.IDABONENTA
group by abonenci.ID

(Select oplaty.ID,oplaty.IDABONENTA,oplaty.TYPTYTULU,typytyt.NAZWA,oplaty.ZTYTULU,typoplat.NAZWASKR,oplaty.DODNIA,oplaty.KWOTA,(0),oplaty.NRFAKTURY,oplaty.NRPOZYCJI,oplaty.ZAMIESIAC from oplaty,typytyt,typoplat where (oplaty.IDABONENTA=3338 and oplaty.TYPTYTULU=typytyt.id and oplaty.ZTYTULU=typoplat.id) )
union (select nadplaty.ID, nadplaty.IDABONENTA, nadplaty.TYPTYTULU, typytyt.NAZWA, nadplaty.ZTYTULU, typoplat.NAZWASKR, nadplaty.DODNIA, nadplaty.WYSWPL, 0, nadplaty.NRFAKTURY, nadplaty.NRPOZYCJI, '' from nadplaty,typytyt,typoplat where (nadplaty.IDABONENTA=3338 and nadplaty.TYPTYTULU=typytyt.id and nadplaty.ZTYTULU=typoplat.id)) limit 0,16

Select dokwplat.ID,if(dokwplat.KPLUBBANK='0','KP',dokwplat.KPLUBBANK),dokwplat.NRDOKUM,dokwplat.DATAPRZYJ,dokwplat.IDOPERATOR,dokwplat.GOT3CZEK,dokwplat.NRCZEKU,dokwplat.BANKCZEKU,Sum(wplaty.WYSWPL),Sum(splaty.WYSWPL) from dokwplat left join wplaty on (dokwplat.NRDOKUM=wplaty.NRDOKUM and dokwplat.IDOPERATOR=wplaty.IDOPERATOR and dokwplat.DATAPRZYJ=wplaty.DATAPRZYJ) left join splaty on (dokwplat.NRDOKUM=splaty.NRDOKUM and dokwplat.IDOPERATOR=splaty.IDOPERATOR and dokwplat.DATAPRZYJ=splaty.DATAPRZYJ)
  group by dokwplat.ID order by dokwplat.ID desc limit 0,21;

"numer umowy"
"data umowy"
"abonent kod i miasto"
"abonent adres"
"dowÛd osobisty"
"abonent telefon"
"op≥ata jednorazowa" 100 z≥
"modem parametry":
	001371D9FA6E
	140255518869267301021001
	ZAGRODNIKI
"≥πcze przepustowoúÊ gwarantowana"	256/128 kbps
"adres IP"
"maska podsieci"
"brama"
"serwery DNS"
"terminal"
"nazwa konta 1"
"has≥o"
"nazwa konta 2"
"strona"
"adres terminala"	MAC 00B6AD30836
"kara umowna"		300 z≥


2 KOZARZEWSKA MARIA


CREATE TABLE abonenci (
ID int(11) NOT NULL auto_increment,
IDGRUPY int(11) NOT NULL default 0,
IDABONENTA char(7) default '',
TYPINST char(1) default '',
RODZADM char(1) default '',
IDULICY char(3) default '',
NRDOMU char(4) default '',
NRMIESZK char(4) default '',
NAZWISKO char(20) default '',
IMIE char(20) default '',
RODZDOK char(2) default '',
SERIADOK char(3) default '',
NUMERDOK char(12) default '',
DATAUMOWY date default NULL,
DATAREAL date default NULL,
TYPUMOWY char(1) default '',
WALUTA char(3) default '',
KONSPOMSC int(2) default 0,
PLOPLDOD char(1) default '',
UWAGIAB char(20) default '',
STATUS char(1) default '',
ZABLOK char(1) default '',
NAZWA_F char(40) default '',
MIEJSC_F char(20) default '',
KOD_F char(6) default '',
ULICA_F char(30) default '',
NIPABONENT char(13) default '',
NRKSIAZECZ char(12) default '',
IDP char(6) default '',
DATAMODYF date default NULL,
PRIMARY KEY (ID)
) TYPE=MyISAM;

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON abonenci TO 'guest' IDENTIFIED BY '123';

order by NAZWISKO,IDULICY,NRDOMU,NRMIESZK

CREATE TABLE wplaty (
ID int(11) NOT NULL auto_increment,
IDGRUPY int(11) NOT NULL default 0,
IDABONENTA char(7) default '',
TYPINST char(1) default '',
RODZADM char(1) default '',
TYPTYTULU int(11) default 0,
ZTYTULU int(11) default 0,
WYSWPL float(9,2) NOT NULL default 0,
NRDOKUM char(12) default '',
KPLUBBANK char(1) default '',
DATAWPLATY date default NULL,
DATAPRZYJ date default NULL,
DODNIA date default NULL,
IDOPERATOR int(3) default 0,
NRFAKTURY char(6) default '',
NRPOZYCJI char(3) default '',
ZAMIESIAC char(7) default '',
PRIMARY KEY (ID),
KEY NRDOKUM (NRDOKUM),
KEY IDABONENTA (IDABONENTA),
KEY NRDOKUM (NRDOKUM)
) TYPE=MyISAM;

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON wplaty TO 'guest' IDENTIFIED BY '123';

CREATE TABLE dokwplat (
ID int(11) NOT NULL auto_increment,
KPLUBBANK char(1) default '',
NRDOKUM char(12) default '',
DATAPRZYJ date default NULL,
IDOPERATOR int(3) default 0,
GOT3CZEK char(1) default '',
NRCZEKU char(12) default '',
BANKCZEKU char(7) default '',
PRIMARY KEY (ID)
) TYPE=MyISAM;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON dokwplat TO 'guest' IDENTIFIED BY '123';

Sum(wplaty.WYSWPL)|Wp||
Sum(splaty.WYSWPL)|Sp||
 left join wplaty on (dokwplat.NRDOKUM=wplaty.NRDOKUM and dokwplat.IDOPERATOR=wplaty.IDOPERATOR and dokwplat.DATAPRZYJ=wplaty.DATAPRZYJ) left join splaty on (dokwplat.NRDOKUM=splaty.NRDOKUM and dokwplat.IDOPERATOR=splaty.IDOPERATOR and dokwplat.DATAPRZYJ=splaty.DATAPRZYJ)
group by dokwplat.ID

dokwplat
ID||0|
if(dokwplat.KPLUBBANK='0','KP',dokwplat.KPLUBBANK)|DOK|2||style="font-size:18"|
NRDOKUM|NR DOKUM|||style="font-size:18"|
DATAPRZYJ|DATA PRZYJ|||style="font-size:18"|
IDOPERATOR|ID operatora|||style="font-size:18"|
GOT3CZEK|GOT CZEK|||style="font-size:18"|
NRCZEKU|NR CZEKU|||style="font-size:18"|
BANKCZEKU|BANK CZEKU|||style="font-size:18"|
from dokwplat

+---------------------------------------–----------------------+
¶             Od kogo :                 ¶ Nr:  7176/2005/CATV  ¶
„---------------------------------------+----------------------¬
¶ ZBIGNIEW CIEÌBIπSKI                   ¶             ¶        ¶
¶ BAT.CHùOPSKICH 2/1                    ¶     MA      ¶ WINIEN ¶
¶ indeks : 1-1-B01-002 -001             ¶    KASA     ¶  KONTO ¶
„---------------------------------------¶             ¶        ¶
¶  Za co :                              ¶     zÂ      ¶    zÂ  ¶
„---------------------------------------+-------------+--------¬
¶ WPISOWE DO INTERNETU  1               ¶      300.00 ¶        ¶
„---------------------------------------+-------------+--------¬
¶                         RAZEM         ¶      300.00 ¶        ¶
„--------------------------------------------------------------¬
¶                         sÂownie:                             ¶
¶  trzysta zÂotych                                             ¶
+--------------------------------------------------------------+
  WystawiÂ : KOZARZEWSKA MARIA
  Dnia     : 2005.03.03
  ZapÂacono gotÛwkÊ

+---------------------------------------–----------------------+
¶             Od kogo :                 ¶ Nr: 28646/2005/CATV  ¶
„---------------------------------------+----------------------¬
¶ ZBIGNIEW CIEÌBIπSKI                   ¶             ¶        ¶
¶ BAT.CHùOPSKICH 2/1                    ¶   WINIEN    ¶   MA   ¶
¶ indeks : 1-1-B01-002 -001             ¶    KASA     ¶  KONTO ¶
„---------------------------------------¶             ¶        ¶
¶  Za co :                              ¶     zÂ      ¶    zÂ  ¶
„---------------------------------------+-------------+--------¬
¶ SkÂadka 2005.07                       ¶       20.00 ¶        ¶
¶ SkÂadka 2005.08                       ¶       20.00 ¶        ¶
¶ SkÂadka 2005.09                       ¶       20.00 ¶        ¶
¶ SkÂadka 2005.10                       ¶       20.00 ¶        ¶
„---------------------------------------+-------------+--------¬
¶                         RAZEM         ¶       80.00 ¶        ¶
„--------------------------------------------------------------¬
¶                         sÂownie:                             ¶
¶  osiemdziesiÊt zÂotych                                       ¶
+--------------------------------------------------------------+
  WystawiÂ : KOZARZEWSKA MARIA
  Dnia     : 2005.09.15
  ZapÂacono gotÛwkÊ


Select ZTYTULU from specbuf where ID_OSOBYUPR=1 limit 0,1;
Select STAWKAVAT from stawkvat where stawkvat.ZTYTULU='97' order by stawkvat.DATASTVAT desc limit 1;
Update specbuf SET STAWKAVAT='22' where ID_OSOBYUPR=1 limit 0,1;
Select STAWKAVAT from specbuf where ID_OSOBYUPR=1 limit 0,1
INSERT INTO specbuf SELECT NULL, 1, wplaty.ZTYTULU, typoplat.NAZWAPELNA, wplaty.ZAMIESIAC, 'pkwiu', 'jm', 1, 0, 0, '', 0, wplaty.WYSWPL, wplaty.WYSWPL FROM wplaty LEFT JOIN typoplat ON wplaty.ZTYTULU=typoplat.id WHERE (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040')
INSERT INTO specbuf SELECT NULL, 1, ZTYTULU, typoplat.NAZWAPELNA, ZAMIESIAC, 'pkwiu', 'jm', 1, 0, 0, '', 0, WYSWPL, WYSWPL FROM wplaty LEFT JOIN typoplat ON wplaty.ZTYTULU=typoplat.id WHERE (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040')
INSERT INTO specbuf SELECT NULL, osoba_id, ZTYTULU, typoplat.NAZWAPELNA, ZAMIESIAC, 'pkwiu', 'jm', 1, 0, 0, '', 0, WYSWPL, WYSWPL FROM wplaty LEFT JOIN typoplat ON wplaty.ZTYTULU=typoplat.id WHERE (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040');

union (Select oplaty.ID, oplaty.IDABONENTA, typytyt.NAZWA, typoplat.NAZWASKR, oplaty.ZAMIESIAC, oplaty.KWOTA from oplaty left join typytyt on oplaty.TYPTYTULU=typytyt.id left join typoplat on oplaty.ZTYTULU=typoplat.id where (oplaty.NRFAKTURY='[3]' and oplaty.IDABONENTA='[1]'))

union (Select splaty.ID, splaty.IDABONENTA, typytyt.NAZWA, concat(typoplat.NAZWASKR,splaty.NRRATY), '', splaty.WYSWPL from splaty left join typytyt on splaty.TYPTYTULU=typytyt.id left join typoplat on splaty.ZTYTULU=typoplat.id where (splaty.NRFAKTURY='[3]' and splaty.IDABONENTA='[1]'))

union (Select dlugi.ID, dlugi.IDABONENTA, typytyt.NAZWA, concat(typoplat.NAZWASKR,dlugi.NRRATY), '', dlugi.KWOTA from dlugi left join typytyt on dlugi.TYPTYTULU=typytyt.id left join typoplat on dlugi.ZTYTULU=typoplat.id where (dlugi.NRFAKTURY='[3]' and dlugi.IDABONENTA='[1]'))

union (Select anulpoz.ID, 0, typytyt.NAZWA, concat(typoplat.NAZWASKR,anulpoz.NRRATY), anulpoz.ZAMIESIAC, anulpoz.KWOTA from anulpoz left join typytyt on anulpoz.TYPTYTULU=typytyt.id left join typoplat on anulpoz.ZTYTULU=typoplat.id where (anulpoz.NRFAKTURY='[4]'))

Select sum(wplaty.WYSWPL/(1+(stawkvat.STAWKAVAT*0.01))), wplaty.ID, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on wplaty.ZTYTULU=stawkvat.ZTYTULU having (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040')
Select wplaty.WYSWPL, stawkvat.STAWKAVAT, wplaty.ID, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on wplaty.ZTYTULU=stawkvat.ZTYTULU having (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040')
Select wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.ID, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on wplaty.ZTYTULU=stawkvat.ZTYTULU having (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') group by wplaty.ID
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) having (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') order by stawkvat.DATASTVAT desc group by wplaty.ID 
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') order by stawkvat.DATASTVAT desc group by wplaty.ID 
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') order by stawkvat.DATASTVAT desc
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') order by wplaty.ID, stawkvat.DATASTVAT desc
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) having (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') order by wplaty.ID, stawkvat.DATASTVAT desc group by wplaty.ID
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) having (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') group by wplaty.ID order by wplaty.ID, stawkvat.DATASTVAT desc 
Explain Select wplaty.ID, Max(wplaty.WYSWPL), Max(stawkvat.STAWKAVAT), Max(stawkvat.DATASTVAT), Max(wplaty.NRFAKTURY), Max(wplaty.IDABONENTA) from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) having (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') group by ID
Select wplaty.ID, Max(wplaty.WYSWPL), Max(stawkvat.STAWKAVAT), Max(stawkvat.DATASTVAT), Max(wplaty.NRFAKTURY), Max(wplaty.IDABONENTA) from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') GROUP BY 'wplaty.ID' order by wplaty.wyswpl
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') GROUP BY 'wplaty.ID' order by wplaty.wyswpl
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') group by wplaty.ID order by stawkvat.STAWKAVAT
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') group by wplaty.ID order by stawkvat.DATASTVAT desc
Select wplaty.ID, wplaty.WYSWPL, stawkvat.STAWKAVAT, stawkvat.DATASTVAT, wplaty.NRFAKTURY, wplaty.IDABONENTA from wplaty left join stawkvat on (wplaty.ZTYTULU=stawkvat.ZTYTULU) where (wplaty.NRFAKTURY='013504' and wplaty.IDABONENTA='1040') group by wplaty.ID

SELECT WPLATY.WYSWPL as KWOTA  INTO BUFOR
FROM `WPLATY`
WHERE WPLATY.IDABONENTA = '1040'
AND WPLATY.NRFAKTURY = '013504' 

Select WPLATY.WYSWPL as bufor.WYSWPL INTO `bufor`
FROM `WPLATY`
WHERE WPLATY.IDABONENTA = '1040'
AND WPLATY.NRFAKTURY = '013504' 

stawkvat
ID|||style="font-size:6"|style="font-size:6"|
ZTYTULU|ZTY- TULU|3|style="font-size:16"|style="font-size:8"|
typoplat.NAZWASKR|Nazwa||
DATASTVAT|DATASTVAT||
STAWKAVAT|STAWKAVAT||
SWW_KU|SWW_KU||
OPISSTVAT|OPISSTVAT||
from stawkvat,typoplat
where (stawkvat.ZTYTULU=typoplat.ID)
order by DATASTVAT desc

netto    st    vat      brutto
100.00   22%   22.00    122.00


n+n*st*0.01=b
n*(1+st*0.01)=b
n=b/(1+st*0.01)

v=n*(st*0.01)

v=b-n
v=b-b/(1+st*0.01)
v=b*(1-1/(1+st*0.01))

n=122/(1+1.22)
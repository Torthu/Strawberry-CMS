<?php
#_strawberry

 if (!defined("str_setup")) die("Access dinaed");

define ("SETUP", "Instalacija Strawberry 1.2.0");
define ("_LANG", "Izaberi jezik");
define ("_LICENSE", "Prihvatanje uslova koriscenja");
define ("_CONFIG", "Osnovna podesavanja");
define ("_SERRORPERM", "nije povezan sa serverom. <br>Podesite neophodne atribute.");
define ("_NEXT", "Nastavi");
define ("_LICENSE_OK", "Slazem se sa uslovima.");
define ("_CONF_1", "Server baze podataka");
define ("_CONF_2", "Korisnicko ime baze podatak");
define ("_CONF_3", "Lozinka baze podataka");
define ("_CONF_4", "Ime baze podataka");
define ("_CONF_5", "Prefiks tabela u bazi podataka");
define ("_CONF_6", "Ime fajla za ulazak u administraciju");
define ("_TABLE", "Tabela");
define ("_ADMIN", "Instalacija administracije");
define ("_CONF_FILE", "Fajl sa podesavanjima");
define ("_FILE", "Fajl sa podesavanjima");
define ("_SETUP_NEW", "Nova instalacija sistema");
define ("_SUPDATE", "Nadogradnja sistema");
define ("_SUPDATE_TO", "na 1.2");
define ("_SUPDATE_TO_TEXT", "Tacka \"Nadogradnja sistema na 1.2\" ako ste vec koristili MySQL bazu podataka. Ako ne, <a href=\"".way("setup.php?mod=no111")."\">kliknite ovde</a> i prebacite vasu tekstualnu bazu u MySQL bazu podataka.");
define ("_SAVE_NEW", "Instalacija i podesavanja");
define ("_SAVE_UPDATE", "Nadogradnja i podesavanja");
define ("_PHPSETUP", "PHP verzija na vasem serveru je manja od minimalne preporucene. Ne sme biti manja od PHP verzije 5.0.0!");
define ("_CONF_5_INFO", "Zbog bezbednosti promenite standardno ime fajla admin.php. Mozete ubisati bilo koji naziv, na primer: %1\$s.php, ali bez «.php» sto bi u ovom slucaju izgledalo ovako: %1\$s");
define ("_SAVED", "je sacuvano");
define ("_OK", "je kreirano");
define ("_NO_OK", "nije kreirano");
define ("_ERROR", "greska");
define ("_ERROR2", "greska!");
define ("_LIC_TEXT_MENU", "Ukoliko zelite da nadogradite vas stari sistem na verziju Strawberry 1.2 i ako vas sistem nije stariji od verzije CuteNews 0.2x, mozete to da uradite na sledeci nacin. <br><li> Kopirajte fajlove iz direktorijuma /data/ sa vaseg sistema CuteNewsRu 0.2x (0.3x) u folder /system/setup/db/cn02x/. Ukoliko koristite Strawberry 1.1.1 kopirajte fajlove iz foldera /data/db/base/ u folder /system/setup/db/strawberry/. <a href=\"".way('setup.php?mod=no111')."\">Sada je potrebno da kliknete na ovaj link</a>. Posle nadogradnje potrebno je da uradite sledece. <br><li> Ukoliko nadogradjujete MySQL bazu potrebno je da prihvatite uslove koriscenja i da nastavite instalaciju sistema.");
define ("_L_A_P_MHOLS", "Korisnicko ime i lozinka mogu sadrzati samo latinicna slova.");
define ("_ADM_SETUP", "Instalacija administracije");
define ("_LOGIN", "Korisnicko ime:");
define ("_PASS", "Lozinka:");
define ("_ADD_ADM", "Dodaj administratora");
define ("_ADM_CREATED", "Administrator je kreiran");
define ("_GO_IN_ADPAN", "Idi u AdminPanel");
define ("_INSTALL_COMPLITE", "Instalacija uspesna! <br>Hvala! <br><br>Sada mozete da pristupite administraciji. <br><br>Podesite administraciju prema vasim zeljama. <br>Obavezno uklonite fajlove od instalacije sa servera!");
define ("_BACK", "Nazad");
define ("_NO_ADM_LOGIN", "niste uneli korisnicko ime");
define ("_NO_ADM_PASS", "niste uneli lozinku");
define ("_TXT_TO_MYSQL", "Sa txtSQL (tekstualna baza) na MySQL");
define ("_FROM_CN_TO_STRAW", "Prebacivanje sa CuteNews. RU serije 02x i 03x na Strawberry 1.1.1");
define ("_UPD_OLD_VERS", "Nadogradnja baze podataka na poslednju verziju");
define ("_UPD_OLD_VERS_TXT", "Pre nego sto nadogradite vas sistem na verziju Strawberry 1.2, neophodno je da nadogradite vasu bazu da odgovara verziji Strawberry 1.1.1 (MySQL). Zatim treba da izaberete nadogradnju na Strawberry 1.2. <br>Sada treba da izaberete varijantu nadogradnje koja vama najvise odgovara. <br><br><li><a href=\"".way('setup.php?mod=no111&amp;act=mysql')."\">Sa txtSQL (tekstualna baza) na MySQL za Strawberry 1.1.1</a> <li><a href=\"".way('setup.php?mod=no111&amp;act=02x')."\">sa CuteNews. RU 02x ili serije CuteNews. RU 03x na MySQL za Strawberry 1.1.1</a> <br><br>Posle nadogradnje, potrebno je da izvrsite standardnu proceduru za nadogradnju Strawberry 1.1.1 na verziju Strawberry 1.2.<br><br>Ukoliko imate problema sa bazom ili neki drugi problem, obratite nam se na zvanicnom Strawberry forumu.");
define ("_USUALY_LH", "obicno je to localhost");
define ("_PRIMER_PREFIX", "na primer strwbr _");
define ("_PRIMER_AUTHOR", "na primer Srecko Sreckovic");
define ("_NO_SLASH", "bez kose crte (/) na kraju");
define ("_SITE_AUTHOR", "Autor sajta");
define ("_SITE_ADDRES", "Adresa sajta");
define ("_CODER_SITE", "Kodiranje:");
define ("_PRIMER_CODER", "na primer windows-1251");
define ("_CREATE_TABLES", "Kreiranje tabela.");
define ("_IMPORT_DATA", "Uvoz podataka u tabele");
define ("_CREATE_AND_UPD_TABLES", "Kreiranje i nadogradnja tabela.");
define ("_MESS_ABOUT_ERR", "U tabeli \"".$xprefix."news\" postoji mogucnost da se pojave greske (na primer sa rejting sistemom u vestima).");
define ("_CONNECT_DB", "U ovoj fazi ce se izvrsiti povezivanje sa bazom podataka za dalje kreiranje tabela u MySQL bazi");
define ("_LOGIN_DB", "Korisnicko ime baze");
define ("_PASS_DB", "Lozinka baze");
define ("_SERVER_DB", "Server baze");
define ("_NAME_DB", "Ime baze");
define ("_PREFIX_DB", "Prefiks tabela");
define ("_CRATE_TAB_IN_DB", "U ovoj fazi ce se izvrsiti kreiranje tabela u MySQL bazi");
define ("_TABLE", "Tabela");
define ("_IMPORT_IN_PREPARE_DB", "U ovoj fazi ce se izvrsiti prebacivanje informacija iz tekstualne baze podataka, kako bi se pripremila MySQL baza");
define ("_DATA_OF_TABLE", "Tabela sa podacima");
define ("_IT_IMPORT", "je uvezeno");
define ("_IT_NO_IMPORT", "nije uvezeno");
define ("_IT_NEXT", "Dalje");
define ("_STEP", "korak");
define ("_LAST_STEP", "poslednji");
define ("_DB_CONVERTED_111", "vasa baza je prebacena u MySQL. Sada je potrebno da izvrsite nadogradnju na Strawberry 1.2.");
define ("_DO_LAST_UPD_DB", "Izvrsite nadogradnju baze na poslednju verziju");
define ("_WRITE_CAN", "Omoguceno zapisivanje");
define ("_WRITE_CANT", "Onemoguceno zapisivanje");
define ("_CHECK_CHMOD", "Provera dozvola za fajlove (chmod)");
define ("_MOVE", "Premesti");
define ("_USERS", "Korisnici");
define ("_CATS", "Kategorije");
define ("_NEWS", "Vesti");
define ("_COMMS", "Komentari");
define ("_XFLDS", "Dodatna polja (XFields)");
define ("_MOVE_USERS_MESS", "svi korisnici ce biti prebaceni iz stare baze, osim njihovih lozinki");
define ("_DB_NEWS_BEFORE", "prvo je potrebno da vratite bazu vesti");


//define("", "");
//define("", "");


?>
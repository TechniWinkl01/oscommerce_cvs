<?
/*
German Text for The Exchange Project Administration Tool Preview Release 2.0
Last Update: 29.11.2000
Author(s): Mathias Kowalkowski (mathias@zoomed.de)
*/

define('TOP_BAR_TITLE', 'Administrationsinformationen');
define('HEADING_TITLE', 'Wof&uuml;r dient dieses Administrationstool ?');
define('SUB_BAR_TITLE', 'The Exchange Project: Administrationstool Preview Release 2.0');
define('TEXT_MAIN', 'Alle &Auml;nderungen die mit diesem Administrationstool vorgenommen werden treten sofort in Kraft. Sie ver&auml;ndern damit also direkt die Datenbank. Wenn Sie sich nicht sicher sind wie und wof&uuml;r Sie dieses Administrationstool nutzen k&ouml;nnen so sollten Sie sich das <a href="http://theexchangeproject.org/documentation_dbmodel.php" target="_blank"><u>Datenbankmodell</u></a> anschauen.<br>&nbsp;<br>Sie sind selber f&uuml;r die Ver&auml;nderungen die Sie mit diesem Administrationstool vornehmen verantwortlich. Niemand ausser Ihnen kann f&uuml;r Sch&auml;den haftbar gemacht werden.<br>&nbsp;<br>Es wird empfohlen, dass sie regelm&auml;ssig Sicherungen von Ihrer Datenbank erstellen. Sie k&ouml;nnen dies mit MySQL tun. Benutzen Sie daf&uuml;r mysqldump:<br>&nbsp;<br>mysqldump catalog >./catalog.sql&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// die Struktur und den Inhalt nach ./catalog.sql wiedergeben.<br>&nbsp;<br>mysql catalog <./catalog.sql&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Die Struktur und den Inhalt in die Datenbank importieren.<br>&nbsp;<br>Dieses Administrationstool wurde f&uuml;r das Preview Release 2.0 des Exchange Project erstellt. F&uuml;r Hinweise und Hilfe zu diesem Programm besuchen Sie die Supportseite.<br>&nbsp;<br><font color="#ff0000"><small><b>HINWEIS:</b></small></font> Um Bilder hochzuladen, l&ouml;schen und zu entfernen stellen Sie sicher, dass das Verzeichniss catalog/images SCHREIBRECHTE f&uuml;r den Benutzer in dem der Apache-Prozess l&auml;uft hat (z.B. nobody). Das k&ouml;nnen Sie erreichen, indem Sie folgendes Kommando ausf&uuml;hren:<br><br>cd catalog<br>chmod -R nobody.nobody images');

define('TABLE_HEADING_NEW_CUSTOMERS', 'Neue Kunden');
define('TABLE_HEADING_LAST_ORDERS', 'Letzte Bestellungen');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Artikel');
define('TABLE_HEADING_NEW_REVIEWS', 'Neue Berichte');
?>
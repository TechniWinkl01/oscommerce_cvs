<?php
/*
  $Id: checkout_success.php,v 1.12 2002/02/02 22:00:28 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Kasse');
define('NAVBAR_TITLE_2', 'Erfolg');
define('HEADING_TITLE', 'Ihr Bestellung ist ausgef&uuml;hrt worden.');
define('TEXT_SUCCESS', 'Ihre Bestellung ist eingegangen und wird bearbeitet! Die Lieferung erfolgt innerhalb von ca. 2-5 Werktagen.<br><br>Sie k&ouml;nnen Ihre Bestellung auf der Seite <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Mein Konto\'</a> nochmals abfragen.<br><br>Falls Sie Fragen bez&uuml;glich Ihrer Bestellung haben, wenden Sie sich an unseren <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">Vertrieb</a>.<br><br><font size="3">Vielen Dank f&uuml;r Ihre Bestellung!</font>');
define('TABLE_HEADING_DOWNLOAD_DATE', 'herunterladen m&ouml;glich bis:');
define('TABLE_HEADING_DOWNLOAD_COUNT', 'max. Anz. Downloads');
define('HEADING_DOWNLOAD', 'Artikel herunterladen:');
define('FOOTER_DOWNLOAD', 'Sie k&ouml;nnen Ihre Artikel auch sp&auml;ter unter \'%s\' herunterladen');
?>
<?php
/*
  $Id: checkout_success.php,v 1.11 2002/02/02 16:32:08 clescuyer Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Kasse');
define('NAVBAR_TITLE_2', 'Erfolg');
define('HEADING_TITLE', 'Ihr Bestellung ist ausgef&uuml;hrt worden.');
define('TEXT_SUCCESS', 'Ihre Bestellung ist eingegangen und wird bearbeitet! Die Lieferung erfolgt innerhalb von ca. 2-5 Werktagen.<br><br>Sie k&ouml;nnen Ihre Bestellung auf der Seite <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Mein Konto\'</a> nochmals abfragen.<br><br>Falls Sie Fragen bez&uuml;glich Ihrer Bestellung haben, wenden Sie sich an unseren <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">Vertrieb</a>.<br><br><font size="3">Vielen Dank f&uuml;r Ihre Bestellung!</font>');
define('TABLE_HEADING_DOWNLOAD_DATE', 'Expiry Date');
define('TABLE_HEADING_DOWNLOAD_COUNT', 'Max # downloads');
define('HEADING_DOWNLOAD', 'Download your products here:');
define('FOOTER_DOWNLOAD', 'You can also download your products at a later time at \'%s\'');
?>
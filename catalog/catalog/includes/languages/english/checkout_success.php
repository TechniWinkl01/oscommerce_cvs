<?php
/*
  $Id: checkout_success.php,v 1.8 2002/02/02 16:32:08 clescuyer Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Success');
define('HEADING_TITLE', 'Your Checkout Has Been Processed!');
define('TEXT_SUCCESS', 'Your checkout has been successfully processed! Your products will arrive at their destination within 2-5 working days.<br><br>You can view your order history by going to your <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'My Account\'</a> page and by clicking on <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'History\'</a>.<br><br>If you have any questions about the checkout procedure, please direct them to the <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">store owner</a>.<br><br><font size="3">Thanks for shopping with us online!</font>');
define('TABLE_HEADING_DOWNLOAD_DATE', 'Expiry Date');
define('TABLE_HEADING_DOWNLOAD_COUNT', 'Max # downloads');
define('HEADING_DOWNLOAD', 'Download your products here:');
define('FOOTER_DOWNLOAD', 'You can also download your products at a later time at \'%s\'');
?>

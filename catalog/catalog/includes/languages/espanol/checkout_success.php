<?php
/*
  $Id: checkout_success.php,v 1.8 2002/03/13 13:07:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Pedido');
define('NAVBAR_TITLE_2', 'Realizado con Exito');
define('HEADING_TITLE', 'Su Pedido ha sido Procesado!');
define('TEXT_SUCCESS', 'Su pedido ha sido realizado con exito! Sus productos llegaran a su destino de 2 a 5 dias laborales.');
define('TEXT_NOTIFY_PRODUCTS', 'Please notify me of updates to the products I have selected below:');
define('TEXT_SEE_ORDERS', 'You can view your order history by going to the <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'My Account\'</a> page and by clicking on <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'History\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', 'Please direct any questions you have to the <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">store owner</a>.');
define('TEXT_THANKS_FOR_SHOPPING', 'Thanks for shopping with us online!');
define('TABLE_HEADING_DOWNLOAD_DATE', 'Expiry Date');
define('TABLE_HEADING_DOWNLOAD_COUNT', 'Max # downloads');
define('HEADING_DOWNLOAD', 'Download your products here:');
define('FOOTER_DOWNLOAD', 'You can also download your products at a later time at \'%s\'');
?>
<?php
/*
  $Id: install.php,v 1.7 2002/08/14 13:30:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  if (!$script_filename = str_replace('\\', '/', getenv('PATH_TRANSLATED'))) {
    $script_filename = getenv('SCRIPT_FILENAME');
  }
  $script_filename = str_replace('//', '/', $script_filename);

  if (!$request_uri = getenv('REQUEST_URI')) {
    if (!$request_uri = getenv('PATH_INFO')) {
      $request_uri = getenv('SCRIPT_NAME');
    }

    if (getenv('QUERY_STRING')) $request_uri .=  '?' . getenv('QUERY_STRING');
  }

  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0; $i<sizeof($dir_fs_www_root_array)-2; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_www_root = implode('/', $dir_fs_www_root);

  $dir_ws_www_root_array = explode('/', dirname($request_uri));
  $dir_ws_www_root = array();
  for ($i=0; $i<sizeof($dir_ws_www_root_array)-1; $i++) {
    $dir_ws_www_root[] = $dir_ws_www_root_array[$i];
  }
  $dir_ws_www_root = implode('/', $dir_ws_www_root);
?>
<p><span class="pageHeading">osCommerce</span><br><font color="#9a9a9a">Open Source E-Commerce Solutions</font></p>

<p class="pageTitle">New Install</p>

<form name="install" action="install.php?step=2" method="post">

<p><b>1. Please customize your new installation with the following options:</b></p>

<p><?php echo osc_draw_checkbox_field('install[]', 'database', true); ?> <b>Import Catalog Database</b><br>
Import the Catalog database structure which includes tables and sample data.</p>

<p><?php echo osc_draw_checkbox_field('install[]', 'configure', true); ?> <b>Automatic Configuration</b><br>
The information you submit regarding the web server and database server will be automatically saved into both Catalog and Administration Tool configuration files.</p>

<p><b>2. Please enter your web server information:</b></p>

<p><b>Webserver Root Directory</b><br><?php echo osc_draw_input_field('DIR_FS_DOCUMENT_ROOT', $dir_fs_www_root); ?><br>
The directory where your web pages are being served from, usually <i>/home/myname/public_html</i>.</p>

<p><b>Webserver Catalog Directory</b><br><?php echo osc_draw_input_field('DIR_FS_CATALOG', '/catalog/'); ?><br>
The directory where your catalog pages are being served from (from the webserver root directory), usually <i>/home/myname/public_html<b>/catalog/</b></i>.</p>

<p><b>Webserver Administration Tool Directory</b><br><?php echo osc_draw_input_field('DIR_FS_ADMIN', '/catalog/admin/'); ?><br>
The directory where your administration tool pages are being served from (from the webserver root directory), usually <i>/home/myname/public_html<b>/catalog/admin/</b></i>.</p>

<p><b>WWW Catalog Directory</b><br><?php echo osc_draw_input_field('DIR_WS_CATALOG', $dir_ws_www_root . '/'); ?><br>
The virtual directory where the osCommerce Catalog module resides, usually <i>/catalog/</i>.</p>

<p><b>WWW Administration Tool Directory</b><br><?php echo osc_draw_input_field('DIR_WS_ADMIN', $dir_ws_www_root . '/admin/'); ?><br>
The virtual directory where the osCommerce Administration Tool resides, usually <i>/catalog/admin/</i>.</p>

<p><b>3. Please enter your database server information:</b></p>

<p><b>Database Server</b><br><?php echo osc_draw_input_field('DB_SERVER'); ?><br>
The database server can be in the form of a hostname, such as <i>db1.myserver.com</i>, or as an IP address, such as <i>192.168.0.1</i>.</p>

<p><b>Username</b><br><?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?><br>
The username is used to connect to the database server. An example username is <i>mysql_10</i>.<br><br>Note: If the catalog is to be imported (selected above), the account used to connect to the database server needs to have Create and Drop permissions.</p>

<p><b>Password</b><br><?php echo osc_draw_input_field('DB_SERVER_PASSWORD'); ?><br>
The password is used together with the username, which forms the database user account.</p>

<p><b>Database</b><br><?php echo osc_draw_input_field('DB_DATABASE'); ?><br>
The database used to hold the catalog data. An example database name is <i>catalog</i>.</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_continue.gif" border="0" alt="Continue"></td>
  </tr>
</table>

</form>

<?php
/*
  $Id: install_5.php,v 1.4 2002/01/05 12:21:29 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<p><span class="pageHeading">osCommerce</span><br><font color="#9a9a9a">Open Source E-Commerce Solutions</font></p>

<p class="pageTitle">New Install</p>

<p><b>Step 2: osCommerce Configuration</b></p>

<?php
  $db = array();
  $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
  $db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
  $db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
  $db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));

  $db_error = false;
  osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD']);

  if (!$db_error) {
    osc_db_test_connection($db['DB_DATABASE']);
  }

  if ($db_error) {
?>

<p>A test connection made to the database was NOT successful.</p>

<p>The error message returned is:</p>

<p class="boxme"><?php echo $db_error; ?></p>

<p>Please click on the <i>Back</i> graphic to review your database server settings.</p>

<p>If you require help with your database server settings, please consult your hosting company.</p>

<form name="install" action="install.php?step=4" method="post">

<?php
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if ($key != 'x' && $key != 'y') {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]);
          }
        } else {
          echo osc_draw_hidden_field($key, $value);
        }
      }
    }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_back.gif" border="0" alt="Back"></td>
  </tr>
</table>

</form>
<?php
  } else {
    $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     'osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     'http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     'Copyright (c) 2002 osCommerce' . "\n" .
                     '' . "\n" .
                     'Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     'define(\'HTTP_SERVER\', \'' . $HTTP_POST_VARS['HTTP_SERVER'] . '\'); // eg, http://localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'HTTPS_SERVER\', \'' . $HTTP_POST_VARS['HTTPS_SERVER'] . '\'); // eg, https://localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'ENABLE_SSL\', ' . (($HTTP_POST_VARS['ENABLE_SSL'] == 'true') ? 'true' : 'false') . '); // secure webserver for checkout procedure?' . "\n" .
                     'define(\'DIR_WS_CATALOG\', \'' . $HTTP_POST_VARS['DIR_WS_CATALOG'] . '\'); // absolute path required' . "\n" .
                     'define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     'define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     'define(\'DIR_WS_INCLUDES\', \'includes/\'); // If "URL fopen wrappers" are enabled in PHP (which they are in the default configuration), this can be a URL instead of a local pathname' . "\n" .
                     'define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     'define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     'define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     'define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     'define(\'DIR_WS_PAYMENT_MODULES\', DIR_WS_MODULES . \'payment/\');' . "\n" .
                     'define(\'DIR_WS_SHIPPING_MODULES\', DIR_WS_MODULES . \'shipping/\');' . "\n" .
                     'define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     '' . "\n" .
                     '// define our database connection' . "\n" .
                     'define(\'DB_SERVER\', \'' . $HTTP_POST_VARS['DB_SERVER'] . '\'); // eg, localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'DB_SERVER_USERNAME\', \'' . $HTTP_POST_VARS['DB_SERVER_USERNAME'] . '\');' . "\n" .
                     'define(\'DB_SERVER_PASSWORD\', \'' . $HTTP_POST_VARS['DB_SERVER_PASSWORD']. '\');' . "\n" .
                     'define(\'DB_DATABASE\', \'' . $HTTP_POST_VARS['DB_DATABASE']. '\');' . "\n" .
                     'define(\'USE_PCONNECT\', ' . (($HTTP_POST_VARS['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '); // use persisstent connections?' . "\n" .
                     'define(\'STORE_SESSIONS\', \'' . (($HTTP_POST_VARS['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

    $fp = fopen($HTTP_POST_VARS['DIR_FS_DOCUMENT_ROOT'] . $HTTP_POST_VARS['DIR_WS_CATALOG'] . 'includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);

    $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     'osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     'http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     'Copyright (c) 2002 osCommerce' . "\n" .
                     '' . "\n" .
                     'Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     'define(\'HTTP_SERVER\', \'' . $HTTP_POST_VARS['HTTP_SERVER'] . '\'); // eg, http://localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'HTTPS_SERVER\', \'' . $HTTP_POST_VARS['HTTPS_SERVER'] . '\'); // eg, https://localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'ENABLE_SSL\', ' . (($HTTP_POST_VARS['ENABLE_SSL'] == 'true') ? 'true' : 'false') . '); // secure webserver for checkout procedure?' . "\n" .
                     'define(\'DIR_FS_DOCUMENT_ROOT\', \'' . $HTTP_POST_VARS['DIR_FS_DOCUMENT_ROOT'] . '\'); // where the pages are located on the server' . "\n" .
                     'define(\'DIR_WS_ADMIN\', \'' . $HTTP_POST_VARS['DIR_WS_ADMIN'] . '\'); // absolute path required' . "\n" .
                     'define(\'DIR_WS_CATALOG\', \'' . $HTTP_POST_VARS['DIR_WS_CATALOG'] . '\'); // absolute path required' . "\n" .
                     'define(\'DIR_FS_CATALOG\', DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG); // absolute path required' . "\n" .
                     'define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     'define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     'define(\'DIR_WS_CATALOG_IMAGES\', DIR_WS_CATALOG . \'images/\');' . "\n" .
                     'define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     'define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     'define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     'define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     'define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     'define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     'define(\'DIR_WS_CATALOG_LANGUAGES\', DIR_WS_CATALOG . \'includes/languages/\');' . "\n" .
                     'define(\'DIR_FS_CATALOG_LANGUAGES\', DIR_FS_CATALOG . \'includes/languages/\');' . "\n" .
                     'define(\'DIR_FS_CATALOG_IMAGES\', DIR_FS_CATALOG . \'images/\');' . "\n" .
                     'define(\'DIR_FS_PAYMENT_MODULES\', DIR_FS_CATALOG . \'includes/modules/payment/\');' . "\n" .
                     'define(\'DIR_FS_SHIPPING_MODULES\', DIR_FS_CATALOG . \'includes/modules/shipping/\');' . "\n" .
                     'define(\'DIR_FS_CACHE\', \'/tmp/\');' . "\n" .
                     'define(\'DIR_FS_BACKUP\', DIR_FS_DOCUMENT_ROOT . DIR_WS_ADMIN . \'backups/\');' . "\n" .
                     '' . "\n" .
                     '// define our database connection' . "\n" .
                     'define(\'DB_SERVER\', \'' . $HTTP_POST_VARS['DB_SERVER'] . '\'); // eg, localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'DB_SERVER_USERNAME\', \'' . $HTTP_POST_VARS['DB_SERVER_USERNAME'] . '\');' . "\n" .
                     'define(\'DB_SERVER_PASSWORD\', \'' . $HTTP_POST_VARS['DB_SERVER_PASSWORD']. '\');' . "\n" .
                     'define(\'DB_DATABASE\', \'' . $HTTP_POST_VARS['DB_DATABASE']. '\');' . "\n" .
                     'define(\'USE_PCONNECT\', ' . (($HTTP_POST_VARS['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '); // use persisstent connections?' . "\n" .
                     'define(\'STORE_SESSIONS\', \'' . (($HTTP_POST_VARS['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

    $fp = fopen($HTTP_POST_VARS['DIR_FS_DOCUMENT_ROOT'] . $HTTP_POST_VARS['DIR_WS_ADMIN'] . 'includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);
?>

<p>The configuration was successful!</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="<?php echo $HTTP_POST_VARS['HTTP_SERVER'] . $HTTP_POST_VARS['DIR_WS_CATALOG'] . 'default.php'; ?>" target="_blank"><img src="images/button_catalog.gif" border="0" alt="Catalog"></a></td>
    <td align="center"><a href="<?php echo $HTTP_POST_VARS['HTTP_SERVER'] . $HTTP_POST_VARS['DIR_WS_ADMIN'] . 'default.php'; ?>" target="_blank"><img src="images/button_administration_tool.gif" border="0" alt="Administration Tool"></a></td>
  </tr>
</table>

</form>

<?php
  }
?>
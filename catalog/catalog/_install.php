<?php
/*
  $Id: _install.php,v 1.7 2001/12/13 12:13:25 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  if ($HTTP_POST_VARS['action'] == 'process') {
    $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     'The Exchange Project - Community Made Shopping!' . "\n" .
                     'http://www.theexchangeproject.org' . "\n" .
                     '' . "\n" .
                     'Copyright (c) 2000,2001 The Exchange Project' . "\n" .
                     '' . "\n" .
                     'Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     'define(\'HTTP_SERVER\', \'' . $HTTP_POST_VARS['HTTP_SERVER'] . '\'); // eg, http://localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'HTTPS_SERVER\', \'' . $HTTP_POST_VARS['HTTPS_SERVER'] . '\'); // eg, https://localhost - should not be NULL for productive servers' . "\n" .
                     'define(\'ENABLE_SSL\', ' . (($HTTP_POST_VARS['ENABLE_SSL'] == 'on') ? 'true' : 'false') . '); // secure webserver for checkout procedure?' . "\n" .
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
                     'define(\'USE_PCONNECT\', ' . (($HTTP_POST_VARS['USE_PCONNECT'] == 'on') ? 'true' : 'false') . '); // use persisstent connections?' . "\n" .
                     'define(\'STORE_SESSIONS\', \'' . $HTTP_POST_VARS['STORE_SESSIONS'] . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

    if ($fp = fopen('includes/configure.php', 'w')) {
      fputs($fp, $file_contents);
      fclose($fp);
    } else {
      echo 'Could not write to <b>includes/configure.php</B>. Please check the file permissions.';
    }
    header('Location: ' . $HTTP_POST_VARS['HTTP_SERVER'] . $HTTP_POST_VARS['DIR_WS_CATALOG']);
    exit;
  }
?>
<html>
<head>
<title>The Exchange Project Preview Release 2.2 Automatic Configuration Script</title>
<style type=text/css><!--
  TD, UL, P, BODY {
    font-family: Verdana, Arial, sans-serif;
    font-size: 14px;
    color: #000000;
  }

  .boxMe {
    font-family: Verdana, Arial, sans-serif;
    font-size: 11px;
    color: #000000;
    background-color: #e5e5e5;
  }
//--></style>
</head>
<body>
<p><b>The Exchange Project Preview Release 2.2 Automatic Installation Script</b></p>
<p>This installation script will overwrite <b>includes/configure.php</b> with the following information.</p>
<p>For this installation script to successfully write to <i>includes/configure.php</i>, the right user permissions on this file has to be set.</p>
<?php
  if ($fp = @fopen('includes/configure.php', 'w')) {
    echo '<p class="boxMe">Checking file permissions: <b>File exists, and I can write to it.</b></p>' .  "\n";
  } else {
    echo '<div class="boxMe">Checking file permissions: <b>File does not exist, or I cannot write to it.</b><br><br>Please perform the following actions:<ul class="boxMe"><li>cd ' . dirname($HTTP_SERVER_VARS["SCRIPT_FILENAME"]) . '/includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul></div>' . "\n";
    echo '<p>' . "\n" . 'Please <a href="' . basename($PHP_SELF) . '">reload</a> this page once the above actions have been executed.' . "\n" .
         '</body>' . "\n" .
         '</html>';
    exit;
  }
?>
<p>After this file has been written to, reset the file permissions for security reasons.</p>
<div class="boxMe"><ul class="boxMe"><li>cd <?php echo dirname($HTTP_SERVER_VARS["SCRIPT_FILENAME"]); ?>/includes</li><li>chmod 704 configure.php</li></ul></div>
<p><font color="#ff0000"><b>It is important that this installation script <small>(_install.php)</small> is deleted after the configuration has been written.</b></font></p>
<form name="install" action="<?php echo basename($PHP_SELF); ?>" method="post">
<table border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="2"><b>Web Server Information</b></td>
  </tr>
  <tr>
    <td>HTTP Server:</td>
    <td><input type="text" name="HTTP_SERVER" value="http://<?php echo $HTTP_SERVER_VARS["HTTP_HOST"]; ?>" size="45"> <small>(eg, http://localhost)</small></td>
  </tr>
  <tr>
    <td>HTTPS Server:</td>
    <td><input type="text" name="HTTPS_SERVER" value="https://<?php echo $HTTP_SERVER_VARS["HTTP_HOST"]; ?>" size="45"> <small>(eg, https://localhost)</small></td>
  </tr>
  <tr>
    <td>Enable HTTPS/SSL:</td>
    <td><input type="checkbox" name="ENABLE_SSL"></td>
  </tr>
  <tr>
    <td>Catalog Directory:<br><small>(Web Server path)</small></td>
    <td valign="top"><input type="text" name="DIR_WS_CATALOG" value="<?php echo dirname($HTTP_SERVER_VARS["REQUEST_URI"]); ?>/" size="45"> <small>(eg, /catalog/)</small></td>
  </tr>
  <tr>
    <td height="10" colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><b>Database Server Information</b></td>
  </tr>
  <tr>
    <td>Server:</td>
    <td><input type="text" name="DB_SERVER"> <small>(eg, 192.168.0.1)</small></td>
  </tr>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="DB_SERVER_USERNAME"> <small>(eg, mysql)</small></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="text" name="DB_SERVER_PASSWORD"> <small>(eg, bee)</small></td>
  </tr>
  <tr>
    <td>Database:</td>
    <td><input type="text" name="DB_DATABASE"> <small>(eg, catalog)</small></td>
  </tr>
  <tr>
    <td>Persistent Connections:</td>
    <td><input type="checkbox" name="USE_PCONNECT" CHECKED></td>
  </tr>
  <tr>
    <td>Sessions Storage:</td>
    <td valign="top"><select name="STORE_SESSIONS"><option value="" CHECKED>Default PHP File Based</option><option value="mysql">Database Based</option></select></td>
  </tr>
  <tr>
    <td height="10" colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="hidden" name="action" value="process"><input type="submit" value="Submit"></td>
  </tr>
</table>
</form>
</body>
</html>
<?php
  phpinfo();
?>

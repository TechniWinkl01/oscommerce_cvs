<?php
/*
  $Id: ms2_to_ms3.php,v 1.2 2004/02/16 07:32:16 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<script language="JavaScript"><!--
function changeStyle(what, how) {
  if (document.getElementById) {
    document.getElementById(what).style.fontWeight = how;
  } else if (document.all) {
    document.all[what].style.fontWeight = how;
  }
}

function changeText(where, what) {
  if (document.getElementById) {
    document.getElementById(where).innerHTML = what;
  } else if (document.all) {
    document.all[where].innerHTML = what;
  }
}
//--></script>

<html>
<head>
<title>osCommerce Preview Release 2.2 Database Update Script</title>
<style type=text/css><!--
A:link, A:visited { color: #0029A3; text-decoration: none; }
A:hover { color: #5D59ac; text-decoration: underline; }
TD, UL, P, BODY { font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 1.5; }
.boxMe { font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #000000; background-color: #e5e5e5; }
.noteBox { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; line-height: 1.5; background-color: #fef3da; border: thin dashed; padding: 6px; }
.navigationBar { font-family: Verdana, Arial, sans-serif; font-size: 10px; font-weight: bold; color: #ffffff; }
.footerBar { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #ffffff; }
.mainText { font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 1.5; }
.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; line-height: 1.5; }
.infoBoxHeading { font-family: Verdana, Arial, sans-serif; font-size: 10px; font-weight: bold; color: #ffffff; }
.infoBoxText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
.pageHeading { font-family: Verdana, Arial, sans-serif; font-size: 20px; color: #9a9a9a; font-weight: bold; }
.pageTitle { font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 1.5; font-weight: bold; text-decoration: underline; }
  }
//--></style>
</head>
<body>

<p><b>osCommerce Release 2.2 MS2 to MS3 Database Update Script</b></p>

<p>This script can be copied to any web directory to upgrade a MS2 database to a MS3 database.</p>

<p><i>This script should only be used on MS2 databases, not MS2-CVS daily snapshot databases.</i></p>

<p>After the upgrade procedure has been preformed, please setup the store configuration again via the online installation routine (without importing the database otherwise your data may be overwritten!)</p>

<?php
  if (!isset($HTTP_POST_VARS['DB_SERVER'])) {
?>
<form name="database" action="<?php echo basename($PHP_SELF); ?>" method="post">
<table border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="2"><b>Database Server Information</b></td>
  </tr>
  <tr>
    <td>Server:</td>
    <td><input type="text" name="DB_SERVER"> <small>(eg, 192.168.0.1)</small></td>
  </tr>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="DB_SERVER_USERNAME"> <small>(eg, root)</small></td>
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
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit"></td>
  </tr>
</table>
</form>
</body>
</html>
<?php
      exit;
  }

  function osc_db_connect() {
    global $db_link, $HTTP_POST_VARS;

    $db_link = mysql_connect($HTTP_POST_VARS['DB_SERVER'], $HTTP_POST_VARS['DB_SERVER_USERNAME'], $HTTP_POST_VARS['DB_SERVER_PASSWORD']);

    if ($db_link) mysql_select_db($HTTP_POST_VARS['DB_DATABASE']);

    return $db_link;
  }

  function osc_db_error ($query, $errno, $error) {
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function osc_db_query($db_query) {
    global $db_link;

    $result = mysql_query($db_query, $db_link) or osc_db_error($db_query, mysql_errno(), mysql_error());

    return $result;
  }

  function osc_db_fetch_array($db_query) {
    $result = mysql_fetch_array($db_query);

    return $result;
  }

  function osc_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

// Sets timeout for the current script.
// Cant be used in safe mode.
  function osc_set_time_limit($limit) {
    if (!get_cfg_var('safe_mode')) {
      set_time_limit($limit);
    }
  }

  osc_db_connect() or die('Unable to connect to database server!');
?>
<p><span class="pageHeading">osCommerce</span><br><font color="#9a9a9a">Open Source E-Commerce Solutions</font></p>

<p class="pageTitle">Upgrade</p>

<p><b>Step 1: Database Upgrade</b></p>

<?php
  osc_set_time_limit(0);
?>

<p><span id="configuration"><span id="configurationMarker">-</span> Configuration</span><br>
<span id="whosOnline"><span id="whosOnlineMarker">-</span> Who's Online</span></p>

<p>Status: <span id="statusText">Preparing</span></p>

<?php flush(); ?>

<script language="javascript"><!--
changeStyle('configuration', 'bold');
changeText('configurationMarker', '?');
changeText('statusText', 'Updating Configuration');
//--></script>

<?php
  flush();

  osc_db_query("delete from configuration where configuration_key = 'DIR_FS_CACHE'");
  osc_db_query("delete from configuration where configuration_key = 'SESSION_WRITE_DIRECTORY'");
?>

<script language="javascript"><!--
changeStyle('configuration', 'normal');
changeText('configurationMarker', '*');
changeText('statusText', 'Updating Configuration ..done!');

changeStyle('whosOnline', 'bold');
changeText('whosOnlineMarker', '?');
changeText('statusText', 'Updating Who\'s Online');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE whos_online CHANGE last_page_url last_page_url VARCHAR(255) NOT NULL");
?>
<script language="javascript"><!--
changeStyle('whosOnline', 'normal');
changeText('whosOnlineMarker', '*');
changeText('statusText', 'Updating Who\'s online.. done!');
//--></script>

<script language="javascript"><!--
changeStyle('statusText', 'bold');
changeText('statusText', 'Update Complete!');
//--></script>

<?php flush(); ?>

<p>The database upgrade procedure was successful!</p>

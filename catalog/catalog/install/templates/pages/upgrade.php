<?php
/*
  $Id: upgrade.php,v 1.2 2002/01/29 11:57:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<p><span class="pageHeading">osCommerce</span><br><font color="#9a9a9a">Open Source E-Commerce Solutions</font></p>

<p class="pageTitle">Upgrade</p>

<p>This upgrade procedure will upgrade previous 2.1 database structures to the 2.2 database structure.</p>

<p>It is recommended to perform this upgrade procedure on an up-to-date backup of your 2.1 database.</p>

<form name="upgrade" action="upgrade.php?step=2" method="post">

<p><b>1. Please enter your database server information:</b></p>

<p><b>Database Server</b><br><?php echo osc_draw_input_field('DB_SERVER'); ?><br>
The database server can be in the form of a hostname, such as <i>db1.myserver.com</i>, or as an IP address, such as <i>192.168.0.1</i>.</p>

<p><b>Username</b><br><?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?><br>
The username is used to connect to the database server. An example username is <i>mysql_10</i>.<br><br>Note: The account used to connect to the database server needs to have Create and Drop permissions.</p>

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

<?php
/*
  $Id: main_page.php,v 1.2 2002/01/29 11:48:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>

<title>osCommerce :// Open Source E-Commerce Solutions</title>

<meta name="ROBOTS" content="NOFOLLOW">

<link rel="stylesheet" type="text/css" href="templates/main_page/stylesheet.css">

<?php
  if ($page_contents == 'upgrade_3.php') {
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

<?php
  }
?>

</head>

<body text="#000000" bgcolor="#ffffff" leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">

<?php require('templates/main_page/header.php'); ?>

<table cellspacing="0" cellpadding="0" width="780" border="0">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" width="1" height="5"></td>
  </tr>
</table>

<table cellspacing="0" cellpadding="0" width="780" border="0" align="center">
  <tr>
    <td width="125" valign="top">

<?php require('templates/main_page/boxes_left.php'); ?>

    </td>
    <td width="5"></td>
    <td width="650" valign="top">

<?php require('templates/pages/' . $page_contents); ?>

    </td>
  </tr>
</table>

<br>
<?php require('templates/main_page/footer.php'); ?>
<br>

</body>

</html>
<?php
/*
  $Id: default.php,v 1.1 2004/07/22 23:24:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  $template = 'default';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="templates/default/stylesheet.css">

<script language="javascript" src="includes/general.js"></script>

</head>

<body>

<?php require('templates/default/header.php'); ?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td valign="top" width="125">
      <table border="0" width="100%" cellspacing="0" cellpadding="2" class="columnLeft">
      <?php require('includes/column_left.php'); ?>
      </table>
    </td>
    <td valign="top"><?php require('templates/pages/' . $page_contents); ?></td>
  </tr>
</table>

<?php require('templates/default/footer.php'); ?>

</body>

</html>

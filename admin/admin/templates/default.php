<?php
/*
  $Id: default.php,v 1.2 2004/08/25 20:05:15 hpdl Exp $

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

<div class="pageContents"><?php require('templates/pages/' . $page_contents); ?></div>

<?php require('templates/default/footer.php'); ?>

</body>

</html>

<?php
/*
  $Id: info_shopping_cart.php,v 1.17 2002/01/15 19:55:35 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_SHOPPING_CART);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<p class="main"><b><?php echo HEADING_TITLE; ?></b><br><?php echo tep_draw_separator(); ?></p>
<p class="main"><b><i><?php echo SUB_HEADING_TITLE_1; ?></i></b><br><?php echo SUB_HEADING_TEXT_1; ?></p>
<p class="main"><b><i><?php echo SUB_HEADING_TITLE_2; ?></i></b><br><?php echo SUB_HEADING_TEXT_2; ?></p>
<p class="main"><b><i><?php echo SUB_HEADING_TITLE_3; ?></i></b><br><?php echo SUB_HEADING_TEXT_3; ?></p>
<p align="right" class="main"><a href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
</body>
</html>
<?php
  require("includes/counter.php");
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

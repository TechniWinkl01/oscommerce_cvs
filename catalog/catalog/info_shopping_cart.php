<? include("includes/application_top.php"); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_SHOPPING_CART; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<?php echo FONT_STYLE_MAIN; ?>
<p><b><? echo HEADING_TITLE; ?></b><br><? echo tep_black_line(); ?></p>
<p><b><i><? echo SUB_HEADING_TITLE_1; ?></i></b><br><? echo SUB_HEADING_TEXT_1; ?></p>
<p><b><i><? echo SUB_HEADING_TITLE_2; ?></i></b><br><? echo SUB_HEADING_TEXT_2; ?></p>
<p><b><i><? echo SUB_HEADING_TITLE_3; ?></i></b><br><? echo SUB_HEADING_TEXT_3; ?></p>
<p align="right"><a href="javascript:window.close();"><font color="<? echo CHECKOUT_BAR_TEXT_COLOR; ?>"><? echo TEXT_CLOSE_WINDOW; ?></font></a></p>
</font>
</body>
</html>
<?  include("includes/counter.php"); ?>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

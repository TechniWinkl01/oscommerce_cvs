<? include("includes/application_top.php"); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_SHOPPING_CART; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<base href="<? echo (getenv('HTTPS') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<p class="main"><b><? echo HEADING_TITLE; ?></b><br><? echo tep_black_line(); ?></p>
<p class="main"><b><i><? echo SUB_HEADING_TITLE_1; ?></i></b><br><? echo SUB_HEADING_TEXT_1; ?></p>
<p class="main"><b><i><? echo SUB_HEADING_TITLE_2; ?></i></b><br><? echo SUB_HEADING_TEXT_2; ?></p>
<p class="main"><b><i><? echo SUB_HEADING_TITLE_3; ?></i></b><br><? echo SUB_HEADING_TEXT_3; ?></p>
<p align="right" class="main"><a href="javascript:window.close();"><font color="<? echo CHECKOUT_BAR_TEXT_COLOR; ?>"><? echo TEXT_CLOSE_WINDOW; ?></font></a></p>
</body>
</html>
<?  include("includes/counter.php"); ?>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

<?php require('includes/application_top.php'); ?>
<?php
  $products_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_id = '" . $HTTP_GET_VARS['pID'] . "' and pd.language_id = '" . $languages_id . "'");
  $products_values = tep_db_fetch_array($products_query);
?>
<html>
<head>
<title><?php echo $products_values['products_name']; ?></title>
<base href="<? echo (getenv('HTTPS') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  window.resizeTo(document.images[0].width +30, document.images[0].height+60-i);
}
//--></script>
</head>
<body onload="resize();">
<?php echo tep_image($products_values['products_image'], $products_values['products_name']); ?>
</body>
</html>

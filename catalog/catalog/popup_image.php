<html>

<head>
<title><?php echo stripslashes($HTTP_GET_VARS['alt']); ?></title>
<script language="javascript"><!--
var i=0;

function resize() {
  if (navigator.appName == 'Netscape') i=40;
  window.resizeTo(document.images[0].width +30, document.images[0].height+60-i);
}
//--></script>
</head>

<body onload="resize();">

<?php echo '<img src="' . $HTTP_GET_VARS['image'] . '" border="0" alt="' . rawurldecode(stripslashes($HTTP_GET_VARS['alt'])) . '">'; ?>

</body>

</html>

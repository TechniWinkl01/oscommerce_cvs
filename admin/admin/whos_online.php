<?php
/*
  $Id: whos_online.php,v 1.21 2002/03/14 21:52:43 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $xx_mins_ago = (time() - 900);

// remove entries that have expired
  tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_ONLINE; ?></td>
                <td class="tableHeading" align="center"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_FULL_NAME; ?></td>
                <td class="tableHeading" align="center"><?php echo TABLE_HEADING_IP_ADDRESS; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_ENTRY_TIME; ?></td>
                <td class="tableHeading" align="center"><?php echo TABLE_HEADING_LAST_CLICK; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_LAST_PAGE_URL; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, session_id from " . TABLE_WHOS_ONLINE);
  while ($whos_online = tep_db_fetch_array($whos_online_query)) {
    $time_online = (time() - $whos_online['time_entry']);
    if ( ((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $whos_online['session_id'])) && (!$info) ) {
      $info = $whos_online['session_id'];
    }
    if ($whos_online['session_id'] == $info) {
      echo '              <tr class="selectedRow">' . "\n";
    } else {
      echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_WHOS_ONLINE, tep_get_all_get_params(array('info', 'action')) . 'info=' . $whos_online['session_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td class="tableData"><?php echo gmdate('H:i:s', $time_online); ?></td>
                <td class="tableData" align="center"><?php echo $whos_online['customer_id']; ?></td>
                <td class="tableData"><?php echo $whos_online['full_name']; ?></td>
                <td class="tableData" align="center"><?php echo $whos_online['ip_address']; ?></td>
                <td class="tableData"><?php echo date('H:i:s', $whos_online['time_entry']); ?></td>
                <td class="tableData" align="center"><?php echo date('H:i:s', $whos_online['time_last_click']); ?></td>
                <td class="tableData"><?php if (eregi('^(.*)' . tep_session_name() . '=[a-f,0-9]+[&]*(.*)', $whos_online['last_page_url'], $array)) { echo $array[1] . $array[2]; } else { echo $whos_online['last_page_url']; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="7"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td class="smallText" colspan="7"><?php echo sprintf(TEXT_NUMBER_OF_CUSTOMERS, tep_db_num_rows($whos_online_query)); ?></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  if ($info) {
    $heading[] = array('text' => '<b>' . TABLE_HEADING_SHOPPING_CART . '</b>');

    $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $info . "'");
    if (tep_db_num_rows($session_data)) {
      $session_data = trim(tep_db_fetch_array($session_data));
    } else {
      $session_data = @file(tep_session_save_path() . '/sess_' . $info);
      $session_data = trim($session_data[0]);
    }
    session_decode($session_data);

    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $contents[] = array('text' => $products[$i]['quantity'] . ' x ' . $products[$i]['name']);
    }

    if (sizeof($contents) > 0) {
      $contents[] = array('text' => tep_draw_separator('pixel_black.gif', '100%', '1'));
      $contents[] = array('align' => 'right', 'text'  => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . tep_currency_format($cart->show_total()));
    } else {
      $contents[] = array('text' => '');
    }
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
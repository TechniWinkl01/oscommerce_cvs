<?php
/*
  $Id: whos_online.php,v 1.18 2001/12/14 13:19:17 jan0815 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

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
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ONLINE; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_CUSTOMER_ID; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_FULL_NAME; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_IP_ADDRESS; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ENTRY_TIME; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_LAST_CLICK; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_LAST_PAGE_URL; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, session_id from " . TABLE_WHOS_ONLINE);
  while ($whos_online = tep_db_fetch_array($whos_online_query)) {
    $time_online = (time() - $whos_online['time_entry']);
    if ( ((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $whos_online['session_id'])) && (!$info) ) {
      $info = $whos_online['session_id'];
    }
    if ($whos_online['session_id'] == @$info) {
      echo '              <tr class="selectedRow">' . "\n";
    } else {
      echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_WHOS_ONLINE, tep_get_all_get_params(array('info', 'action')) . 'info=' . $whos_online['session_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td align="center" class="smallText">&nbsp;<?php echo gmdate('H:i:s', $time_online); ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $whos_online['customer_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $whos_online['full_name']; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $whos_online['ip_address']; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo date('H:i:s', $whos_online['time_entry']); ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo date('H:i:s', $whos_online['time_last_click']); ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php if (eregi('^(.*)' . tep_session_name() . '=[a-f,0-9]+[&]*(.*)', $whos_online['last_page_url'], $array)) echo $array[1] . $array[2]; else echo $whos_online['last_page_url']; ?>&nbsp</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td class="smallText" colspan="7">&nbsp;<?php echo sprintf(TEXT_NUMBER_OF_CUSTOMERS, tep_db_num_rows($whos_online_query)); ?></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TABLE_HEADING_SHOPPING_CART . '</b>');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $info_box_contents = array();
  $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '$info'");
  if (tep_db_num_rows($session_data)) {
    $session_data = tep_db_fetch_array($session_data);
    $session_data = trim($session_data['value']);
    session_decode($session_data);

    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $info_box_contents[] = array('text' => $products[$i]['quantity'] . 'x' . $products[$i]['name']);
    }
    $info_box_contents[] = array('text' => tep_black_line());
    $info_box_contents[] = array('align' => 'right',
                                 'text'  => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . tep_currency_format($cart->show_total())
                                );
  }
?>
              <tr>
                <td class="box"><?php new infoBox($info_box_contents); ?></td>
              </tr>
              <tr>
                <td class="box"><?php echo tep_black_line(); ?></td>
              </tr>
            </table></td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action'] == 'process') {
    $check_customer = tep_db_query("select customers_id, customers_password from customers where customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "'");
    if (tep_db_num_rows($check_customer)) {
      $check_customer_values = tep_db_fetch_array($check_customer);
      // Check that password is good
      $pass_ok = validate_password($HTTP_POST_VARS['password'], $check_customer_values['customers_password']);
      if ($pass_ok != true) {
	  if (@$HTTP_POST_VARS['origin']) {
            if (@$HTTP_POST_VARS['products_id']) {
              header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
              tep_exit();
            } else {
              header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'], 'NONSSL'));
              tep_exit();
            }
	  } else {
            header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail', 'NONSSL'));
            tep_exit();
	  }
	  tep_exit();
      }
 
      $customer_id = $check_customer_values['customers_id'];
      tep_session_register('customer_id');

      if ($HTTP_POST_VARS['setcookie'] == '1') {
        setcookie('email_address', $HTTP_POST_VARS['email_address'], time()+2592000);
        setcookie('password', $HTTP_POST_VARS['password'], time()+2592000);
      } else {
        setcookie('email_address', '');
        setcookie('password', '');
      }

      $date_now = date('Ymd');
      tep_db_query("update customers_info set customers_info_date_of_last_logon = '" . $date_now . "', customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . $customer_id . "'");

      if (tep_session_is_registered('nonsess_cart')) { //transfer session cart to account cart
        $nonsess_cart_contents = explode('|', $nonsess_cart);
        for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
          $product_info = explode(':', $nonsess_cart_contents[$i]);
          if (($product_info[0] != '0') && ($product_info[1] != '0')) {
            $product_in_cart = '1';
            $check_cart = tep_db_query("select customers_basket_quantity from customers_basket where customers_id = '" . $customer_id . "' and products_id = '" . $product_info[0] . "'");
            if (tep_db_num_rows($check_cart)) {
              $check_cart_values = tep_db_fetch_array($check_cart);
              tep_db_query("update customers_basket set customers_basket_quantity = customers_basket_quantity+" . $product_info[1] . " where customers_id = '" . $customer_id . "' and products_id = '" . $product_info[0] . "'");
            } else {
              tep_db_query("insert into customers_basket values ('', '" . $customer_id . "', '" . $product_info[0] . "', '" . $product_info[1] . "', '" . $date_now . "')");
            }
          }
        }
        tep_session_unregister('nonsess_cart');
      }

      if (@$HTTP_POST_VARS['origin']) {
        if (@$HTTP_POST_VARS['products_id']) {
          header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'] . '.php', 'products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
          tep_exit();
        } else {
          if (@$HTTP_POST_VARS['connection'] == 'secure') {
            $connection_type = 'SSL';
          } else {
            $connection_type = 'NONSSL';
          }
          header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'] . '.php', '', $connection_type));
          tep_exit();
        }
      } else {
        header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
        tep_exit();
      }
    } else {
      if (@$HTTP_POST_VARS['origin']) {
        if (@$HTTP_POST_VARS['products_id']) {
          header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
          tep_exit();
        } else {
          header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'], 'NONSSL'));
          tep_exit();
        }
      } else {
        header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail', 'NONSSL'));
        tep_exit();
      }
    }
  } else {
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_LOGIN; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<?=FILENAME_INFO_SHOPPING_CART;?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="#AABBDD" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_login.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><form name="login" method="post" action="<?=tep_href_link(FILENAME_LOGIN, 'action=process', 'NONSSL');?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="3">
          <tr>
            <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_EMAIL_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="email_address" maxlength="96" value="<? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['email_address']; } ?>">&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_PASSWORD;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="password" name="password" maxlength="12" value="<? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['password']; } ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="4"><br><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td valign="top" colspan="2" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<label for="setcookie"><input type="checkbox" name="setcookie" value="1" id="setcookie" <? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo 'CHECKED'; } ?>>&nbsp;<?=TEXT_COOKIE;?></label>&nbsp;</font></td>
            <td align="right" valign="top" colspan="2" nowrap><?=tep_image_submit(DIR_IMAGES . 'button_log_in.gif', '67', '24', '0', IMAGE_LOGIN);?>&nbsp;</td>
          </tr>
          <tr>
            <td align="right" colspan="4" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<a href="<?=tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'NONSSL');?>"><?=TEXT_PASSWORD_FORGOTTEN;?></a>&nbsp;</font></td>
          </tr>
<?
  if ($nonsess_cart) {
?>
          <tr>
            <td colspan="4"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><br><?=TEXT_VISITORS_CART;?></font></td>
          </tr>
<?
  }
  if ($HTTP_GET_VARS['login'] == 'fail') {
?>
          <tr>
            <td colspan="4" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_LOGIN_ERROR;?></font></td>
          </tr>
<?
  }
?>
        </table><? if ($HTTP_GET_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; } ?><? if ($HTTP_GET_VARS['connection']) { echo '<input type="hidden" name="connection" value="' . $HTTP_GET_VARS['connection'] . '">'; } ?><? if ($HTTP_GET_VARS['products_id']) { echo '<input type="hidden" name="products_id" value="' . $HTTP_GET_VARS['products_id'] . '">'; } ?></form></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_right.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?
  }
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>

<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action'] == 'process') {
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address from customers where customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "'");

    if ($HTTP_POST_VARS['user'] == 'new') {
      if (!tep_db_num_rows($check_customer_query)) {
        header('Location: ' . tep_href_link(FILENAME_CREATE_ACCOUNT, 'email_address=' . $HTTP_POST_VARS['email_address'] . '&origin=' . $HTTP_POST_VARS['origin'], 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail_email', 'NONSSL'));
        tep_exit();
      }
    } else {

      if (tep_db_num_rows($check_customer_query)) {
        $check_customer = tep_db_fetch_array($check_customer_query);
        // Check that password is good
        $pass_ok = validate_password($HTTP_POST_VARS['password'], $check_customer['customers_password']);
        if ($pass_ok != true) {
          if (@$HTTP_POST_VARS['origin']) {
            if (@$HTTP_POST_VARS['products_id']) {
              header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
              tep_exit();
            } elseif (@$HTTP_POST_VARS['order_id']) {
              header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&order_id=' . $HTTP_POST_VARS['order_id'], 'NONSSL'));
              tep_exit();
            } else {
              header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'], 'NONSSL'));
              tep_exit();
            }
          } else {
            header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail', 'NONSSL'));
            tep_exit();
          }
        } else {

          $customer_id = $check_customer['customers_id'];
          $customer_first_name = $check_customer['customers_firstname'];
          tep_session_register('customer_id');
          tep_session_register('customer_first_name');

          if ($HTTP_POST_VARS['setcookie'] == '1') {
            setcookie('email_address', $HTTP_POST_VARS['email_address'], time()+2592000);
            setcookie('password', $HTTP_POST_VARS['password'], time()+2592000);
            setcookie('first_name', $customer_first_name, time()+2592000);
          } elseif ( ($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password']) ) {
            setcookie('email_address', '');
            setcookie('password', '');
            setcookie('first_name', '');
          }

          $date_now = date('Ymd');
          tep_db_query("update customers_info set customers_info_date_of_last_logon = '" . $date_now . "', customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . $customer_id . "'");

// restore cart contents
          $cart->restore_contents();

          if (@$HTTP_POST_VARS['origin']) {
            if (@$HTTP_POST_VARS['products_id']) {
              header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], 'products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
              tep_exit();
            } elseif (@$HTTP_POST_VARS['order_id']) {
              header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], 'order_id=' . $HTTP_POST_VARS['order_id'], 'NONSSL'));
              tep_exit();
            } elseif (@$HTTP_POST_VARS['emailproduct']) {
              header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], 'action=where&products_id=' . $HTTP_POST_VARS['emailproduct'] . '&send_to=' . $HTTP_POST_VARS['send_to'], 'NONSSL'));
              tep_exit();
            } else {
              if (@$HTTP_POST_VARS['connection'] == 'SSL') {
                $connection_type = 'SSL';
              } else {
                $connection_type = 'NONSSL';
              }
              header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], '', $connection_type));
              tep_exit();
            }
          } else {
            header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
            tep_exit();
          }
        }
      } else {
        if (@$HTTP_POST_VARS['origin']) {
          if (@$HTTP_POST_VARS['products_id']) {
            header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
            tep_exit();
          } elseif (@$HTTP_POST_VARS['order_id']) {
            header('Location: ' . tep_href_link(FILENAME_LOGIN, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&order_id=' . $HTTP_POST_VARS['order_id'], 'NONSSL'));
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
    }
  } else {
   $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN; include(DIR_WS_INCLUDES . 'include_once.php');
   $location = ' : <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>';
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<? echo FILENAME_INFO_SHOPPING_CART; ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" nowrap>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td rowspan="2" align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="main">&nbsp;&nbsp;<? echo TEXT_STEP_BY_STEP; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><form name="login" method="post" action="<? echo tep_href_link(FILENAME_LOGIN, 'action=process&email_address=' . $HTTP_POST_VARS['email_address'], 'NONSSL'); ?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  if ($HTTP_GET_VARS['login'] == 'fail') {
?>
          <tr>
            <td colspan="2" class="smallText" nowrap><? echo TEXT_LOGIN_ERROR; ?>&nbsp;<br>&nbsp;</td>
          </tr>
<?
  }
  if ($HTTP_GET_VARS['login'] == 'fail_email') {
?>
          <tr>
            <td colspan="2" class="smallText" nowrap><? echo TEXT_LOGIN_ERROR_EMAIL; ?>&nbsp;<br>&nbsp;</td>
          </tr>
<?
  }
?>
          <tr>
            <td align="right" class="main" nowrap>&nbsp;<? echo ENTRY_EMAIL_ADDRESS2; ?>&nbsp;</td>
            <td class="main" nowrap>&nbsp;<input type="text" name="email_address" maxlength="96" value="<? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['email_address']; } ?>">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="main"><input type="radio" name="user" value="new"></td>
            <td class="main">&nbsp;<? echo TEXT_NEW_CUSTOMER; ?>&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="main"><input type="radio" name="user" value="returning" checked></td>
            <td class="main">&nbsp;<? echo TEXT_RETURNING_CUSTOMER; ?>&nbsp;</td>
          </tr>
          <tr>
            <td class="main" nowrap>&nbsp;</td>
            <td class="main" nowrap>&nbsp;<input type="password" name="password" maxlength="12" value="<? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['password']; } ?>">&nbsp;</td>
          </tr>
          <tr><label for="setcookie">
            <td align="right" class="main"><input type="checkbox" name="setcookie" value="1" id="setcookie" <? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo 'CHECKED'; } ?>></td>
            <td class="main" nowrap>&nbsp;<? echo TEXT_COOKIE; ?></td>
          </label></tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><br><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top" class="smallText" nowrap>&nbsp;<a href="<? echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'NONSSL'); ?>"><? echo TEXT_PASSWORD_FORGOTTEN; ?></a></td>
            <td align="right" class="smallText" nowrap><? echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;</td>
          </tr>
<?
   $origin = '';
   if ($HTTP_GET_VARS['products_id']) {
     $origin = 'products_id=' . $HTTP_GET_VARS['products_id'];
   }
   if ($HTTP_GET_VARS['order_id']) {
     $origin = 'order_id=' . $HTTP_GET_VARS['order_id'];
   }
   if ($HTTP_GET_VARS['emailproduct']) {
     $origin = 'emailproduct=' . $HTTP_GET_VARS['emailproduct'];
   }
   if ($HTTP_GET_VARS['origin']) {
     if ($origin != '') {
       $origin = $origin . '&';
     }
     $origin = $origin . 'origin=' . $HTTP_GET_VARS['origin'];
   }
   if ($HTTP_GET_VARS['connection']) {
     if ($origin != '') {
       $origin = $origin . '&';
     }
     $origin = $origin . 'connection=' . $HTTP_GET_VARS['connection'];
   }
?>
        </table><? if ($HTTP_GET_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; } ?><? if ($HTTP_GET_VARS['connection']) { echo '<input type="hidden" name="connection" value="' . $HTTP_GET_VARS['connection'] . '">'; } ?><? if ($HTTP_GET_VARS['products_id']) { echo '<input type="hidden" name="products_id" value="' . $HTTP_GET_VARS['products_id'] . '">'; } ?><? if ($HTTP_GET_VARS['send_to']) { echo '<input type="hidden" name="send_to" value="' . $HTTP_GET_VARS['send_to'] . '">'; } ?><? if ($HTTP_GET_VARS['order_id']) { echo '<input type="hidden" name="order_id" value="' . $HTTP_GET_VARS['order_id'] . '">'; } ?><? if ($HTTP_GET_VARS['emailproduct']) { echo '<input type="hidden" name="emailproduct" value="' . $HTTP_GET_VARS['emailproduct'] . '">'; } ?></form></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_right.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?
  }
?>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

<?php
/*
  $Id: login.php,v 1.64 2002/05/30 18:27:42 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action'] == 'process') {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    // Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
    if ($HTTP_POST_VARS['user'] == 'new') {
      if (!tep_db_num_rows($check_customer_query)) {
        tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, 'email_address=' . $email_address, 'SSL'));
      } else {
        tep_redirect(tep_href_link(FILENAME_LOGIN, 'login=fail_email', 'SSL'));
      }
    } else {
      if (!tep_db_num_rows($check_customer_query)) {
        tep_redirect(tep_href_link(FILENAME_LOGIN, 'login=fail', 'SSL'));
      } else {
        $check_customer = tep_db_fetch_array($check_customer_query);
        // Check that password is good
        $pass_ok = validate_password($password, $check_customer['customers_password']);
        if ($pass_ok != true) {
          tep_redirect(tep_href_link(FILENAME_LOGIN, 'login=fail', 'SSL'));
        } else {
          $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $check_customer['customers_id'] . "' and address_book_id = '1'");
          $check_country = tep_db_fetch_array($check_country_query);
          $customer_id = $check_customer['customers_id'];
          $customer_default_address_id = $check_customer['customers_default_address_id'];
          $customer_first_name = $check_customer['customers_firstname'];
          $customer_country_id = $check_country['entry_country_id'];
          $customer_zone_id = $check_country['entry_zone_id'];
          tep_session_register('customer_id');
          tep_session_register('customer_default_address_id');
          tep_session_register('customer_first_name');
          tep_session_register('customer_country_id');
          tep_session_register('customer_zone_id');

          if ($HTTP_POST_VARS['setcookie'] == '1') {
            setcookie('email_address', $email_address, time()+2592000);
            setcookie('password', $password, time()+2592000);
            setcookie('first_name', $customer_first_name, time()+2592000);
          } elseif ( ($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password']) ) {
            setcookie('email_address', '');
            setcookie('password', '');
            setcookie('first_name', '');
          }

          $date_now = date('Ymd');
          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . $customer_id . "'");

// restore cart contents
          $cart->restore_contents();

          if (sizeof($navigation->snapshot) > 0) {
            $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
            $navigation->clear_snapshot();
            tep_redirect($origin_href);
          } else {
            tep_redirect(tep_href_link(FILENAME_DEFAULT));
          }
        }
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<?php echo FILENAME_INFO_SHOPPING_CART; ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td rowspan="2" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><form name="login" method="post" action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'); ?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($HTTP_GET_VARS['login'] == 'fail') {
?>
          <tr>
            <td colspan="2" class="smallText"><?php echo TEXT_LOGIN_ERROR; ?></td>
          </tr>
<?php
  } elseif ($HTTP_GET_VARS['login'] == 'fail_email') {
?>
          <tr>
            <td colspan="2" class="smallText"><?php echo TEXT_LOGIN_ERROR_EMAIL; ?></td>
          </tr>
<?php
  } elseif ($cart->count_contents()) {
?>
          <tr>
            <td colspan="2" class="smallText"><?php echo TEXT_VISITORS_CART; ?></td>
          </tr>
<?
  }

  $email_address = tep_db_prepare_input($HTTP_COOKIE_VARS['email_address']);
  $password = tep_db_prepare_input($HTTP_COOKIE_VARS['password']);
?>
          <tr>
            <td align="right" class="main"><?php echo ENTRY_EMAIL_ADDRESS2; ?></td>
            <td class="main"><input type="text" name="email_address" maxlength="96" value="<?php echo $email_address; ?>"></td>
          </tr>
          <tr>
            <td align="right" class="main"><input type="radio" name="user" value="new"<?php if (!$email_address) echo ' checked'; ?>></td>
            <td class="main"><?php echo TEXT_NEW_CUSTOMER; ?></td>
          </tr>
          <tr>
            <td align="right" class="main"><input type="radio" name="user" value="returning"<?php if ($email_address) echo ' checked'; ?>></td>
            <td class="main"><?php echo TEXT_RETURNING_CUSTOMER; ?><br><input type="password" name="password" maxlength="40" value="<?php echo $password; ?>"></td>
          </tr>
          <tr><label for="setcookie">
            <td align="right" class="main"><input type="checkbox" name="setcookie" value="1" id="setcookie" <?php if ($email_address) echo 'CHECKED'; ?>></td>
            <td class="main"><?php echo TEXT_COOKIE; ?></td>
          </label></tr>
          <tr>
            <td colspan="2"><br><table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td valign="top" class="smallText"><a href="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'); ?>"><?php echo TEXT_PASSWORD_FORGOTTEN; ?></a></td>
                <td align="right" class="smallText"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></form></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
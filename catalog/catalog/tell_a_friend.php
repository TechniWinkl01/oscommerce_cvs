<?php
/*
  $Id: tell_a_friend.php,v 1.13 2001/10/02 12:07:47 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TELL_A_FRIEND);

  $location = ' : <a href="' . tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>';

  if (tep_session_is_registered('customer_id')) {
    $account = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
    $account_values = tep_db_fetch_array($account);
  } elseif (EMAILPRODUCT_GUEST == false) {
    tep_redirect(tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_TELL_A_FRIEND . '&emailproduct=' . $HTTP_GET_VARS['products_id'] . '&send_to=' . $HTTP_GET_VARS['send_to'], 'NONSSL'));
  }

  if (!$HTTP_GET_VARS['products_id']) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
  }

  $product_info = tep_db_query("select pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.language_id = '" . $languages_id . "'");
  $product_info_values = tep_db_fetch_array($product_info);
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $product_info_values['products_name']); ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_contact_us.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'process') {
    if (tep_session_is_registered('customer_id')) {
      $from_name = $account_values['customers_firstname'] . ' ' . $account_values['customers_lastname'];
      $from_email_address = $account_values['customers_email_address'];
    } else {
      $from_name = $HTTP_POST_VARS['yourname'];
      $from_email_address = $HTTP_POST_VARS['from'];
    }

    $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
    $email_body = sprintf(TEXT_EMAIL_INTRO, $HTTP_POST_VARS['friendname'], $from_name, $HTTP_POST_VARS['products_name'], STORE_NAME) . "\n\n";
    if ($HTTP_POST_VARS['yourmessage'] != '') {
      $email_body .= $HTTP_POST_VARS['yourmessage'] . "\n\n";
    }
    $email_body .= sprintf(TEXT_EMAIL_LINK, HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id=' . $HTTP_GET_VARS['products_id']) . "\n\n";
    $email_body .= sprintf(TEXT_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");
    tep_mail($HTTP_POST_VARS['friendname'], $HTTP_POST_VARS['friendemail'], $email_subject, stripslashes($email_body), '', $from_email_address, '');
?>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $HTTP_POST_VARS['products_name'], $HTTP_POST_VARS['friendemail']); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>&nbsp;&nbsp;</td>
      </tr>
<?php
  } else {
    if (tep_session_is_registered('customer_id')) {
      $your_name_prompt = $account_values['customers_firstname'] . ' ' . $account_values['customers_lastname'];
      $your_email_address_prompt = $account_values['customers_email_address'];
    } else {
      $your_name_prompt = '<input type="text" name="yourname" value="' . $account_values['customers_firstname'] . ' ' . $account_values['customers_lastname'] . '">';
      $your_email_address_prompt = '<input type="text" name="from" value="' . $account_values['customers_email_address'] . '">';
    }
?>
      <form <?php echo 'action="' . tep_href_link(FILENAME_TELL_A_FRIEND, 'action=process&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '"'; ?> method="post"><input type="hidden" name="products_name" value="<?php echo $product_info_values['products_name']; ?>">
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="formAreaTitle"><?php echo FORM_TITLE_CUSTOMER_DETAILS; ?></td>
          </tr>
          <tr>
            <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
              <tr>
                <td class="main"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main">&nbsp;<?php echo FORM_FIELD_CUSTOMER_NAME; ?>&nbsp;</td>
                    <td class="main">&nbsp;<?php echo $your_name_prompt; ?></td>
                  </tr>
                  <tr>
                    <td class="main">&nbsp;<?php echo FORM_FIELD_CUSTOMER_EMAIL; ?>&nbsp;</td>
                    <td class="main">&nbsp;<?php echo $your_email_address_prompt; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="formAreaTitle"><br><?php echo FORM_TITLE_FRIEND_DETAILS; ?></td>
          </tr>
          <tr>
            <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
              <tr>
                <td class="main"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main">&nbsp;<?php echo FORM_FIELD_FRIEND_NAME; ?>&nbsp;</td>
                    <td class="main">&nbsp;<input type="text" name="friendname"></td>
                  </tr>
                  <tr>
                    <td class="main">&nbsp;<?php echo FORM_FIELD_FRIEND_EMAIL; ?>&nbsp;</td>
                    <td class="main">&nbsp;<input type="text" name="friendemail" value="<?php echo $HTTP_GET_VARS['send_to']; ?>">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="formAreaTitle"><br><?php echo FORM_TITLE_FRIEND_MESSAGE; ?></td>
          </tr>
          <tr>
            <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
              <tr>
                <td class="main"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main">&nbsp;<textarea cols="40" rows="8" name="yourmessage"></textarea>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
          </tr>
        </table></td>
      </tr></form>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php
  require(DIR_WS_INCLUDES . 'column_right.php');
?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_TELL_A_FRIEND; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<?
  if (tep_session_is_registered('customer_id')) {
    $account = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
    $account_values = tep_db_fetch_array($account);
  } elseif (EMAILPRODUCT_GUEST == false) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_TELL_A_FRIEND . '&emailproduct=' . $HTTP_GET_VARS['products_id'] . '&send_to=' . $HTTP_GET_VARS['send_to'], 'NONSSL'));
    tep_exit();
  }
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
            <td width="100%" class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_contact_us.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'process') {
    $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $HTTP_POST_VARS['yourname'], STORE_NAME);
    $email_body = sprintf(TEXT_EMAIL_INTRO, $HTTP_POST_VARS['yourname'], $HTTP_POST_VARS['products_name'], STORE_NAME) . "\n\n";
    if ($HTTP_POST_VARS['yourmessage'] != '') {
      $email_body .= $HTTP_POST_VARS['yourmessage'] . "\n\n";
    }
    $email_body .= sprintf(TEXT_EMAIL_LINK, HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id=' . $products_id) . "\n\n";
    $email_body .= sprintf(TEXT_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");
    tep_mail('', '', $HTTP_POST_VARS['friendemail'], $email_subject, $email_body, '', $from, '');
?>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><? echo TEXT_EMAILPRODUCT_YOUR_MAIL_ABOUT; ?> <?php echo $HTTP_POST_VARS['products_name']; ?> <?php echo TEXT_EMAILPRODUCT_HAS_BEEN_SENT; ?> <? echo $HTTP_POST_VARS['friendemail']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><a href="<? echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id, 'NONSSL'); ?>"><? echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a>&nbsp;&nbsp;</td>
      </tr>
<?
  } else {
    $product_info = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.language_id = '" . $languages_id . "'");
    $product_info_values = tep_db_fetch_array($product_info);
?>
      <form action="<? echo tep_href_link(FILENAME_TELL_A_FRIEND, 'action=process', 'NONSSL'); ?>" method="post"><input type="hidden" name="products_id" value="<? echo $product_info_values['products_id']; ?>"><input type="hidden" name="products_name" value="<? echo $product_info_values['products_name']; ?>">
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><? echo TEXT_EMAILPRODUCT_EMAIL; ?>&nbsp;</td>
                <td width="100%"><input type="text" name="from" value="<? echo $account_values['customers_email_address']; ?>"></td>
              </tr>
              <tr>
                <td class="main"><? echo TEXT_EMAILPRODUCT_NAME; ?>&nbsp;</td>
                <td><input type="text" name="yourname" value="<? echo $account_values['customers_firstname']; ?> <? echo $account_values['customers_lastname']; ?>"></td>
              </tr>
              <tr>
                <td class="main"><? echo TEXT_EMAILPRODUCT_FRIEND_EMAIL; ?>&nbsp;</td>
                <td><input type="text" name="friendemail" value="<?php echo $HTTP_GET_VARS['send_to']; ?>"></td>
              </tr>
              <tr>
                <td colspan="2" class="main"><br><? echo TEXT_EMAILPRODUCT_MESSAGE; ?>&nbsp;<br><textarea cols="40" rows="8" name="yourmessage"></textarea></td>
              </tr>
              <tr>
                <td colspan="2" class="main"><? echo TEXT_EMAILPRODUCT_TELLAFRIEND; ?> <b><?php echo $product_info_values['products_name']; ?></b></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info_values['products_id'], 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
          </tr>
        </table></td>
      </tr></form>
<?
  }
?>
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
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

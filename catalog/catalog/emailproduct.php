<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_EMAILPRODUCT; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<?
  if (tep_session_is_registered('customer_id')) {
    $account_query = 'select ';
    $account_query = $account_query . 'customers_firstname, customers_lastname, ';
    $account_query = $account_query . 'customers_email_address, customers_street_address, ';
    $account_query = $account_query . "customers_country_id from customers where customers_id = '" . $customer_id . "'";
    $account = tep_db_query($account_query);
    $account_values = tep_db_fetch_array($account);
  } elseif (!EMAILPRODUCT_GUEST) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ACCOUNT_EDIT, 'NONSSL'));
    tep_exit();
  }
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_EMAILPRODUCT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
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
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_contact_us.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'process') {
    mail($friendemail, $email_subject, $email_taf, 'Content-Type: text/plain; charset="iso-8859-15"' . "\n" . 'Content-Transfer-Encoding: 8bit' . "\n" . 'From: ' . $from);
?>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_EMAILPRODUCT_YOUR_MAIL_ABOUT; ?> <?php echo $products_name; ?> <?php echo TEXT_EMAILPRODUCT_HAS_BEEN_SENT; ?> <? echo $friendemail; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><?php echo FONT_STYLE_MAIN; ?><a href="<? echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id, 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK); ?></a>&nbsp;&nbsp;</font></td>
      </tr>
<?
  }

  if ($HTTP_GET_VARS['action'] == 'where') {
    $product_info = tep_db_query("select products_id, products_name, products_description, products_model, products_quantity, products_image, products_url, products_price, products_date_added, products_date_available, manufacturers_id from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $product_info_values = tep_db_fetch_array($product_info);
?>
      <form action="<? echo tep_href_link(FILENAME_EMAILPRODUCT, 'action=process', 'NONSSL'); ?>" method="post"><input type="hidden" name="products_id" value="<? echo $product_info_values['products_id']; ?>"><input type="hidden" name="products_name" value="<? echo $product_info_values['products_name']; ?>">
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_EMAILPRODUCT_EMAIL; ?>&nbsp;</td>
                <td width="100%"><input type="text" name="from" value="<? echo $account_values['customers_email_address']; ?>"></td>
              </tr>
              <tr>
                <td nowrap><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_EMAILPRODUCT_NAME; ?>&nbsp;</td>
                <td><input type="text" name="yourname" value="<? echo $account_values['customers_firstname']; ?> <? echo $account_values['customers_lastname']; ?>"></td>
              </tr>
              <tr>
                <td nowrap><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_EMAILPRODUCT_FRIEND_EMAIL; ?>&nbsp;</td>
                <td><input type="text" name="friendemail"></td>
              </tr>
              <tr>
                <td colspan="2"><br><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_EMAILPRODUCT_MESSAGE; ?>&nbsp;<br><textarea cols="40" rows="8" name="yourmessage"></textarea></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_EMAILPRODUCT_TELLAFRIEND; ?> <b><?php echo $product_info_values['products_name']; ?></b></td>
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
            <td align="left" nowrap><?php echo FONT_STYLE_MAIN; ?><? echo tep_image_submit(DIR_WS_IMAGES . 'button_process.gif', IMAGE_PROCESS); ?></a>&nbsp;&nbsp;</font></td>
            <td align="right" nowrap><?php echo FONT_STYLE_MAIN; ?><a href="<? echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info_values['products_id'], 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK); ?></a>&nbsp;&nbsp;</font></td>
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
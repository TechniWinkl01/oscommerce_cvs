<?php
/*
  $Id: mail.php,v 1.20 2001/12/28 12:12:57 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ( ($HTTP_POST_VARS['action'] == 'send_email_to_user') && ($HTTP_POST_VARS['customers_email_address']) ) {
    if ($HTTP_POST_VARS['customers_email_address'] == '***') {
      $mail_query = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS);
      $mail_sent_to = TEXT_ALLCUSTOMERS;
    } elseif ($customers_email_address=="**D") {
      $mail_query = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter='1'");
      $mail_sent_to = TEXT_NEWSLETTERCUSTOMERS;
    } else {
      $mail_query_raw = "select customers_email_address, customers_lastname, customers_firstname from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $HTTP_POST_VARS['customers_email_address'] . "'";
      $mail_query = tep_db_query($mail_query_raw);
      $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];
    }
    $nb_emails = tep_db_num_rows($mail_query);
    while (list ($customers_email_address, $customers_lastname, $customers_firstname) = tep_db_fetch_array($mail_query)) {
      mail($customers_email_address, $subject, $message, 'Content-Type: text/plain; charset="iso-8859-15"' . "\n" . 'Content-Transfer-Encoding: 8bit' . "\n" . 'From: ' . $from);
    }
    Header('Location: ' . tep_href_link(FILENAME_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
  } else {
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="subBar">
            <td class="subBar">&nbsp;<?php echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><form action="<?php echo tep_href_link(FILENAME_MAIL, '', 'NONSSL'); ?>" method="post"><input type="hidden" name="action" value="send_email_to_user"><input type="hidden" name="all" value="0"><table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
    if ( ($HTTP_POST_VARS['action'] == 'send_email_to_user') && (!$HTTP_POST_VARS['customers_email_address']) ) {
?>
              <tr>
                <td colspan="2" class="main"><b><?php echo TEXT_NO_CUSTOMER_SELECTED; ?></b></td>
              </tr>
<?php
    } elseif ($HTTP_GET_VARS['mail_sent_to']) {
?>
              <tr>
                <td colspan="2" class="main"><b><?php echo TEXT_EMAILSENT . ':' . $HTTP_GET_VARS['mail_sent_to']; ?></b></td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
<?php
    $customers[] = array('id' => '', 'text' => TEXT_SELECTCUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALLCUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTERCUSTOMERS);
    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
    while($customers_values = tep_db_fetch_array($mail_query)) {
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
    }
?>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER_NAME; ?></td>
                <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, $HTTP_GET_VARS['customer']);?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_EMAIL_FROM; ?></td>
                <td><input type="text" size="28" name="from" value="<?php echo TEXT_EMAILFROM; ?>"></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                <td><input type="text" size="28" name="subject"></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                <td><textarea wrap="virtual" cols="60" rows="15" name="message"></textarea></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><?php echo tep_image_submit(DIR_WS_IMAGES . 'button_send_mail.gif', TEXT_SEND_EMAIL); ?></td>
              </tr>
            </table></form></td>
          </tr>
<!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?
  }
?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

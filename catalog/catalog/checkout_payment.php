<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<?=JS_ERROR;?>";

  var cc_owner = document.payment.cc_owner.value;
  var cc_number = document.payment.cc_number.value;
  var cc_expires = document.payment.cc_expires.value;

  if (document.payment.payment[1].checked) {
    if (cc_owner = "" || cc_owner.length < <?=CC_OWNER_MIN_LENGTH;?>) {
      error_message = error_message + "<?=JS_CC_OWNER;?>";
      error = 1;
    }

    if (cc_number = "" || cc_number.length < <?=CC_NUMBER_MIN_LENGTH;?>) {
      error_message = error_message + "<?=JS_CC_NUMBER;?>";
      error = 1;
    }

    if (cc_expires = "" || cc_expires.length < <?=CC_EXPIRY_MIN_LENGTH;?>) {
      error_message = error_message + "<?=JS_CC_EXPIRES;?>";
      error = 1;
    }
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
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
    <td width="100%" valign="top"><form name="payment" method="post" action="<?=tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL');?>" onsubmit="return check_form();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_payment.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_METHODS;?></b>&nbsp;</font></td>
                <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_SELECTION;?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_CASH_ON_DELIVERY;?>&nbsp;</font></td>
                <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="radio" name="payment" value="cod" CHECKED>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_CREDIT_CARD;?>&nbsp;</font></td>
                <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="radio" name="payment" value="cc">&nbsp;</font></td>
              </tr>
              <tr>
                <td colspan="2"><br><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_CREDIT_CARD_TYPE;?>&nbsp;</font></td>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<select name="cc_type"><option value="Visa">Visa</option><option value="Mastercard">Mastercard</option><option name="American Express">American Express</option></select>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_CREDIT_CARD_OWNER;?>&nbsp;</font></td>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="cc_owner">&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_CREDIT_CARD_NUMBER;?>&nbsp;</font></td>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="cc_number">&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_CREDIT_CARD_EXPIRES;?>&nbsp;</font></td>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="cc_expires">&nbsp;</font></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"><br><?=tep_black_line();?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_next.gif', '50', '24', '0', IMAGE_NEXT);?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<font color="<?=CHECKOUT_BAR_TEXT_COLOR;?>">[ <?=CHECKOUT_BAR_CART_CONTENTS;?> | <?=CHECKOUT_BAR_DELIVERY_ADDRESS;?> | <font color="<?=CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED;?>"><?=CHECKOUT_BAR_PAYMENT_METHOD;?></font> | <?=CHECKOUT_BAR_CONFIRMATION;?> | <?=CHECKOUT_BAR_FINISHED;?> ]</font>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
    </table><input type="hidden" name="sendto" value="<? echo $HTTP_POST_VARS['sendto']; ?>"></form></td>
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
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>

<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
<html>
<head>
<title><? echo TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<? echo JS_ERROR;?>";

  var cc_owner = document.payment.cc_owner.value;
  var cc_number = document.payment.cc_number.value;
  var cc_expires = document.payment.cc_expires.value;

  if (document.payment.payment[1].checked) {
    if (cc_owner == "" || cc_owner.length < <? echo CC_OWNER_MIN_LENGTH;?>) {
      error_message = error_message + "<? echo JS_CC_OWNER;?>";
      error = 1;
    }

    if (cc_number == "" || cc_number.length < <? echo CC_NUMBER_MIN_LENGTH;?>) {
      error_message = error_message + "<? echo JS_CC_NUMBER;?>";
      error = 1;
    }

    if (cc_expires == "" || cc_expires.length < <? echo CC_EXPIRY_MIN_LENGTH;?>) {
      error_message = error_message + "<? echo JS_CC_EXPIRES;?>";
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
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="payment" method="post" action="<? echo tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL');?>" onsubmit="return check_form();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_payment.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_METHODS;?></b>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_SELECTION;?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_CASH_ON_DELIVERY;?>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<input type="radio" name="payment" value="cod"
<? if (@$HTTP_POST_VARS['cc_owner'] == '' || @$HTTP_POST_VARS['cc_expires'] == '') { echo 'CHECKED';} ?>>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><br><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_CREDIT_CARD;?>&nbsp;</font></td>
                <td align="right" nowrap><br><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<input type="radio" name="payment" value="cc"
<? if (@$HTTP_POST_VARS['cc_owner'] != '' || @$HTTP_POST_VARS['cc_expires'] != '') { echo 'CHECKED';} ?>>&nbsp;</font></td>
              </tr>
              <tr>
                <td colspan="2"><br><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_CREDIT_CARD_OWNER;?>&nbsp;</font></td>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="cc_owner" value="<? echo $HTTP_POST_VARS['cc_owner'];?>">&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_CREDIT_CARD_NUMBER;?>&nbsp;</font></td>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="cc_number">&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_CREDIT_CARD_EXPIRES;?>&nbsp;</font></td>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="cc_expires" value="<? echo $HTTP_POST_VARS['cc_expires'];?>">&nbsp;</font></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"><br><? echo tep_black_line();?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" nowrap><br><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo tep_image_submit(DIR_IMAGES . 'button_next.gif', '50', '24', '0', IMAGE_NEXT);?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE;?>" size="<? echo SMALL_TEXT_FONT_SIZE;?>" color="<? echo SMALL_TEXT_FONT_COLOR;?>">&nbsp;<font color="<? echo CHECKOUT_BAR_TEXT_COLOR;?>">[ <? echo CHECKOUT_BAR_CART_CONTENTS;?> | <? echo CHECKOUT_BAR_DELIVERY_ADDRESS;?> | <font color="<? echo CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED;?>"><? echo CHECKOUT_BAR_PAYMENT_METHOD;?></font> | <? echo CHECKOUT_BAR_CONFIRMATION;?> | <? echo CHECKOUT_BAR_FINISHED;?> ]</font>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
    </table><input type="hidden" name="sendto" value="<? echo $HTTP_POST_VARS['sendto']; ?>">
            <input type="hidden" name="prod" value="<? echo $HTTP_POST_VARS['prod']; ?>"></form></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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

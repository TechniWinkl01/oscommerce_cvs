<?
  $payment_code = 'cc';
  $payment_description = TEXT_CREDIT_CARD;
  $payment_enabled = PAYMENT_SUPPORT_CC;

  if ($payment_action == 'PM_VALIDATION' && $payment_enabled)
  { 
?>
  if (payment_value == '<? echo $payment_code; ?>') {
    var cc_owner = document.payment.cc_owner.value;
    var cc_number = document.payment.cc_number.value;
    if (cc_owner == "" || cc_owner.length < <? echo CC_OWNER_MIN_LENGTH; ?>) {
      error_message = error_message + "<? echo JS_CC_OWNER; ?>";
      error = 1;
    }
    if (cc_number == "" || cc_number.length < <? echo CC_NUMBER_MIN_LENGTH; ?>) {
      error_message = error_message + "<? echo JS_CC_NUMBER; ?>";
      error = 1;
    }
  }
<?
  } 
  elseif ($payment_action == 'PM_SELECTION' && $payment_enabled) 
  {
?>
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                  <tr>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_CREDIT_CARD_OWNER; ?>&nbsp;</font></td>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="cc_owner" value="<? echo $HTTP_POST_VARS['cc_owner']; ?>">&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_CREDIT_CARD_NUMBER; ?>&nbsp;</font></td>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="cc_number">&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_CREDIT_CARD_EXPIRES; ?>&nbsp;</font></td>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<select name="cc_expires_month">
                      <? 
                      for ($i=1; $i <= 12; $i++) {
                        $selected = ($HTTP_POST_VARS['cc_expires_month']==$i) ? ' selected' : '';
                        echo '<option' . $selected . ' value="' . sprintf('%02d', $i) . '">' . strftime("%B",mktime(0,0,0,$i,1,2000)) . '</option>'; 
                      }
                      ?>
                    </select>&nbsp;/&nbsp;<select name="cc_expires_year">
                      <? 
                      $today=getdate(); 
                      for ($i=$today['year']; $i < $today['year']+10; $i++) {
                        $selected = ($HTTP_POST_VARS['cc_expires_year']==strftime("%y",mktime(0,0,0,1,1,$i))) ? ' selected' : '';
                        echo '<option' . $selected . ' value="' . strftime("%y",mktime(0,0,0,1,1,$i)) . '">' . strftime("%Y",mktime(0,0,0,1,1,$i)) . '</option>';
                      }
                      ?>
                    </select></font></td>
                  </tr>
                </table>
<?
  } 
  elseif ($payment_action == 'PM_CONFIRMATION' && $payment_enabled) 
  {
    $include_file = DIR_FUNCTIONS . 'ccval.php'; include(DIR_INCLUDES . 'include_once.php');
    $cc_val = OnlyNumericSolution($HTTP_POST_VARS['cc_number']);
    $cc_val = CCValidationSolution($cc_val);

    echo '          <tr>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_OWNER . '&nbsp;' . $HTTP_POST_VARS['cc_owner'] . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
    if ($cc_val == '1')
    {
      echo '          <tr>' . "\n";
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
      echo '          <tr>' . "\n";
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
    }
    echo '          <tr>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_EXPIRES . '&nbsp;' . strftime('%B', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'],1,2000)) . ' / ' . strftime('%Y',mktime(0,0,0,1,1,$HTTP_POST_VARS['cc_expires_year'])) . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
    if ($cc_val != '1') 
    {
      echo '          <tr>' . "\n";
      echo '            <td><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<font color="#FF0000"><b>' . TEXT_VAL . '</b></font><br>&nbsp;' . $cc_val . '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
    }
    if ($cc_val != '1') 
    {
      $checkout_form_action = tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
      $checkout_form_submit = tep_image_submit(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK) . '&nbsp;</font>' . "\n";
    }
  } 
  elseif ($payment_action == 'PM_PROCESS_BUTTON' && $payment_enabled) 
  {
    echo '<input type="hidden" name="cc_owner" value="' . $HTTP_POST_VARS['cc_owner'] . '">';
    echo '<input type="hidden" name="cc_expires" value="' . $HTTP_POST_VARS['cc_expires_month'] . $HTTP_POST_VARS['cc_expires_year'] . '">';
    if ($cc_val == '1') 
    {
      echo '<input type="hidden" name="cc_type" value="' . $CardName . '">';
      echo '<input type="hidden" name="cc_number" value="' . $CardNumber . '">';
    } 
    else 
    {
      echo '<input type="hidden" name="cc_expires_month" value="' . $HTTP_POST_VARS['cc_expires_month'] . '">';
      echo '<input type="hidden" name="cc_expires_year" value="' . $HTTP_POST_VARS['cc_expires_year'] . '">';
      echo '<input type="hidden" name="prod" value="' . $HTTP_POST_VARS['prod'] . '">';
    }
  } 
  elseif ($payment_action == 'PM_BEFORE_PROCESS' && $payment_enabled) 
  {
    $cc_number = $HTTP_POST_VARS['cc_number'];
    if (defined('PAYMENT_EMAIL_CC') && PAYMENT_EMAIL_CC != 'NONE') {
      $len = strlen($cc_number);
      $new_cc = substr($cc_number, 0, 4) . substr('XXXXXXXXXXXXXXXX', 0, $len-8) . substr($cc_number, -4);
      $cc_middle = substr($cc_number, 4, $len-8);
      $cc_number = $new_cc;
    }
  } 
  elseif ($payment_action == 'PM_AFTER_PROCESS' && $payment_enabled) 
  {
    // send emails to other people
    if (defined('PAYMENT_EMAIL_CC') && PAYMENT_EMAIL_CC != 'NONE') {
      $message = "Order #" . $insert_id . "\nMiddle " . $cc_middle ."\n";
      mail(PAYMENT_EMAIL_CC, "Extra Order Info", $message, 'From: ' . EMAIL_FROM);
    }
    header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); 
  } 
  elseif ($payment_action == 'PM_CHECK') 
  {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_SUPPORT_CC'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($payment_action == 'PM_INSTALL') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Credit Card', 'PAYMENT_SUPPORT_CC', '1', 'Do you want to accept credit card payments?', '6', '2', now())");
  } elseif ($payment_action == 'PM_REMOVE') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'PAYMENT_SUPPORT_CC'");
  }
?>
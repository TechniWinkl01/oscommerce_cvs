<?
  if (defined('PAYMENT_MODULES')) {
    
    // Get array of modules
    $modules = explode(';', PAYMENT_MODULES);

    // Get language defines
    reset($modules);
    while (list(,$value) = each($modules)) {
      $include_file = DIR_WS_LANGUAGES . $language . '/modules/payment/' . $value; 
      include(DIR_WS_INCLUDES . 'include_once.php');
    }

    // Include Javascript Code for every payment method
    if ($payment_action == 'PM_VALIDATION') {
      reset($modules);
      while (list(,$value) = each($modules)) {
        include(DIR_WS_PAYMENT_MODULES . $value);
      }
    }

    // List Payment Options
    if ($payment_action == 'PM_SELECTION') {
      $rows = 0;
      reset($modules);
      while (list(,$value) = each($modules)) {
        $rows ++;
        // Get id and description from payment modules
        $payment_action = '';
        include(DIR_WS_PAYMENT_MODULES . $value);
        if ($payment_enabled) {
?>
              <tr bgcolor="<? echo TABLE_ALT_BACKGROUND_COLOR; ?>">
                <td nowrap colspan=3><?php echo FONT_STYLE_MAIN; ?>&nbsp;<? echo $payment_description; ?>&nbsp;</font></td>
                <td align="right" nowrap><?php echo FONT_STYLE_MAIN; ?>&nbsp;<input type="radio" name="payment" value="<? echo $payment_code; ?>"
                <? if ((!$payment && $rows == 1) || ($payment == $payment_code)) echo " checked"; ?>>&nbsp;</font></td>
              </tr>
              <tr bgcolor="<? echo TABLE_ROW_BACKGROUND_COLOR; ?>">
                <td colspan="2">
<? 
          // Display extra fields for each payment
          $payment_action = 'PM_SELECTION'; 
          include(DIR_WS_PAYMENT_MODULES . $value); 
        }
?>
                </td>
              </tr>
<?
      }
    }

    if ($payment_action == 'PM_CONFIRMATION') {
      reset($modules);
      while (list(,$payment_file) = each($modules)) {
        $payment_action = '';
        include(DIR_WS_PAYMENT_MODULES . $payment_file);
        if ($payment_code == $HTTP_POST_VARS['payment']) {
          echo '          <tr>' . "\n";
          echo '            <td nowrap>' . FONT_STYLE_MAIN . '&nbsp;' . $payment_description . '&nbsp;</font></td>' . "\n";
          echo '          </tr>' . "\n";
          $payment_action = 'PM_CONFIRMATION';
          include(DIR_WS_PAYMENT_MODULES . $payment_file);
          break;
        }
      }
    }

    if ($payment_action == 'PM_PROCESS_BUTTON') {
      include(DIR_WS_PAYMENT_MODULES . $payment_file);
    }

    if ($payment_action == 'PM_BEFORE_PROCESS') {
      reset($modules);
      while (list(,$payment_file) = each($modules)) {
        include(DIR_WS_PAYMENT_MODULES . $payment_file);
      }
    }

    if ($payment_action == 'PM_AFTER_PROCESS') {
      reset($modules);
      while (list(,$payment_file) = each($modules)) {
        $payment_action = '';
        include(DIR_WS_PAYMENT_MODULES . $payment_file);
        if ($payment_code == $payment) {
          $payment_action = 'PM_AFTER_PROCESS';
          include(DIR_WS_PAYMENT_MODULES . $payment_file);
          break;
        }
      }
    }

    if ($payment_action == 'PM_SHOW_INFO') {
      reset($modules);
      while (list(,$payment_file) = each($modules)) {
        $payment_action = '';
        include(DIR_WS_PAYMENT_MODULES . $payment_file);
        if ($payment_code == $order_values['payment_method']) {
          $payment_text = $payment_description;
          break;
        }
      }
      echo '          <tr>' . "\n";
      echo '            <td nowrap>' . FONT_STYLE_MAIN . '&nbsp;' . $payment_text. '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
    }

  }
?>
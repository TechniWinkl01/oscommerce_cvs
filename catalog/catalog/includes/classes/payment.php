<?php
  class payment {
    var $modules;

// class constructor
    function payment() {
      global $language;

      if (PAYMENT_MODULES) {
        $this->modules = explode(';', PAYMENT_MODULES); // get array of accepted modules

        reset($this->modules);
        while (list(, $value) = each($this->modules)) { // get module defines
          $include_file = DIR_WS_LANGUAGES . $language . '/modules/payment/' . $value; include(DIR_WS_INCLUDES . 'include_once.php');
          $include_file = DIR_WS_PAYMENT_MODULES . $value; include(DIR_WS_INCLUDES . 'include_once.php');

          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
        }
      }
    }

// class methods
    function javascript_validation() {
      if (PAYMENT_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->javascript_validation();
        }
      }
    }

    function selection() {
      if (PAYMENT_MODULES) {
        $rows = 0;
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->payment_enabled) {
            $rows ++;
            $selection_string .= '              <tr class="payment-odd">' . "\n" .
                                 '                <td colspan="3" class="main" nowrap>&nbsp;' . $GLOBALS[$class]->payment_description . '&nbsp;</td>' . "\n" .
                                 '                <td align="right" class="main" nowrap>&nbsp;<input type="radio" name="payment" value="' . $GLOBALS[$class]->payment_code . '"';
            if ( (!$payment && $rows == 1) || ($payment == $GLOBALS[$class]->payment_code)) {
              $selection_string .= ' CHECKED';
            }
            $selection_string .= '>&nbsp;</td>' . "\n" .
                                 '              </tr>' . "\n" .
                                 '              <tr class="payment-even">' . "\n" .
                                 '                <td colspan="2">';
            $selection_string .= $GLOBALS[$class]->selection();
            $selection_string .= '</td>' . "\n" .
                                 '              </tr>' . "\n";
          }
        }
        echo $selection_string;
      }
    }

    function confirmation() {
      global $HTTP_POST_VARS;

      if (PAYMENT_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->payment_code == $HTTP_POST_VARS['payment']) {
            $confirmation_string = '          <tr>' . "\n" .
                                   '            <td class="main" nowrap>&nbsp;' . $GLOBALS[$class]->payment_description . '&nbsp;</td>' . "\n" .
                                   '          </tr>' . "\n";
            $confirmation_string .= $GLOBALS[$class]->confirmation();
          }
        }
        echo $confirmation_string;
      }
    }

    function process_button() {
      if (PAYMENT_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->process_button();
        }
      }
    }

    function before_process() {
      if (PAYMENT_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->before_process();
        }
      }
    }

    function after_process() {
      global $payment;

      if (PAYMENT_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->payment_code == $payment) {
            $GLOBALS[$class]->after_process();
          }
        }
      }
    }

    function show_info() {
      global $order_values;

      if (PAYMENT_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->payment_code == $order_values['payment_method']) {
            $payment_text = $GLOBALS[$class]->payment_description;
          }
        }
        $show_info_string = '          <tr>' . "\n" .
                            '            <td class="main" nowrap>&nbsp;' . $payment_text. '&nbsp;</td>' . "\n" .
                            '          </tr>' . "\n";

        echo $show_info_string;
      }
    }

  }
?>
<?php
  class payment {
    var $modules;

// class constructor
    function payment() {
      global $language;

      if (MODULE_PAYMENT_INSTALLED) {
        $this->modules = explode(';', MODULE_PAYMENT_INSTALLED); // get array of accepted modules

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
      if (MODULE_PAYMENT_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->javascript_validation();
        }
      }
    }

    function selection() {
      global $payment;

      if (MODULE_PAYMENT_INSTALLED) {
        $rows = 0;
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $rows ++;
            $selection_string .= '              <tr class="payment-odd">' . "\n" .
                                 '                <td colspan="3" class="main">&nbsp;' . $GLOBALS[$class]->description . '&nbsp;</td>' . "\n" .
                                 '                <td align="right" class="main">&nbsp;<input type="radio" name="payment" value="' . $GLOBALS[$class]->code . '"';
            if ( (!$payment && $rows == 1) || ($payment == $GLOBALS[$class]->code)) {
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

      if (MODULE_PAYMENT_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->code == $HTTP_POST_VARS['payment']) {
            $confirmation_string = '          <tr>' . "\n" .
                                   '            <td class="main">&nbsp;' . $GLOBALS[$class]->description . '&nbsp;</td>' . "\n" .
                                   '          </tr>' . "\n";
            $confirmation_string .= $GLOBALS[$class]->confirmation();
          }
        }
        echo $confirmation_string;
      }
    }

    function process_button() {
      global $payment;

      if (MODULE_PAYMENT_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->code == $payment) {
            echo $GLOBALS[$class]->process_button();
          }
        }
      }
    }

    function before_process() {
      if (MODULE_PAYMENT_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->before_process();
        }
      }
    }

    function after_process() {
      global $payment;

      if (MODULE_PAYMENT_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->code == $payment) {
            $GLOBALS[$class]->after_process();
          }
        }
      }
    }

    function show_info() {
      global $order_values;

      if (MODULE_PAYMENT_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->code == $order_values['payment_method']) {
            $payment_text = $GLOBALS[$class]->description;
          }
        }
        $show_info_string = '          <tr>' . "\n" .
                            '            <td class="main">&nbsp;' . $payment_text. '&nbsp;</td>' . "\n" .
                            '          </tr>' . "\n";

        echo $show_info_string;
      }
    }

    function output_error() {
      global $payment;

      if (MODULE_PAYMENT_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->code == $payment) {
            echo $GLOBALS[$class]->output_error();
          }
        }
      }
    }

  }
?>
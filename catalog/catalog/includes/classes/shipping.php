<?
  class shipping {
    var $modules;

// class constructor
    function shipping() {
      global $shipping_count, $language;

      $shipping_count = 0;
      if (SHIPPING_MODULES) {
        $this->modules = explode(';', SHIPPING_MODULES); // get array of accepted modules
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $include_file = DIR_WS_LANGUAGES . $language . '/modules/shipping/' . $value; include(DIR_WS_INCLUDES . 'include_once.php');
          $include_file = DIR_WS_SHIPPING_MODULES . $value; include(DIR_WS_INCLUDES . 'include_once.php');

          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
        }
      }
    }

// class methods
    function select() {
      if (SHIPPING_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->select();
        }
        echo '<input type="hidden" name="shipping_quote_all" value="0">';
      }
    }

    function quote() {
      global $total_weight, $shipping_weight, $shipping_quoted, $shipping_num_boxes;

      if (SHIPPING_MODULES) {
        $shipping_quoted = '';
        $shipping_num_boxes = 1;
        $shipping_weight = $total_weight;

        if ($total_weight > SHIPPING_MAX_WEIGHT) { // Split into many boxes
          $shipping_num_boxes = round(($total_weight/SHIPPING_MAX_WEIGHT)+0.5);
          $shipping_weight = $total_weight/$shipping_num_boxes;
        }

        if ($shipping_weight < SHIPPING_BOX_WEIGHT*SHIPPING_BOX_PADDING) {
          $shipping_weight = $shipping_weight+SHIPPING_BOX_WEIGHT;
        } else {
          $shipping_weight = $shipping_weight + ($shipping_weight*SHIPPING_BOX_PADDING/100);
        }

        $shipping_weight = round($shipping_weight+0.5);

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class]->quote();
        }
      }
    }

    function cheapest() {
      if (SHIPPING_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->cheapest();
        }
      }
    }

    function display() {
      if (SHIPPING_MODULES) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          echo $GLOBALS[$class]->display();
        }
      }
    }

    function confirm() {
      global $shipping_cost, $shipping_method;

      if (SHIPPING_MODULES) {
        $confirm_string .= '<input type="hidden" name="shipping_cost" value="' . $shipping_cost . '">' . 
                           '<input type="hidden" name="shipping_method" value="' . $shipping_method . '">';

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          $confirm_string .= $GLOBALS[$class]->confirm();
        }

        return $confirm_string;
      }
    }
  }
//
//
// select (used in checkout_address.php) to select which methods should provide quotes w/options
// quote - used to calculate the rate
// cheapest - used to see who offers lowest rate
// display - used to display the quotes
// confirm - used to capture which method was selected
// check - used by admin to check to see if we are enabled
// install - used by admin to install the method`
//
// The module is required to pass the POST variables is needs for it's own use,
// in addition the following inputs and outputs are needed by the pages:
//
// select
//
// quote
// Inputs - $address_values (postcode and maybe country) $total_count
//          $shipping_weight $shipping_num_boxes
//
//
// confirm
// Outputs - $shipping_cost and $shipping_method
//
//
//
?>

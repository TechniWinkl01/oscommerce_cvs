<?
  // No language file for this file...

  /* Include the configured shipping methods */
  define('DIR_SHIPPING_MODULES', DIR_MODULES . 'shipping/');

  $shipping_count = 0;
  if ($action == 'quote') {
    $shipping_quoted = '';
    $shipping_num_boxes = 1;
    $shipping_weight = $total_weight;
    if ($total_weight > SHIPPING_MAX_WEIGHT) { // Split into many boxes
      $shipping_num_boxes = round(($total_weight/SHIPPING_MAX_WEIGHT)+0.5);
      $shipping_weight = $total_weight/$shipping_num_boxes;
    }
    if ($shipping_weight < SHIPPING_BOX_WEIGHT*SHIPPING_BOX_PADDING) $shipping_weight = $shipping_weight+SHIPPING_BOX_WEIGHT;
    else $shipping_weight = $shipping_weight + ($shipping_weight*SHIPPING_BOX_PADDING/100);
    $shipping_weight = round($shipping_weight+0.5);
  }

  if (SHIPPING_MODULES) {
    $modules = explode(' ', SHIPPING_MODULES);
    while (list(,$value) = each($modules)) {
      include(DIR_SHIPPING_MODULES . $value); 
    }
  }

  if ($action == 'confirm') {
    echo '              <input type="hidden" name="shipping_cost" value=' . $shipping_cost . ">\n";
    echo '              <input type="hidden" name="shipping_method" value=' . $shipping_method . ">\n";
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

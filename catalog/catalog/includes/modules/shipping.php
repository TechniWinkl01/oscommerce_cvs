<?
  /* Include the configured shipping methods */
  define('DIR_SHIPPING_MODULES', DIR_MODULES . 'shipping/');

  // define('SHIPPING_MODULES', 'ups.php flat.php item.php');
  // define('SHIPPING_BOX_WEIGHT', '3');
  // define('SHIPPING_BOX_PADDING', '10');
  // define('SHIPPING_HANDLING', '5.00');
  // define('SHIPPING_FLAT_COST', '5.00');
  // define('SHIPPING_ITEM_COST', '2.50');

  define('SHIPPING_UPS_NAME', 'United Parcel Service'); // It's a trademark, can't translate

  if ($language == 'german') {
    define('SHIPPING_FLAT_NAME', 'Einzelne Kosten');
    define('SHIPPING_FLAT_WAY', 'Beste Weise');
    define('SHIPPING_ITEM_NAME', 'Pro Stück');
    define('SHIPPING_ITEM_WAY', 'Beste Weise');
  } elseif ($language == 'espanol') {
    define('SHIPPING_FLAT_NAME', 'Solo Coste');
    define('SHIPPING_FLAT_WAY', 'La Mejor Manera');
    define('SHIPPING_ITEM_NAME', 'Por artículo');
    define('SHIPPING_ITEM_WAY', 'La Mejor Manera');
  } else {  // english
    define('SHIPPING_FLAT_NAME', 'Flat Rate');
    define('SHIPPING_FLAT_WAY', 'Best Way');
    define('SHIPPING_ITEM_NAME', 'Per Item');
    define('SHIPPING_ITEM_WAY', 'Best Way');
  }

  $shipping_count = 0;
  if ($action == 'quote') {
    $shipping_quoted = '';
    if ($total_weight < SHIPPING_BOX_WEIGHT*SHIPPING_BOX_PADDING) $total_weight = $total_weight+SHIPPING_BOX_PADDING;
    else $total_weight = $total_weight + ($total_weight*100/SHIPPING_BOX_PADDING);
  }

  $modules = explode(' ', SHIPPING_MODULES);
  while (list(,$value) = each($modules)) {
    include(DIR_SHIPPING_MODULES . $value); 
  }

  if ($action == 'confirm') {
    echo '              <input type="hidden" name="shipping_cost" value=' . $shipping_cost . ">\n";
    echo '              <input type="hidden" name="shipping_method" value=' . $shipping_method . ">\n";
  }
//
// There are three stages at this point:
//
// select (used in checkout_address.php) to select which methods should provide quotes w/options
// quote (used in checkout_payment.php) to calculate and display the quotes
// confirm (used in checkout_confirmation.php) to capture which method was selected
//
// The module is required to pass the POST variables is needs for it's own use,
// in addition the following inputs and outputs are needed by the pages:
//
// select
//
//
// quote
// Inputs - $address_values (postcode and maybe country) $total_count and $total_weight
//
//
// confirm
// Outputs - $shipping_cost and $shipping_method
//
//
//
?>

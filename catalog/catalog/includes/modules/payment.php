<?
  if (defined('PAYMENT_MODULES')) {
    
    if ($payment_action == 'PM_VALIDATION') {
      $modules = explode(';', PAYMENT_MODULES);
      while (list(,$value) = each($modules)) {
        include(DIR_PAYMENT_MODULES . $value);
      }
    }
  }
	    
?>
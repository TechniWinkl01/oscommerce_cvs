<?
// $HTTP_xxx_VARS are always set on php4
  if (!is_array($HTTP_GET_VARS)) {
    $HTTP_GET_VARS = array();
  }
  if (!is_array($HTTP_POST_VARS)) {
    $HTTP_POST_VARS = array();
  }
  if (!is_array($HTTP_COOKIE_VARS)) {
    $HTTP_COOKIE_VARS = array();
  }

// handle magic_quotes_gpc turned off.
  if (!get_magic_quotes_gpc()) { 
    if (is_array($HTTP_GET_VARS)) {
      while (list($var, $val) = each($HTTP_GET_VARS)) {
        $HTTP_GET_VARS[$var] = addslashes($val);
      }
    }
    if (is_array($HTTP_POST_VARS)) {
      while (list($var, $val) = each($HTTP_POST_VARS)) {
        $HTTP_POST_VARS[$var] = addslashes($val);
      }
    }
    if (is_array($HTTP_COOKIE_VARS)) {
      while (list($var, $val) = each($HTTP_COOKIE_VARS)) {
        $HTTP_COOKIE_VARS[$var] = addslashes($val);
      }
    }
  }
?>
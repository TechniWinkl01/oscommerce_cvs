<?php
/*
  $Id: compatibility.php,v 1.4 2001/06/09 19:21:25 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License

  Modified by Marco Canini, <m.canini@libero.it>
  - Fixed a bug with arrays in $HTTP_xxx_VARS
*/

////
// Recursively handle magic_quotes_gpc turned off.
// This is due to the possibility of have an array in
// $HTTP_xxx_VARS
// Ie, products attributes
  function do_magic_quotes_gpc(&$ar) {
    if (!is_array($ar)) return;

    while (list($key, $value) = each($ar)) {
      if (is_array($value)) {
        do_magic_quotes_gpc($value);
      } else {
        $ar[$key] = addslashes($value);
      }
    }
  }

// $HTTP_xxx_VARS are always set on php4
  if (!is_array($HTTP_GET_VARS)) $HTTP_GET_VARS = array();
  if (!is_array($HTTP_POST_VARS)) $HTTP_POST_VARS = array();
  if (!is_array($HTTP_COOKIE_VARS)) $HTTP_COOKIE_VARS = array();

// handle magic_quotes_gpc turned off.
  if (!get_magic_quotes_gpc()) {
    do_magic_quotes_gpc($HTTP_GET_VARS);
    do_magic_quotes_gpc($HTTP_POST_VARS);
    do_magic_quotes_gpc($HTTP_COOKIE_VARS);
  }
?>
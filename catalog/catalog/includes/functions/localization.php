<?php
/*
  $Id: localization.php,v 1.5 2001/12/01 19:36:45 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

////
// If no parameter is passed, this function returns all languages and required information such as id, name, language path, etc
// If the language code is given as a parameter, it returns the same information just for that one language
// TABLES: languages
  function tep_get_languages($language = '') {
    if ($language != '') {
      $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " where code = '" . $language . "'");
    } else {
      $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    }
    while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']
                                );
    }

    return $languages_array;
  }

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
  function tep_currency_exists($code) {
    $currency_code = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . $code . "'");
    if (tep_db_num_rows($currency_code)) {
      return $code;
    } else {
      return false;
    }
  }
?>

<?
  class moduleInfo {
    var $code, $description, $keys;

// class constructor
    function moduleInfo($mInfo_array) {
      $this->code = $mInfo_array['code'];

      for ($i=0; $i<sizeof($mInfo_array)-1; $i++) { // minus 1 due to 'code'
        $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description from " . TABLE_CONFIGURATION . " where configuration_key = '" . $mInfo_array[$i] . "'");
        $key_value = tep_db_fetch_array($key_value_query);

        $this->keys[$mInfo_array[$i]]['title'] = $key_value['configuration_title'];
        $this->keys[$mInfo_array[$i]]['value'] = $key_value['configuration_value'];
        $this->keys[$mInfo_array[$i]]['description'] = $key_value['configuration_description'];
      }
    }
  }
?>
<?php
  class moduleInfo {
    var $code, $title, $description, $keys, $status;

// class constructor
    function moduleInfo($mInfo_array) {
      $this->code = $mInfo_array['code'];
      $this->title = $mInfo_array['title'];
      $this->description = $mInfo_array['description'];
      $this->status = $mInfo_array['status'];

      for ($i=0; $i<sizeof($mInfo_array)-4; $i++) {
        $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . $mInfo_array[$i] . "'");
        $key_value = tep_db_fetch_array($key_value_query);

        $this->keys[$mInfo_array[$i]]['title'] = $key_value['configuration_title'];
        $this->keys[$mInfo_array[$i]]['value'] = $key_value['configuration_value'];
        $this->keys[$mInfo_array[$i]]['description'] = $key_value['configuration_description'];
        $this->keys[$mInfo_array[$i]]['use_function'] = $key_value['use_function'];
        $this->keys[$mInfo_array[$i]]['set_function'] = $key_value['set_function'];
      }
    }
  }
?>
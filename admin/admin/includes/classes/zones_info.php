<?php
  class zonesInfo {
    var $id, $country, $name, $code;

// class constructor
    function zonesInfo($zInfo_array) {
      $this->id = $zInfo_array['zone_id'];
      $this->country = $zInfo_array['countries_name'];
      $this->country_id = $zInfo_array['zone_country_id'];
      $this->name = $zInfo_array['zone_name'];
      $this->code = $zInfo_array['zone_code'];
    }
  }
?>
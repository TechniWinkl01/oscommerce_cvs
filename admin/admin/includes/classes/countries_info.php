<?php
  class countriesInfo {
    var $id, $name, $iso_code_2, $iso_code_3, $address_format_id;

// class constructor
    function countriesInfo($cInfo_array) {
      $this->id = $cInfo_array['countries_id'];
      $this->name = $cInfo_array['countries_name'];
      $this->iso_code_2 = $cInfo_array['countries_iso_code_2'];
      $this->iso_code_3 = $cInfo_array['countries_iso_code_3'];
      $this->address_format_id = $cInfo_array['address_format_id'];
    }
  }
?>
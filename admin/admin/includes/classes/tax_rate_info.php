<?php
  class taxRateInfo {
    var $id, $country, $country_id, $zone, $zone_id, $class_title, $class_id, $priority, $rate, $description, $date_added, $last_modified;

// class constructor
    function taxRateInfo($trInfo_array) {
      $this->id = $trInfo_array['tax_rates_id'];
      $this->country = $trInfo_array['countries_name'];
      $this->country_id = $trInfo_array['zone_country_id'];
      $this->zone = $trInfo_array['zone_title'];
      $this->zone_id = $trInfo_array['tax_zone_id'];
      $this->class_title = $trInfo_array['tax_class_title'];
      $this->class_id = $trInfo_array['tax_class_id'];
      $this->priority = $trInfo_array['tax_priority'];
      $this->rate = $trInfo_array['tax_rate'];
      $this->description = $trInfo_array['tax_description'];
      $this->date_added = $trInfo_array['date_added'];
      $this->last_modified = $trInfo_array['last_modified'];
    }
  }
?>
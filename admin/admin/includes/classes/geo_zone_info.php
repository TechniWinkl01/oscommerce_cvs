<?php
  class geoZoneInfo {
    var $id, $title, $priority, $description, $last_modified, $date_added;

// class constructor
    function geoZoneInfo($tcInfo_array) {
      $this->id = $tcInfo_array['geo_zone_id'];
      $this->title = $tcInfo_array['geo_zone_name'];
      $this->priority = $tcInfo_array['geo_zone_priority'];
      $this->description = $tcInfo_array['geo_zone_description'];
      $this->last_modified = $tcInfo_array['last_modified'];
      $this->date_added = $tcInfo_array['date_added'];
    }
  }
  class geoZoneAssociationInfo {
    var $id, $title, $country_id, $zone_id, $geo_zone_id, $last_modified, $date_added;

// class constructor
    function geoZoneAssociationInfo($tzaInfo_array) {
      $this->id = $tzaInfo_array['association_id'];
	  $this->title = $tzaInfo_array['countries_name'];
	  if($tzaInfo_array['zone_name']) $this->title = $this->title . " / " . $tzaInfo_array['zone_name'];
      $this->country_id = $tzaInfo_array['zone_country_id'];
      $this->zone_id = $tzaInfo_array['zone_id'];
      $this->geo_zone_id = $tzaInfo_array['geo_zone_id'];
      $this->last_modified = $tzaInfo_array['last_modified'];
      $this->date_added = $tzaInfo_array['date_added'];
    }
  }
?>
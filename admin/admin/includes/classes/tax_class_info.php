<?php
  class taxClassInfo {
    var $id, $title, $description, $last_modified, $date_added;

// class constructor
    function taxClassInfo($tcInfo_array) {
      $this->id = $tcInfo_array['tax_class_id'];
      $this->title = $tcInfo_array['tax_class_title'];
      $this->description = $tcInfo_array['tax_class_description'];
      $this->last_modified = $tcInfo_array['last_modified'];
      $this->date_added = $tcInfo_array['date_added'];
    }
  }
?>
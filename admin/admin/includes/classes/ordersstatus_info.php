<?php
  class ordersstatusInfo {
    var $id, $language, $name;

// class constructor
    function ordersstatusInfo($osInfo_array) {
      $this->id = $osInfo_array['orders_status_id'];
      $this->language = $osInfo_array['language_id'];
      $this->name = $osInfo_array['orders_status_name'];
    }
  }
?>
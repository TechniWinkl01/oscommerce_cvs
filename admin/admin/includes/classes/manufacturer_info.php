<?php
/*
  $Id: manufacturer_info.php,v 1.4 2001/12/05 09:26:13 jan0815 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class manufacturerInfo {
    var $id, $name, $image, $added, $modified, $location, $products_count;

// class constructor
    function manufacturerInfo($mInfo_array) {
      $this->id = $mInfo_array['manufacturers_id'];
      $this->name = $mInfo_array['manufacturers_name'];
      $this->image = $mInfo_array['manufacturers_image'];
      $this->added = $mInfo_array['date_added'];
      $this->modified = $mInfo_array['last_modified'];
      $this->products_count = $mInfo_array['total'];
    }
  }
?>
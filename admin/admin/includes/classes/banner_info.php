<?php
/*
  $Id: banner_info.php,v 1.6 2001/12/22 21:20:20 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

  class bannerInfo {
    var $id, $title, $url, $image, $group, $html_text, $expires_impressions, $expires_date, $date_scheduled, $date_added, $date_status_change, $status, $clicked, $shown;

// class constructor
    function bannerInfo($bInfo_array) {
      $this->id = $bInfo_array['banners_id'];
      $this->title = $bInfo_array['banners_title'];
      $this->url = $bInfo_array['banners_url'];
      $this->image = $bInfo_array['banners_image'];
      $this->group = $bInfo_array['banners_group'];
      $this->html_text = $bInfo_array['banners_html_text'];
      $this->expires_impressions = $bInfo_array['expires_impressions'];
      $this->expires_date = $bInfo_array['expires_date'];
      $this->date_scheduled = $bInfo_array['date_scheduled'];
      $this->date_added = $bInfo_array['date_added'];
      $this->date_status_change = $bInfo_array['date_status_change'];
      $this->status = $bInfo_array['status'];
      $this->clicked = $bInfo_array['banners_clicked'];
      $this->shown = $bInfo_array['banners_shown'];
    }
  }
?>
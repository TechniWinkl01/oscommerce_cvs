<?
  class bannerInfo {
    var $id, $title, $image, $group, $status, $shown, $clicked, $expires_date, $expires_impressions, $date_status_change;

// class constructor
    function bannerInfo($bInfo_array) {
      $this->id = $bInfo_array['banners_id'];
      $this->title = $bInfo_array['banners_title'];
      $this->image = DIR_WS_CATALOG . $bInfo_array['banners_image'];
      $this->group = $bInfo_array['banners_group'];
      $this->status = $bInfo_array['status'];
      $this->shown = $bInfo_array['banners_shown'];
      $this->clicked = $bInfo_array['banners_clicked'];
      $this->expires_date = $bInfo_array['expires_date'];
      $this->expires_impressions = $bInfo_array['expires_impressions'];
      $this->date_status_change = $bInfo_array['date_status_change'];
    }
  }
?>
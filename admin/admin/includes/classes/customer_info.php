<?php
/*
  $Id: customer_info.php,v 1.3 2001/11/19 12:09:13 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class customerInfo {
    var $id, $name, $email_address, $country, $date_account_created, $date_account_last_modified, $date_last_logon, $number_of_logons, $number_of_reviews;

// class constructor
    function customerInfo($cuInfo_array) {
      $this->id = $cuInfo_array['customers_id'];
      $this->name = $cuInfo_array['customers_firstname'] . ' ' . $cuInfo_array['customers_lastname'];
      $this->email_address = $cuInfo_array['customers_email_address'];
      $this->country = $cuInfo_array['countries_name'];
      $this->date_account_created = $cuInfo_array['date_account_created'];
      $this->date_account_last_modified = $cuInfo_array['date_account_last_modified'];
      $this->date_last_logon = $cuInfo_array['date_last_logon'];
      $this->number_of_logons = $cuInfo_array['number_of_logons'];
      $this->number_of_reviews = $cuInfo_array['number_of_reviews'];
    }
  }
?>
<?php
/*
  $Id: customer.php,v 1.1 2003/11/17 16:51:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class osC_Customer {
    var $is_logged_on,
        $id,
        $gender,
        $first_name,
        $last_name,
        $full_name,
        $email_address,
        $default_address_id,
        $country_id,
        $zone_id;

// class constructor
    function osC_Customer() {
      $this->setIsLoggedOn(false);
    }

// class methods
    function setCustomerData($customer_id = -1) {
      if (is_numeric($customer_id) && ($customer_id > 0)) {
        $customer_query = tep_db_query("select c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_default_address_id, ab.entry_country_id, ab.entry_zone_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab where c.customers_id = '" . (int)$customer_id . "' and c.customers_default_address_id = ab.address_book_id and c.customers_id = ab.customers_id");
        if (tep_db_num_rows($customer_query)) {
          $customer = tep_db_fetch_array($customer_query);

          $this->setIsLoggedOn(true);
          $this->setID($customer_id);
          $this->setGender($customer['customers_gender']);
          $this->setFirstName($customer['customers_firstname']);
          $this->setLastName($customer['customers_lastname']);
          $this->setFullName();
          $this->setEmailAddress($customer['customers_email_address']);
          $this->setCountryID($customer['entry_country_id']);
          $this->setZoneID($customer['entry_zone_id']);
          $this->setDefaultAddressID($customer['customers_default_address_id']);
        }
      }
    }

    function setIsLoggedOn($state) {
      if ($state == true) {
        $this->is_logged_on = true;
      } else {
        $this->is_logged_on = false;
      }
    }

    function isLoggedOn() {
      if ($this->is_logged_on == true) {
        return true;
      }

      return false;
    }

    function isGuest() {
      return !$this->isLoggedOn();
    }

    function setID($id) {
      $this->id = $id;
    }

    function setDefaultAddressID($id) {
      $this->default_address_id = $id;
    }

    function setGender($gender) {
      $this->gender = $gender;
    }

    function setFirstName($firstname) {
      $this->first_name = $firstname;
    }

    function setLastName($lastname) {
      $this->last_name = $lastname;
    }

    function setFullName($fullname = '') {
      if (tep_not_null($fullname)) {
        $this->full_name = $fullname;
      } else {
        $this->full_name = $this->first_name . ' ' . $this->last_name;
      }
    }

    function setEmailAddress($email_address) {
      $this->email_address = $email_address;
    }

    function setCountryID($id) {
      $this->country_id = $id;
    }

    function setZoneID($id) {
      $this->zone_id = $id;
    }
  }
?>

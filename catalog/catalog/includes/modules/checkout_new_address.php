<?php
/*
  $Id: checkout_new_address.php,v 1.2 2003/02/12 23:55:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
    $male = ($gender == 'm') ? true : false;
    $female = ($gender == 'f') ? true : false;
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_GENDER; ?></b></td>
    <td class="main">
<?php
    if ($process == true) {
      if ($gender_error == true) {
        echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('gender');
      }
    } else {
      echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT;
    }
?>
    </td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_FIRST_NAME; ?></b></td>
    <td class="main">
<?php
  if ($process == true) {
    if ($firstname_error == true) {
      echo tep_draw_input_field('firstname') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $firstname . tep_draw_hidden_field('firstname');
    }
  } else {
    echo tep_draw_input_field('firstname') . '&nbsp;' . ENTRY_FIRST_NAME_TEXT;
  }
?>
    </td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_LAST_NAME; ?></b></td>
    <td class="main">
<?php
  if ($process == true) {
    if ($lastname_error == true) {
      echo tep_draw_input_field('lastname') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $lastname . tep_draw_hidden_field('lastname');
    }
  } else {
    echo tep_draw_input_field('lastname') . '&nbsp;' . ENTRY_LAST_NAME_TEXT;
  }
?>
    </td>
  </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_COMPANY; ?></b></td>
    <td class="main">
<?php
    if ($process == true) {
      if ($company_error == true) {
        echo tep_draw_input_field('company') . '&nbsp;' . ENTRY_COMPANY_ERROR;
      } else {
        echo $company . tep_draw_hidden_field('company');
      }
    } else {
      echo tep_draw_input_field('company') . '&nbsp;' . ENTRY_COMPANY_TEXT;
    }
?>
    </td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_STREET_ADDRESS; ?></b></td>
    <td class="main">
<?php
  if ($process == true) {
    if ($street_address_error == true) {
      echo tep_draw_input_field('street_address') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $street_address . tep_draw_hidden_field('street_address');
    }
  } else {
    echo tep_draw_input_field('street_address') . '&nbsp;' . ENTRY_STREET_ADDRESS_TEXT;
  }
?>
    </td>
  </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_SUBURB; ?></b></td>
    <td class="main">
<?php
    if ($process == true) {
      echo $suburb . tep_draw_hidden_field('suburb');
    } else {
      echo tep_draw_input_field('suburb') . '&nbsp;' . ENTRY_SUBURB_TEXT;
    }
?>
    </td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_POST_CODE; ?></b></td>
    <td class="main">
<?php
  if ($process == true) {
    if ($postcode_error == true) {
      echo tep_draw_input_field('postcode') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $postcode . tep_draw_hidden_field('postcode');
    }
  } else {
    echo tep_draw_input_field('postcode') . '&nbsp;' . ENTRY_POST_CODE_TEXT;
  }
?>
    </td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CITY; ?></b></td>
    <td class="main">
<?php
  if ($process == true) {
    if ($city_error == true) {
      echo tep_draw_input_field('city') . '&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $city . tep_draw_hidden_field('city');
    }
  } else {
    echo tep_draw_input_field('city') . '&nbsp;' . ENTRY_CITY_TEXT;
  }
?>
    </td>
  </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_STATE; ?></b></td>
    <td class="main">
<?php
    if ($process == true) {
      $state = tep_get_zone_name($country, $zone_id, $state);
      if ($entry_state_error == true) {
        if ($entry_state_has_zones == true) {
          $zones_array = array();
          $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
          while ($zones_values = tep_db_fetch_array($zones_query)) {
            $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          }
          echo tep_draw_pull_down_menu('state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
        } else {
          echo tep_draw_input_field('state') . '&nbsp;' . ENTRY_STATE_ERROR;
        }
      } else {
        echo $state . tep_draw_hidden_field('zone_id') . tep_draw_hidden_field('state');
      }
    } else {
      echo tep_draw_input_field('state') . '&nbsp;' . ENTRY_STATE_TEXT;
    }
?>
    </td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_COUNTRY; ?></b></td>
    <td class="main">
<?php
  if ($process == true) {
    if ($country_error == true) {
      echo tep_get_country_list('country') . '&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      echo tep_get_country_name($country) . tep_draw_hidden_field('country');
    }
  } else {
    echo tep_get_country_list('country', ((isset($country)) ? $country : STORE_COUNTRY)) . '&nbsp;' . ENTRY_COUNTRY_TEXT;
  }
?>
    </td>
  </tr>
</table>

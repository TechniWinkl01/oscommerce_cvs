<?php
/*
  $Id: account_details.php,v 1.1 2001/06/13 20:12:19 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
<?php
  if (ACCOUNT_GENDER) {
    $male = ($account['customers_gender'] == 'm') ? true : false;
    $female = ($account['customers_gender'] == 'f') ? true : false;
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_GENDER; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp' . FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT; ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('firstname', $account['customers_firstname']) . '&nbsp;' . ENTRY_FIRST_NAME_TEXT; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('lastname', $account['customers_lastname']) . '&nbsp;' . ENTRY_LAST_NAME_TEXT; ?></td>
          </tr>
<?php
  if (ACCOUNT_DOB) {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob'])) . '&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT; ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('email_address', $account['customers_email_address']) . '&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_ADDRESS; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('street_address', $account['customers_street_address']) . '&nbsp;' . ENTRY_STREET_ADDRESS_TEXT; ?></td>
          </tr>
<?
  if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_SUBURB; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('suburb', $account['customers_suburb']) . '&nbsp;' . ENTRY_SUBURB_TEXT; ?></td>
          </tr>
<?
   }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('postcode', $account['customers_postcode']) . '&nbsp;' . ENTRY_POST_CODE_TEXT; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_CITY; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('city', $account['customers_city']) . '&nbsp;' . ENTRY_CITY_TEXT; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">&nbsp;<?php tep_get_country_list("country", $account['customers_country_id'], (ACCOUNT_STATE)?"onChange=\"update_zone(this.form);\"":""); ?>&nbsp;<? echo ENTRY_COUNTRY_TEXT; ?></td>
          </tr>
<?
  if (ACCOUNT_STATE) {
    $customers_state = ($account['customers_state']) ? $account['customers_state'] : JS_STATE_SELECT;
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_STATE; ?></td>
            <td class="main">&nbsp;<?php tep_get_zone_list('zone_id', $account['customers_country_id'], $account['customers_zone_id'], 'onChange="resetStateText(this.form);"'); ?>&nbsp;<? echo ENTRY_STATE_TEXT; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('state', $customers_state, 'onChange="resetZoneSelected(this.form);"') . '&nbsp;' . ENTRY_STATE_TEXT; ?></td>
          </tr>
<?
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_CONTACT; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('telephone', $account['customers_telephone']) . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('fax', $account['customers_fax']) . '&nbsp;' . ENTRY_FAX_NUMBER_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_OPTIONS; ?></td>
  </tr>
<?php
  $newsletter_array = array(array('id' => '1',
                                  'text' => ENTRY_NEWSLETTER_YES),
                            array('id' => '0',
                                  'text' => ENTRY_NEWSLETTER_NO));
?>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_NEWSLETTER; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_pull_down_menu('newsletter', $newsletter_array, $account['customers_newsletter']) . '&nbsp;' . ENTRY_NEWSLETTER_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_PASSWORD; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_PASSWORD; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_password_field('password') . '&nbsp;' . ENTRY_PASSWORD_TEXT; ?></td>
         </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_password_field('confirmation') . '&nbsp;' . ENTRY_PASSWORD_CONFIRMATION_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

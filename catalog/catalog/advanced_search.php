<?php
/*
  $Id: advanced_search.php,v 1.42 2002/01/07 23:17:08 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);

  $location = ' : <a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function check_form() {
  var error_message = "<?php echo JS_ERROR; ?>";
  var error_found = false;
  var error_field;
  var keywords = document.advanced_search.keywords.value;
  var dfrom = document.advanced_search.dfrom.value;
  var dto = document.advanced_search.dto.value;
  var pfrom = document.advanced_search.pfrom.value;
  var pto = document.advanced_search.pto.value;
  var pfrom_float;
  var pto_float;

  if ( (keywords == "" || keywords.length < 1) &&
       (dfrom == ""    || dfrom == "<?php echo DOB_FORMAT_STRING; ?>" || dfrom.length < 1 ) &&
       (dto == ""      || dto   == "<?php echo DOB_FORMAT_STRING; ?>" || dto.length < 1) &&
       (pfrom == ""    || pfrom.length < 1) &&
       (pto == ""      || pto.length < 1) ) {
    error_message = error_message + "<?php echo JS_AT_LEAST_ONE_INPUT; ?>";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }
  
  if (dfrom.length > 0 && dfrom != "<?php echo DOB_FORMAT_STRING; ?>") {
    if (!IsValidDate(dfrom, "<?php echo DOB_FORMAT_STRING; ?>")) {
      error_message = error_message + "<?php echo JS_INVALID_FROM_DATE; ?>";
      error_field = document.advanced_search.dfrom;
      error_found = true;
    }
  }  

  if (dto.length > 0 && dto != "<?php echo DOB_FORMAT_STRING; ?>") {
    if (!IsValidDate(dto, "<?php echo DOB_FORMAT_STRING; ?>")) {
      error_message = error_message + "<?php echo JS_INVALID_TO_DATE; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }  

  if (dfrom.length > 0 && dfrom != "<?php echo DOB_FORMAT_STRING; ?>" && IsValidDate(dfrom, "<?php echo DOB_FORMAT_STRING; ?>") &&
      dto.length > 0 && dto != "<?php echo DOB_FORMAT_STRING; ?>" && IsValidDate(dto, "<?php echo DOB_FORMAT_STRING; ?>") ) {
    if (!CheckDateRange(document.advanced_search.dfrom, document.advanced_search.dto)) {
      error_message = error_message + "<?php echo JS_TO_DATE_LESS_THAN_FROM_DATE; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }
  
  if (pfrom.length > 0) {
    pfrom_float = parseFloat(pfrom);
    if (isNaN(pfrom_float)) {
      error_message = error_message + "<?php echo JS_PRICE_FROM_MUST_BE_NUM; ?>";
      error_field = document.advanced_search.pfrom;
      error_found = true;
    }
  }
  else {
    pfrom_float = 0;
  }

  if (pto.length > 0) {
    pto_float = parseFloat(pto);
    if (isNaN(pto_float)) {
      error_message = error_message + "<?php echo JS_PRICE_TO_MUST_BE_NUM; ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }
  else {
    pto_float = 0;
  }

  if (pfrom.length > 0 && pto.length > 0) {
    if (!isNaN(pfrom_float) && !isNaN(pto_float) && pto_float < pfrom_float) {
      error_message = error_message + "<?php echo JS_PRICE_TO_LESS_THAN_PRICE_FROM; ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }

  if (error_found) {
    alert(error_message);
    error_field.focus();
    return false;
  } else {
    RemoveFormatString(document.advanced_search.dfrom, "<?php echo DOB_FORMAT_STRING; ?>");
    RemoveFormatString(document.advanced_search.dto, "<?php echo DOB_FORMAT_STRING; ?>");
    return true;
  }
}
//--></script>
</head>
<body onload="SetFocus('advanced_search');" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="advanced_search" method="get" action="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false); ?>" onSubmit="return check_form(this);"><?php echo tep_hide_session_id(); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_browse.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="left" width="20%" class="fieldKey"><?php echo ENTRY_CATEGORIES; ?></td>
            <td align="left" colspan="3" class="fieldValue">
<?php
  $selected[0] = ($HTTP_GET_VARS['categories_id']) ? $HTTP_GET_VARS['categories_id'] : '0';
  echo tep_display_cat_select('categories_id', $selected, 1, 0, TEXT_ALL_CATEGORIES) . ' ' .
  tep_draw_selection_field('inc_subcat', 'checkbox', '1', false). ' ' . ENTRY_INCLUDES_SUBCATEGORIES . '</td>';
?>
          </tr>
          <tr>
            <td align="left" width="20%" class="fieldKey"><?php echo ENTRY_MANUFACTURER; ?></td>
            <td align="left" colspan="3" class="fieldValue">
              <select name="manufacturers_id">
                <option value="" selected><?php echo TEXT_ALL_MANUFACTURERS; ?>
<?php
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
    echo '<option value="' . $manufacturers_values['manufacturers_id'] . '">' . $manufacturers_values['manufacturers_name'] . "\n";
  }
?>
              </select>
            </td>
          </tr>
          <tr>
            <td align="left" class="fieldKey"><?php echo ENTRY_KEYWORDS; ?></td>
            <td align="left" colspan="3" class="fieldValue"><?php echo tep_draw_input_field('keywords', '' , 'maxlength="40"', true) . ' ' . ENTRY_KEYWORDS_TEXT;?></td>
          </tr>
          <tr>
            <td class="fieldValue">&nbsp;</td>
            <td colspan="3" class="fieldValue"><?php echo tep_draw_selection_field('search_in_description', 'checkbox', '1', false) . ' ' . TEXT_SEARCH_IN_DESCRIPTION; ?></td>
          </tr>
          <tr>
            <td align="left" class="fieldKey"><?php echo ENTRY_DATE_ADDED_FROM; ?></td>
            <td align="left" class="fieldValue"><?php echo tep_draw_input_field('dfrom', '' . DOB_FORMAT_STRING . '' , 'size="11" maxlength="10" onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"', true);?></td>
            <td align="left" class="fieldKey"><?php echo ENTRY_TO; ?></td>
            <td align="left" class="fieldValue"><?php echo tep_draw_input_field('dto', '' . DOB_FORMAT_STRING . '' , 'size="11" maxlength="10" onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"', true) . ' ' . ENTRY_DATE_ADDED_TEXT; ?></td>
          </tr>
          <tr>
            <td align="left" class="fieldKey"><?php echo ENTRY_PRICE_FROM; ?></td>
            <td align="left" class="fieldValue"><?php echo tep_draw_input_field('pfrom', '' , 'size="11" maxlength="9"', true);?></td>
            <td align="left" class="fieldKey"><?php echo ENTRY_TO; ?></td>
            <td align="left" class="fieldValue"><?php echo tep_draw_input_field('pto', '' , 'size="11" maxlength="9"', true);?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php
  if ($HTTP_GET_VARS['errorno']) {
    if (($HTTP_GET_VARS['errorno'] & 1) == 1) {
      echo nl2br(JS_AT_LEAST_ONE_INPUT);
    }
    if (($HTTP_GET_VARS['errorno'] & 10) == 10) {
      echo nl2br(JS_INVALID_FROM_DATE);
    }
    if (($HTTP_GET_VARS['errorno'] & 100) == 100) {
      echo nl2br(JS_INVALID_TO_DATE);
    }
    if (($HTTP_GET_VARS['errorno'] & 1000) == 1000) {
      echo nl2br(JS_TO_DATE_LESS_THAN_FROM_DATE);
    }
    if (($HTTP_GET_VARS['errorno'] & 10000) == 10000) {
      echo nl2br(JS_PRICE_FROM_MUST_BE_NUM);
    }
    if (($HTTP_GET_VARS['errorno'] & 100000) == 100000) {
      echo nl2br(JS_PRICE_TO_MUST_BE_NUM);
    }
    if (($HTTP_GET_VARS['errorno'] & 1000000) == 1000000) {
      echo nl2br(JS_PRICE_TO_LESS_THAN_PRICE_FROM);
    }
    if (($HTTP_GET_VARS['errorno'] & 10000000) == 10000000) {
      echo nl2br(JS_INVALID_KEYWORDS);
    }
  } else {
    new infoBoxHeading(array(array('text' => TEXT_ADVANCED_SEARCH_TIPS_HEADING)), true, false);
    new infoBox(array(array('text' => TEXT_ADVANCED_SEARCH_TIPS)));
  }
?>
        </td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><?echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function check_form() {
  var error_message = "<? echo JS_ERROR; ?>";
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
       (dfrom == ""    || dfrom == "<? echo DOB_FORMAT_STRING; ?>" || dfrom.length < 1 ) &&
       (dto == ""      || dto   == "<? echo DOB_FORMAT_STRING; ?>" || dto.length < 1) &&
       (pfrom == ""    || pfrom.length < 1) &&
       (pto == ""      || pto.length < 1) ) {
    error_message = error_message + "<? echo JS_AT_LEAST_ONE_INPUT; ?>";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }
  
  if (dfrom.length > 0 && dfrom != "<? echo DOB_FORMAT_STRING; ?>") {
    if (!IsValidDate(dfrom, "<? echo DOB_FORMAT_STRING; ?>")) {
      error_message = error_message + "<? echo JS_INVALID_FROM_DATE; ?>";
      error_field = document.advanced_search.dfrom;
      error_found = true;
    }
  }  

  if (dto.length > 0 && dto != "<? echo DOB_FORMAT_STRING; ?>") {
    if (!IsValidDate(dto, "<? echo DOB_FORMAT_STRING; ?>")) {
      error_message = error_message + "<? echo JS_INVALID_TO_DATE; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }  

  if (dfrom.length > 0 && dfrom != "<? echo DOB_FORMAT_STRING; ?>" && IsValidDate(dfrom, "<? echo DOB_FORMAT_STRING; ?>") &&
      dto.length > 0 && dto != "<? echo DOB_FORMAT_STRING; ?>" && IsValidDate(dto, "<? echo DOB_FORMAT_STRING; ?>") ) {
    if (!CheckDateRange(document.advanced_search.dfrom, document.advanced_search.dto)) {
      error_message = error_message + "<? echo JS_TO_DATE_LESS_THAN_FROM_DATE; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }
  
  if (pfrom.length > 0) {
    pfrom_float = parseFloat(pfrom);
    if (isNaN(pfrom_float)) {
      error_message = error_message + "<? echo JS_PRICE_FROM_MUST_BE_NUM; ?>";
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
      error_message = error_message + "<? echo JS_PRICE_TO_MUST_BE_NUM; ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }
  else {
    pto_float = 0;
  }

  if (pfrom.length > 0 && pto.length > 0) {
    if (!isNaN(pfrom_float) && !isNaN(pto_float) && pto_float < pfrom_float) {
      error_message = error_message + "<? echo JS_PRICE_TO_LESS_THAN_PRICE_FROM; ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }

  if (error_found) {
    alert(error_message);
    error_field.focus();
    return false;
  } else {
    RemoveFormatString(document.advanced_search.dfrom, "<? echo DOB_FORMAT_STRING; ?>");
    RemoveFormatString(document.advanced_search.dto, "<? echo DOB_FORMAT_STRING; ?>");
    return true;
  }
}
//--></script>
</head>
<body onload="SetFocus('advanced_search');" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="advanced_search" method="get" action="<?echo tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL'); ?>" onSubmit="return check_form(this);"><? if (SID) echo tep_hide_fields(array(tep_session_name())); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" nowrap>&nbsp;<?echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right" nowrap>&nbsp;<?echo tep_image(DIR_WS_IMAGES . 'table_background_browse.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?echo tep_black_line(); ?></td>
      </tr>

      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="left" width="20%" class="fieldKey" nowrap>&nbsp;<?echo ENTRY_CATEGORIES; ?>&nbsp;</td>
            <td align="left" colspan="3" class="fieldValue" nowrap>
<?
if ($HTTP_GET_VARS['categories_id'])
  $selected[0] = $HTTP_GET_VARS['categories_id'];
else
  $selected[0] = 0;
tep_display_cat_select("categories_id",$selected, 1, 0, TEXT_ALL_CATEGORIES);
?>
            &nbsp;&nbsp;(&nbsp;<input type="checkbox"  name="inc_subcat" value="1">&nbsp;<?echo ENTRY_INCLUDES_SUBCATEGORIES; ?>&nbsp;)&nbsp;</td>
          </tr>
          <tr>
            <td align="left" width="20%" class="fieldKey" nowrap>&nbsp;<?echo ENTRY_MANUFACTURER; ?>&nbsp;</td>
            <td align="left" colspan="3" class="fieldValue" nowrap>
              <select name="manufacturers_id">
                <option value="" selected><? echo TEXT_ALL_MANUFACTURERS; ?>
<?  
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from manufacturers order by manufacturers_name");
  while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
    echo '<option value="' . $manufacturers_values['manufacturers_id'] . '">' . $manufacturers_values['manufacturers_name'] . "\n";
  }
?>
              </select>&nbsp;
            </td>
          </tr>
          <tr>
            <td align="left" class="fieldKey" nowrap>&nbsp;<?echo ENTRY_KEYWORDS; ?>&nbsp;</td>
            <td align="left" colspan="3" class="fieldValue" nowrap><input type="text" name="keywords" size="40" <?if ($HTTP_GET_VARS['keywords']) echo 'value="' . htmlspecialchars(StripSlashes($HTTP_GET_VARS['keywords'])) . '"';?>>&nbsp;<?echo ENTRY_KEYWORDS_TEXT; ?>&nbsp;</td>
          </tr>
          <tr>
            <td align="left" class="fieldKey" nowrap>&nbsp;</td>
            <td align="left" colspan="3" class="fieldValue" nowrap>&nbsp;(&nbsp;<input type="checkbox" name="search_in_description" value="1">&nbsp;<? echo TEXT_SEARCH_IN_DESCRIPTION; ?>&nbsp;)&nbsp;</td>
          </tr>
          <tr>
            <td align="left" class="fieldKey" nowrap>&nbsp;<?echo ENTRY_DATE_ADDED_FROM; ?>&nbsp;</td>
            <td align="left" class="fieldValue" nowrap><input type="text" name="dfrom" value="<?if ($HTTP_GET_VARS['dfrom']) echo $HTTP_GET_VARS['dfrom']; else echo DOB_FORMAT_STRING; ?>" size="10" maxlength="10" onFocus="RemoveFormatString(this, '<?echo DOB_FORMAT_STRING; ?>');">&nbsp;</td>
            <td align="left" class="fieldKey" nowrap>&nbsp;<?echo ENTRY_TO; ?>&nbsp;</td>
            <td align="left" class="fieldValue" nowrap><input type="text" name="dto" value="<?if ($HTTP_GET_VARS['dto']) echo $HTTP_GET_VARS['dto']; else echo DOB_FORMAT_STRING; ?>" size="10" maxlength="10" onFocus="RemoveFormatString(this, '<?echo DOB_FORMAT_STRING; ?>');">&nbsp;<?echo ENTRY_DATE_ADDED_TEXT; ?></td>
          </tr>
          <tr>
            <td align="left" class="fieldKey" nowrap>&nbsp;<?echo ENTRY_PRICE_FROM; ?>&nbsp;</td>
            <td align="left" class="fieldValue" nowrap><input type="text" name="pfrom" size="9" maxlength="9" <?if ($HTTP_GET_VARS['pfrom']) echo 'value="' . $HTTP_GET_VARS['pfrom'] . '"';?>>&nbsp;</td>
            <td align="left" class="fieldKey" nowrap>&nbsp;<?echo ENTRY_TO; ?>&nbsp;</td>
            <td align="left" class="fieldValue" nowrap><input type="text" name="pto" size="9" maxlength="9" <?if ($HTTP_GET_VARS['pfrom']) echo 'value="' . $HTTP_GET_VARS['pfrom'] . '"';?>>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main" nowrap><? echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td><?echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main">
<?
  if ($HTTP_GET_VARS['errorno']) {
    if (($HTTP_GET_VARS['errorno'] & 1) == 1) {
      echo str_replace ('\n', '<br>', JS_AT_LEAST_ONE_INPUT);
    }
    if (($HTTP_GET_VARS['errorno'] & 10) == 10) {
      echo str_replace ('\n', '<br>', JS_INVALID_FROM_DATE);
    }
    if (($HTTP_GET_VARS['errorno'] & 100) == 100) {
      echo str_replace ('\n', '<br>', JS_INVALID_TO_DATE);
    }
    if (($HTTP_GET_VARS['errorno'] & 1000) == 1000) {
      echo str_replace ('\n', '<br>', JS_TO_DATE_LESS_THAN_FROM_DATE);
    }
    if (($HTTP_GET_VARS['errorno'] & 10000) == 10000) {
      echo str_replace ('\n', '<br>', JS_PRICE_FROM_MUST_BE_NUM);
    }
    if (($HTTP_GET_VARS['errorno'] & 100000) == 100000) {
      echo str_replace ('\n', '<br>', JS_PRICE_TO_MUST_BE_NUM);
    }
    if (($HTTP_GET_VARS['errorno'] & 1000000) == 1000000) {
      echo str_replace ('\n', '<br>', JS_PRICE_TO_LESS_THAN_PRICE_FROM);
    }
    if (($HTTP_GET_VARS['errorno'] & 10000000) == 10000000) {
      echo str_replace ('\n', '<br>', JS_INVALID_KEYWORDS);
    }
  }
  else {
    if (ADVANCED_SEARCH_DISPLAY_TIPS) {
      new infoBox(array(array('text' => TEXT_ADVANCED_SEARCH_TIPS)));
    }
  }
?>
        </td>
      </tr>
      <tr>
        <td><?echo tep_black_line(); ?></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_right.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="advanced_search" method="get" action="<?=tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL');?>" onSubmit="return check_form(this);"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_browse.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>

      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="left" width="20%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_CATEGORIES;?>&nbsp;</font></td>
            <td align="left" colspan="3" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">
<?
$selected[0] = 0;
tep_display_cat_select("categories_id",$selected, 1, 0, TEXT_ALL_CATEGORIES);
?>
            &nbsp;&nbsp;(&nbsp;<input type="checkbox"  name="inc_subcat" value="1">&nbsp;<?echo ENTRY_INCLUDES_SUBCATEGORIES;?>&nbsp;)&nbsp;</font></td>
          </tr>
          <tr>
            <td align="left" width="20%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_MANUFACTURER;?>&nbsp;</font></td>
            <td align="left" colspan="3" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">
              <select name="manufacturers_id">
                <option value="" selected><? echo TEXT_ALL_MANUFACTURERS; ?>
<?  
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from manufacturers order by manufacturers_name");
  while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
    echo '<option value="' . $manufacturers_values['manufacturers_id'] . '">' . $manufacturers_values['manufacturers_name'] . "\n";
  }
?>
              </select>&nbsp;</font>
            </td>
          </tr>
          <tr>
            <td align="left" width="20%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_KEYWORDS;?>&nbsp;</font></td>
            <td align="left" colspan="3" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>"><input type="text" name="keywords" size="40" maxlength="40">&nbsp;<?=ENTRY_KEYWORDS_TEXT;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="left" width="20%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_DATE_ADDED_FROM;?>&nbsp;</font></td>
            <td align="left" width="10%" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>"><input type="text" name="dfrom" value="<?=DOB_FORMAT_STRING;?>" size="10" maxlength="10" onFocus="RemoveFormatString(this, '<?echo DOB_FORMAT_STRING;?>');">&nbsp;</font></td>
            <td align="left" width="5%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_TO;?>&nbsp;</font></td>
            <td align="left" width="65%" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>"><input type="text" name="dto" value="<?=DOB_FORMAT_STRING;?>" size="10" maxlength="10" onFocus="RemoveFormatString(this, '<?echo DOB_FORMAT_STRING;?>');">&nbsp;<?echo ENTRY_DATE_ADDED_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="left" width="20%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_PRICE_FROM;?>&nbsp;</font></td>
            <td align="left" width="10%" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>"><input type="text" name="pfrom" size="9" maxlength="9">&nbsp;</font></td>
            <td align="left" width="5%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_TO;?>&nbsp;</font></td>
            <td align="left" width="65%" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>"><input type="text" name="pto" size="9" maxlength="9">&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="4"><br></td>
          </tr>
          <tr>
            <td align="left" width="20%" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?echo ENTRY_SORT_BY;?>&nbsp;</font></td>
            <td align="left" colspan="3" nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">
              <select name="sortby">
                <option value="1"><?echo TEXT_CATEGORY_NAME;?>
                <option value="2"><?echo TEXT_MANUFACTURER_NAME;?>
                <option value="3" selected><?echo TEXT_PRODUCT_NAME;?>
                <option value="4"><?echo TEXT_PRICE;?>
              </select>&nbsp;</font>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_done.gif', '53', '24', '0', TEXT_PERFORM_ADVANCED_SEARCH);?>&nbsp;&nbsp;</font></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_right.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
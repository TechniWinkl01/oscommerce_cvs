<?php
/*
  $Id: products_attributes.php,v 1.15 2001/06/05 12:45:41 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php'); 
  $languages = tep_get_languages();

  if ($HTTP_GET_VARS['action']) {
    switch($HTTP_GET_VARS['action']) {
      case 'add_product_options':
        for ($i = 0; $i < sizeof($languages); $i ++) {
          $option_name = $HTTP_POST_VARS['option_name'];
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id) values ('" . $HTTP_POST_VARS['products_options_id'] . "', '" . $option_name[$languages[$i]['id']] . "', '" . $languages[$i]['id'] . "')");
        }
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page); 
        break;
      case 'add_product_option_values':
        for ($i = 0; $i < sizeof($languages); $i ++) {
          $value_name = $HTTP_POST_VARS['value_name'];
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . $HTTP_POST_VARS['value_id'] . "', '" . $languages[$i]['id'] . "', '" . $value_name[$languages[$i]['id']] . "')");
        }
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . $HTTP_POST_VARS['option_id'] . "', '" . $HTTP_POST_VARS['value_id'] . "')");
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page);
        break;
      case 'add_product_attributes':
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['options_id'] . "', '" . $HTTP_POST_VARS['values_id'] . "', '" . $HTTP_POST_VARS['value_price'] . "', '" . $HTTP_POST_VARS['price_prefix'] . "')");
        $products_attributes_id = tep_db_insert_id();
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page);
        break;
      case 'update_option_name':
        for ($i = 0; $i < sizeof($languages); $i ++) {
          $option_name = $HTTP_POST_VARS['option_name'];
          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . $option_name[$languages[$i]['id']] . "' where products_options_id = '" . $HTTP_POST_VARS['option_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
        }
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page);
        break;
      case 'update_value':
        for ($i = 0; $i < sizeof($languages); $i ++) {
          $value_name = $HTTP_POST_VARS['value_name'];
          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . $value_name[$languages[$i]['id']] . "' where products_options_values_id = '" . $HTTP_POST_VARS['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
        }
        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . $HTTP_POST_VARS['option_id'] . "', products_options_values_id = '" . $HTTP_POST_VARS['value_id'] . "'  where products_options_values_to_products_options_id = '" . $HTTP_POST_VARS['value_id'] . "'");
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page);
        break;
      case 'update_product_attribute':
        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $HTTP_POST_VARS['products_id'] . "', options_id = '" . $HTTP_POST_VARS['options_id'] . "', options_values_id = '" . $HTTP_POST_VARS['values_id'] . "', options_values_price = '" . $HTTP_POST_VARS['value_price'] . "', price_prefix = '" . $HTTP_POST_VARS['price_prefix'] . "' where products_attributes_id = '" . $HTTP_POST_VARS['attribute_id'] . "'");
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page);
        break;
      case 'delete_option':
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $HTTP_GET_VARS['option_id'] . "'");
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, '');
        break;
      case 'delete_value':
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "'");
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, '');
        break;
      case 'delete_attribute':
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $HTTP_GET_VARS['attribute_id'] . "'");
        tep_redirect(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . '&value_page=' . $value_page);
        break;
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function checkFormOpt() {
  var error_message = "<? echo JS_ERROR; ?>";
  var error = 0;
  var option_name = document.options.option_name.value;
  
  if (option_name.length < 1) {
    error_message = error_message + "<? echo JS_OPTIONS_OPTION_NAME; ?>";
    error = 1;
  }
  
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

function checkFormVal() {
  var error_message = "<? echo JS_ERROR; ?>";
  var error = 0;
  var value_name = document.values.value_name.value;
  
  if (value_name.length < 1) {
    error_message = error_message + "<? echo JS_OPTIONS_VALUE_NAME; ?>";
    error = 1;
  }
  
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<? echo FILENAME_PRODUCTS_ATTRIBUTES . '?option_page=';
	if (!$HTTP_GET_VARS['option_page']) {
	$option_page = 1;
	} else {
	$option_page = $HTTP_GET_VARS['option_page'];
	}
	echo $option_page; ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}

function checkFormAtrib() {
  var error_message = "<? echo JS_ERROR; ?>";
  var error = 0;
  var price = document.attributes.value_price.value;
  var price_prefix = document.attributes.price_prefix.value;
  
  if (price.length < 1) {
    error_message = error_message + "<? echo JS_OPTIONS_VALUE_PRICE; ?>";
    error = 1;
  }
  if (price_prefix.length < 1) {
    error_message = error_message + "<? echo JS_OPTIONS_VALUE_PRICE_PREFIX; ?>";
    error = 1;
  }
  
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top" width="50%">
<!-- options //-->
<table width="100%" border="0" cellspacing="0" cellpadding="2">

<?
  if ($HTTP_GET_VARS['action'] == 'delete_product_option') { // delete product option
    $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $HTTP_GET_VARS['option_id'] . "' and language_id = '" . $languages_id . "'");
    $options_values = tep_db_fetch_array($options);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo $options_values['products_options_name']; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '1', '70', '0', ''); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100% " cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
    $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . $languages_id . "' and pd.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . $HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
          <tr>
            <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PRODUCT; ?>&nbsp;</b></font></td>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
        if (floor($rows/2) == ($rows/2)) {
          echo '          <tr bgcolor="#ffffff">' . "\n";
        } else {
          echo '          <tr bgcolor="#f4f7fd">' . "\n";
        }
?>
            <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_values['products_id']; ?>&nbsp;</font></td>
            <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_values['products_name']; ?>&nbsp;</font></td>
            <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_values['products_options_values_name']; ?>&nbsp;</font></td>
          </tr>
<?
      }
?>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_WARNING_OF_DELETE; ?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', ' cancel '); ?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_OK_TO_DELETE; ?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $HTTP_GET_VARS['option_id'], 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_delete.gif', '50', '14', '0', 'Delete'); ?></a>&nbsp;&nbsp;&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', 'Cancel'); ?></a>&nbsp;&nbsp;</font></td>
          </tr>
    
<?
    }
?>
  	</td></tr></table>
<?
	} else {
	  if ($HTTP_GET_VARS['option_order_by']) {
      $option_order_by = $HTTP_GET_VARS['option_order_by'];
    } else {
      $option_order_by = 'products_options_id';
    }
?>
      <tr>
        <td colspan="2"><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE_OPT; ?>&nbsp;</font></td>
        <td align="center"><br><form name="option_order_by" action="<? echo FILENAME_PRODUCTS_ATTRIBUTES; ?>"><select name="selected" onChange="go_option()"><option value="products_options_id"<? if ($option_order_by == 'products_options_id') { echo ' SELECTED'; } ?>>Option ID</option><option value="products_options_name"<? if ($option_order_by == 'products_options_name') { echo ' SELECTED'; } ?>>Option Name</option></select></form></td>
      </tr>
      <tr>
        <td colspan=3><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">
<? 
    $per_page = MAX_ROW_LISTS_OPTIONS; 
    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by " . $option_order_by;
    if (!$option_page) { 
      $option_page = 1; 
    } 
    $prev_option_page = $option_page - 1; 
    $next_option_page = $option_page + 1; 

    $option_query = tep_db_query($options); 

    $option_page_start = ($per_page * $option_page) - $per_page; 
    $num_rows = tep_db_num_rows($option_query); 

    if ($num_rows <= $per_page) { 
      $num_pages = 1; 
    } else if (($num_rows % $per_page) == 0) { 
      $num_pages = ($num_rows / $per_page); 
    } else { 
      $num_pages = ($num_rows / $per_page) + 1; 
    }
    $num_pages = (int) $num_pages; 

    if (($option_page > $num_pages) || ($option_page < 0)) { 
      die("You have specified an invalid page number"); 
    } 

    $options = $options . " LIMIT $option_page_start, $per_page"; 

    // Previous 
    if ($prev_option_page)  { 
      echo "<a href=\"$PHP_SELF?page=$prev_option_page\"><< </a> | "; 
    }

    for ($i = 1; $i <= $num_pages; $i++) { 
      if ($i != $option_page) { 
         echo " <a href=\"$PHP_SELF?option_page=$i\">$i</a> | "; 
      } else { 
         echo " <b><font color=red>$i</font><font color=black></b> |"; 
      } 
    } 
    
    // Next 
    if ($option_page != $num_pages) { 
      echo " <a href=\"$PHP_SELF?option_page=$next_option_page\"> >></a>"; 
    } 
    echo '</td>'; 
?> 		
          </tr>
	  <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</b></font></td>
            <td align="center"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if (($HTTP_GET_VARS['action'] == 'update_option') && ($HTTP_GET_VARS['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name', 'NONSSL') . '" method="post" onSubmit="return checkFormOption();">';

        $inputs = '';
        for ($i = 0; $i < sizeof($languages); $i ++) {
          $option_name = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $option_name = tep_db_fetch_array($option_name);
          $inputs .= '&nbsp;' . $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" value="' . $option_name['products_options_name'] . '">&nbsp;<br>';
        }
?>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<? echo $options_values['products_options_id']; ?>">&nbsp;</font></td>
	          <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo $inputs; ?></font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE); ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL); ?></a>&nbsp;</font></td>
<?
        echo '</form>' . "\n";
?>
          </tr>
<?
      } else {
?>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $options_values["products_options_id"]; ?>&nbsp;</font></td>
            <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $options_values["products_options_name"]; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'], 'NONSSL') , '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE); ?></a>&nbsp;</font></td>
          </tr>
<?
      }
      $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
?>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
    if (!$HTTP_GET_VARS['action'] == 'update_option') {
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      } else {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      }
      echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options', 'NONSSL') . '" method="post" onSubmit="return checkFormOpt();"><input type="hidden" name="products_options_id" value="' . $next_id . '">';

      $inputs = '';
      for ($i = 0; $i < sizeof($languages); $i ++) {
        $inputs .= '&nbsp;' . $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20">&nbsp;<br>';
      }
?>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $next_id; ?>&nbsp;</font></td>
            <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo $inputs; ?></font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT); ?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
    }
  }
?>	  
</table>
<!-- options eof //-->
</td><td valign="top" width="50%">
<!-- value //-->
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<?
  if ($HTTP_GET_VARS['action'] == 'delete_option_value') { // delete product option value
    $values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "' and language_id = '" . $languages_id . "'");
    $values_values = tep_db_fetch_array($values);
?>
      <tr>
        <td colspan="3"><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo $values_values['products_options_values_name']; ?>&nbsp;</font></td>
        <td>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '1', '70', '0', ''); ?>&nbsp;</td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
    $products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and po.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . $HTTP_GET_VARS['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
          <tr>
            <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PRODUCT; ?>&nbsp;</b></font></td>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
        if (floor($rows/2) == ($rows/2)) {
          echo '          <tr bgcolor="#ffffff">' . "\n";
        } else {
          echo '          <tr bgcolor="#f4f7fd">' . "\n";
        }
?>
            <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_values['products_id']; ?>&nbsp;</font></td>
            <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_values['products_name']; ?>&nbsp;</font></td>
            <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_values['products_options_name']; ?>&nbsp;</font></td>
          </tr>
<?
      }
?>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_WARNING_OF_DELETE; ?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', ' cancel '); ?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_OK_TO_DELETE; ?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $HTTP_GET_VARS['value_id'], 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_delete.gif', '50', '14', '0', 'Delete'); ?></a>&nbsp;&nbsp;&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', 'Cancel'); ?></a>&nbsp;&nbsp;</font></td>
          </tr>
    
<?
    }
?>
  	</td></tr></table>
<? 
  } else {
?>
      <tr>
        <td colspan="3"><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE_VAL; ?>&nbsp;</font></td>
        <td>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '1', '56', '0', ''); ?>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">
<? 
    $per_page = MAX_ROW_LISTS_OPTIONS; 
    $values = ("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " order by products_options_values_id"); 
    if (!$value_page) { 
      $value_page = 1; 
    } 
    $prev_value_page = $value_page - 1; 
    $next_value_page = $value_page + 1; 

    $value_query = tep_db_query($values); 

    $value_page_start = ($per_page * $value_page) - $per_page; 
    $num_rows = tep_db_num_rows($value_query); 

    if ($num_rows <= $per_page) { 
      $num_pages = 1; 
    } else if (($num_rows % $per_page) == 0) { 
      $num_pages = ($num_rows / $per_page); 
    } else { 
      $num_pages = ($num_rows / $per_page) + 1; 
    } 
    $num_pages = (int) $num_pages; 
    
    if (($value_page > $num_pages) || ($value_page < 0)) { 
      die("You have specified an invalid page number"); 
    } 
    
    $values = $values . " LIMIT $value_page_start, $per_page"; 
    
    // Previous 
    if ($prev_value_page)  { 
      echo "<a href=\"$PHP_SELF?option_order_by=$option_order_by&value_page=$prev_value_page\"><< </a> | "; 
    } 
    
    for ($i = 1; $i <= $num_pages; $i++) { 
      if ($i != $value_page) { 
         echo " <a href=\"$PHP_SELF?option_order_by=$option_order_by&value_page=$i\">$i</a> | "; 
      } else { 
         echo " <b><font color=red>$i</font><font color=black></b> |"; 
      } 
    }
    
    // Next 
    if ($value_page != $num_pages) { 
      echo " <a href=\"$PHP_SELF?option_order_by=$option_order_by&value_page=$next_value_page\"> >></a>"; 
    } 
    echo '</td>'; 
?> 		
          </tr>
	        <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</b></font></td>
      			<td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</b></font></td>
            <td align="center"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
    $values = tep_db_query($values);
    while ($values_values = tep_db_fetch_array($values)) {
      $options_name = tep_options_name($values_values['products_options_id']);
      $values_name = tep_values_name($values_values['products_options_values_id']);
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if (($HTTP_GET_VARS['action'] == 'update_option_value') && ($HTTP_GET_VARS['value_id'] == $values_values['products_options_values_id'])) {
        echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value', 'NONSSL') . '" method="post" onSubmit="return checkFormValue();">';

        $inputs = '';
        for ($i = 0; $i < sizeof($languages); $i ++) {
          $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $values_values['products_options_values_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $value_name = tep_db_fetch_array($value_name);
          $inputs .= '&nbsp;' . $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . $value_name['products_options_values_name'] . '">&nbsp;<br>';
        }
?>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<? echo $values_values['products_options_values_id']; ?>">&nbsp;</font></td>
			<td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo "\n"; ?><select name="option_id"><?
        $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
        while ($options_values = tep_db_fetch_array($options)) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) { 
            echo ' selected';
          }
          echo '>' . $options_values['products_options_name'] . '</option>';
        } ?></select>&nbsp;</font></td>
			<td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo $inputs; ?></font></td>
            <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE); ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL); ?></a>&nbsp;</font></td>
<?
        echo '</form>' . "\n";
?>
          </tr>
<?
      } else {
?>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $values_values["products_options_values_to_products_options_id"]; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $options_name; ?>&nbsp;</font></td>
	    	    <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $values_name; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&value_page=' . $HTTP_GET_VARS['value_page'], 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_MODIFY); ?></a>&nbsp;&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'], 'NONSSL') , '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE); ?></a>&nbsp;</font></td>
          </tr>
<?
      }
      $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
      $max_values_id_values = tep_db_fetch_array($max_values_id_query);
      $next_id = $max_values_id_values['next_id'];
    }
?>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
    if (!$HTTP_GET_VARS['action'] == 'update_value') {
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      } else {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      }
      echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values', 'NONSSL') . '" method="post" onSubmit="return checkFormVal();">';
?>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $next_id; ?>&nbsp;</font></td>
			<td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<select name="option_id">
<?
      $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
      }

      $inputs = '';
      for ($i = 0; $i < sizeof($languages); $i ++) {
        $inputs .= '&nbsp;' . $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br>';
      }
?></select>&nbsp;</font></td>
            <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><input type="hidden" name="value_id" value="<? echo $next_id; ?>"><? echo $inputs; ?></font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT); ?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
    }
  }
?>	  
</table></td></tr></table>
<!-- option value eof //-->
</td></tr> 
<!-- products_attributes //-->  
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE_ATRIB; ?>&nbsp;</font></td>
            <td>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr><td colspan=7><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">
<? 
   $per_page = MAX_ROW_LISTS_OPTIONS; 
   $attributes = ("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " order by products_attributes_id"); 
   if (!$attribute_page) 
    { 
      $attribute_page = 1; 
    } 
   $prev_attribute_page = $attribute_page - 1; 
   $next_attribute_page = $attribute_page + 1; 
    
   $attribute_query = tep_db_query($attributes); 
    
   $attribute_page_start = ($per_page * $attribute_page) - $per_page; 
   $num_rows = tep_db_num_rows($attribute_query); 
    
   if ($num_rows <= $per_page) { 
      $num_pages = 1; 
   } else if (($num_rows % $per_page) == 0) { 
      $num_pages = ($num_rows / $per_page); 
   } else { 
      $num_pages = ($num_rows / $per_page) + 1; 
   } 
   $num_pages = (int) $num_pages; 
    
   if (($attribute_page > $num_pages) || ($attribute_page < 0)) { 
      die("You have specified an invalid page number");
   } 
    
   $attributes = $attributes . " LIMIT $attribute_page_start, $per_page"; 
    
   // Previous 
   if ($prev_attribute_page)  { 
      echo "<a href=\"$PHP_SELF?attribute_page=$prev_attribute_page\"><< </a> | "; 
   } 
    
   for ($i = 1; $i <= $num_pages; $i++) { 
      if ($i != $attribute_page) { 
         echo " <a href=\"$PHP_SELF?attribute_page=$i\">$i</a> | "; 
      } else { 
         echo " <b><font color=red>$i</font><font color=black></b> |"; 
      } 
   } 
    
   // Next 
   if ($attribute_page != $num_pages) { 
      echo " <a href=\"$PHP_SELF?attribute_page=$next_attribute_page\"> >></a>"; 
   } 
   echo '</td></tr>'; 
    
?> 		
          <tr>
            <td colspan="7"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
            <td align="center"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PRODUCT; ?>&nbsp;</b></font></td>
            <td align="center"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</b></font></td>
            <td align="center"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</b></font></td>
	        <td align="right"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</b></font></td>
            <td align="center"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_OPT_PRICE_PREFIX; ?>&nbsp;</b></font></td>
	    <td align="center"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="7"><? echo tep_black_line(); ?></td>
          </tr>
<?
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
  $products_name_only = tep_get_products_name($attributes_values['products_id']);
  $options_name = tep_options_name($attributes_values['options_id']);
  $values_name = tep_values_name($attributes_values['options_values_id']);
    $rows++;
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    } else {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    }
    if (($HTTP_GET_VARS['action'] == 'update_attribute') && ($HTTP_GET_VARS['attribute_id'] == $attributes_values['products_attributes_id'])) {
      echo '<form name="attributes" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_product_attribute' . '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '" method="post" onSubmit="return checkFormAtrib();">';
?>
        <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $attributes_values['products_attributes_id']; ?><input type="hidden" name="attribute_id" value="<? echo $attributes_values['products_attributes_id']; ?>">&nbsp;</font></td>
	<td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo "\n"; ?><select name="products_id"><?
      $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        if ($attributes_values['products_id'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
      } ?></select>&nbsp;</font></td>
	<td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo "\n"; ?><select name="options_id"><?
      $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " order by products_options_name");
      while($options_values = tep_db_fetch_array($options)) {
        if ($attributes_values['options_id'] == $options_values['products_options_id']) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" SELECTED>' . $options_values['products_options_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
        }
      } ?></select>&nbsp;</font></td>
        <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo "\n"; ?><select name="values_id"><?
      $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " order by products_options_values_name");
      while($values_values = tep_db_fetch_array($values)) {
        if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '" SELECTED>' . $values_values['products_options_values_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
        }
      } ?></select>&nbsp;</font></td>
            <td align="right"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="value_price" value="<? echo $attributes_values['options_values_price']; ?>" size="6">&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="price_prefix" value="<? echo $attributes_values['price_prefix']; ?>" size="2">&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE); ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL); ?></a>&nbsp;</font></td>
<?
      echo '</form>' . "\n";
?>
          </tr>
<?
    } elseif (($HTTP_GET_VARS['action'] == 'delete_product_attribute') && ($HTTP_GET_VARS['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo $attributes_values["products_attributes_id"]; ?></b>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo $products_name_only; ?></b>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo $options_name; ?></b>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo $values_name; ?></b>&nbsp;</font></td>
            <td align="right"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo $attributes_values["options_values_price"]; ?></b>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo $attributes_values["price_prefix"]; ?></b>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_attribute&attribute_id=' . $HTTP_GET_VARS['attribute_id'], 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_confirm_red.gif', '50', '14', '0', IMAGE_CONFIRM); ?></a>&nbsp;&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL); ?></a>&nbsp;</b></font></td>
          </tr>
<?
    } else {
?>
            <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $attributes_values["products_attributes_id"]; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_name_only; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $options_name; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $values_name; ?>&nbsp;</font></td>
            <td align="right"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $attributes_values["options_values_price"]; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $attributes_values["price_prefix"]; ?>&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') , '">'; ?><? echo tep_image(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE); ?></a>&nbsp;</font></td>
          </tr>
<?
    }
        $max_attributes_id_query = tep_db_query("select max(products_attributes_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES);
        $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
        $next_id = $max_attributes_id_values['next_id'];
  }
?>
          <tr>
            <td colspan="7"><? echo tep_black_line(); ?></td>
          </tr>
<?
  if (!$HTTP_GET_VARS['action'] == 'update_attribute') {
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    } else {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    }
      echo '<form name="attributes" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_attributes', 'NONSSL') . '" method="post" onSubmit="return checkFormAtrib();">';
?>
            <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $next_id; ?>&nbsp;</font></td>
	    <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<select name="products_id"><?
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
      echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
    } ?></select>&nbsp;</font></td>
	        <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<select name="options_id"><?
    $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
    while ($options_values = tep_db_fetch_array($options)) {
      echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
    } ?></select>&nbsp;</font></td>
	        <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<select name="values_id"><?
    $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $languages_id . "' order by products_options_values_name");
    while ($values_values = tep_db_fetch_array($values)) {
      echo '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
    } ?></select>&nbsp;</font></td>
            <td align="right"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="value_price" size="6">&nbsp;</font></td>
            <td align="right"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="price_prefix" size="2" value="+">&nbsp;</font></td>
            <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT); ?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="7"><? echo tep_black_line(); ?></td>
          </tr>
<?
  }
?>
        </table></td>
      </tr>
    </table></td>
<!-- products_attributes_eof //-->
</tr></table>
<!-- body_text_eof //-->
<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

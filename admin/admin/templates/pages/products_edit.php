<?php
/*
  $Id: products_edit.php,v 1.1 2004/08/27 22:13:15 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  if (isset($_GET['pID']) && empty($_POST)) {
    $Qp = $osC_Database->query('select products_id, products_quantity, products_model, products_image, products_price, products_weight, products_weight_class, products_date_added, products_last_modified, date_format(products_date_available, "%Y-%m-%d") as products_date_available, products_status, products_tax_class_id, manufacturers_id from :table_products where products_id = :products_id');
    $Qp->bindTable(':table_products', TABLE_PRODUCTS);
    $Qp->bindInt(':products_id', $_GET['pID']);
    $Qp->execute();

    $Qpd = $osC_Database->query('select products_name, products_description, products_url, language_id from :table_products_description where products_id = :products_id');
    $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qpd->bindInt(':products_id', $_GET['pID']);
    $Qpd->execute();

    $pd_extra = array();
    while ($Qpd->next()) {
      $pd_extra['products_name'][$Qpd->valueInt('language_id')] = $Qpd->value('products_name');
      $pd_extra['products_description'][$Qpd->valueInt('language_id')] = $Qpd->value('products_description');
      $pd_extra['products_url'][$Qpd->valueInt('language_id')] = $Qpd->value('products_url');
    }

    $pInfo = new objectInfo(array_merge($Qp->toArray(), $pd_extra));
  } elseif (!empty($_POST)) {
    $pInfo = new objectInfo($_POST);
  }

  $Qmanufacturers = $osC_Database->query('select manufacturers_id, manufacturers_name from :table_manufacturers order by manufacturers_name');
  $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
  $Qmanufacturers->execute();

  $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
  while ($Qmanufacturers->next()) {
    $manufacturers_array[] = array('id' => $Qmanufacturers->valueInt('manufacturers_id'),
                                   'text' => $Qmanufacturers->value('manufacturers_name'));
  }

  $Qtc = $osC_Database->query('select tax_class_id, tax_class_title from :table_tax_class order by tax_class_title');
  $Qtc->bindTable(':table_tax_class', TABLE_TAX_CLASS);
  $Qtc->execute();

  $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
  while ($Qtc->next()) {
    $tax_class_array[] = array('id' => $Qtc->valueInt('tax_class_id'),
                               'text' => $Qtc->value('tax_class_title'));
  }

  $Qwc = $osC_Database->query('select weight_class_id, weight_class_title from :table_weight_class where language_id = :language_id order by weight_class_title');
  $Qwc->bindTable(':table_weight_class', TABLE_WEIGHT_CLASS);
  $Qwc->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qwc->execute();

  $weight_class_array = array();
  while ($Qwc->next()) {
    $weight_class_array[] = array('id' => $Qwc->valueInt('weight_class_id'),
                                  'text' => $Qwc->value('weight_class_title'));
  }

  $languages = tep_get_languages();
?>

<script type="text/javascript" src="external/FCKeditor/2.0b1/fckeditor.js"></script>
<style type="text/css">@import url('external/jscalendar/calendar-win2k-1.css');</style>
<script type="text/javascript" src="external/jscalendar/calendar.js"></script>
<script type="text/javascript" src="external/jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="external/jscalendar/calendar-setup.js"></script>

<style type="text/css">
.attributeRemove {
  background-color: #FFC6C6;
}

.attributeAdd {
  background-color: #E8FFC6;
}
</style>

<script language="javascript"><!--
  var tax_rates = new Array();
<?php
  foreach ($tax_class_array as $tc_entry) {
    if ($tc_entry['id'] > 0) {
      echo '  tax_rates["' . $tc_entry['id'] . '"] = ' . tep_get_tax_rate_value($tc_entry['id']) . ';' . "\n";
    }
  }
?>

  function doRound(x, places) {
    return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
  }

  function getTaxRate() {
    var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
    var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

    if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
      return tax_rates[parameterVal];
    } else {
      return 0;
    }
  }

  function updateGross() {
    var taxRate = getTaxRate();
    var grossValue = document.forms["new_product"].products_price.value;

    if (taxRate > 0) {
      grossValue = grossValue * ((taxRate / 100) + 1);
    }

    document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
  }

  function updateNet() {
    var taxRate = getTaxRate();
    var netValue = document.forms["new_product"].products_price_gross.value;

    if (taxRate > 0) {
      netValue = netValue / ((taxRate / 100) + 1);
    }

    document.forms["new_product"].products_price.value = doRound(netValue, 4);
  }

  var counter = 0;

  function moreFields() {
    var existingFields = document.new_product.getElementsByTagName('input');
    var attributeExists = false;

    for (i=0; i<existingFields.length; i++) {
      if (existingFields[i].name == 'attribute_prefix[' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '][' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value + ']') {
        attributeExists = true;
        break;
      }
    }

    if (attributeExists == false) {
      counter++;
      var newFields = document.getElementById('readroot').cloneNode(true);
      newFields.id = '';
      newFields.style.display = 'block';

      var spanFields = newFields.getElementsByTagName('span');
      var inputFields = newFields.getElementsByTagName('input');

      spanFields[0].innerHTML = document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.label;
      spanFields[1].innerHTML = document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].text;

      for (y=0; y<inputFields.length; y++) {
        inputFields[y].name = inputFields[y].name.substr(4) + '[' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '][' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value + ']';
        inputFields[y].disabled = false;
      }

      var insertHere = document.getElementById('writeroot');
      insertHere.parentNode.insertBefore(newFields,insertHere);
    }
  }

  function toggleAttributeStatus(attributeID) {
    var row = document.getElementById(attributeID);
    var rowButton = document.getElementById(attributeID + '-button');
    var rowFields = row.getElementsByTagName('input');

    if (rowButton.value == '-') {
      for (rF=0; rF<rowFields.length; rF++) {
        if (rowFields[rF].type != 'button') {
          rowFields[rF].disabled = true;
        }
      }

      row.className = 'attributeRemove';
      rowButton.value = '+';
    } else {
      for (rF=0; rF<rowFields.length; rF++) {
        if (rowFields[rF].type != 'button') {
          rowFields[rF].disabled = false;
        }
      }

      row.className = '';
      rowButton.value = '-';
    }
  }

//--></script>

<link type="text/css" rel="stylesheet" href="external/tabpane/css/luna/tab.css" />
<script type="text/javascript" src="external/tabpane/js/tabpane.js"></script>

<h1><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></h1>

<div class="tab-pane" id="tabPane1">
  <script type="text/javascript">
    tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
  </script>

<?php
  echo tep_draw_form('new_product', FILENAME_PRODUCTS, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data"');
?>

  <div class="tab-page" id="tabPage1">
    <h2 class="tab"><?php echo TAB_GENERAL; ?></h2>

    <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
        <td class="main"><?php echo osc_draw_radio_field('products_status', array(array('id' => '1', 'text' => TEXT_PRODUCT_AVAILABLE), array('id' => '0', 'text' => TEXT_PRODUCT_NOT_AVAILABLE)), (isset($pInfo) ? $pInfo->products_status : '0')); ?></td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br><small>(YYYY-MM-DD)</small></td>
        <td class="main"><?php echo osc_draw_input_field('products_date_available', (isset($pInfo) ? $pInfo->products_date_available : ''), 'id="calendarValue"'); ?><input type="button" value="..." id="calendarTrigger" class="operationButton"><script type="text/javascript">Calendar.setup( { inputField: "calendarValue", ifFormat: "%Y-%m-%d", button: "calendarTrigger" } );</script></td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
        <td class="main"><?php echo osc_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($pInfo) ? $pInfo->manufacturers_id : '')); ?></td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr bgcolor="#fff3e7">
        <td class="main">&nbsp;</td>
        <td>
<?php
  foreach ($languages as $l_entry) {
    echo '          <span id="lang_' . $l_entry['code'] . '"' . (($l_entry['directory'] == $osC_Session->value('language')) ? ' class="highlight"' : '') . '><a href="javascript:toggleDivBlocks(\'pName_\', \'pName_' . $l_entry['code'] . '\'); toggleDivBlocks(\'pDesc_\', \'pDesc_' . $l_entry['code'] . '\'); toggleDivBlocks(\'pURL_\', \'pURL_' . $l_entry['code'] . '\'); toggleClass(\'lang_\', \'lang_' . $l_entry['code'] . '\', \'highlight\', \'span\');">' . tep_image(DIR_WS_CATALOG_LANGUAGES . $l_entry['directory'] . '/images/' . $l_entry['image'], $l_entry['name']) . '</a></span>&nbsp;&nbsp;';
  }
?>
        </td>
      </tr>
      <tr bgcolor="#fff3e7">
        <td class="main"><?php echo TEXT_PRODUCTS_NAME; ?></td>
        <td>
<?php
  foreach ($languages as $l_entry) {
    echo '          <div id="pName_' . $l_entry['code'] . '"' . (($l_entry['directory'] != $osC_Session->value('language')) ? ' style="display: none;"' : '') . '>' . osc_draw_input_field('products_name[' . $l_entry['id'] . ']', (isset($pInfo) && is_array($pInfo->products_name) && isset($pInfo->products_name[$l_entry['id']]) ? $pInfo->products_name[$l_entry['id']] : '')) . '</div>';
  }
?>
        </td>
      </tr>
      <tr bgcolor="#fff3e7">
        <td class="main" valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
        <td>
<?php
  foreach ($languages as $l_entry) {
    echo '        <div id="pDesc_' . $l_entry['code'] . '"' . (($l_entry['directory'] != $osC_Session->value('language')) ? ' style="display: none;"' : '') . '>' . tep_draw_textarea_field('products_description[' . $l_entry['id'] . ']', 'soft', '70', '15', (isset($pInfo) && is_array($pInfo->products_description) && isset($pInfo->products_description[$l_entry['id']]) ? $pInfo->products_description[$l_entry['id']] : ''), 'id="fckpd_' . $l_entry['code'] . '" style="width: 100%;"') . '</div>';

    echo '<script type="text/javascript"><!--' . "\n" .
         '  var fckpd_' . $l_entry['code'] . ' = new FCKeditor(\'fckpd_' . $l_entry['code'] . '\');' . "\n" .
         '  fckpd_' . $l_entry['code'] . '.BasePath = "' . DIR_WS_ADMIN . 'external/FCKeditor/2.0b1/";' . "\n" .
         '  fckpd_' . $l_entry['code'] . '.Height = "400";' . "\n" .
         '  fckpd_' . $l_entry['code'] . '.ReplaceTextarea();' . "\n" .
         '//--></script>';
  }
?>
        </td>
      </tr>
      <tr bgcolor="#fff3e7">
        <td class="main"><?php echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
        <td>
<?php
  foreach ($languages as $l_entry) {
    echo '        <div id="pURL_' . $l_entry['code'] . '"' . (($l_entry['directory'] != $osC_Session->value('language')) ? ' style="display: none;"' : '') . '>' . osc_draw_input_field('products_url[' . $l_entry['id'] . ']', (isset($pInfo) && is_array($pInfo->products_url) && isset($pInfo->products_url[$l_entry['id']]) ? $pInfo->products_url[$l_entry['id']] : '')) . '</div>';
  }
?>
        </td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr bgcolor="#ebebff">
        <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
        <td class="main"><?php echo osc_draw_pull_down_menu('products_tax_class_id', $tax_class_array, (isset($pInfo) ? $pInfo->products_tax_class_id : ''), 'onchange="updateGross()"'); ?></td>
      </tr>
      <tr bgcolor="#ebebff">
        <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
        <td class="main"><?php echo osc_draw_input_field('products_price', (isset($pInfo) ? $pInfo->products_price : ''), 'onKeyUp="updateGross()"'); ?></td>
      </tr>
      <tr bgcolor="#ebebff">
        <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
        <td class="main"><?php echo osc_draw_input_field('products_price_gross', (isset($pInfo) ? $pInfo->products_price : ''), 'OnKeyUp="updateNet()"'); ?></td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
        <td class="main"><?php echo osc_draw_input_field('products_quantity', (isset($pInfo) ? $pInfo->products_quantity : '')); ?></td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
        <td class="main"><?php echo osc_draw_input_field('products_model', (isset($pInfo) ? $pInfo->products_model : '')); ?></td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
        <td class="main"><?php echo osc_draw_file_field('products_image') . '<br>' . (isset($pInfo) ? $pInfo->products_image . osc_draw_hidden_field('products_previous_image', $pInfo->products_image) : ''); ?></td>
      </tr>
      <tr>
        <td class="smallText" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
        <td class="main"><?php echo osc_draw_input_field('products_weight', (isset($pInfo) ? $pInfo->products_weight : '')). '&nbsp;' . osc_draw_pull_down_menu('products_weight_class', $weight_class_array, (isset($pInfo) ? $pInfo->products_weight_class : SHIPPING_WEIGHT_UNIT)); ?></td>
      </tr>
    </table>

<script language="javascript"><!--
  updateGross();
//--></script>

  </div>

  <div class="tab-page" id="tabPage2">
    <h2 class="tab"><?php echo TAB_ATTRIBUTES; ?></h2>

<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30%" valign="top"><select name="attributes" size="20" style="width: 100%;">
<?php
  $Qoptions = $osC_Database->query('select products_options_id, products_options_name from :table_products_options where language_id = :language_id order by products_options_name');
  $Qoptions->bindTable(':table_products_options', TABLE_PRODUCTS_OPTIONS);
  $Qoptions->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qoptions->execute();

  while ($Qoptions->next()) {
    echo '          <optgroup label="' . $Qoptions->value('products_options_name') . '" id="' . $Qoptions->value('products_options_id') . '">' . "\n";

    $Qvalues = $osC_Database->query('select pov.products_options_values_id, pov.products_options_values_name from :table_products_options_values pov, :table_products_options_values_to_products_options pov2po where pov2po.products_options_id = :products_options_id and pov2po.products_options_values_id = pov.products_options_values_id and pov.language_id = :language_id order by pov.products_options_values_name');
    $Qvalues->bindTable(':table_products_options_values', TABLE_PRODUCTS_OPTIONS_VALUES);
    $Qvalues->bindTable(':table_products_options_values_to_products_options', TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS);
    $Qvalues->bindInt(':products_options_id', $Qoptions->valueInt('products_options_id'));
    $Qvalues->bindInt(':language_id', $osC_Session->value('languages_id'));
    $Qvalues->execute();

    while ($Qvalues->next()) {
      echo '            <option value="' . $Qvalues->valueInt('products_options_values_id') . '">' . $Qvalues->value('products_options_values_name') . '</option>' . "\n";
    }

    echo '          </optgroup>' . "\n";
  }
?>
        </select></td>
        <td align="center" width="10%" class="smallText">
          <input type="button" value=">>" onClick="moreFields()">
        </td>
        <td width="60%" valign="top" class="smallText">
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_ATTRIBUTES; ?></legend>

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $Qattributes = $osC_Database->query('select po.products_options_id, po.products_options_name, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from :table_products_attributes pa, :table_products_options po, :table_products_options_values pov where pa.products_id = :products_id and pa.options_id = po.products_options_id and po.language_id = :language_id and pa.options_values_id = pov.products_options_values_id and pov.language_id = :language_id order by po.products_options_name, pov.products_options_values_name');
  $Qattributes->bindTable(':table_products_attributes', TABLE_PRODUCTS_ATTRIBUTES);
  $Qattributes->bindTable(':table_products_options', TABLE_PRODUCTS_OPTIONS);
  $Qattributes->bindTable(':table_products_options_values', TABLE_PRODUCTS_OPTIONS_VALUES);
  $Qattributes->bindInt(':products_id', $_GET['pID']);
  $Qattributes->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qattributes->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qattributes->execute();

  $current_attribute_group = '';

  while ($Qattributes->next()) {
    if ($Qattributes->value('products_options_name') != $current_attribute_group) {
      echo '              <tr>' . "\n" .
           '                <td class="smallText" colspan="3"><b>' . $Qattributes->value('products_options_name') . '</b></td>' . "\n" .
           '              </tr>' . "\n";

      $current_attribute_group = $Qattributes->value('products_options_name');
    }

    echo '              <tr id="attribute-' . $Qattributes->valueInt('products_options_id') . '_' . $Qattributes->valueInt('products_options_values_id') . '">' . "\n" .
         '                <td class="smallText" width="50%">' . $Qattributes->value('products_options_values_name') . '</td>' . "\n" .
         '                <td class="smallText">' . osc_draw_input_field('attribute_prefix[' . $Qattributes->valueInt('products_options_id') . '][' . $Qattributes->valueInt('products_options_values_id') . ']', $Qattributes->value('price_prefix'), 'size="1" maxlength="1"') . '&nbsp;' . osc_draw_input_field('attribute_price[' . $Qattributes->valueInt('products_options_id') . '][' . $Qattributes->valueInt('products_options_values_id') . ']', $Qattributes->value('options_values_price')) . '</td>' . "\n" .
         '                <td class="smallText" align="right"><input type="button" value="-" id="attribute-' . $Qattributes->valueInt('products_options_id') . '_' . $Qattributes->valueInt('products_options_values_id') . '-button" onClick="toggleAttributeStatus(\'attribute-' . $Qattributes->valueInt('products_options_id') . '_' . $Qattributes->valueInt('products_options_values_id') . '\');"></td>' . "\n" .
         '              </tr>' . "\n";
  }
?>
            </table>

            <span id="writeroot"></span>

            <div id="readroot" style="display: none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="smallText" colspan="3"><b><span id="attribteGroupName">&nbsp;</span></b></td>
                </tr>
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><span id="attributeKey">&nbsp;</span></td>
                  <td class="smallText"><?php echo osc_draw_input_field('new_attribute_prefix', '+', 'disabled size="1" maxlength="1"') . '&nbsp;' . osc_draw_input_field('new_attribute_price', '', 'disabled'); ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="this.parentNode.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode.parentNode);"></td>
                </tr>
              </table>
            </div>
          </fieldset>
        </td>
      </tr>
    </table>
  </div>

  <p align="right"><?php echo osc_draw_hidden_field('products_date_added', (isset($pInfo) && isset($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . '<input type="submit" value="' . IMAGE_PREVIEW . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '\';" class="operationButton">'; ?></p>

  </form>
</div>

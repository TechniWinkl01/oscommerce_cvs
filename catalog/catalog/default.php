<?php
/*
  $Id: default.php,v 1.55 2001/12/01 19:36:45 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// the following cPath references come from application_top.php
  $category_depth = 'top';
  if ($cPath) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . $current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

  $location = '';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
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
<?php
  if ($category_depth == 'nested') {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?php
    $category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    $category = tep_db_fetch_array($category_query);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="subBar">
            <td class="subBar">&nbsp;<?php echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?php
    if (($HTTP_GET_VARS['cPath']) && (ereg('_', $HTTP_GET_VARS['cPath']))) {
// check to see if there are deeper categories within the current category
      $category_links = tep_array_reverse($cPath_array);
      for($i=0; $i<sizeof($category_links); $i++) {
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
        if (tep_db_num_rows($categories_query) < 1) {
          // do nothing, go through the loop
        } else {
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
    }

    $rows = 0;
    while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = tep_get_path($categories['categories_id']);
      echo '                <td align="center" class="main"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
        echo '              </tr>' . "\n";
        echo '              <tr>' . "\n";
      }
    }
?>
              </tr>
            </table></td>
          </tr>
<?php
// display new products page module
    $new_products_category_id = $current_category_id;
    include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
?>
        </table></td>
      </tr>
    </table></td>
<?php
  } elseif ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?php
// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER, 
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE, 
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY, 
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT, 
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE, 
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($column, $value) = each($define_list)) {
      if ($value) $column_list[] = $column;
    }

    $select_column_list = '';

    for ($col=0; $col<sizeof($column_list); $col++) {
      if ( ($column_list[$col] == 'PRODUCT_LIST_BUY_NOW') || ($column_list[$col] == 'PRODUCT_LIST_PRICE') ) {
        continue;
      }

      if ($select_column_list != '') {
        $select_column_list .= ', ';
      }

      switch ($column_list[$col]) {
        case 'PRODUCT_LIST_MODEL':        $select_column_list .= 'p.products_model';
                                          break;
        case 'PRODUCT_LIST_NAME':         $select_column_list .= 'pd.products_name';
                                          break;
        case 'PRODUCT_LIST_MANUFACTURER': $select_column_list .= 'm.manufacturers_name';
                                          break;
        case 'PRODUCT_LIST_QUANTITY':     $select_column_list .= 'p.products_quantity';
                                          break;
        case 'PRODUCT_LIST_IMAGE':        $select_column_list .= 'p.products_image';
                                          break;
        case 'PRODUCT_LIST_WEIGHT':       $select_column_list .= 'p.products_weight';
                                          break;
      }
    }

    if ($select_column_list != '') {
      $select_column_list .= ', ';
    }

// show the products of a specified manufacturer
    if ($HTTP_GET_VARS['manufacturers_id']) {
      if ($HTTP_GET_VARS['filter_id']) {
// We are asked to show only a specific category
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $HTTP_GET_VARS['filter_id'] . "'";
      } else {
// We show them all
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'";
      }
// We build the categories-dropdown
      $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and p.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";
    } else {
// show the products in a given categorie
      if ($HTTP_GET_VARS['filter_id']) {
// We are asked to show only specific catgeory
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $current_category_id . "'";
      } else {
// We show them all
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $current_category_id . "'";
      }
// We build the manufacturers Dropdown
      $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by m.manufacturers_name";
    }

    if ( (!$HTTP_GET_VARS['sort']) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) ) {
      for ($col=0; $col<sizeof($column_list); $col++) {
        if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = $col+1 . 'a';
          $listing_sql .= " order by pd.products_name";
          break;
        }
      }
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);

      if ($sort_col <= sizeof($column_list)) {
        $listing_sql .= ' order by ';
        switch ($column_list[$sort_col-1]) {
          case 'PRODUCT_LIST_MODEL':        $listing_sql .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
                                            break;
          case 'PRODUCT_LIST_NAME':         $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? "desc" : "");
                                            break;
          case 'PRODUCT_LIST_MANUFACTURER': $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
                                            break;
          case 'PRODUCT_LIST_QUANTITY':     $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
                                            break;
          case 'PRODUCT_LIST_IMAGE':        $listing_sql .= "pd.products_name";
                                            break;
          case 'PRODUCT_LIST_WEIGHT':       $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
                                            break;
          case 'PRODUCT_LIST_PRICE':        $listing_sql .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
                                            break;
        }
      } else {
        for ($col=0; $col<sizeof($column_list); $col++) {
          if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
            $HTTP_GET_VARS['sort'] = $col . 'a';
            $listing_sql .= " order by pd.products_name";
            break;
          }
        }
      }
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <form>
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
<?php
// optional Product List Filter
    if (PRODUCT_LIST_FILTER) {
      $filterlist = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist) > 1) {
        echo '            <td align="center" class="main">' . TEXT_SHOW . '<select size="1" onChange="if(options[selectedIndex].value) window.location.href=(options[selectedIndex].value)">';
        if ($HTTP_GET_VARS['manufacturers_id']) {
          $arguments = 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'];
        } else {
          $arguments = 'cPath=' . $cPath;
        }
        $arguments .= '&sort=' . $HTTP_GET_VARS['sort'];

        $option_url = tep_href_link(FILENAME_DEFAULT, $arguments, 'NONSSL');

        if (!$HTTP_GET_VARS['filter_id']) {
          echo '<option value="' . $option_url . '" SELECTED>' . TEXT_ALL . '</option>';
        } else {
          echo '<option value="' . $option_url . '">' . TEXT_ALL . '</option>';
        }

        echo '<option value="">---------------</option>';
        while ($filterlist_values = tep_db_fetch_array($filterlist)) {
          $option_url = tep_href_link(FILENAME_DEFAULT, $arguments . '&filter_id=' . $filterlist_values['id'], 'NONSSL');
          if ( ($HTTP_GET_VARS['filter_id']) && ($HTTP_GET_VARS['filter_id'] == $filterlist_values['id']) ) {
            echo '<option value="' . $option_url . '" SELECTED>' . $filterlist_values['name'] . '</option>';
          } else {
            echo '<option value="' . $option_url . '">' . $filterlist_values['name'] . '</option>';
          }
        }
        echo '</select></td>' . "\n";
      }
    }

// Get the right image for the top-right
    $image = DIR_WS_IMAGES . 'table_background_list.gif';
    if ($HTTP_GET_VARS['manufacturers_id']) {
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . $current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . $image, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
          </form>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); ?></td>
      </tr>
    </table></td>
<?php
  } else { // default page
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_default.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="subBar">
            <td class="subBar">&nbsp;<?php echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_customer_greeting(); ?></td>
          </tr>
          <tr>
            <td class="main"><br><?php echo TEXT_MAIN; ?></td>
          </tr>
<?php
    $new_products_category_id = '0';
    include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
    include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS);
?>
        </table></td>
      </tr>
    </table></td>
<?php
  }
?>
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

<? include('includes/application_top.php'); ?>
<?
// remember the following cPath references come from application_top.php
  $category_depth = 'top';
  if ($cPath) {
    $categories_products_query = tep_db_query("select count(*) as total from products_to_categories where categories_id = '" . $current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from categories where parent_id = '" . $current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_DEFAULT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ''; ?>
<html>
<head>
<title><? echo TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
<?
  if ($category_depth == 'nested') {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
    $category_query = tep_db_query("select categories_name, categories_image from categories where categories_id = '" . $current_category_id . "'");
    $category = tep_db_fetch_array($category_query);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image($category['categories_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $category['categories_name']);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<? echo SUB_BAR_BACKGROUND_COLOR;?>">
            <td nowrap><font face="<? echo SUB_BAR_FONT_FACE;?>" size="<? echo SUB_BAR_FONT_SIZE;?>" color="<? echo SUB_BAR_FONT_COLOR;?>">&nbsp;<? echo SUB_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
<?
    $new_products_category_id = $current_category_id;
    $include_file = DIR_MODULES . FILENAME_NEW_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
?>
        </table></td>
      </tr>
    </table></td>
<?
  } elseif ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
    if ($HTTP_GET_VARS['manufacturers_id']) {
      if ($HTTP_GET_VARS['filter_id']) {
        $listing_sql = "select p.products_id, p.products_name, p.products_model, m.manufacturers_name, m.manufacturers_location, p.products_price from products p, manufacturers m, products_to_manufacturers p2m, products_to_categories p2c where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $HTTP_GET_VARS['filter_id'] . "' order by p.products_name";
      } else {
        $listing_sql = "select p.products_id, p.products_name, p.products_model, m.manufacturers_name, m.manufacturers_location, p.products_price from products p, manufacturers m, products_to_manufacturers p2m where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' order by p.products_name";
      }
      $filterlist_sql = "select distinct c.categories_id as id, c.categories_name as name from products p, products_to_manufacturers p2m, products_to_categories p2c, categories c where p.products_status = '1' and p.products_id = p2m.products_id and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' order by c.categories_name";
    } else {
      if ($HTTP_GET_VARS['filter_id']) {
        $listing_sql = "select p.products_id, p.products_name, p.products_model, m.manufacturers_name, m.manufacturers_location, p.products_price from products p, manufacturers m, products_to_manufacturers p2m, products_to_categories p2c where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by p.products_name";
      } else {
        $listing_sql = "select p.products_id, p.products_name, p.products_model, m.manufacturers_name, m.manufacturers_location, p.products_price from products p, manufacturers m, products_to_manufacturers p2m, products_to_categories p2c where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by p.products_name";
      }
      $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from products p, products_to_manufacturers p2m, products_to_categories p2c, manufacturers m where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by m.manufacturers_name";
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <form>
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
<?
// optional Product List Filter
    if (PRODUCT_LIST_FILTER) {
      $filterlist = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist) > 1) {
        echo '            <td align="center"><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . "\n";
        echo '              ' . TEXT_SHOW . "\n";
        echo '              <select size="1" onChange="if(options[selectedIndex].value) window.location.href=(options[selectedIndex].value)">' . "\n";

        if ($HTTP_GET_VARS['manufacturers_id']) {
          $arguments = 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] ;
        } else {
          $arguments = 'cPath=' . $cPath ;
        }

        $option_url = tep_href_link(FILENAME_DEFAULT, $arguments, 'NONSSL');

        if (!$HTTP_GET_VARS['filter_id']) {
          echo '                <option value="' . $option_url . '" SELECTED>All' . "\n";
        } else {
          echo '                <option value="' . $option_url . '">All' . "\n";
        }

        echo '                <option value="">---------------' . "\n";
        while ($filterlist_values = tep_db_fetch_array($filterlist)) {
          $option_url = tep_href_link(FILENAME_DEFAULT, $arguments . '&filter_id=' . $filterlist_values['id'], 'NONSSL');
          if ($HTTP_GET_VARS['filter_id'] && $HTTP_GET_VARS['filter_id'] == $filterlist_values['id']) {
            echo '              <option value="' . $option_url . '" SELECTED>' . $filterlist_values['name'] . '&nbsp;' . "\n" ;
          } else {
            echo '              <option value="' . $option_url . '">' . $filterlist_values['name'] . '&nbsp;' . "\n" ;
          }
        }
        echo '              </select>' . "\n";
        echo '            </font></td>' . "\n";
      }
    }
?>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_list.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
          </form>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td>
<? $include_file = DIR_MODULES . 'product_listing.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
        </td>
      </tr>
    </table></td>
<?
  } else { // default page
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_default.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<? echo SUB_BAR_BACKGROUND_COLOR;?>">
            <td nowrap><font face="<? echo SUB_BAR_FONT_FACE;?>" size="<? echo SUB_BAR_FONT_SIZE;?>" color="<? echo SUB_BAR_FONT_COLOR;?>">&nbsp;<? echo SUB_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>"><? echo TEXT_MAIN;?></font></td>
          </tr>
<?
  $new_products_category_id = '0'; $include_file = DIR_MODULES . FILENAME_NEW_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_MODULES . FILENAME_UPCOMING_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
?>
        </table></td>
      </tr>
    </table></td>
<?
  }
?>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
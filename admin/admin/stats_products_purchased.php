<?php
/*
  $Id: stats_products_purchased.php,v 1.21 2001/12/14 13:19:17 jan0815 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_NUMBER; ?>&nbsp;</td>
            <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_PRODUCTS; ?>&nbsp;</td>
            <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_PURCHASED; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><?php echo tep_black_line(); ?></td>
          </tr>
<?php
  if ($HTTP_GET_VARS['page'] > 1) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $products_query_raw = "select p.products_id, pd.products_name, sum(op.products_quantity) as ordersum from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_ORDERS_PRODUCTS . " op where p.products_id = op.products_id and pd.products_id = op.products_id and pd.language_id = '" . $languages_id. "' group by pd.products_id order by ordersum DESC, pd.products_name";
  $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
// fix counted products
  $products_query_numrows = tep_db_query("select products_id from " . TABLE_ORDERS_PRODUCTS . " group by products_id");
  $products_query_numrows = tep_db_num_rows($products_query_numrows);

  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
          <tr class="tableRow" onmouseover="this.className='tableRowOver';this.style.cursor='hand'" onmouseout="this.className='tableRow'" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . FILENAME_STATS_PRODUCTS_PURCHASED . '?page=' . $HTTP_GET_VARS['page'], 'NONSSL'); ?>'">
            <td align="center" class="smallText">&nbsp;<?php echo $rows; ?>.&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . FILENAME_STATS_PRODUCTS_PURCHASED . '?page=' . $HTTP_GET_VARS['page'], 'NONSSL') . '" class="blacklink">' . $products['products_name'] . '</a>'; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $products['ordersum']; ?>&nbsp;</td>
          </tr>
<?php
  }
?>
          <tr>
            <td colspan="3"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText">&nbsp;<?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE; ?> <?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
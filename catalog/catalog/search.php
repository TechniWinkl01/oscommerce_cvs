<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SEARCH; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : ' . NAVBAR_TITLE; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr><td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"> 
<?
// here its tricky for the sort becuase of manufacturers_name / products_name via manufacturers_location.. now there is a sort-order on the products name until a good solution is found..
  $row = 0;
  $per_page = MAX_ROW_LISTS;
  $search_keywords = explode(' ', trim($HTTP_POST_VARS['query']));
  $search_query = "select m.manufacturers_name, m.manufacturers_location, p.products_id, p.products_name, p.products_price from manufacturers m, products_to_manufacturers p2m, products p where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and ";
  for ($i=0; ($i<count($search_keywords)-1); $i++ ) {
    $search_query .= "(p.products_name like '%" . $search_keywords[$i] . "%' or m.manufacturers_name like '%" . $search_keywords[$i] . "%') and ";
  }
  $search_query .= "(p.products_name like '%" . $search_keywords[$i] . "%' or m.manufacturers_name like '%" . $search_keywords[$i] . "%') order by p.products_name";
  $search = tep_db_query($search_query);
            if (!$page) 
            { 
            $page = 1; 
            } 
            $prev_page = $page - 1; 
            $next_page = $page + 1; 
            $query = tep_db_query($search_query); 
            $page_start = ($per_page * $page) - $per_page; 
            $num_rows = tep_db_num_rows($query);
			if ($num_rows > MAX_DISPLAY_SEARCH_RESULTS) {
			$num_rows = MAX_DISPLAY_SEARCH_RESULTS;
			} 
            if ($num_rows <= $per_page) { 
            $num_pages = 1; 
            } else if (($num_rows % $per_page) == 0) { 
            $num_pages = ($num_rows / $per_page); 
            } else { 
            $num_pages = ($num_rows / $per_page) + 1; 
            } 
            $num_pages = (int) $num_pages; 
    
            if (($page > $num_pages) || ($page < 0)) { 
            error("You have specified an invalid page number"); 
            } 
			$search_query = $search_query . " limit $page_start, $per_page"; 

   // Previous 
      if ($prev_page)  { 
      echo "<a href=\"$PHP_SELF?page=$prev_page\"><<</a> | "; 
      } 
    
      for ($i = 1; $i <= $num_pages; $i++) { 
      if ($i != $page) { 
         echo " <a href=\"$PHP_SELF?page=$i\">$i</a> | "; 
             } else { 
         echo " <b><font color=red>$i<font color=black></b> |"; 
             } 
             } 
   // Next 
      if ($page != $num_pages) { 
      echo " <a href=\"$PHP_SELF?page=$next_page\">>></a> "; 
      } 
      echo '</td></tr>';
?>
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_PRODUCTS_NAME;?></b>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_PRODUCTS_PRICE;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
<?		  
	$search = tep_db_query("$search_query"); 
    while ($search_values = tep_db_fetch_array($search)) {
    $row++;
    $products_name = tep_products_name($search_values['manufacturers_location'], $search_values['manufacturers_name'], $search_values['products_name']);
    if (floor($row/2) == ($row/2)) {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    } else {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    }
    echo '            <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $search_values['products_id'], 'NONSSL') . '">' . $products_name . '</a>&nbsp;</font></td>' . "\n";
    $check_special = tep_db_query("select specials.specials_new_products_price from specials where products_id = '" . $search_values['products_id'] . "'");
         if (tep_db_num_rows($check_special)) {
           $check_special_values = tep_db_fetch_array($check_special);
           $new_price = $check_special_values['specials_new_products_price'];
         }
         echo '            <td align="right" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;';
         if ($new_price) {
           echo '<s>' .  tep_currency_format($search_values['products_price']) . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($new_price) . '</font>'; 
           unset($new_price);
         } else {
           echo tep_currency_format($search_values['products_price']);
         }
         echo '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=sprintf(TEXT_FOUND_MATCHES, $num_rows);?><? if ($num_rows == MAX_DISPLAY_SEARCH_RESULTS) { echo TEXT_MAXIMUM_SEARCH_RESULTS_REACHED; } ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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
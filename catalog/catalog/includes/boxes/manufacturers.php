<!-- manufacturers //-->
<?
  if (DISPLAY_MANUFACTURERS_BOX) {
?>
<script language="JavaScript"><!-- Hide the script from old browsers -- 
function surfto(form) {
  i = form.manufacturers.selectedIndex; 
  if (i == 0) return; 
  arguments = form.manufacturers.options[i].value ;
  window.location.href = "<?=HTTP_SERVER . DIR_CATALOG . FILENAME_DEFAULT . '?';?>" + arguments; 
} 
// --End Hiding Here --></script>
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_MANUFACTURERS;?>&nbsp;</font></td>
          </tr>
<?
    if (DISPLAY_EMPTY_MANUFACTURERS)
      $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from manufacturers order by manufacturers_name");
    else
      $manufacturers_query = tep_db_query("select distinct m.manufacturers_id, m.manufacturers_name from manufacturers m, products_to_manufacturers p2m, products p where p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id order by m.manufacturers_name");
  
    if (tep_db_num_rows($manufacturers_query) <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
      //------------------------------------------
      // Display a list
      //------------------------------------------
      echo '          <tr>' . "\n";
      echo '            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n";
      while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
        if (USE_ROLLOVER_EFFECT) {
          echo '              <tr onclick="window.location.href=\'' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_values['manufacturers_id'], 'NONSSL') . '\';" onmouseout="this.style.backgroundColor=\'' . BOX_CONTENT_BACKGROUND_COLOR . '\';" onmouseover="this.style.backgroundColor=\'' . BOX_CONTENT_HIGHLIGHT_COLOR . '\';this.style.cursor=\'hand\';">' . "\n";
          echo '                <td nowrap>' . "\n";
        } else {
          echo '              <tr>' . "\n";
          echo '                <td bgcolor="' . BOX_CONTENT_BACKGROUND_COLOR . '" nowrap>' . "\n";
        }
        echo '                  <font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">' . "\n";
        echo '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_values['manufacturers_id'], 'NONSSL') . '">' . substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</a><br>' . "\n";
        echo '                  </font>' . "\n";
        echo '                </td>' . "\n";
        echo '              </tr>' . "\n";
      }
      echo '            </table></td>' . "\n";
      echo '          </tr>' . "\n";
  
    } else {
      //------------------------------------------
      // Display a drop-down
      //------------------------------------------
      echo '          <form>' . "\n";
      echo '            <tr>' . "\n";
      echo '              <td bgcolor="' . BOX_CONTENT_BACKGROUND_COLOR . '" nowrap><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">' . "\n";
  
      $select_box = '<select name="manufacturers" onChange="surfto(this.form);">' . "\n";
      $select_box .= '<option value="">' . BOX_MANUFACTURERS_SELECT_ONE . "\n";
  
      while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
        $select_box .= '<option value="manufacturers_id=' . $manufacturers_values['manufacturers_id'] . '"';
        if ($HTTP_GET_VARS['manufacturers_id'] == $manufacturers_values['manufacturers_id'])
          $select_box .= ' SELECTED';
        $select_box .= '>' . substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . "\n";
      }
      $select_box .= "</select>";
      echo $select_box;
  
      echo '              </font></td>' . "\n";
      echo '            </tr>' . "\n";
      echo '          </form>' . "\n";
    }
  }
?>
<!-- manufacturers_eof //-->
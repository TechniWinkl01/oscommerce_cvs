<!-- add_a_quickie //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_ADD_PRODUCT_ID
                              );
  new infoBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form name="quick_add" method="post" action="' . tep_href_link(FILENAME_SHOPPING_CART, 'action=add_update_product', 'NONSSL') . '">',
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="products_id" size="10"><input type="hidden" name="cart_quantity" value="1">&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_add_quick.gif', '16', '17', '0', BOX_HEADING_ADD_PRODUCT_ID) . '</div>' . BOX_ADD_PRODUCT_ID_TEXT
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- add_a_quickie_eof //-->

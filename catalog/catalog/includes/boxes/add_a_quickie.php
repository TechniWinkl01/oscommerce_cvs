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
  $info_box_contents[] = array('form' => '<form name="quick_add" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add_a_quickie', 'NONSSL') . '">',
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="quickie" size="10">&nbsp;' . tep_image_submit(DIR_WS_IMAGES . 'button_add_quick.gif', BOX_HEADING_ADD_PRODUCT_ID) . '</div>' . BOX_ADD_PRODUCT_ID_TEXT
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- add_a_quickie_eof //-->

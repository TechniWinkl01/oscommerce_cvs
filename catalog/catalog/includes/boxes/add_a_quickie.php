<!-- add_a_quickie //-->
          <form name="quick_add" method="get" action="<?=tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL');?>"><tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_ADD_PRODUCT_ID;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><div align="center"><input type="hidden" name="action" value="add_product"><input type="text" name="products_id" size="10">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_add_quick.gif', '16', '17', '0', BOX_HEADING_ADD_PRODUCT_ID);?></div><?=BOX_ADD_PRODUCT_ID_TEXT;?></font></td>
          </tr></form>
<!-- add_a_quickie_eof //-->

          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_CUSTOMERS;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>" nowrap><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '">';?><?=BOX_CUSTOMERS_CUSTOMERS;?></a>&nbsp;<br>&nbsp;<?='<a href="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '">';?><?=BOX_CUSTOMERS_ORDERS;?></a>&nbsp;<br></font></td>
          </tr>

<!-- search //-->
          <form name="quick_find" method="get" action="<?=tep_href_link(FILENAME_SEARCH, '', 'NONSSL');?>"><tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_SEARCH;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><div align="center"><input type="text" name="query" size="10" maxlength="30"<? if ($HTTP_GET_VARS["query"]) { echo ' value="' . $HTTP_GET_VARS["query"] . '"'; } ?>>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_quick_find.gif', '16', '17', '0', BOX_HEADING_SEARCH);?></div><?=BOX_SEARCH_TEXT;?></font></td>
          </tr></form>
<!-- search_eof //-->
          <tr>
            <td bgcolor="<? echo BOX_HEADING_BACKGROUND_COLOR; ?>" class="boxborder" nowrap><font face="<? echo BOX_HEADING_FONT_FACE; ?>" color="<? echo BOX_HEADING_FONT_COLOR; ?>" size="<? echo BOX_HEADING_FONT_SIZE; ?>">&nbsp;<? echo BOX_HEADING_CUSTOMERS; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<? echo BOX_CONTENT_BACKGROUND_COLOR; ?>" nowrap><font face="<? echo BOX_CONTENT_FONT_FACE; ?>" color="<? echo BOX_CONTENT_FONT_COLOR; ?>" size="<? echo BOX_CONTENT_FONT_SIZE; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '">' . BOX_CUSTOMERS_CUSTOMERS . '</a>'; ?>&nbsp;<br>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '">' . BOX_CUSTOMERS_ORDERS . '</a>'; ?>&nbsp;<br></font></td>
          </tr>

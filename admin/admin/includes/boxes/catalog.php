          <tr>
            <td bgcolor="<? echo BOX_HEADING_BACKGROUND_COLOR; ?>" class="boxborder" nowrap><font face="<? echo BOX_HEADING_FONT_FACE; ?>" color="<? echo BOX_HEADING_FONT_COLOR; ?>" size="<? echo BOX_HEADING_FONT_SIZE; ?>">&nbsp;<? echo BOX_HEADING_CATALOG; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<? echo BOX_CONTENT_BACKGROUND_COLOR; ?>" nowrap><font face="<? echo BOX_CONTENT_FONT_FACE; ?>" color="<? echo BOX_CONTENT_FONT_COLOR; ?>" size="<? echo BOX_CONTENT_FONT_SIZE; ?>">&nbsp;
			<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '">' . BOX_CATALOG_CATEGORIES_PRODUCTS . '</a>'; ?>&nbsp;<br>&nbsp;
			<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">' . BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES . '</a>'; ?>&nbsp;<br>&nbsp;
			<? echo '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL') . '">' . BOX_CATALOG_MANUFACTURERS . '</a>'; ?>&nbsp;<br>&nbsp;
			<? echo '<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '">' . BOX_CATALOG_REVIEWS . '</a>'; ?>&nbsp;<br>&nbsp;
			<? echo '<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">' . BOX_CATALOG_SPECIALS . '</a>'; ?>&nbsp;<br>&nbsp;
			<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a>'; ?>&nbsp;<br></font></td>
          </tr>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr bgcolor="<?=HEADER_BACKGROUND_COLOR;?>">
    <td align="left" valign="middle" nowrap><?=tep_image(DIR_IMAGES . 'header_exchange_logo.gif', '57', '50', '0', STORE_NAME) . tep_image(DIR_IMAGES . 'pixel_trans.gif', '6', '1', '0', '') . tep_image(DIR_IMAGES . 'header_exchange.gif', '351', '50', '0', STORE_NAME);?></td>
    <td align="right" nowrap><a href="http://theexchangeproject.org"><?=tep_image(DIR_IMAGES . 'header_support.gif', '50', '50', '0', HEADER_TITLE_SUPPORT_SITE);?></a>&nbsp;&nbsp;<a href="/catalog/default.php"><?=tep_image(DIR_IMAGES . 'header_checkout.gif', '53', '50', '0', HEADER_TITLE_ONLINE_DEMO);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'header_administration.gif', '50', '50', '0', HEADER_TITLE_ADMINISTRATION);?></a>&nbsp;&nbsp;</td>
  </tr>
  <tr bgcolor="<?=HEADER_NAVIGATION_BAR_BACKGROUND_COLOR;?>" height="19">
    <td align="left" nowrap><font face="<?=HEADER_NAVIGATION_BAR_FONT_FACE;?>" color="<?=HEADER_NAVIGATION_BAR_FONT_COLOR;?>" size="<?=HEADER_NAVIGATION_BAR_FONT_SIZE;?>"><b>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_DEFAULT, '" class="whitelink"', 'NONSSL') . '">';?><?=HEADER_TITLE_TOP;?></a></b></font></td>
    <td align="right" nowrap><font face="<?=HEADER_NAVIGATION_BAR_FONT_FACE;?>" color="<?=HEADER_NAVIGATION_BAR_FONT_COLOR;?>" size="<?=HEADER_NAVIGATION_BAR_FONT_SIZE;?>"><b><a href="http://theexchangeproject.org" class="whitelink"><?=HEADER_TITLE_SUPPORT_SITE;?></a> &nbsp;|&nbsp; <a href="/catalog/default.php" class="whitelink"><?=HEADER_TITLE_ONLINE_DEMO;?></a> &nbsp;|&nbsp; <?='<a href="' . tep_href_link(FILENAME_DEFAULT, '" class="whitelink"', 'NONSSL') . '">';?><?=HEADER_TITLE_ADMINISTRATION;?></a>&nbsp;&nbsp;</b></font></td>
  </tr>
</table>

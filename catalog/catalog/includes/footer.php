<? $include_file = DIR_INCLUDES . 'counter.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr bgcolor="<? echo FOOTER_BAR_BACKGROUND_COLOR; ?>" height="19">
    <td align="left" nowrap><font face="<? echo FOOTER_BAR_FONT_FACE; ?>" color="<? echo FOOTER_BAR_FONT_COLOR; ?>" size="<? echo FOOTER_BAR_FONT_SIZE; ?>"><b>&nbsp;&nbsp;<? echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</b></font></td>
    <td align="right" nowrap><font face="<? echo FOOTER_BAR_FONT_FACE; ?>" color="<? echo FOOTER_BAR_FONT_COLOR; ?>" size="<? echo FOOTER_BAR_FONT_SIZE; ?>"><b>&nbsp;&nbsp;<? echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</b></font></td>
  </tr>
</table>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" nowrap><font face="Verdana, Arial" size="1">Copyright &copy; 2001 <a href="http://theexchangeproject.org">The Exchange Project</a> : <a href="mailto:hpdl@theexchangeproject.org">Harald Ponce de Leon</a><br><a href="debug_phpinfo.php">Display PHP parameters</a></font></td>
  </tr>
</table>
<? $include_file = DIR_WS_INCLUDES . 'counter.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr bgcolor="<? echo FOOTER_BAR_BACKGROUND_COLOR; ?>" height="19">
    <td class="footer" nowrap>&nbsp;&nbsp;<? echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
    <td align="right" class="footer" nowrap>&nbsp;&nbsp;<? echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</font></td>
  </tr>
</table>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="smallText" nowrap>Copyright &copy; 2001 <a href="http://theexchangeproject.org">The Exchange Project</a> : <a href="mailto:hpdl@theexchangeproject.org">Harald Ponce de Leon</a><br>Running on The Exchange Project <?php echo PROJECT_VERSION; ?><br><br><a href="debug_phpinfo.php">Display my PHP parameters</a></td>
  </tr>
</table>
<? $include_file = DIR_WS_INCLUDES . 'counter.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="footer">
    <td class="footer">&nbsp;&nbsp;<? echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
    <td align="right" class="footer">&nbsp;&nbsp;<? echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</td>
  </tr>
</table>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="smallText"><? echo FOOTER_TEXT_BODY ?></td>  </tr>
</table>
<?
  if (tep_banner_exists('dynamic', '468x50')) {
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><? echo tep_display_banner('dynamic', '468x50'); ?></td>
  </tr>
</table>
<?
  }
?>

<? include('includes/application_top.php'); ?>
<?
  tep_session_destroy();
  include('includes/counter.php');
  header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
  tep_exit();
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
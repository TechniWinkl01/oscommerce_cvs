<?php
/*
  $Id: create_account.php,v 1.43 2001/11/09 20:18:55 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $location = ' : <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function resetStateText(theForm) {
  theForm.state.value = '';
  if (theForm.zone_id.options.length > 1) {
    theForm.state.value = "<?php echo JS_STATE_SELECT; ?>";
  }
}

function resetZoneSelected(theForm) {
  if (theForm.zone_id.options.length > 1) {
    theForm.state.value = "<?php echo JS_STATE_SELECT; ?>";
  }
}

function update_zone(theForm) {
  var NumState = theForm.zone_id.options.length;
  var SelectedCountry = '';

  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }         

  SelectedCountry = theForm.country.options[theForm.country.selectedIndex].value;
            
<?php tep_js_zone_list('SelectedCountry', 'theForm'); ?>

  resetStateText(theForm);
}
//--></script>
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'NONSSL') . '"'; ?> onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
<?php
  if ($HTTP_GET_VARS['origin']) {
?>
      <tr>
        <td class="smallText"><br>&nbsp;<?php echo TEXT_ORIGIN_LOGIN; ?>&nbsp;</td>
      </tr>
<?php
  }
?>
      <tr>
        <td><br>
<?php
  $email_address = $HTTP_GET_VARS['email_address'];
  require(DIR_WS_MODULES . 'account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
      </tr>
    </table><?php if ($HTTP_GET_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; } if ($HTTP_GET_VARS['connection']) { echo '<input type="hidden" name="connection" value="' . $HTTP_GET_VARS['connection'] . '">'; } ?></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<? include('includes/application_top.php'); ?>
<html>
<head>
<SCRIPT language=JavaScript>

<!-- Begin
function nothing() {
}
// End -->

</SCRIPT>

<script type="text/javascript">
<!--
function OpenRandWindow(WinName,width,height)
{      	
var d = new Date();
var t = d.getTime();
var myWindow = window.open(WinName, 'Win' + t, 'resizable=yes,location=no,scrollbars=no,status=0,width=' + width + ',height='
+ height );
}
//-->
</script>
<SCRIPT Language="JavaScript">
 <!--
 // Copyright 1999, 2000 by Ray Stott - ver 2.0
 // Script is available at http://www.crays.com/jsc          

 var popWin = null    // use this when referring to pop-up window
 var winCount = 0
 var winName = "popWin"
 function openPopWin(winURL, winWidth, winHeight, winFeatures, winLeft, winTop){
   var d_winLeft = 20  // default, pixels from screen left to window left
   var d_winTop = 20   // default, pixels from screen top to window top
   winName = "popWin" + winCount++ //unique name for each pop-up window
   closePopWin()           // close any previously opened pop-up window
   if (openPopWin.arguments.length >= 4)  // any additional features? 
     winFeatures = "," + winFeatures
   else 
     winFeatures = "" 
   if (openPopWin.arguments.length == 6)  // location specified
     winFeatures += getLocation(winWidth, winHeight, winLeft, winTop)
   else
     winFeatures += getLocation(winWidth, winHeight, d_winLeft, d_winTop)
   popWin = window.open(winURL, winName, "width=" + winWidth 
            + ",height=" + winHeight + ",location=no" + winFeatures)
   }
 function closePopWin(){    // close pop-up window if it is open 
   if (navigator.appName != "Microsoft Internet Explorer" 
       || parseInt(navigator.appVersion) >=4) //do not close if early IE
     if(popWin != null) if(!popWin.closed) popWin.close() 
   }
 function getLocation(winWidth, winHeight, winLeft, winTop){
   return ""
   }
 //-->
 </SCRIPT>
 <SCRIPT Language="JavaScript1.2">  // for Netscape 4+ and IE 4+
 <!--
 function getLocation(winWidth, winHeight, winLeft, winTop){
   var winLocation = ""
   if (winLeft < 0)
     winLeft = screen.width - winWidth + winLeft
   if (winTop < 0)
     winTop = screen.height - winHeight + winTop
   if (winTop == "cen")
     winTop = (screen.height - winHeight)/2 - 20
   if (winLeft == "cen")
     winLeft = (screen.width - winWidth)/2
   if (winLeft>0 & winTop>0)
     winLocation =  ",screenX=" + winLeft + ",left=" + winLeft   
                 + ",screenY=" + winTop + ",top=" + winTop
   else
     winLocation = ""
   return winLocation
   }
 //-->
 </SCRIPT>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr class="subBar">
            <td class="subBar">&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><!form action="<? echo tep_href_link(FILENAME_MAIL, '', 'NONSSL'); ?>" method="get"><input type="hidden" name="action" value="send_email_to_user"><input type="hidden" name="all" value="0">
		<table border="0" width="100%" cellpadding="0" cellspacing="0">

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

<? // TriciaB - Start of login box. ?>

<a href="javascript:nothing()"
onClick='openPopWin("https://secure.itransact.com/support/login.html","300","250","","cen","cen")'>
<img src="http://www.itransact.com/images/cpanel_open.jpg" border=0></a>

<? // TriciaB - End of login box. ?>

</table>
</table>

<!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

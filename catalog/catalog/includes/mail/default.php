<?php
/*
  $Id: default.php,v 1.2 2001/06/10 20:19:40 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  // Create the mail object. Optional headers argument. Do not put From: here, this
  // will be added when $mail->send Does not have to have trailing \r\n
  // but if adding multiple headers, must be separated by \r\n.
  $mail = new html_mime_mail('X-Mailer: The Exchange Project Mailer');

  $text = $email_text;

  if (EMAIL_USE_HTML) {
    // If running this script upon Windows then you may need to change the fopen()
    // mode from 'r' to 'rb' - Thanks to Thomas Unger for this nugget
    $filename = $DOCUMENT_ROOT . DIR_WS_CATALOG. DIR_WS_IMAGES . 'mail/background.gif';
    $backgrnd = fread($fp = fopen($filename, 'r'), filesize($filename));
    fclose($fp);

    // If sending an html email, then these two variables ($text and $html) specify
    // the text and html versions of the mail. Don't have to be named as these are.
    // Just make sure the names tie in to the $mail->add_html() command further down.
    $html = '<html>' . "\r\n" . '<body background="background.gif">' . "\r\n" .
            '<font face="Verdana, Arial" color="#0000000"><pre>' . "\r\n" .
            $email_text . "\r\n" .
            '</pre></font>' . "\r\n" .
            '</body></html>';

    // Add the text, html and embedded images. Each embedded image has to be added
    // using $mail->add_html_image() BEFORE  calling $mail->add_html(). The name
    // of the image should match exactly (case-sensitive) to the name in the html.
    $mail->add_html_image($backgrnd, 'background.gif', 'image/gif');
    $mail->add_html($html, strip_tags($text));
  } else {
    // If not sending an html email, then this is used to set the plain text body of the email.
    $mail->set_body(strip_tags($text));
  }

  // Set Character Set
  $mail->set_charset('iso-8859-15', TRUE);

  // Builds the message.
  $mail->build_message();

  // Sends the message. $mail->build_message() is separate to $mail->send so that the
  // same email can be sent many times to differing recipients simply by putting
  // $mail->send() in a loop.
  $mail->send($firstname.' '.$lastname, // to name
              $email_address, // to email address
              strip_tags(STORE_NAME), // from name
              EMAIL_FROM, // from email address
              EMAIL_SUBJECT // subject
             );
?>
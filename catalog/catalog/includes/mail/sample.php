<?php
/*
  $Id: sample.php,v 1.1 2001/06/10 11:32:23 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License


  Having trouble? Read this article on HTML email: http://www.arsdigita.com/asj/mime/
*/

// Example of usage.

// Read the file background.gif into $backgrnd.

// !! Important !!
// If running this script upon Windows then you may need to change the fopen() mode from 'r' to 'rb'.
// Thanks to Thomas Unger for this nugget.

  $filename = 'background.gif';
  $backgrnd = fread($fp = fopen($filename, 'r'), filesize($filename));
  fclose($fp);

// Read the file test.zip into $attachment.
  $filename = 'example.zip';
  $attachment = fread($fp = fopen($filename, 'r'), filesize($filename));
  fclose($fp);

// Create the mail object. Optional headers argument. Do not put From: here, this will be added when $mail->send
// Does not have to have trailing \r\n but if adding multiple headers, must be seperated by \r\n.
  $mail = new html_mime_mail('X-Mailer: Html Mime Mail Class');

// If sending an html email, then these two variables specify the text and html versions of the mail. Don't
// have to be named as these are. Just make sure the names tie in to the $mail->add_html() command further down.
  $text = 'Success.';
  $html = '<HTML><BODY BACKGROUND="background.gif">'."\r\n".'<FONT FACE="Verdana, Arial" COLOR="#FF0000">'."\r\n".'&nbsp;&nbsp;&nbsp;&nbsp;Success...</FONT>'."\r\n".'<P></BODY></HTML>';

// Add the text, html and embedded images. Each embedded image has to be added using $mail->add_html_image() BEFORE
// calling $mail->add_html(). The name of the image should match exactly (case-sensitive) to the name in the html.
  //$mail->add_html_image($backgrnd, 'background.gif', 'image/gif');
  //$mail->add_html($html, $text);

// If not sending an html email, then this is used to set the plain text body of the email.
  $mail->set_body('ftfuygfyugyguilgulghlgjhlg'."\n\n\n".'jhlkgjguilguilguil ghjli');

// This is used to add an attachment to the email.
  //$mail->add_attachment($attachment, 'example.zip', 'application/octet-stream');

// Set Character Set
  $mail->set_charset('iso-8859-1', TRUE);

// Builds the message.
  $mail->build_message();

// Sends the message. $mail->build_message() is seperate to $mail->send so that the same email can be sent many times to
// differing recipients simply by putting $mail->send() in a loop.
  $mail->send('TO NAME', 'TO ADDRESS', 'FROM NAME', 'FROM ADDRESS', 'SUBJECT LINE');
  //$mail->send('TO NAME', 'TO ADDRESS', 'FROM NAME', 'FROM ADDRESS', 'SUBJECT LINE');

// Example of smtp_send()
/*
  $smtp = new smtp_class;

  $smtp->host_name = 'mail.yourdomain.com';                 // Address/host of mailserver
  $smtp->localhost = 'yourdomain.com';                      // Address/host of this machine / HELO:

  $from    = 'you@yourdomain.com';
  $to      = array('you@yourdomain.com');                   // Can be more than one address in this array.
  $headers = array('To: "Your Name" <you@yourdomain.com>'); // A To: header is necessary, but does
                                                            // not have to match the list in $to.

  $mail->smtp_send('smtp', $from, $to, 'Subject Line', $headers);
*/

// Example of using get_rfc822() to return the entirety of the mail and then attaching it to another mail. Fun eh?
/*
  $rfc822_email = $mail->get_rfc822('TO NAME', 'TO ADDRESS', 'FROM NAME', 'FROM ADDRESS', 'SUBJECT LINE');

  $mail2 = new html_mime_mail('X-Mailer: Big red postbox');
  $mail2->set_body("\r\n".'This is an email, that has another email attached.');
  $mail2->add_attachment($rfc822_email, 'Forwarded Email', 'message/rfc822');
  $mail2->build_message();
  $mail2->send('TO NAME', 'TO ADDRESS', 'FROM NAME', 'FROM ADDRESS', 'SUBJECT LINE');
*/

// Debug stuff. Entirely unnecessary.
  echo '<PRE>'.$mail->get_rfc822('TO NAME', 'TO ADDRESS', 'FROM NAME', 'FROM ADDRESS', 'SUBJECT LINE').'</PRE>';
?>
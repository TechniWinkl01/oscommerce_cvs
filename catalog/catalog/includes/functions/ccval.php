<?php
/*
  $Id: ccval.php,v 1.3 2001/09/01 15:24:46 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License

  Credit Card Validation Solution version 3.5 PHP Edition
  COPYRIGHT NOTICE:
  a) This code is property of The Analysis and Solutions Company.
  b) It is being distributed free of charge and on an "as is" basis.
  c) Use of this code, or any part thereof, is contingent upon leaving
      this copyright notice, name and address information in tact.
  d) Written permission must be obtained from us before this code, or any
      part thereof, is sold or used in a product which is sold.
  e) By using this code, you accept full responsibility for its use
      and will not hold the Analysis and Solutions Company, its employees
      or officers liable for damages of any sort.
  f) This code is not to be used for illegal purposes.
  g) Please email us any revisions made to this code.

  Copyright 2000                 http://www.AnalysisAndSolutions.com/code/
  The Analysis and Solutions Company         info@AnalysisAndSolutions.com
*/

  function CCValidationSolution($Number) {
    global $CardName, $CardNumber;

// Get rid of spaces and non-numeric characters.
    $Number = OnlyNumericSolution($Number);

// Do the first four digits fit within proper ranges? If so, who's the card issuer and how long should the number be?
    $NumberLeft = substr($Number, 0, 4);
    $NumberLength = strlen($Number);

    if ( ($NumberLeft >= 3000) && ($NumberLeft <= 3059) ) {
      $CardName = 'Diners Club';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 3600) && ($NumberLeft <= 3699) ) {
      $CardName = 'Diners Club';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 3800) && ($NumberLeft <= 3889) ) {
      $CardName = 'Diners Club';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 3400) && ($NumberLeft <= 3499) ) {
      $CardName = 'American Express';
      $ShouldLength = 15;
    } elseif ( ($NumberLeft >= 3700) && ($NumberLeft <= 3799) ) {
      $CardName = 'American Express';
      $ShouldLength = 15;
    } elseif ( ($NumberLeft >= 3528) && ($NumberLeft <= 3589) ) {
      $CardName = 'JCB';
      $ShouldLength = 16;
    } elseif ( ($NumberLeft >= 3890) && ($NumberLeft <= 3899) ) {
      $CardName = 'Carte Blache';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 4000) && ($NumberLeft <= 4999) ) {
      $CardName = 'Visa';
      if ($NumberLength > 14) {
        $ShouldLength = 16;
      } elseif ($NumberLength < 14) {
        $ShouldLength = 13;
      } else {
        $cc_val = 'The <b>Visa</b> number entered, ' . $Number . ', in is 14 digits long. <b>Visa</b> cards usually have 16 digits, though some have 13.<br>Please check the number and try again.';
        return $cc_val;
      }
    } elseif ( ($NumberLeft >= 5100) && ($NumberLeft <= 5599) ) {
      $CardName = 'MasterCard';
      $ShouldLength = 16;
    } elseif ($NumberLeft == 5610) {
      $CardName = 'Australian BankCard';
      $ShouldLength = 16;
    } elseif ($NumberLeft == 6011) {
      $CardName = 'Discover/Novus';
      $ShouldLength = 16;
    } else {
      $cc_val = 'The first four digits of the number entered are ' . $NumberLeft . '.<br>&nbsp;If that\'s correct, we don\'t accept that type of credit card.<br>&nbsp;If it\'s wrong, please try again.';
      return $cc_val;
    }

// Is the number the right length?
    if ($NumberLength <> $ShouldLength) {
      $Missing = $NumberLength - $ShouldLength;
      if ($Missing < 0) {
        $cc_val = 'The <b>' . $CardName . '</b> number entered, ' . $Number . ', is <font color="#FF0000"><b>missing</b></font> ' . abs($Missing) . ' digit(s).<br>&nbsp;Please check the number and try again.';
      } else {
        $cc_val = 'The <b>' . $CardName . '</b> number entered, ' . $Number . ', has ' . $Missing . ' too many digit(s).<br>&nbsp;Please check the number and try again.';
      }

      return $cc_val;
    }

// Does the number pass the Mod 10 Algorithm Checksum?
    if (Mod10Solution($Number)) {
     $CardNumber = $Number;
     return true;
    } else {
      $cc_val = 'The <b>' . $CardName . '</b> number entered, ' . $Number . ', is <font color="#FF0000"><b>invalid</b></font>. Please check the number and try again.';
      return $cc_val;
    }
  }

  function OnlyNumericSolution($Number) {
// Remove any non numeric characters.
// Ensure number is no more than 19 characters long.
    return substr(ereg_replace('[^0-9]', '', $Number) , 0, 19);
  }

  function Mod10Solution($Number) {
    $NumberLength = strlen($Number);
    $Checksum = 0;

// Add even digits in even length strings or odd digits in odd length strings.
    for ($Location = 1-($NumberLength%2); $Location<$NumberLength; $Location+=2) {
      $Checksum += substr($Number, $Location, 1);
    }

// Analyze odd digits in even length strings or even digits in odd length strings.
    for ($Location = ($NumberLength%2); $Location<$NumberLength; $Location+=2) {
      $Digit = substr($Number, $Location, 1) * 2;
      if ($Digit < 10) {
        $Checksum += $Digit;
      } else {
        $Checksum += $Digit - 9;
      }
    }

// Is the checksum divisible by ten?
    return ($Checksum % 10 == 0);
  }

  function ValidateExpiry ($month, $year) {
    $cc_val = '';
    $year = '20' . $year;

    if (date('Y') == $year) {
      if (date('m') <= $month) {
        $cc_val = '1';
      } else {
        $cc_val = 'The expiry date entered, ' . $month . '/' . $year . ', is <font color="#FF0000"><b>invalid</b></font>. Please check the date and try again.';
      }
    } elseif (date('Y') > $year) {
        $cc_val = 'The expiry date entered, ' . $month . '/' . $year . ', is <font color="#FF0000"><b>invalid</b></font>. Please check the date and try again.';
    } else {
      $cc_val = '1';
    }

    return $cc_val;
  }
?>
<?

function tep_validate_email($email)  // Validate the email address by checking the users mail server
{  
 if (!eregi("^[a-z0-9]+([\.%!][_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$", $email))
 {
  $return = false;
 }
 else
 {
  list($user, $host) = explode("@", $email);
  if (checkdnsrr($host, "MX") or checkdnsrr($host, "A")) 
  {
   $return = true;
  } 
  else
  {
   $return = false;
  }
 }
 return $return;
}

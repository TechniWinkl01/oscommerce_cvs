<?php 
class USPS { 
    var $server = ""; 
    var $user = ""; 
    var $pass = ""; 
    var $service = ""; 
    var $dest_zip; 
    var $orig_zip; 
    var $pounds; 
    var $ounces; 
    var $container = "None"; 
    var $size = "REGULAR"; 
    var $machinable; 
     
    function setServer($server) { 
        $this->server = $server; 
    } 

    function setUserName($user) { 
        $this->user = $user; 
    } 

    function setPass($pass) { 
        $this->pass = $pass; 
    } 

    function setService($service) { 
        /* Must be: Express, Priority, or Parcel */ 
        $this->service = $service; 
    } 
     
    function setDestZip($sending_zip) { 
        /* Must be 5 digit zip (No extension) */ 
        $this->dest_zip = $sending_zip; 
    } 

    function setOrigZip($orig_zip) { 
        $this->orig_zip = $orig_zip; 
    } 

    function setWeight($pounds, $ounces=0) { 
        /* Must weight less than 70 lbs. */ 
        $this->pounds = $pounds; 
        $this->ounces = $ounces; 
    } 

    function setContainer($cont) { 
        /* 
        Valid Containers 
                Package Name             Description 
        Express Mail 
                None                For someone using their own package 
                0-1093 Express Mail         Box, 12.25 x 15.5 x 
                0-1094 Express Mail         Tube, 36 x 6 
                EP13A Express Mail         Cardboard Envelope, 12.5 x 9.5 
                EP13C Express Mail         Tyvek Envelope, 12.5 x 15.5 
                EP13F Express Mail         Flat Rate Envelope, 12.5 x 9.5 

        Priority Mail 
                None                For someone using their own package 
                0-1095 Priority Mail        Box, 12.25 x 15.5 x 3 
                0-1096 Priority Mail         Video, 8.25 x 5.25 x 1.5 
                0-1097 Priority Mail         Box, 11.25 x 14 x 2.25 
                0-1098 Priority Mail         Tube, 6 x 38 
                EP14 Priority Mail         Tyvek Envelope, 12.5 x 15.5 
                EP14F Priority Mail         Flat Rate Envelope, 12.5 x 9.5 
         
        Parcel Post 
                None                For someone using their own package 
        */ 

        $this->container = $cont; 
    } 

    function setSize($size) { 
        /* Valid Sizes 
        Package Size                Description        Service(s) Available 
        Regular package length plus girth     (84 inches or less)    Parcel Post 
                                        Priority Mail  
                                        Express Mail  

        Large package length plus girth        (more than 84 inches but    Parcel Post 
                            not more than 108 inches)    Priority Mail 
                                        Express Mail  

        Oversize package length plus girth   (more than 108 but        Parcel Post 
                             not more than 130 inches) 

        */ 
        $this->size = $size; 
    } 

    function setMachinable($mach) { 
        /* Required for Parcel Post only, set to True or False */ 
        $this->machinable = $mach; 
    } 
     
    function getPrice() { 
        // may need to urlencode xml portion 
        $str = $this->server. "?API=Rate&XML=<RateRequest%20USERID=\""; 
        $str .= $this->user . "\"%20PASSWORD=\"" . $this->pass . "\"><Package%20ID=\"0\"><Service>"; 
        $str .= $this->service . "</Service><ZipOrigination>" . $this->orig_zip . "</ZipOrigination>"; 
        $str .= "<ZipDestination>" . $this->dest_zip . "</ZipDestination>"; 
        $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounces . "</Ounces>"; 
        $str .= "<Container>" . $this->container . "</Container><Size>" . $this->size . "</Size>"; 
        $str .= "<Machinable>" . $this->machinable . "</Machinable></Package></RateRequest>"; 
        // echo $str;

        $fp = fopen($str, "r");
        if (!$fp) {
          $body = 'Error';
        } else {
          while(!feof($fp)){  
            $result = fgets($fp, 500);  
            $body.=$result; 
          }  
          fclose($fp); 
        }

        # note: using split for systems with non-perl regex (don't know how to do it in sys v regex) 
        if (!ereg("Error", $body)) { 
            $split = split("<Postage>", $body);  
            $body = split("</Postage>", $split[1]); 
            $price = $body[0]; 
            return($price); 
        } else { 
            return(false); 
        } 
    } 

    function trackPackage($ids) { 
        $url = "$this->server?API=Track&XML="; 
        $xml = "<TrackRequest USERID=\"$this->user\" PASSWORD=\"$this->pass\">"; 
     
        for ($i=0;$i<count($ids);$i++) { 
            $id = $ids[$i]; 
            $xml .= "<TrackID ID='$id'></TrackID>"; 
        } 
         
        $xml .= "</TrackRequest>"; 
        $xml = urlencode($xml); 
        $url = $url . $xml; 

        $fp = fopen($url, "r"); 
        while (!feof($fp)) { 
            $str .= fread($fp, 80); 
        } 
        fclose($fp); 
         
        $cnt = 0; 

        $text = split("<TrackInfo ID=", $str); 
        for ($i=0;$i<count($text);$i++) { 
            if (ereg("<TrackSummary>(.+)</TrackSummary>", $text[$i], $regs)) { 
                $values["eta"] = $regs[1]; 
                if (eregi("delivered", $values["eta"])) { 
                    $values["eta"] = "Delivered"; 
                } else { 
                    $values["eta"] = "In Transit"; 
                } 
                $cnt++; 
            } 
        } 
        $values["type"] = "Priority Mail"; 

        return $values; 
    } 
} 
// Example
// $usps = new USPS; 
// $usps->setDestZip($zip); 
// $usps->setOrigZip($vendor_zip); 
// $usps->setWeight($pounds, $ounces); 
// $price = $usps->getPrice();
?>

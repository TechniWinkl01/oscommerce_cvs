<?php
	/*
	 * $Id: _fedex.php,v 1.1 2001/03/24 00:28:12 hpdl Exp $
	 *
	 * FedEx Shipping Calculator.
	 * Inspired by the UPS Shipping Class.  Calculate shipping costs
	 * through FedEx.  Currently doesn't support accessorials, but
	 * that could be added easly enough if needed.
	 *
	 * Gotta add error checking soon I suppose.
	 *
	 * Error Numbers:
	 *		902 - An invalid origin zip/postal code was entered.
	 *		903 - An invalid destination zip/postal code was entered.
	 *		904 - An invalid origin country code was entered.
	 *		905 - An invalid destination country code was entered.
	 *		935 - An invalid weight was entered.
	 *		946 - An invalid screen was entered.
	 *		948 - An Invalid Accessorial code was entered.
	 */
/*
	$rate = new FedEx();
	$rate->SetOrigin(95991, 'US');
	$rate->SetDest(C1C1C1, 'CA');
	$rate->SetWeight(50);
	$quote = $rate->GetQuote();
	print $quote['Service'] . "<br>";
	print $quote['TotalCharges'] . "\n";
*/
	class _FedEx {
		var $Screen = 'Ground';
		var $OriginZip;
		var $OriginCountryCode = 'US';
		var $DestZip;
		var $DestCountryCode = 'US';
		var $Weight = 0;
		var $WeightUnit = 'LBS';
		var $Length = 0;
		var $Width = 0;
		var $Height = 0;
		var $DimUnit = 'IN';
		function _FedEx($zip = NULL, $country = NULL) {
			if($zip) {
				$this->SetOrigin($zip, $country);
			}
		}
		function SetOrigin($zip, $country = NULL) {
			$this->OriginZip = $zip;
			if($country) {
				$this->OriginCountryCode = $country;
			}
		}
		function SetDest($zip, $country = NULL) {
			$this->DestZip = $zip;
			if($country) {
				$this->DestCountryCode = $country;
			}
		}
		function SetWeight($weight, $units = NULL) {
			$this->Weight = $weight;
			if($units) {
				$this->WeightUnit = $units;
			}
		}
		function SetSize($length = NULL, $width = NULL, $height = NULL, $units = NULL) {
			if($length) {
				$this->Length = $length;
			}
			if($width) {
				$this->Width = $width;
			}
			if($height) {
				$this->Height = $height;
			}
			if($units) {
				$this->DimUnit = $units;
			}
		}

		function GetQuote() {
			$url = array(
				'http://grd.fedex.com/cgi-bin/rrr2010.exe?func=Rate',
				'Screen=' . $this->Screen,
				'OriginZip=' . $this->OriginZip,
				'OriginCountryCode=' . $this->OriginCountryCode,
				'DestZip=' . $this->DestZip,
				'DestCountryCode=' . $this->DestCountryCode,
				'Weight=' . $this->Weight,
				'WeightUnit=' . $this->WeightUnit,
				'DimUnit=' . $this->DimUnit
			);
			if($this->Length) {
				$url[] = 'Length=' . $this->Length;
			}
			if($this->Width) {
				$url[] = 'Width=' . $this->Width;
			}
			if($this->Height) {
				$url[] = 'Height=' . $this->Height;
			}
			$url = join('&', $url);
			$fp = fopen($url, 'r');
			while(!feof($fp)) {
				$line = trim(fgets($fp, 1024));
				if($line == '<!-- End Reply Message -->') {
					break;
				}
				if($ok) {
					$p = strpos($line, '>');
					$tag = substr($line, 2, ($p - 2));
					$text = strip_tags($line);
					if($line[1] == '!') {
// print "<!-- " . htmlentities($tag) . " - " . htmlentities($text) . " -->\n";
						$output[$tag] = $text;
					}
				}
				else {
					if($line == '<!-- Begin Reply Message -->') {
						$ok = 1;
					}
				}
			}
			fclose($fp);
			return $output;
		}
	}
?>

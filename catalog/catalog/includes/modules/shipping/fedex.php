<?php
	/* $Id: fedex.php,v 1.9 2001/03/02 13:19:02 tmoulton Exp $ */
        if ($action != 'install' && $action != 'remove' && $action != 'check') { // Only use language for catalog
	  $include_file = DIR_LANGUAGES . $language . '/modules/shipping/fedex.php';include(DIR_INCLUDES . 'include_once.php');
        }
	// only these three are needed since FedEx only ships to them
	// convert TEP country id to ISO 3166 id
	$fedex_countries = array(38 => 'CA',138 => 'MX',223 => 'US');
	$fedex_countries_nbr = array(38,138,223);
	switch($action) {
		case 'select' :
			print "<TR><TD><FONT FACE=\"" . TEXT_FONT_FACE . "\" SIZE=\"" . TEXT_FONT_SIZE . "\" COLOR=\"" . TEXT_FONT_COLOR . "\">&nbsp;";
			print htmlentities(SHIPPING_FEDEX_NAME) . "</FONT></TD>";
			print "<TD>&nbsp;</TD>";
			print "<TD ALIGN=\"right\">&nbsp;<INPUT TYPE=\"checkbox\" NAME=\"shipping_quote_fedex\" VALUE=\"1\"";
			// if(!$shipping_count) {
				print ' CHECKED';
			// }
			print "></TD></TR>\n";
			break;
		case 'quote' :
			if ($shipping_quote_fedex == "1" || $shipping_quote_all == '1') {
				$shipping_quoted = 'fedex';
				// only calculate if FedEx ships there.
				if(in_array($address_values['country_id'], $fedex_countries_nbr)) {
					include(DIR_CLASSES . 'fedex.php');
					$rate = new FedEx(STORE_ORIGIN_ZIP, STORE_ORIGIN_COUNTRY);
					$rate->SetDest($address_values['postcode'], $fedex_countries[$address_values['country_id']]);
					$rate->SetWeight($shipping_weight);
					$quote = $rate->GetQuote();
					$shipping_fedex_cost = $shipping_num_boxes * (SHIPPING_HANDLING + $quote['TotalCharges']);
					// clean up the service text a little
					$shipping_fedex_method = str_replace(' Package', '', $quote['Service']);
					$shipping_fedex_method = str_replace(' FedEx', '', $shipping_fedex_method);
					$shipping_fedex_method = $shipping_fedex_method . ' ' . $shipping_num_boxes . ' X ' . $shipping_weight;
				}
				else {
					$quote['ErrorNbr'] = 1;
					$quote['Error'] = SHIPPING_FEDEX_NOTAVAILABLE;
				}
			}
			break;
		case 'cheapest' :
			if($shipping_quote_fedex == "1" || $shipping_quote_all == '1') {
				// only calculate if FedEx ships there.
				if(in_array($address_values['country_id'], $fedex_countries_nbr) AND !$quote['ErrorNbr']) {
					if($shipping_count == 0) {
						$shipping_cheapest = 'fedex';
						$shipping_cheapest_cost = $shipping_fedex_cost;
					}
					else {
						if($shipping_fedex_cost < $shipping_cheapest_cost) {
							$shipping_cheapest = 'fedex';
							$shipping_cheapest_cost = $shipping_fedex_cost;
						}
					}
				}
			}
			break;
		case 'display' :
			if($shipping_quote_fedex == "1" || $shipping_quote_all == '1') {
				// check for errors
				if($quote['ErrorNbr']) {
					print "<TR>\n";
					print '<TD><FONT FACE="' . TEXT_FONT_FACE . '" SIZE="' . TEXT_FONT_SIZE . '" COLOR="' . TEXT_FONT_COLOR . '">&nbsp;' . htmlentities(SHIPPING_FEDEX_NAME) . "</FONT></TD>\n";
					print '<TD><FONT FACE="' . TEXT_FONT_FACE . '" SIZE="' . TEXT_FONT_SIZE . '" COLOR="' . TEXT_FONT_COLOR . '"><FONT COLOR="red">Error:</FONT> ' . htmlentities($quote['Error']) . "</FONT></TD>\n";
					print '<TD ALIGN="right"><FONT FACE="' . TEXT_FONT_FACE . '" SIZE="' . TEXT_FONT_SIZE . '" COLOR="' . TEXT_FONT_COLOR . "\">&nbsp;</FONT></TD>\n";
					print "<TD ALIGN=\"right\" NOWRAP>&nbsp;</TD>\n";
					print "</TR>\n";
				}
				else {
					print "<TR>\n";
					print '<TD><FONT FACE="' . TEXT_FONT_FACE . '" SIZE="' . TEXT_FONT_SIZE . '" COLOR="' . TEXT_FONT_COLOR . '">&nbsp;' . htmlentities(SHIPPING_FEDEX_NAME) . "</FONT></TD>\n";
					print '<TD><FONT FACE="' . TEXT_FONT_FACE . '" SIZE="' . TEXT_FONT_SIZE . '" COLOR="' . TEXT_FONT_COLOR . '">' . $shipping_fedex_method . "</FONT></TD>\n";
					print '<TD ALIGN="right"><FONT FACE="' . TEXT_FONT_FACE . '" SIZE="' . TEXT_FONT_SIZE . '" COLOR="' . TEXT_FONT_COLOR . '">' . tep_currency_format($shipping_fedex_cost) . "</FONT></TD>\n";
					print '<TD ALIGN="right" NOWRAP>&nbsp;<INPUT TYPE="radio" NAME="shipping_selected" VALUE="fedex"';
					if($shipping_cheapest == 'fedex') {
						print ' CHECKED';
					}
					print ">&nbsp;</TD>\n";
					print "</TR>\n";
					print '<INPUT TYPE="hidden" NAME="shipping_fedex_cost" VALUE="' . $shipping_fedex_cost . "\">\n";
					print '<INPUT TYPE="hidden" NAME="shipping_fedex_method" VALUE="' . $shipping_fedex_method . "\">\n";
				}
			}
			break;
		case 'confirm' :
			if($HTTP_POST_VARS['shipping_selected'] == 'fedex') {
				$shipping_cost = $HTTP_POST_VARS['shipping_fedex_cost'];
				$shipping_method = $HTTP_POST_VARS['shipping_fedex_method'];
			}
			break;
		case 'check' :
			$check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_FEDEX_ENABLED'");
			$check = tep_db_num_rows($check) + 1;
			break;
		case 'install' :
			tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable FedEx Shipping', 'SHIPPING_FEDEX_ENABLED', '1', 'Do you want to offer Federal Express (FedEx) shipping?', '7', '10', now())");
			break;
		case 'remove' :
			tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_FEDEX_ENABLED'");
			break;
	}
	$shipping_count++;
?>

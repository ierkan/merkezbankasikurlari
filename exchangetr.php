<?php

	function getExchangeRate($currencyType, $dateWithTime) {
		if (empty($dateWithTime)) {
			$dateWithTime = new DateTime();
		}

		list($xmlURL, $previousDate) = getXmlURL($dateWithTime);

		if (isset($xmlURL)) {
			$URL_headers = get_headers($xmlURL);
			$notFound = "HTTP/1.0 404 Not Found";
			$found = "HTTP/1.1 200 OK";

		  if (empty($URL_headers) || $URL_headers[0] == $notFound) {
				return getExchangeRate($currencyType, $previousDate);
		  } elseif (is_array($URL_headers) && $URL_headers[0] == $found) {
				$exchangeRates = simplexml_load_file($xmlURL);
				$result = $exchangeRates->Currency[$currencyType]->BanknoteSelling;
				return $result;
			}
		}	else {
			return 0;
		}
	} // end of getExchangeRate function

	/*
	 * @param
	 * @return Returns 0 for future date&time requests and erroneous requests
	 */
	function getXmlURL($dateWithTime) {
		// Detect the current date&time in REVERSE ORDER
		$todayDate = date('Y.m.d');
		$todayDate = preg_replace('/[^0-9]/', '', $todayDate);
		$todayDateInt = (int)$todayDate;

		$todayTime = date('h:i:s');
		$todayTime = preg_replace('/[^0-9]/', '', $todayTime);
		$todayTimeInt = (int)$todayTime;

		# Detect yesterday date&time in PROPER ORDER
		$yesterdayDate = date('d.m.Y',strtotime("-1 days"));
		$yesterdayDate = preg_replace('/[^0-9]/', '', $yesterdayDate);

		# Detect the requested date&time
		$requestedDate = strtotime($dateWithTime);
		if ($requestedDate) {
			$requestedDate = date('d.m.Y h:i:s', $requestedDate);
			$dateWithTime = preg_replace('/[^0-9]/', '', $requestedDate);
		} else {
			$dateWithTime = preg_replace('/[^0-9]/', '', $dateWithTime);
		}

		$stringLength = strlen($dateWithTime);
		if ($stringLength == 14) {

			# ... substr(string, startIndex, length);
			$dayNum = substr($dateWithTime, 0, 2);
			$monthNum = substr($dateWithTime, 2, 2);
			$yearNum = substr($dateWithTime, 4, 4);

			$folder = $yearNum.$monthNum;
			$requestedDate = $folder.$dayNum;
			$requestedDateInt = (int)$requestedDate;

			$requestedTime = substr($dateWithTime, 8, 6);
			$requestedTimeInt = (int)$requestedTime;


			if ($todayDateInt < $requestedDateInt) { # //////////////////////////////

				# Return false for future date&time requests
				return array(false, false);

			} # /////////////////////////////////////////////////////////////////////

			if ($todayDate == $requestedDate) { # ///////////////////////////////////

				# TCMB publishes daily exchange rates after 15.30 pm
				# All requests before 15.30 are subject to previous day's exchange rate

				if ($requestedTimeInt < 153000) {
					$xmlURL = "http://www.tcmb.gov.tr/kurlar/".$folder."/".$yesterdayDate.".xml";
				} elseif ($requestedTimeInt >= 153000) {
					if ($todayTimeInt < 153000) {
						# Return false for future date&time requests
						return array(false, false);
					} elseif ($todayTimeInt >= 153000) {
						$xmlURL = "http://www.tcmb.gov.tr/kurlar/today.xml";
					}
				}
			} # /////////////////////////////////////////////////////////////////////

			if ($todayDateInt > $requestedDateInt) { # //////////////////////////////
				$xmlURL = "http://www.tcmb.gov.tr/kurlar/".$folder."/".$dayNum.$monthNum.$yearNum.".xml";
			} # /////////////////////////////////////////////////////////////////////

			$previousDate = date('d.m.Y', strtotime("-1 day", strtotime($dayNum.".".$monthNum.".".$yearNum)))." - 19:30:00";
			return array($xmlURL, $previousDate);

		} else {
				return array(false, false);
		}
	}

?>

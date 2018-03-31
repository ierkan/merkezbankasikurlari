<?php

	function getExchangeRate($currencyType, $dateStringWithTime=null) {
		$format = 'd-m-Y - H:i';
		if (empty($dateStringWithTime)) {
			$targetDate = new DateTime();
		} else {
			if (dateIsValid($dateStringWithTime)) {
				$targetDate = DateTime::createFromFormat($format, $dateStringWithTime);
			} else {
				return 'Invalid date! The date should be in ' . $format . ' format! Ex: 19-03-2018 - 13:30';
			}
		}

		$xmlURL = constructUrlFrom($targetDate);
		//die($xmlURL);

		if (!pageExists($xmlURL)) {
			$xmlURL = findLastValidDateBefore($targetDate);
		}

		if (is_null($xmlURL)) {
			return 'An error occured! Cannot find a valid day!';
		}

		$exchangeRates = simplexml_load_file($xmlURL);
		$result = $exchangeRates->Currency[$currencyType]->BanknoteSelling;
		return $result;

	}

	// Bakalım formata uygun bir tarih stringi gönderilmiş mi
	function dateIsValid($date, $format = 'd-m-Y - H:i')	{
			try {
				$d = DateTime::createFromFormat($format, $date);
				return $d && $d->format($format) == $date;
			} catch (Exception $e) {
				var_dump (DateTime::getLastErrors());
			}
	}

	//Şöyle bir url string oluştur: http://www.tcmb.gov.tr/kurlar/201803/26032018.xml
	function constructUrlFrom($targetDate) {
		if (empty($targetDate)) {
			echo "Invalid date! Cannot construct url from nothing!";
			return NULL;
		} else {
			$xmlurl = 'http://www.tcmb.gov.tr/kurlar/';
			$xmlurl = $xmlurl . $targetDate->format('Ym/dmY') . '.xml';
			return $xmlurl;
		}
	}

	// bakalım böyle bir sayfa var mı
	function pageExists($url) {
		if (empty($url)) {
			echo('Cannot check a blank url');
			return FALSE;
		}
		$URL_headers = get_headers($url);
		if (is_array($URL_headers) && $URL_headers[0] == 'HTTP/1.1 200 OK') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	// bu tarihten önceki en yakın geçerli günü bul (en fazla 15 gün geriye kadar git)
	function findLastValidDateBefore($targetDate) {
		$maxDays = 15;

		for ($i=1; $i <= $maxDays; $i++) {
			$theDate = $targetDate->modify('-' . $i . ' day');
			$url = constructUrlFrom ($theDate);
			if (pageExists($url)) {
				return $url;
			}
		}
		return NULL;
	}


?>

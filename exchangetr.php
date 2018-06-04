<?php

	require_once('sabitler.php');
	require_once('sonuc.php');

	$Sabitler = new Sabitler();

	function getExchangeRate($currencyType, $dateStringWithTime=null) {
		global $Sabitler;
		$sonuc = new sonuc();

		$format = 'd-m-Y - H:i';
		if (empty($dateStringWithTime)) {
			$targetDate = new DateTime();
		} else {
			if (dateIsValid($dateStringWithTime)) {
				$targetDate = DateTime::createFromFormat($format, $dateStringWithTime);
			} else {
				$sonuc->setHata('Geçersiz tarih! Tarih formatı \'' . $format . '\' şeklinde olmalıdır. Örn: 19-03-2018 - 13:30');
				return $sonuc;
			}
		}

		if (!isset($currencyType)) {
			$sonuc->setHata('Geçersiz kur!');
			return $sonuc;
		} else {
			if (!is_int($currencyType)) {
				try {
					$currencyType = intval($currencyType);
				}
				catch (Exception $e) {
					$sonuc->setHata('Geçersiz kur değişkeni!');
					return $sonuc;
				}
			}
		}

		$xmlURL = constructUrlFrom($targetDate);

		if (!pageExists($xmlURL)) {
			$xmlURL = findLastValidDateBefore($targetDate);
		}

		if (is_null($xmlURL)) {
			$sonuc->setHata('Bir hata oluştu. Geçerli bir geçmiş gün bulunamadı!');
			return $sonuc;
		}


		$exchangeRates = simplexml_load_file($xmlURL);
		$result = (string)$exchangeRates->Currency[$currencyType]->BanknoteSelling;
		$sonuc->veri($result, $targetDate, $Sabitler->kurlar[$currencyType]);
		return $sonuc;

	}

	// Kullanıcının istediği tarih bugün mü
	function dateIsToday($targetDate) {
		try {
			if ($targetDate == null) {
				return true;
			} else {
				$format = 'd-m-Y';
				$d = DateTime::createFromFormat($format, $targetDate->format($format));
				//echo $targetDate->format('d-m-Y - H:i');
				$dateNow = new DateTime();
				//echo $dateNow->format('d-m-Y - H:i');
				return $d >= $dateNow;
			}
		} catch (Exception $e) {
			var_dump (DateTime::getLastErrors());
		}
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
		} elseif (dateIsToday($targetDate)) {
			$xmlurl = 'http://www.tcmb.gov.tr/kurlar/today.xml';
			return $xmlurl;
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

		// create curl resource
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url); // set url
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
		curl_setopt($ch, CURLOPT_HEADER, 1); //enable headers
		curl_setopt($ch, CURLOPT_NOBODY, 1); //get only headers
		$output = curl_exec($ch); // $output contains the output string
		curl_close($ch);// close curl resource to free up system resources
		//echo $output;
		$konum = strpos($output,"\r\n");
		$header = substr ($output,0,$konum);
		if ($header == "HTTP/1.1 200 OK") {
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

<?php

	require_once('sonuc.php');
	require_once('sabitler.php');

	function getExchangeRate($currencyType, $dateStringWithTime=null) {
		$sonuc = new sonuc();

		$format = 'd-m-Y - H:i';
		if (empty($dateStringWithTime)) {
			$targetDate = new DateTime();
		} else {
			if (dateIsValid($dateStringWithTime)) {
				$targetDate = DateTime::createFromFormat($format, $dateStringWithTime);
			} else {
				$sonuc->hata('Geçersiz tarih! Tarih formatı \'' . $format . '\' şeklinde olmalıdır. Örn: 19-03-2018 - 13:30');
				return $sonuc;
			}
		}

		if (empty($currencyType)) {
			$sonuc->hata('Geçersiz kur!');
			return $sonuc;
		} else {
			if (!is_int($currencyType)) {
				try {
					$currencyType = intval($currencyType);
				}
				catch (Exception $e) {
					$sonuc->hata('Geçersiz kur değişkeni!');
					return $sonuc;
				}
			}
		}

		$xmlURL = constructUrlFrom($targetDate);

		if (!pageExists($xmlURL)) {
			$xmlURL = findLastValidDateBefore($targetDate);
		}

		if (is_null($xmlURL)) {
			$sonuc->hata('Bir hata oluştu. Geçerli bir geçmiş gün bulunamadı!');
			return $sonuc;
		}

		$exchangeRates = simplexml_load_file($xmlURL);
		$result = $exchangeRates->Currency[$currencyType]->BanknoteSelling;
		$sonuc->veri($result);
		return $sonuc;

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

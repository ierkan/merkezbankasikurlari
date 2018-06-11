<?php
  require_once ('exchangetr.php');

  $timezone = new DateTimeZone('Europe/Istanbul');
  if(isset($_POST['targetdate'])) {
    $targetDate = DateTime::createFromFormat("Y-m-d\TH:i:s",$_POST['targetdate']);
    if (!$targetDate) {
      $sonuc = new sonuc();
      $sonuc->setHata("Tarih geçersiz. Gönderilen tarih Y-m-d\TH:i:s formatında olmalıdır. Örneğin: 2017-05-29T00:00:00");
      echo json_encode($sonuc);
      die();
    }
  } else {
    $targetDate = new DateTime('now', $timezone);
  }

  if(isset($_POST['currency'])) {
    $targetCurrency = $_POST['currency'];
    if (!is_numeric($targetCurrency)) {
      $sonuc = new sonuc();
      $sonuc->setHata("Gecersiz kur girildi.");
      echo json_encode($sonuc);
      die();
    }
  } else {
    $targetCurrency = '0';//0:USD
  }

  $a = getExchangeRate($targetCurrency, $targetDate->format('d-m-Y - H:i'));//0:dolar

  echo json_encode($a);
?>

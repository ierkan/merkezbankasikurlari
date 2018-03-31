<?php
  require_once ('exchangetr.php');

  $timezone = new DateTimeZone('Europe/Istanbul');
  if(isset($_POST['targetdate'])) {
    $targetDate = DateTime::createFromFormat("Y-m-d\TH:i:s",$_POST['targetdate']);
  } else {
    $targetDate = new DateTime('now', $timezone);
  }

  if(isset($_POST['currency'])) {
    $targetCurrency = $_POST['currency'];
  } else {
    $targetCurrency = '0';//0:USD
  }

  $a = getExchangeRate($targetCurrency, $targetDate->format('d-m-Y - H:i'));//0:dolar

  $mesaj = "";
  if ($a->durum ==='başarılı') {
    $mesaj = $a->veri;
  } elseif ($a->durum ==='uyarı') {
    $mesaj = $a->veri . ' (' . $a->aciklama . ')';
  } else { //hata
    $mesaj = $a->durum . ' (' . $a->aciklama . ')';
  }
?>

<!DOCTYPE html>
<html lang="tr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Merkez Bankası Kurları</title>
  </head>
  <body>
    <form class="" action="index.php" method="post">
      <input id="targetdate" name="targetdate" type="datetime-local"
             autofocus="autofocus"
             value="<?php echo $targetDate->format('Y-m-d\TH:i:s'); ?>"
             required = "required">
      <select class="" name="currency">
        <option value="0">USD (ABD DOLARI)</option>
        <option value="1">AUD (AVUSTRALYA DOLARI)</option>
        <option value="2">DKK (DANİMARKA KRONU)</option>
        <option value="3">EUR (EURO)</option>
        <option value="4">GPB (İNGİLİZ STERLİNİ)</option>
        <option value="5">CHF (İSVİÇRE FRANGI)</option>
        <option value="6">SEK (İSVEÇ KRONU)</option>
        <option value="7">CAD (KANADA DOLARI)</option>
        <option value="8">KWD (KUVEYT DİNARI)</option>
        <option value="9">NOK (NORVEÇ KRONU)</option>
        <option value="10">SAR (SUUDİ ARABİSTAN RİYALİ)</option>
        <option value="11">JPY (100	JAPON YENİ)</option>
        <option value="12">BGN (BULGAR LEVASI)</option>
        <option value="13">RON (RUMEN LEYİ)</option>
        <option value="14">RUB (RUS RUBLESİ)</option>
        <option value="15">IRR (100 İRAN RİYALİ)</option>
        <option value="16">CNY (ÇİN YUANI)</option>
        <option value="17">PKR (PAKİSTAN RUPİSİ)</option>
      </select>
      <input type="submit" value="Getir">
    </form>
    <h2>
      <?php echo $targetDate->format('d-m-Y - H:i') . " tarihi kuru: " . $mesaj ?>
    </h2>
  </body>
</html>
<script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"/>

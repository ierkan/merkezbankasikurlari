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
        <?php
          foreach ($Sabitler->kurlar as $key => $value) {
            if($key == $targetCurrency) {
              echo("<option value='$key' selected='selected'>$value</option>");
            } else {
              echo("<option value='$key'>$value</option>");
            }
          }
        ?>
      </select>
      <input type="submit" value="Getir">
    </form>
    <h2>
      <?php echo $targetDate->format('d-m-Y - H:i') . " tarihli " . $Sabitler->kurlar[$targetCurrency] . " kuru: " . $mesaj ?>
    </h2>
  </body>
</html>
<script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"/>

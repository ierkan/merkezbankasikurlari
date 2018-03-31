<?php
  require_once ('exchangetr.php');
  //$a = getExchangeRate(0);//0:dolar -> bugün
  $a = getExchangeRate(0,"29-03-2018 - 15:35");//0:dolar

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
    <h2>
      <?php echo "Dolar kuru: " . $mesaj ?>
    </h2>
  </body>
</html>

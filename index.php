<?php
  require_once ('exchangetr.php');
  $a = getExchangeRate(0,"29.03.2018 - 15:35:00");//0:dolar
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Merkez Bankası Kurları</title>
  </head>
  <body>
    <h2>
      <?php echo "Dolar kuru: " . $a ?>
    </h2>
  </body>
</html>

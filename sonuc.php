<?php
  class sonuc {
    var $durum;
    var $veri;
    var $aciklama;
    var $zaman;
    var $kur;

    function __construct($durum=null, $veri=null, $zaman=null, $aciklama=null) {
      $this->durum    = $durum;
      $this->veri     = $veri;
      $this->zaman    = $zaman;
      $this->aciklama = $aciklama;
    }

    function setDurum($yeni_durum) {
			$this->durum = $yeni_durum;
 		}
 		function getDurum() {
			return $this->durum;
		}

    function setVeri($yeni_veri) {
			$this->veri = $yeni_veri;
 		}
 		function getVeri() {
			return $this->veri;
		}

    function setAciklama($yeni_aciklama) {
			$this->aciklama = $yeni_aciklama;
 		}
 		function getAciklama() {
			return $this->aciklama;
		}

    function setZaman($yeni_zaman) {
			$this->zaman = $yeni_zaman;
 		}
 		function getZaman() {
			return $this->zaman;
		}

    function setKur($yeni_kur) {
			$this->kur = $yeni_kur;
 		}
 		function getKur() {
			return $this->kur;
		}

    function setHata($aciklama) {
      $this->durum    = 'hata';
      $this->aciklama = $aciklama;
    }
    function setUyari($veri, $aciklama) {
      $this->durum    = 'uyarı';
      $this->veri     = $veri;
      $this->aciklama = $aciklama;
    }
    function setVeri($veri, $zaman, $kur="") {
      $this->durum    = 'basarili';
      $this->veri     = $veri;
      $this->kur      = $kur;
      $this->zaman    = $zaman->format('Y-m-d H:i:s');
      $this->aciklama = $zaman->format('d M Y - H:i:s') . " itibariyle 1 " . $kur . " bedeli.";
    }
  }
?>

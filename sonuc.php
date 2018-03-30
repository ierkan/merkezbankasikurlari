<?php
  class sonuc {
    var $durum;
    var $veri;
    var $aciklama;

    function __construct($durum=null, $veri=null, $aciklama=null) {
      $this->durum    = $durum;
      $this->veri     = $veri;
      $this->aciklama = $aciklama;
    }

    function set_durum($yeni_durum) {
			$this->durum = $yeni_durum;
 		}
 		function get_durum() {
			return $this->durum;
		}

    function set_veri($yeni_veri) {
			$this->veri = $yeni_veri;
 		}
 		function get_veri() {
			return $this->veri;
		}

    function set_aciklama($yeni_aciklama) {
			$this->aciklama = $yeni_aciklama;
 		}
 		function get_aciklama() {
			return $this->aciklama;
		}

    function hata($aciklama) {
      $this->durum    = 'hata';
      $this->aciklama = $aciklama;
    }
    function uyari($veri, $aciklama) {
      $this->durum    = 'uyarı';
      $this->veri     = $veri;
      $this->aciklama = $aciklama;
    }
    function veri($veri) {
      $this->durum    = 'başarılı';
      $this->veri     = $veri;
    }
  }
?>

<?php
require_once 'Vaisseau.class.php';

class Vaisseau1 extends Vaisseau {
	function __construct($x, $y, $dir, $player) {
		parent::__construct($x, $y, $dir, $player);
		$this->nom = 'Frégate Impériale';
		$this->top = 0;
		$this->bottom = 0;
		$this->left = 3;
		$this->right = 1;
		$this->img = 'img/vaisseau1.png';
		$this->pt_coque = 1;
		$this->PP_init = 10;
		$this->PP = $this->PP_init;
		$this->vitesse_init = 15;
		$this->vitesse = $this->vitesse_init;
		$this->manoeuvr = 4;
		$this->bouclier = 0;
		//$this->arme = new Arme1();
		$this->w = 5;
		$this->h = 1;
	}

	static function doc() {
		return file_get_contents('Vaisseau1.doc.txt');
	}
}

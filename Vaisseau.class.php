<?php
require_once 'globals.php';

abstract class Vaisseau
{
	public $nom;
	public $top;
	public $bottom;
	public $left;
	public $right;
	public $sprite;
	public $pt_coque;
	public $PP;
	public $vitesse;
	public $manoeuvr;
	public $bouclier;
	public $armes;
	public $x;
	public $y;
	public $w;
	public $h;
	public $center;
	public $activated = false;
	public $immobile = true;
	public $dir;
	public $player;

	function __construct($x, $y, $dir, $player) {
		$this->x = $x;
		$this->y = $y;
		$this->dir = $dir;
		$this->player = $player;
	}

	static function doc() {
		return file_get_contents('Vaisseau.doc.txt');
	}

	function move($count) {
		if ($this->dir === UP)
			$this->y -= $count;
		else if ($this->dir === DOWN)
			$this->y += $count;
		else if ($this->dir === LEFT)
			$this->x -= $count;
		else if ($this->dir === RIGHT)
			$this->x += $count;
	}

	function turn_right() {
		if ($this->dir == RIGHT){
			$this->x += ceil(($this->w - 1) / 2);
			$this->y -= ceil(($this->w - 1) / 2);
		}
		else if ($this->dir == LEFT){
			$this->x += floor(($this->w - 1) / 2);
			$this->y -= floor(($this->w - 1) / 2);
		}
		else if ($this->dir == UP){
			$this->x -= ceil(($this->w - 1) / 2);
			$this->y += floor(($this->w - 1) / 2);
		}
		else if ($this->dir == DOWN){
			$this->x -= floor(($this->w - 1) / 2);
			$this->y += ceil(($this->w - 1) / 2);
		}
		$this->dir = ($this->dir + 1) % 4;
	}

	function turn_left() {
		if ($this->dir == RIGHT){
			$this->x += ceil(($this->w - 1) / 2);
			$this->y -= floor(($this->w - 1) / 2);
		}
		else if ($this->dir == LEFT){
			$this->x += floor(($this->w - 1) / 2);
			$this->y -= ceil(($this->w - 1) / 2);
		}
		else if ($this->dir == UP){
			$this->x -= floor(($this->w - 1) / 2);
			$this->y += floor(($this->w - 1) / 2);
		}
		else if ($this->dir == DOWN){
			$this->x -= ceil(($this->w - 1) / 2);
			$this->y += ceil(($this->w - 1) / 2);
		}
		$this->dir -= 1;
		if ($this->dir == -1)
			$this->dir = 3;
	}
	function attaque($ship){
		if ($this->dir == RIGHT)
		{
			$dx = $this->x + $this->w + 1;
			$fx = $this->x + $this->w + 5;
			while ($dx < $fx){
				if ($ship->x <= $dx && $dx <= $ship->x + $ship->y && $ship->y <= $this->y && $this->y <= $ship->y + $ship->w && $ship != $this)
					$ship->pt_coque--;
				$dx++;
		}
		}
		if ($this->dir == LEFT)
		{
				$dx = $this->x - 1;
				$fx = $this->x - 5;
				while ($dx > $fx){
					if ($ship->x <= $dx && $dx <= $ship->x + $ship->y && $ship->y <= $this->y && $this->y <= $ship->y + $ship->w && $ship != $this)
						$ship->pt_coque--;
					$dx--;
			}
		}
			if ($this->dir == DOWN)
			{
					$dy = $this->y + $this->w + 1;
					$fy = $this->y + $this->w + 5;
					while ($dy < $fy){
						if ($ship->y <= $dy && $dy <= $ship->y + $ship->h && $ship->x <= $this->x && $this->x <= $ship->x + $ship->w && $ship != $this)
							$ship->pt_coque--;
						$dy++;
				}
		}
		if ($this->dir == UP)
		{
				$dy = $this->y - 1;
				$fy = $this->y - 5;
				while ($dy > $fy){
					if ($ship->y - $ship->h <= $dy && $dy <= $ship->y  && $ship->x <= $this->x && $this->x <= $ship->x + $ship->w && $ship != $this)
						$ship->pt_coque--;
					$dy--;
			}
	}
	}
}

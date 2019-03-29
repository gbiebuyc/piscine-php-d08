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

	function getCenterX() {
		$halfw = (int)($this->w / 2);
		$halfh = (int)($this->h / 2);
		if ($this->dir == RIGHT)
			return $this->x + $halfw;
		if ($this->dir == LEFT)
			return $this->x + $this->w - $halfw;
		if ($this->dir == DOWN)
			return $this->y + $halfw;
		if ($this->dir == UP)
			return $this->y + $this->w - $halfw;
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

	function turn_left() {
		$this->dir -= 1;
		if ($this->dir == -1)
			$this->dir = 3;
	}

	function turn_right() {
		$this->dir = ($this->dir + 1) % 4;
	}

	function reposition($center_x, $center_y) {

	}
}

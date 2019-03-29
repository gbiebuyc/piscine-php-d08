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
		if ($this->dir == RIGHT)
			return $this->x + ceil($this->w / 2);
		if ($this->dir == LEFT)
			return $this->x + floor($this->w / 2);
		if ($this->dir == DOWN)
			return $this->x + ceil($this->h / 2);
		if ($this->dir == UP)
			return $this->x + floor($this->h / 2);
	}

	function getCenterY() {
		if ($this->dir == RIGHT)
			return $this->y + ceil($this->h / 2);
		if ($this->dir == LEFT)
			return $this->y + floor($this->h / 2);
		if ($this->dir == DOWN)
			return $this->y + ceil($this->w / 2);
		if ($this->dir == UP)
			return $this->y + floor($this->w / 2);
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
		$cx = $this->getCenterX();
		$cy = $this->getCenterY();
		$this->dir -= 1;
		if ($this->dir == -1)
			$this->dir = 3;
		$this->repositionX($cx);
		$this->repositionY($cy);
	}

	function turn_right() {
		$cx = $this->getCenterX();
		$cy = $this->getCenterY();
		$this->dir = ($this->dir + 1) % 4;
		$this->repositionX($cx);
		$this->repositionY($cy);
	}

	function repositionX($cx) {
		if ($this->dir == RIGHT)
			$this->x = $cx - ceil($this->w / 2);
		if ($this->dir == LEFT)
			$this->x = $cx - floor($this->w / 2);
		if ($this->dir == DOWN)
			$this->x = $cx - ceil($this->h / 2);
		if ($this->dir == UP)
			$this->x = $cx - floor($this->h / 2);
	}

	function repositionY($cy) {
		if ($this->dir == RIGHT)
			$this->y = $cy - ceil($this->h / 2);
		if ($this->dir == LEFT)
			$this->y = $cy - floor($this->h / 2);
		if ($this->dir == DOWN)
			$this->y = $cy - ceil($this->w / 2);
		if ($this->dir == UP)
			$this->y = $cy - floor($this->w / 2);
	}
}

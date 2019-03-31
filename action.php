<?php
require_once 'globals.php';
require_once 'Vaisseau1.class.php';
session_start();
if ($_GET['action'] === 'select') {
	$_SESSION['gamestate'] = MOVE;
	$_SESSION['selected'] = &$_SESSION['vaisseaux'][$_GET['ship']];
}
else if ($_GET['action'] === 'Move') {
	$_SESSION['selected']->vitesse -= $_GET['number'];
	if ($_SESSION['selected']->vitesse <= 0) {
		$_SESSION['selected']->activated = true;
		$_SESSION['selected']->move($_GET['number']);
		unset($_SESSION['selected']);
		$_SESSION['gamestate'] = SELECT_SHIP;
	}
	else if ($_GET['number'] == $_SESSION['selected']->manoeuvr) {
		$_SESSION['selected']->move($_GET['number']);
		$_SESSION['selected']->immobile = true;
	}
	else {
		$_SESSION['selected']->move($_GET['number']);
		$_SESSION['selected']->immobile = false;
	}
}
else if ($_GET['action'] === 'Stay stationary') {
	$_SESSION['selected']->activated = true;
	unset($_SESSION['selected']);
	$_SESSION['gamestate'] = SELECT_SHIP;
}
else if ($_GET['action'] === 'Turn left') {
	$_SESSION['selected']->turn_left();
}
else if ($_GET['action'] === 'Turn right') {
	$_SESSION['selected']->turn_right();
}
else if ($_GET['action'] === 'attaque'){
	$_SESSION['selected']->activated = true;


	foreach ($_SESSION['vaisseaux'] as  $ship) {
			$_SESSION['selected']->attaque($ship);
		}
		unset($_SESSION['selected']);
		$_SESSION['gamestate'] = SELECT_SHIP;
}
header("Location: index.php");
?>

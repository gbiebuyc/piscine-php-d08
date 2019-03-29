<?php
require_once 'globals.php';
require_once 'Vaisseau1.class.php';
session_start();
?>
<html>
<head>
<link rel="stylesheet" href="styles.css">
</head>
<div style='position: relative'>
<?php

echo "<table>";
for ($i = 0; $i < BOARD_HEIGHT; $i++) {
	echo "<tr>";
	for ($j = 0; $j < BOARD_WIDTH; $j++)
		echo "<td></td>";
	echo "</tr>";
}
echo "</table>";

if (!isset($_SESSION['gamestate']))
	$_SESSION['gamestate'] = SELECT_SHIP;

if (!isset($_SESSION['player']))
	$_SESSION['player'] = PLAYER_1;

if (!isset($_SESSION['vaisseaux'])) {
	$_SESSION['vaisseaux'] = array();
	$_SESSION['vaisseaux'][] = new Vaisseau1(3, 3, RIGHT, PLAYER_1);
	$_SESSION['vaisseaux'][] = new Vaisseau1(3, 12, RIGHT, PLAYER_1);
}
$all_activated = true;
foreach ($_SESSION['vaisseaux'] as $v) {
	if ($v->player == $_SESSION['player'] && $v->activated === false) {
		$all_activated = false;
		break;
	}
}
if ($all_activated === true) {
	$_SESSION['player'] = ($_SESSION['player'] + 1) % 2;
	foreach ($_SESSION['vaisseaux'] as $k => $v) {
		$_SESSION['vaisseaux'][$k]->activated = false;
		$_SESSION['vaisseaux'][$k]->vitesse = $v->vitesse_init;
		$_SESSION['vaisseaux'][$k]->PP = $v->PP_init;
	}
}

foreach ($_SESSION['vaisseaux'] as $k => $v) {
	$top = $v->y * SQUARE_WIDTH;
	$left = $v->x * SQUARE_WIDTH;
	$width = $v->w * SQUARE_WIDTH;
	$height = $v->h * SQUARE_WIDTH;
	$clickable = false;
	$highlighted = false;
	if ($_SESSION['player'] == $v->player) {
		if ($_SESSION['gamestate'] === SELECT_SHIP && $v->activated == false) {
			$clickable = true;
			$highlighted = true;
		}
		if ($_SESSION['gamestate'] === MOVE && $v === $_SESSION['selected'])
			$highlighted = true;
	}
	$tmp = $clickable ? "<a href='action.php?action=select&ship=$k'>" : "";
	$tmp .= "<img src='$v->img' style='top:{$top}px; left:{$left}px; width:{$width}px; height:{$height}px; border-radius:3px;";
	if ($v->dir === DOWN)
		$tmp .= " transform: translateY(-100%) rotate(90deg); transform-origin: left bottom;";
	if ($v->dir === UP)
		$tmp .= " transform: translateY(+400%) rotate(-90deg); transform-origin: left top;";
	if ($v->dir === LEFT)
		$tmp .= " transform: scaleX(-1);";
	$tmp .= $highlighted ? " box-shadow:0 0 0 3px green;" : "";
	$tmp .= "'>";
	$tmp .= $clickable ? "</a>" : "";
	echo $tmp;
}
?>
</div>
<div class='turn'>
<?php echo ($_SESSION['player'] === PLAYER_1 ? "Player1" : "Player2") . "'s turn";?>
</div>
<div class='userinput'>
<?php
if ($_SESSION['gamestate'] === SELECT_SHIP) { ?>
	Choisissez un vaisseau svp<br>
<?php }
else if ($_SESSION['gamestate'] === MOVE){ ?>
<form action="/action.php">
<?php
	echo "<b>{$_SESSION['selected']->nom}</b><br>";
echo 'Immobile: ' . ($_SESSION['selected']->immobile ? 'Vrai' : 'Faux') . '<br>';
echo "Manoeuvre: {$_SESSION['selected']->manoeuvr}<br>";
echo "Vitesse: {$_SESSION['selected']->vitesse}<br>";
$min = $_SESSION['selected']->immobile ? 0 : $_SESSION['selected']->manoeuvr;
$max = $_SESSION['selected']->vitesse;
?>
  Déplacement:
  <input type="number" name="number" min="<?php echo $min;?>" max="<?php echo $max;?>" value="<?php echo $min;?>"><br>
  <input type="submit" name="action" value="Move">
<?php
if ($_SESSION['selected']->immobile) { ?>
  <input type="submit" name="action" value="Stay stationary">
  <input type="submit" name="action" value="Turn left">
  <input type="submit" name="action" value="Turn right">
<?php } ?>
</form>
<?php } ?>
</div>
</html>

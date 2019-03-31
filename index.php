<?php
require_once 'globals.php';
require_once 'Vaisseau1.class.php';
session_start();
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="styles.css">
</head>
<div style='position: relative'>
<?php
// Init game
if (!isset($_SESSION['gamestate']))
	$_SESSION['gamestate'] = SELECT_SHIP;

if (!isset($_SESSION['player']))
	$_SESSION['player'] = PLAYER_1;

if (!isset($_SESSION['vaisseaux'])) {
	$_SESSION['vaisseaux'] = array();
	$_SESSION['vaisseaux'][] = new Vaisseau1(3, 3, RIGHT, PLAYER_1);
	$_SESSION['vaisseaux'][] = new Vaisseau1(5, 7, RIGHT, PLAYER_1);
	$_SESSION['vaisseaux'][] = new Vaisseau1(3, 11, RIGHT, PLAYER_1);

	$_SESSION['vaisseaux'][] = new Vaisseau1(60, 3, LEFT, PLAYER_2);
	$_SESSION['vaisseaux'][] = new Vaisseau1(55, 7, LEFT, PLAYER_2);
	$_SESSION['vaisseaux'][] = new Vaisseau1(60, 11, LEFT, PLAYER_2);
	//$_SESSION['vaisseaux'][] = new Vaisseau1(140, 95, LEFT, PLAYER_2);
}
//verification de victoir
if (isset($_SESSION['vaisseaux'])){
		$j1 = 0;
		$j2 = 0;
		foreach ($_SESSION['vaisseaux'] as $value) {
			if ($value->pt_coque <= 0);
				//unset($value);
			else if ($value->player == PLAYER_1)
				$j1++;
			else if ($value->player == PLAYER_2)
				$j2++;
		}
		if ($j1 == 0)
		{
			echo "<script>alert('Joueur 2 a gagnée');</script>";
			unset ($_SESSION['gamestate']);
			unset ($_SESSION['player']);
			unset ($_SESSION['vaisseaux']);
			header("location:victoir.php?player=2");
		}
		if ($j2 == 0){
		echo "<script>alert('Joueur 1 a gagnée');</script>";
		unset ($_SESSION['gamestate']);
		unset ($_SESSION['player']);
		unset ($_SESSION['vaisseaux']);
		header("location:victoir.php?player=1");


			}
	}
// Display grid
echo "<table>";
for ($i = 0; $i < BOARD_HEIGHT; $i++) {
	echo "<tr>";
	for ($j = 0; $j < BOARD_WIDTH; $j++)
		echo "<td></td>";
	echo "</tr>";
}
echo "</table>";





// Check si tous les vaisseaux sont activés
$all_activated = true;
foreach ($_SESSION['vaisseaux'] as $v) {
	if ($v->player == $_SESSION['player'] && $v->activated === false) {
		$all_activated = false;
		break;
	}
}

// Change turn, reset PP
if ($all_activated === true) {
	$_SESSION['player'] = ($_SESSION['player'] + 1) % 2;
	foreach ($_SESSION['vaisseaux'] as $k => $v) {
		$_SESSION['vaisseaux'][$k]->activated = false;
		$_SESSION['vaisseaux'][$k]->vitesse = $v->vitesse_init;
		$_SESSION['vaisseaux'][$k]->PP = $v->PP_init;
		if ($_SESSION['vaisseaux'][$k]->pt_coque <= 0)
			unset($_SESSION['vaisseaux'][$k]);
	}
}


// Display ships
foreach ($_SESSION['vaisseaux'] as $k => $v) {
	$top = ($v->y - $v->animate_y) * SQUARE_WIDTH;
	$left = ($v->x - $v->animate_x) * SQUARE_WIDTH;
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
	$tmp .= $v->animate ? "<img id='animate' " : "<img ";
	$tmp .= "src='$v->img' style='top:{$top}px; left:{$left}px; width:{$width}px; height:{$height}px; border-radius:3px;";
	if ($v->dir === DOWN)
		$tmp .= " transform: translateY(-100%) rotate(90deg); transform-origin: left bottom;";
	if ($v->dir === UP)
		$tmp .= " transform: translateX(-100%) rotate(-90deg); transform-origin: top right;";
	if ($v->dir === LEFT)
		$tmp .= " transform: scaleX(-1);";
	$tmp .= $highlighted ? " box-shadow:0 0 0 3px green;" : "";
	$tmp .= "'>";
	$tmp .= $clickable ? "</a>" : "";
	echo $tmp;
	if ($v->animate) { ?>
		<script>
		$(document).ready(function(){
			$('#animate').animate({
				top: '<?php echo $v->y * SQUARE_WIDTH; ?>px',
				left: '<?php echo $v->x * SQUARE_WIDTH; ?>px'
			});
		});
		</script> <?php
		$_SESSION['vaisseaux'][$k]->animate_x = 0;
		$_SESSION['vaisseaux'][$k]->animate_y = 0;
		$_SESSION['vaisseaux'][$k]->animate = false;
	}

}
?>
</div>
<div class='turn'>
<?php echo ($_SESSION['player'] === PLAYER_1 ? "Player1" : "Player2") . "'s turn";?>
</div>
<div class='userinput'>
<?php
if ($_SESSION['gamestate'] === SELECT_SHIP) {
		echo "Choisissez un vaisseau svp<br>";
	?>
<?php }
else if ($_SESSION['gamestate'] === MOVE){ ?>
<form action="./action.php">
<?php
	echo "<b>{$_SESSION['selected']->nom}</b><br>";
echo 'Immobile: ' . ($_SESSION['selected']->immobile ? 'Vrai' : 'Faux') . '<br>';
echo "Manoeuvre: {$_SESSION['selected']->manoeuvr}<br>";
echo "Vitesse: {$_SESSION['selected']->vitesse}<br>";
echo "coque: {$_SESSION['selected']->pt_coque}<br>";
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
  <input type="submit" name="action" value="attaque">
<?php } ?>
</form>
<?php } ?>
</div>
</html>

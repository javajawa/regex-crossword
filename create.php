<?php

if (empty($_POST['clue']))
{
	header('Location: index.html', false, 303);
	exit;
}

require('crossword.php');

$width = max(array_keys($_POST['clue']));
$height = 0;
foreach ($_POST['clue'] as $x => $c)
{
	$height = max($height, max(array_keys($c)));
	foreach ($c as $y => $regex)
	{
		$direction = ($x == -1);
		$index = (int)($direction ? $y : $x);

		$clues []= new Clue($direction, $index, $regex);
	}
}

$crossword = new Crossword(1+$width, 1+$height);

foreach ($clues as $clue)
{
	$crossword->addClue($clue);
}

$crossword = serialize($crossword);
$hash = sha1($crossword);

file_put_contents(__DIR__ . '/generated/' . $hash, $crossword);
header('Location: view.php?hash=' . $hash, false, 303);


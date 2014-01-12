<!DOCTYPE html>
<html>
	<head>
		<title>Regex Crosswords</title>
	</head>
	<body>
		<h1>Regex Crossword Collection</h1>

		<p>
			<a href="create.html">Create Your Own</a>
		</p>
		<hr />

		<p>
<?php

$files = glob('generated/*');

require('crossword.php');

foreach ($files as $file):
	$c = unserialize(file_get_contents($file));
	$file = end(explode('/', $file));
	echo "<a href='view.php?hash={$file}'>{$c->getTitle()} by {$c->getSetter()}</a><br />";
endforeach;


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Regex Crosswords</title>
		<style type="text/css">
			html {
				background-color: #e0e0ff;
				background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAABlBMVEX4+Pj09PQf/c7fAAAAGUlEQVR4XnXIMQ0AAACDMPyb3gxA0qswVX1+vw/xW46qyAAAAABJRU5ErkJggg==');
				font-family: Nunito,Calibri,'Open Sans',Helvetica,sans-serif;
				font-size: 14px;
				color: #000;
				min-height: 100%;
			}
			body {
				background: rgba(255,255,255,0.8);
				max-width: 1024px;
				margin: 15px auto;
				padding: 15px;
			}
			h1,h2,h3,h4,h5,h6,p { margin: 0 0 0.5em; }
		</style>
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


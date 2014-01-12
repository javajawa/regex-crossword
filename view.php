<?php

$hash = $_GET['hash'];

if (empty($hash))
{
	$hash = `ls -1 generated | sort -r | head -n 1`;
}

require('crossword.php');
$crossword = file_get_contents('generated/' . $hash);
$crossword = unserialize($crossword);

$clues = array(
	array_pad(array(), $crossword->getWidth(), null),
	array_pad(array(), $crossword->getHeight(), null),
);

foreach ($crossword as $clue)
{
	$clues[$clue->getDirection()][$clue->getIndex()] = $clue;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?=$crossword->getTitle();?> - A Regex Crossword By <?=$crossword->getSetter();?></title>
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
			.modal {
				position: fixed; top: 50%; left: 50%; margin: -200px;
				width: 400px; min-height: 100px;
				display: none; background: #fff;
				border-radius: 5px;
				border: 3px #eee solid;
				z-index: 2;
			}
			#cover {
				position: fixed; top: 0; left: 0; right: 0; bottom: 0;
				background: rgba(0,0,0,0.3);
				display: none;
				z-index: 1;
			}
			.modal:target, .modal:target~#cover { display: block }

			form { text-align: center; padding: 10px; }
			input, label {
				font-size: 18px;
				line-height: 25px;
				padding: 0; margin: 0;
				text-align: left;
			}
			label { display: block; margin-bottom: 10px; min-height: 28px; }
			label input { float: right; }

			input[type="number"] { width: 40px; }
			input[type="submit"] { font-weight: normal; padding: 3px 5px; }
			input.answer { text-align: center; width: 25px; font-weight: bold;
				text-transform: uppercase;
			}
			input.answer:empty { background: #fdd; }
			.clue input { display: none; }

			table, tr, td { border: none; padding: 0; }
			th { white-space:nowrap; }

			tr:first-child>th div { height: 60px; width: 20px; position: relative;  }
			tr:first-child>th span {
				position: absolute;
				left: -35px;
				-webkit-transform: rotate(90deg);
				-moz-transform:rotate(90deg);
				-o-transform: rotate(90deg);
				white-space:nowrap;
				width: 58px;
				height: 58px;
				text-align: right;
				display: inline-block;
			}
			th.invalid { color: red; }
		</style>
		<script type="text/javascript">
			function validate()
			{
				x = document.querySelectorAll('th[data-x], th[data-y]');

				for (i = 0; i < x.length; ++i)
				{
					validateClue(x[i]);
				}

				document.querySelector('th.invalid') || alert('Congratulations!');
			}

			function validateClue(e)
			{
				e.classList.add('invalid');

				var x = e.getAttribute('data-x');
				var y = e.getAttribute('data-y');

				var s = document.querySelectorAll('input[data-x="'+x+'"], input[data-y="'+y+'"]');
				var i = 0, txt = '';
				var regex = e.textContent;

				for (i = 0; i < s.length; ++i)
				{
					if (s[i].value.length !== 1)
					{
						return;
					}
					txt += s[i].value;
				}

				regex = new RegExp('^' + regex + '$', 'i');

				if (regex.test(txt))
				{
					e.classList.remove('invalid');
				}
			}
		</script>
	</head>
	<body>
		<h1><?=$crossword->getTitle();?> By <?=$crossword->getSetter();?></h1>
		<form>
			<table>
				<tr>
					<th></th>
<?php foreach ($clues[0] as $c => $clue): ?>
					<th data-x="<?=$c?>"><div><span><?php echo htmlspecialchars($clue->getRegex()); ?></span></div></th>
<?php endforeach; ?>
				</tr>
<?php foreach ($clues[1] as $r => $clue): ?>
				<tr>
					<th data-y="<?=$r?>"><?php echo htmlspecialchars($clue->getRegex()); ?></th>
<?php for ($c = 0; $c < $crossword->getWidth(); ++$c): ?>
					<td><input maxlength="1" data-x="<?=$c?>" data-y="<?=$r?>" class="answer" /></td>
<?php endfor; ?>
				</tr>
<?php endforeach; ?>
			</table>
			<input type="button" onclick="validate(); return false;" value="Check" />
		</form>
		<a href="index.html">Make your own regex crossword</a>
	</body>
</html>


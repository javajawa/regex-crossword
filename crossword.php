<?php

class Crossword implements Iterator
{
	protected $width;
	protected $height;
	public $title;
	public $setter;
	protected $clues = array();

	public function __construct($width, $height)
	{
		if (!($width > 0))
		{
			throw new Exception('width must be greater than 0');
		}
		if (!($height > 0))
		{
			throw new Exception('Height must be greater than 0');
		}

		$this->width = $width;
		$this->height = $height;
	}

	public function addClue(Clue $clue)
	{
		if ($clue->getIndex() < 0)
		{
			throw new Exception('Clue index out of range');
		}
		if ($clue->getIndex() >= ($clue->getDirection() ? $this->height : $this->width))
		{
			throw new Exception('Clue index out of range');
		}

		$this->clues []= $clue;
	}

	public function normalise()
	{
		usort($this->clues, function(Clue $a, Clue $b)
		{
			if ($a->getDirection() > $b->getDirection())
			{
				return 1;
			}
			elseif ($a->getDirection() < $b->getDirection())
			{
				return -1;
			}

			if ($a->getIndex() === $b->getIndex())
			{
				return 0;
			}

			return $a->getIndex() > $b->getIndex() ? 1 : -1;
		});
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getSetter()
	{
		return $this->setter;
	}

	public function current()
	{
		return current($this->clues);
	}

	public function next()
	{
		return next($this->clues);
	}

	public function rewind()
	{
		return reset($this->clues);
	}

	public function valid()
	{
		return false !== current($this->clues);
	}

	public function key()
	{
		return null;
	}
}

class Clue
{
	protected $direction;
	protected $index;
	protected $regex;

	public function __construct($direction, $index, $regex)
	{
		if (empty($regex))
		{
			throw new Exception('Empty regex');
		}
		if (@preg_match('/' . $regex . '/', '') === false)
		{
			throw new Exception('Invalid Regex: ' . $regex);
		}

		$this->direction = $direction;
		$this->index     = $index;
		$this->regex     = $regex;
	}

	public function getDirection()
	{
		return $this->direction;
	}

	public function getIndex()
	{
		return $this->index;
	}

	public function getRegex()
	{
		return $this->regex;
	}
}


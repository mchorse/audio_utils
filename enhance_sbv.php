<?php

/**
 * enhance_sbv.php <file> [break_down]
 * 
 * Simple script which enhances YouTube's .sbv files.
 */

/**
 * I had to use stupid class because PHP copies arrays
 * during every assignment 
 */
class SbvLine
{
	public $start;
	public $end;
	public $content = [];

	public function __construct($start, $end)
	{
		$this->start = $start;
		$this->end = $end;
	}
}

function find_space($line, $shift)
{
	if ($shift == 0)
	{
		$shift = -1;
	}

	$len = strlen($line);
	$index = $len / 2;

	while ($index >= 0 && $index < $len)
	{
		if (substr($line, $index, 1) === ' ')
		{
			break;
		}

		$index += $shift;
	}

	return $index;
}

/* main */
$target = $_SERVER['argv'][1];
$file = file_get_contents($target);
$subs = [];
$regex = '/\d+:\d{2}:\d{2}\.\d{3},\d+:\d{2}:\d{2}\.\d{3}/';
$lines = explode("\n", $file);
$last = null;

foreach ($lines as $line) 
{
	if (preg_match($regex, $line) === 1)
	{
		$explode = explode(',', $line);

		$last = new SbvLine($explode[0], $explode[1]);
		$subs[] = $last;
	}
	else if (!empty(trim($line)))
	{
		array_push($last->content, $line);
	}
}

if (isset($_SERVER['argv'][2]) && ($_SERVER['argv'][2] === 'true' || $_SERVER['argv'][2] === '1'))
{
	foreach ($subs as $sub)
	{
		if (count($sub->content) == 1 && mb_strlen($sub->content[0]) > 50)
		{
			$line = $sub->content[0];
			$len = strlen($line);
			$half = $len / 2;
			$l_index = find_space($line, -1);
			$r_index = find_space($line, 1);
			$index = abs($half - $l_index) < abs($half - $r_index) ? $l_index : $r_index;

			if ($index >= 0 && $index < $len)
			{
				$sub->content = [
					substr($line, 0, $index),
					substr($line, $index)
				];
			}
		}

		$new_subs[] = $sub;
	}
}

$output = '';
$count = count($subs);

foreach ($subs as $i => $sub) 
{
	$start = $sub->start;
	$end = $sub->end;

	if ($i < $count - 1)
	{
		$end = $subs[$i + 1]->start;
	}

	$output .= "{$start},{$end}\n";

	foreach ($sub->content as $j => $line) 
	{
		if ($j > 0)
		{
			$line = trim($line);
			$line = ' ' . $line;

			if (preg_match('/[.!:;,?]$/', $line) !== 1)
			{
				$line = $line . ' ';
			}
		}
		else if ($j == 0)
		{
			$line = rtrim($line) . ' ';
		}

		$output .= $line . "\n";
	}

	$output .= "\n";
}

file_put_contents($target, $output);
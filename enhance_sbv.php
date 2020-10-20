<?php

/**
 * enhance_sbv.php
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

		$output .= $line . "\n";
	}

	$output .= "\n";
}

file_put_contents($target, $output);
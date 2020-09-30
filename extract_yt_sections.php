<?php

/**
 * extract_yt_sections.php
 * 
 * This script outputs nicely formatted YouTube section 
 * timestamps from a .csv file exported from Premiere 
 * (File > Export > Markers).
 */

$file = file_get_contents($_SERVER['argv'][1]);
$file = mb_convert_encoding($file, "UTF-8", "UTF-16");
$output = "00:00 - Intro\n";

$lines = explode("\n", $file);
array_shift($lines);

foreach ($lines as $line)
{
	$rows = explode("\t", $line);

	if (count($rows) < 2)
	{
		continue;
	}

	$label = $rows[0];
	$time = $rows[2];
	$time = substr($time, 3, 5);

	$output .= "$time - $label\n";
}

echo $output;
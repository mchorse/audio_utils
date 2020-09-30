<?php

/**
 * extract_vl.php
 * 
 * Simple script which extracts dialogue lines for given 
 * character within a fountain document.
 * 
 * For example: I run "php extract_vl.php screenplay.fountain BOB"
 * 
 * While the content of "screenplay.fountain" is:
 * 
 *     Bob went outside.
 *     
 *     BOB
 *     I love birds!
 *     
 *     Richard came by.
 * 
 *     RICHARD
 *     Me too!
 *     
 *     BOB
 *     You're weird, Richard.
 * 
 * The output of this script will be:
 * 
 *     I love birds!
 * 
 *     You're weird, Richard.
 */

$file = file_get_contents($_SERVER['argv'][1]);
$target = strtoupper($_SERVER['argv'][2]);
$output = [];
$grab_next = false;
$lines = explode("\n", $file);

foreach ($lines as $line)
{
	if ($grab_next)
	{
		if (empty(trim($line)))
		{
			$grab_next = false;
		}
		else
		{
			$output[] = $line;
		}
	}
	else if (strpos($line, $target) !== false)
	{
		$grab_next = true;
	}
}

if (empty($output))
{
	echo "Found nothing for '$target'...";
}
else
{
	echo implode("\n\n", $output);
}
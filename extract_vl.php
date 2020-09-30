<?php

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
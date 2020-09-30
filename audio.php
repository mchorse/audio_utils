<?php 

/* The length of silence is 0.28 seconds */

function path($variable)
{
	return __DIR__ . DIRECTORY_SEPARATOR . $variable;
}

function rpath($variable)
{
	return realpath(path($variable));
}

$array = [];
$list = exec("ls | grep -E '_\w+.aiff$'", $array);
$silence = rpath('silence.aiff');
$output = '';

foreach ($array as $value)
{
	$output .= "file './$value'\nfile '$silence'\n";
}


file_put_contents(path('list.txt'), $output);
exec(sprintf('ffmpeg -f concat -safe 0 -i %s -c copy full.aiff', rpath('list.txt')));
unlink(rpath('list.txt'));
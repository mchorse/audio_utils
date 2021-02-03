<?php 

/**
 * audio.php
 * 
 * This script joins all .aiff files together, in current working 
 * directory, into one .aiff file. It uses "ffmpeg" (The version
 * shouldn't matter) for conversion, and bash "ls" command 
 * for getting list of aiff files.
 * 
 * The length of silence.aiff is 0.28 seconds 
 */

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
exec(sprintf('ffmpeg -f concat -safe 0 -i %s -c copy full.wav', rpath('list.txt')));
unlink(rpath('list.txt'));
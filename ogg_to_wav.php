<?php 

/**
 * ogg_to_wav.php
 * 
 * This script converts all .ogg file in current working 
 * directory to .wav files (it only creates copies, it doesn't 
 * delete original .ogg files).
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
$list = exec("ls | grep -E '.*\.ogg$'", $array);

foreach ($array as $value)
{
	$array = [];
	$depo = str_replace('.ogg', '.wav', $value);
	exec(sprintf('ffmpeg -y -i %s %s', $value, $depo), $array);

	printf('Converted %s to %s!', $value, $depo);
}
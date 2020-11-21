<?php

/**
 * preview_audio.php <configuration_file>
 * 
 * This script is responsible for adding audio files to videos (configured
 * in the <configuration_file>). It requires ffmpeg to be available in the 
 * PATH.
 * 
 * An example of <configuration_file>'s content:
 * 
 * 1-1.mp4 | test.wav | 200
 * 1-2.mp4 | test.wav | 400
 * 1-3.mp4 | another.wav | 0
 * 
 * Think of these as: video file | audio file | offset for audio file in ticks (seconds * 20)
 */

if (!isset($_SERVER['argv'][1]) || !is_file($_SERVER['argv'][1]))
{
	die("The <configuration_file> argument wasn't provided, or it's not a file!");
}

$configuration_file = $_SERVER['argv'][1];
$content = file_get_contents($configuration_file);
$lines = explode("\n", $content);

foreach ($lines as $line) 
{
	$splits = explode("|", $line);

	if (count($splits) != 3)
	{
		continue;
	}

	$video = trim($splits[0]);
	$audio = trim($splits[1]);
	$offset = trim($splits[2]) / 20.0;

	/* Get video file duration */
	exec("ffmpeg -i $video 2>&1", $ob);

	$ob = implode("\n", $ob);
	preg_match_all('/Duration: (\d{2,}\:\d{2}\:\d{2}\.\d{2})/', $ob, $matches);

	$duration = $matches[1][0];
	$final = str_replace('.mp4', 'out.mp4', $video);

	exec("ffmpeg -i $video -ss $offset -t $duration -i $audio -c:v copy -c:a aac $final 2>&1");
	echo "Added audio to $video, and exported it as $final!\n";
}

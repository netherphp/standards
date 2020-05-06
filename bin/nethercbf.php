<?php

if(!array_key_exists('argv',$_SERVER))
return;

$PathCS = sprintf(
	'%s%s%s',
	dirname(__FILE__,2),
	DIRECTORY_SEPARATOR,
	'NetherCS'
);

$Command = sprintf(
	'phpcs --standard=%s %s',
	$PathCS,
	join(' ',array_map(
		'escapeshellarg',
		$_SERVER['argv']
	))
);

//echo $Command, PHP_EOL;
system($Command);

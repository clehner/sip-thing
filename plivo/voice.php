<?php
require 'config.php';
require 'functions.php';
validate_response();

$r = new Response();
$d = $r->addDial(array(
	'action' => $base_url . 'voicemail.php'
));
$d->addUser($to_address);

header('Content-Type: text/xml');
echo $r->toXML();

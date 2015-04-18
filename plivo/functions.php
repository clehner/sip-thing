<?php
require 'plivo.php';

function nice_format($number) {
	if (preg_match('/^(?:\\+?1)?(...)(...)(....)$/', $number, $matches)) {
		list(, $area, $prefix, $line) = $matches;
		return "($area) $prefix-$line";
	} else {
		return $number;
	}
}

function validate_response() {
	global $auth_token, $host_url;
	$url = $host_url.$_SERVER['REQUEST_URI'];
	$sig = @$_SERVER['HTTP_X_PLIVO_SIGNATURE'];
	if (!$sig || !validate_signature($url, $_POST, $sig, $auth_token)) {
		die('invalid signature');
	}
}
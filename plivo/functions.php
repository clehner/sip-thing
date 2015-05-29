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

function mail_headers($obj) {
	$headers = array();
	foreach ($obj as $key => $value) {
		$headers[] = "$key: $value";
	}
	return implode("\r\n", $headers);
}

function duration_format($ms) {
	$s = $ms/1000;
	if ($s < 60) return $s.'s';
	$m = (int)($s / 60);
	$s %= 60;
	return $m.'m'.$s.'s';
}

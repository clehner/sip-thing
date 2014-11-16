<?php

function headers_to_str($headers) {
	$str = '';
	foreach ($headers as $name => $value) {
		$str .= "$name: $value\r\n";
	}
	return $str;
}

class Media
{
	public $content_type;
	public $url;

	function __construct($content_type, $url) {
		$this->content_type = $content_type;
		$this->url = $url;
	}

	function serialize() {
		$attachment = chunk_split(base64_encode(file_get_contents($this->url)));
		return 'Content-Type: ' . $this->content_type . "\r\n"
			. "Content-Disposition: attachment\r\n"
			. "Content-Transfer-Encoding: base64\r\n"
			. "\r\n"
			. $attachment . "\r\n";
	}
}

class PhoneNumber
{
	public $number;
	public $city;
	public $state;
	public $zip;
	public $country;

	function __construct($map, $prefix) {
		$this->number = strip_tags($map[$prefix]);
		$this->city = strip_tags($map[$prefix.'City']);
		$this->state = strip_tags($map[$prefix.'State']);
		$this->zip = strip_tags($map[$prefix.'Zip']);
		$this->country = strip_tags($map[$prefix.'Country']);
	}

	function __toString() {
		return sprintf("%s (%s, %s %s, %s)", $this->number,
			$this->city, $this->state, $this->zip, $this->country);
	}

	function nice_format() {
		if (preg_match('/^\\+1(...)(...)(....)$/', $this->number, $matches)) {
			list(, $area, $prefix, $line) = $matches;
			return "($area) $prefix-$line";
		} else {
			return $this->number;
		}
	}

	function just_number() {
		return preg_replace('/[^0-9]/', '', $this->number);
	}
}

class Request
{
	public $valid;
	public $from;
	public $to;
	public $body;
	public $media = array();
	private $mime_boundary;

	function __construct($req) {
		$this->valid = isset($req['From']) && isset($req['Body']);
		if (!$this->valid) return;

		$this->mime_boundary = md5(rand());

		$this->body = $req['Body'];
		$this->from = new PhoneNumber($req, 'From');
		$this->to = new PhoneNumber($req, 'To');

		$num_media = (int)$req['NumMedia'];
		for ($i = 0; $i < $num_media; $i++) {
			$this->media[] = new Media(
				$req['MediaContentType'.$i],
				$req['MediaUrl'.$i]);
		}
	}

	function mail_headers($from_address) {
		$headers = array(
			'From: "' . $this->from . '" <' . $from_address . ">",
			'Content-Type: multipart/mixed; boundary=' . $this->mime_boundary,
			'',
			''
		);
		return implode("\r\n", $headers);
	}

	function mail_body() {
		$msg = '';
		// add attachments
		foreach ($this->media as $media) {
			$msg .= '--' . $this->mime_boundary . "\r\n";
		   	$msg .= $media->serialize() . "\r\n";
		}
		// add message text
		$msg .= '--' . $this->mime_boundary . "\r\n";
		$msg .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
		$msg .= "Content-Transfer-Encoding: quoted-printable\r\n";
		$msg .= "\r\n";
		$msg .= quoted_printable_encode($this->body) . "\r\n";
		$msg .= '--' . $this->mime_boundary . '--';
		return $msg;
	}
}

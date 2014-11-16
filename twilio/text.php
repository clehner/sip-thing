<?php
ini_set('display_errors', 0);
include 'functions.php';
include 'config.php';

$req = new Request($_POST);
if (!$req->valid) exit;

$to_address = '"' . $req->to->number . '" <' . $to_email . ">";
$from_address = sprintf($from_email_format,
	$req->to->just_number(), $req->from->just_number());
$subject = 'SMS from '.$req->from->nice_format();
$mail_body = $req->mail_body();
$mail_headers = $req->mail_headers($from_address);

mail($to_address, $subject, $mail_body, $mail_headers);

header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<Response/>';

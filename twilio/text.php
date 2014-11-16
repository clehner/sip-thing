<?php
ini_set('display_errors', 0);
include 'functions.php';
include 'config.php';
//print '<Response><Sms>' . htmlentities(@$_SERVER['QUERY_STRING']) . '</Sms></Response>';

$req = new Request($_POST);
if (!$req->valid) exit;

$to = '"' . $req->to->number . '" <' . $to_email . ">";
$subject = 'SMS from '.$req->from->nice_format();
$mail_body = $req->mail_body();
$mail_headers = $req->mail_headers($from_email);

mail($to, $subject, $mail_body, $mail_headers);

header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<Response/>';

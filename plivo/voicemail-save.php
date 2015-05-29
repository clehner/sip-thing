<?php
include 'config.php';
include 'functions.php';
header('Content-Type: text/xml');
echo '<Response/>';

if (!isset($_POST['RecordUrl'])) exit;
$url = $_POST['RecordUrl'];
$duration = duration_format($_POST['RecordingDurationMs']);
$recording_id = $_POST['RecordingID'];
$from = $_POST['From'];
$to = $_POST['To'];
$from_name = $_POST['CallerName'];

$from_nice = nice_format($from);
$from_address = sprintf($from_email_format, $to, $from);
$to_address = '"' . $to . '" <' . $to_email . ">";
$subject = 'Voicemail from '.$from_nice;
$headers = array(
	"From" => "\"$from_name\" <$from_address>",
	"X-Recording-Id" => "$recording_id",
	"Message-Id" => "$recording_id@$domain"
);
$mail_body = "Duration: $duration\n" .
	"URL: $url";
mail($to_address, $subject, $mail_body, mail_headers($headers));

/*
DELETE https://api.plivo.com/v1/Account/{auth_id}/Call/{call_uuid}/Record/
DELETE https://api.plivo.com/v1/Account/{auth_id}/Recording/$recording_id/
*/


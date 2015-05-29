<?php
include 'config.php';
header('Content-Type: text/xml');
echo '<Response/>';
if (!isset($_POST['RecordUrl'])) exit;
$url = $_POST['RecordUrl'];
$duration = $_POST['RecordingDuration'];
$recording_id = $_POST['RecordingID'];

$subject = 'Voicemail';
$mail_body = "id: $recording_id duration: $duration url: $url";
$mail_headers = '';

mail($to_address, $subject, $mail_body, $mail_headers);

/*
$to_address = '"' . $req->to->number . '" <' . $to_email . ">";
$from_address = sprintf($from_email_format,
	$req->to->just_number(), $req->from->just_number());
$subject = 'Voicemail from '.$req->from->nice_format();
$mail_body = $req->mail_body();
$mail_headers = $req->mail_headers($from_address);

mail($to_address, $subject, $mail_body, $mail_headers);

DELETE https://api.plivo.com/v1/Account/{auth_id}/Call/{call_uuid}/Record/
DELETE https://api.plivo.com/v1/Account/{auth_id}/Recording/$recording_id/
*/


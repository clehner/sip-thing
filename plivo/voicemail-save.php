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
$start_time = $_POST['RecordingStartMs']/1000;

$from_nice = nice_format($from);
$from_address = sprintf($from_email_format, $to, $from);
$to_address = '"' . $to . '" <' . $to_email . ">";
$subject = 'Voicemail from '.$from_nice;
$headers = array(
	"From" => "\"$from_name\" <$from_address>",
	"Date" => date('r', $start_time),
	"Message-Id" => "$recording_id@$domain",
);

$data = @file_get_contents($url);
if ($data) {
	$headers['MIME-Version'] = '1.0';
	$headers['Content-Transfer-Encoding'] = 'base64';
	$headers['Content-Type'] = "audio/mp3; name=\"$recording_id.mp3\"";
	$body = chunk_split(base64_encode($data), 68, "\n");
	$use_attachment = true;

} else {
	// If we can't get the attachment, send a link to it instead
	$use_attachment = false;
	$body = "Duration: $duration\n"
		. "URL: $url";
}
mail($to_address, $subject, $body, mail_headers($headers));

if ($use_attachment) {
	// Mailing the attachment, so delete the URL
	$api = new RestAPI($auth_id, $auth_token);
	$resp = $api->delete_recording(array('recording_id' => $recording_id));
	if ($resp['status'] != 204) {
		mail('cel', 'Error deleting voicemail recording',
			print_r($resp, 1), 'From: cel');
	}
}


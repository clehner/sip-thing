<?php
ini_set('display_errors', 0);
include 'config.php';
include 'functions.php';

if (!isset($_POST['MessageUUID'])) exit;

$from = $_POST['From'];
$to = $_POST['To'];
$text = $_POST['Text'];
$id = $_POST['MessageUUID'];
$type = $_POST['Type'];

$from_nice = nice_format($from);
$from_address = sprintf($from_email_format, $to, $from);
$to_address = '"' . $to . '" <' . $to_email . ">";
$subject = 'SMS from '.$from_nice;
$mail_headers = implode("\r\n", array(
	"From: \"$from_nice\" <$from_address>",
	"Message-Id: $id@$domain",
	'',
	''
));
if ($type == 'sms') {
	$mail_body = $text;
} else {
	$mail_body = print_r($_POST, true);
}

mail($to_address, $subject, $mail_body, $mail_headers);

header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<Response/>';

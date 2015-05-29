<?php
include 'config.php';
header('Content-Type: text/xml');
?>
<Response>
<?php if (@$_POST['DialStatus'] != 'completed') { ?>
	<Play><?php print $base_url; ?>voicemail.mp3</Play>
	<Record action="<?php print $base_url; ?>voicemail-save.php" maxLength="300" finishOnKey="#" redirect="false" playBeep="true" />
<?php } ?>
</Response>

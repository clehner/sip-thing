<?php
include 'config.php';
header('Content-Type: text/xml');
?>
<Response>
	<Dial>
		<User><?php print $to_address; ?></User>
	</Dial>
</Response>

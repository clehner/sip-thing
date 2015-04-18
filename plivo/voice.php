<?php
include 'config.php';
require 'functions.php';
validate_response();

header('Content-Type: text/xml');
?>
<Response>
	<Dial>
		<User><?php print $to_address; ?></User>
	</Dial>
</Response>

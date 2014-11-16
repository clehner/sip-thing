<?php
include 'config.php';
header('Content-Type: text/xml');
?>
<Response>
	<Dial>
		<Sip>
			<Uri><?php print $sip_address; ?></Uri>
		</Sip>
	</Dial>
</Response>

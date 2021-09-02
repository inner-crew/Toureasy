<html>
    <head>
        <title>Hello les guys !</title>
    </head>
    <body>
	<p>Pull </p>
    </body>
</html>


<?php

// Use in the “Post-Receive URLs” section of your GitHub repo.

if ( $_POST['payload'] ) {
shell_exec( 'cd /home/nicolas/www/Toureasy && git pull' );
}
?>

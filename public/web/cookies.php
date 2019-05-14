<?php
error_reporting (E_ALL ^ E_WARNING ^ E_NOTICE);

// Check if cookie has been set or not
if ($_GET['set'] != 'yes') {
     	// Set cookie
     	setcookie ('test', 'test', time() + 60);

     	// Reload page
     	header ("Location: cookies.php?set=yes");
} else {
     	// Check if cookie exists
     	if (!empty($_COOKIE['test'])) {
       	 echo "Cookies are enabled on your browser";
    	} else {
       	 echo "Cookies are NOT enabled on your browser";
    	}
}
?> 
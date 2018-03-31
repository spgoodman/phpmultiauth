<?php
// Default is secure anyway but if you want to set it - $SECURE=TRUE; opposite is you want to put include on each page but want to use '$SECURE' to determine page security (maybe you have a DB of page security who knows)

// $restrict_to='username1:username2' if you want only certain people to access a page like admin control panel or something
require('phpmultiauth.inc.php');
?>
<html>
<head>
<title>Example page</title>
</head>
<body>
<h1>Logged in as <?php echo get_session("username"); ?></h1>
<p><a href="?logout=TRUE">Logout</a></p>
</body>
</html>

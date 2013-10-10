<?php
	/*
	 *Submit Test Page
	 *Sample Application for Mountain Framework v2.x
	 *Luke Bullard, October 2013
	*/
	if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
	$firstName = urldecode(Router::urlsegment(1));
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mountain Framework v2.X Sample Application</title>
	</head>
	<body>
		<h1>Welcome, <i><?php echo htmlentities($firstName); ?></i></h1>
		<center>
			<a href="<?php echo Router::getBaseURL(); ?>index">Home</a>
		</center>
	</body>
</html>
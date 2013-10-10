<?php
	/*
	 *Index Page
	 *Sample Application for Mountain Framework v2.x
	 *Luke Bullard, October 2013
	*/
	if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mountain Framework v2.X Sample Application</title>
		<script type="text/javascript">
			function Login()
			{
				var username = document.getElementById("username").value;
				window.location = "<?php echo Router::getBaseURL(); ?>" + username;
			}
		</script>
	</head>
	<body>
		<h1>Login...</h1>
		First Name: <input type="text" id="username" /><br />
		<input type="submit" onclick="Login();" value="Login!" />
	</body>
</html>
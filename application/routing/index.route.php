<?php
	/*
	 *Mountain Framework Sample Application Routing Page
	 *Mountain Framework v2.x
	 *Luke Bullard, October 2013
	*/
	if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
	$routing = array(
		"index" => "sample/index"
		,"/^([a-zA-Z ]+)$/" => "sample/submit"
	);
?>
<?php
    /*
     *MySQLi Module Configuration File
     *Luke Bullard, 2013
     */
	
	if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    $mysqli = array(
        "hostname" => "127.0.0.1"
        ,"username" => "mntn_user"
        ,"password" => "p455w0rd"
        ,"database" => "mntn_db"
    );
?>
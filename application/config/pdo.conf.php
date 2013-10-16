<?php
    /*
     * PDO Module for Mountain Framework v2.0
     * Configuration File
     * Luke Bullard, October 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    $pdo = array(
        "autoload" => "tgbmysql"
        ,"tgbmysql" => array(
            "type" => "mysql"
            ,"hostname" => "127.0.0.1"
            ,"username" => "mntn_user"
            ,"password" => "p455w0rd"
            ,"database" => "mntn_db"
        )
    );
?>
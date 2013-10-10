<?php
    /*
     *Security Configuration file for Mountain Framework v2.0
     *Generic Security Module
     *Luke Bullard, 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    
    $security = array(
        "forceSSL" => false
    );
?>
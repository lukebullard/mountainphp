<?php
    /*
     * OpenID Library (lightopenid) Wrapper for Mountain Framework v2.0
     * Luke Bullard, September 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    class MOD_openid extends MF_Mod {
        public function __construct()
        {
            require(dirname(__FILE__) . "/openid.php");
        }
        public function instantiate()
        {
            return new LightOpenID($_SERVER['HTTP_HOST']);
        }
    }
?>
<?php
    /*
     *Generic Security Module for Mountain Framework v2.0
     *Luke Bullard, August 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    
    class MOD_security extends MF_Mod
    {
        protected $forceSSL;
        
        public function __construct()
        {
            require("application/config/security.conf.php");
            $this->forceSSL = $security['forceSSL'];
        }
        
        public function init($shouldForceSSL=false)
        {
            if ($this->forceSSL || $shouldForceSSL)
            {
                $this->runAsSSL();
            }
            session_start();
            session_regenerate_id(false);
            if (isset($_SESSION['userAgent'],$_SESSION['remoteIP']) && ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'] || $_SESSION['remoteIP'] != $_SERVER['REMOTE_ADDR']))
            {
                header("HTTP/1.0 403 Forbidden"); die("Session Data Mismatch");
            }
        }
        
        public function initSession()
        {
            $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['remoteIP'] = $_SERVER['REMOTE_ADDR'];
        }
        
        public function shutdownSession()
        {
            if (isset($_SESSION['userAgent']))
            {
                unset($_SESSION['userAgent']);
            }
            if (isset($_SESSION['remoteIP']))
            {
                unset($_SESSION['remoteIP']);
            }
        }
        
        private function runAsSSL()
        {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
            {
                $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                Router::redirect($url);
                exit();
            }
        }
    }
?>
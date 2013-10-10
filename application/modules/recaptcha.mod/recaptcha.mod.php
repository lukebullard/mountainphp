<?php
    /*
     *Recaptcha Module for Mountain Framework 2.0
     *Luke Bullard, August 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    
    require("recaptchalib.php");
    class MOD_recaptcha extends MF_Mod
    {
        protected $publicKey;
        protected $privateKey;
        public function __construct()
        {
            require("application/config/recaptcha.conf.php");
            $this->publicKey = $recaptcha['publicKey'];
            $this->privateKey = $recaptcha['privateKey'];
        }
        public function checkAnswer($challenge,$response)
        {
            return recaptcha_check_answer($this->privateKey,$_SERVER['REMOTE_ADDR'],$challenge,$response);
        }
        public function getHTML()
        {
            return recaptcha_get_html($this->publicKey,null,true);
        }
    }
?>
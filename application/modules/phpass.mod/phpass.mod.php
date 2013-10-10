<?php
    /*
     *PHpass Module for Mountain Framework 2.0
     *Luke Bullard, August 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    
    class MOD_phpass extends MF_Mod
    {
        protected $PassHash;
        protected $iteration_count_log2 = 8;
        protected $portable_hashes = true;
        
        public function __construct()
        {
            require("PasswordHash.php");
            $this->PassHash = new PasswordHash($this->iteration_count_log2,$this->portable_hashes);
        }
        public function hash($password)
        {
            return $this->PassHash->HashPassword($password);
        }
        public function checkPassword($password,$storedHash)
        {
            return $this->PassHash->CheckPassword($password,$storedHash);
        }
    }
?>
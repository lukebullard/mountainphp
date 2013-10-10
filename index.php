<?php
    /*
     *Mountain Framework 2.0
     *Luke Bullard, August 2013
     */
    
    define("INPROCESS",true); //this is defined to show that the front controller is the one calling
    //find the page name, or use the default
    if (isset($_GET['p']))
    {
        $pageName = $_GET['p'];
    } else {
        //we are using the seo-friendly URL syntax
        //we have to fill up the $_GET array, if there is something to put in it.
        $exploded = explode("?",$_SERVER['REQUEST_URI'],2);
        $pageName = $exploded[0];
        if (isset($exploded[1]))
        {
            foreach (explode("&",$exploded[1]) as $var)
            {
                $varExploded = explode("=",$var,2);
                $_GET[$varExploded[0]] = $varExploded[1];
            }
        }
    }
    //include the core code for Mountain Framework
    require_once("system/core.php");
    //run the router
    Router::run($pageName);
?>
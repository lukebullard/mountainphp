<?php
    /*
     *Mountain Framework 2.0 Core Code
     *Luke Bullard, 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    
    class Router
    {
        //disable instancing of this class
        private function __construct() { }
        
        private static $completeURL; //the complete URL to this page (trailing slash stripped)
        private static $baseURL; //aka. urlprefix. the complete URL to the root of this site
        private static $urlSegments; //url segment cache
        
        public static function run($page)
        {
            $page = str_replace("..","",rtrim($page,"/"));
            require("system/system.conf.php");
            if (strlen($page) == strlen($config['baseURL'])-1)
            {
                $page = "/index/";
            }
            $page = str_replace($config['baseURL'],"/",$page);
            Router::$completeURL = $page;
            Router::$baseURL = $config['baseURL'];
            Router::$urlSegments = explode("/",Router::$completeURL);
            $urlSegmentTotal = "";
            $page = "application/routing/index.route.php";
            $segmentsCount = 0;
            $pathToTest = "application/routing";
            foreach (Router::$urlSegments as $segment)
            {
                if (file_exists($pathToTest . $urlSegmentTotal . "/" . $segment))
                {
                    if ($segment != "")
                    {
                        $urlSegmentTotal .= "/" . $segment;
                    }
                    $segmentsCount++;
                } else {
                    break;
                }
            }
            if (file_exists($pathToTest . $urlSegmentTotal . "/index.route.php"))
            {
                $page = $pathToTest . $urlSegmentTotal . "/index.route.php";
            }
            
            if ($segmentsCount > -1)
            {
                $pageKey = "";
                for ($x = $segmentsCount; $x < count(Router::$urlSegments); $x++)
                {
                    $pageKey .= Router::$urlSegments[$x] . "/";
                }
                $pageKey = rtrim($pageKey,"/");
            }
            if ($pageKey == "")
            {
                $pageKey = "index";
            }
            if (!file_exists($page))
            {
                Router::do404();
            }
            require($page);
            if (!isset($routing[$pageKey]))
            {
                foreach (array_keys($routing) as $key)
                {
                    if (substr($key,0,1) == "/" && preg_match($key,$pageKey))
                    {
                        $pageKey = $key;
                        break;
                    }
                }
            }
            if (isset($routing[$pageKey]) && file_exists("application/pages/" . $routing[$pageKey] . ".page.php")) {
                //route to that page!
                require("application/pages/" . $routing[$pageKey] . ".page.php");
            } else {
                Router::do404();
            }
        }
        
        private static function do404()
        {
            //404!
            header("HTTP/1.1 404 Not Found");
            ?>
            <html>
                <head>
                    <title>404: Page Not Found!</title>
                </head>
                <body>
                    404: Page Not Found!<br />
                    Sorry, but the page you were looking for was not found!<br />
                    Please try again later...
                </body>
            </html>
            <?php
            exit();
        }
        
        public static function getBaseURL()
        {
            return Router::$baseURL;
        }
        
        public static function urlSegment($id)
        {
            return (isset(Router::$urlSegments[$id]) ? Router::$urlSegments[$id] : "");
        }
        
        public static function redirect($page)
        {
            header("Location: " . $page);
            exit();
        }
        
        public static function redirectHTML($page)
        {
            ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="refresh" content="0;<?php echo $page; ?>">
                </head>
            </html>
            <?php
            exit();
        }
    }
    
    class MF_Mod
    {
        public $hooks;
        public function __construct()
        {
            $this->hooks = array(
                "before" => array() //"before" => array("functionName" => array(callback)) //the callback is the raw argument to call_user_func.
                //the "before" callback returns true to continue with the module call, and false otherwise. there is no need for this kind of behavior
                //with the after callback.
                ,"after" => array()
            );
        }
        public function hook($type,$function,$callback)
        {
            if (!isset($this->hooks[$type][$function]))
            {
                $this->hooks[$type][$function] = array();
            }
            array_push($this->hooks[$type][$function],$callback);
        }
        public function runHook($type,$function)
        {
            if ($type != "before" && $type != "after")
            {
                return;
            }
            if (isset($this->hooks[$type][$function]))
            {
                foreach ($this->hooks[$type][$function] as $callback)
                {
                    $returnedValue = call_user_func_array("call_user_func",$callback);
                    if ($type == "before")
                    {
                        if (!$returnedValue)
                        {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
    }
    
    class Modules
    {
        private function __construct() { }
        
        protected static $moduleList = array();
        
        public static function load($module)
        {
            $module = strtolower($module);
            if (isset(Modules::$moduleList[$module]))
            {
                //the module already exists
                return;
            }
            $modLocation = basename($module) . ".mod/" . basename($module) . ".mod.php";
            $className = "MOD_" . $module;
            //check the application modules folder first.
            if (file_exists("application/modules/" . $modLocation))
            {
                require_once("application/modules/" . $modLocation);
                Modules::$moduleList[$module] = new $className();
            }
        }
        
        public static function run($module,$method)
        {
            $module = strtolower($module);
            if (!isset(Modules::$moduleList[$module]))
            {
                Modules::load($module);
                if (!isset(Modules::$moduleList[$module]))
                {
                    return;
                }
            }
            if (!Modules::$moduleList[$module]->runHook("before",$method))
            {
                return;
            }
            $data = (func_num_args() > 2 ? array_slice(func_get_args(),2) : array());
            $toReturn = call_user_func_array(array(Modules::$moduleList[$module],$method),$data);
            Modules::$moduleList[$module]->runHook("after",$method);
            return $toReturn;
        }
        public static function hook($module,$type,$method,$callback)
        {
            Modules::$moduleList[$module]->hook($type,$method,$callback);
        }
    }
    
    class Commons
    {
        private function __construct() { }
        
        protected static $commonList = array();
        
        public static function load($common)
        {
            $common = strtolower($common);
            if (isset(Commons::$commonList[$common]))
            {
                //the common is already loaded. nothing to do, so return.
                return;
            }
            $commonLocation = basename($common) . ".common.php";
            $className = "COM_" . $common;
            if (file_exists("application/commons/" . $commonLocation))
            {
                require_once("application/commons/" . $commonLocation);
                Commons::$commonList[$common] = new $className();
            }
        }
        
        public static function run($common,$method)
        {
            $common = strtolower($common);
            if (!isset(Commons::$commonList[$common]))
            {
                Commons::load($common);
                if (!isset(Commons::$commonList[$common]))
                {
                    return;
                }
            }
            $data = (func_num_args() > 2 ? array_slice(func_get_args(),2) : array());
            return call_user_func_array(array(Commons::$commonList[$common],$method),$data);
        }
    }
?>
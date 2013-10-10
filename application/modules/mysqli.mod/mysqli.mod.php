<?php
    /*
     *MySQLi Module for Mountain Framework 2.0
     *Luke Bullard, 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    
    class MOD_mysqli extends MF_Mod
    {
        public static $con;
        public function __construct()
        {
            mysqli_report(MYSQLI_REPORT_STRICT);
            require("application/config/database.conf.php");
            $this->init($mysqli['hostname'],$mysqli['username'],$mysqli['password'],$mysqli['database']);
        }
        public function init($hostname,$username,$password,$database)
        {
            if (!isset(MOD_mysqli::$con))
            {
                register_shutdown_function("_shutdown_C_MOD_mysqli");
            } else {
                MOD_mysqli::$con->close();
            }
            MOD_mysqli::$con = new mysqli($hostname,$username,$password,$database);
        }
        public function getConnection()
        {
            return MOD_mysqli::$con;
        }
        public function rollback()
        {
            MOD_mysqli::$con->rollback();
        }
        public function beginTransaction()
        {
            MOD_mysqli::$con->autocommit(false);
        }
        public function endTransaction()
        {
            MOD_mysqli::$con->autocommit(true);
        }
        public function commit()
        {
            MOD_mysqli::$con->commit();
        }
        public function select()
        {
            $args = func_get_args();
            $query = array_shift($args);
            $sql = MOD_mysqli::$con->prepare($query);
            if (!$sql)
            {
                die("Error Preparing Statement: " . htmlentities($query));
            }
            if (count($args) > 0)
            {
                array_unshift($args,str_repeat("s",count($args)));
                $ref = new ReflectionClass("mysqli_stmt");
                $method = $ref->getMethod("bind_param");
                $method->invokeArgs($sql,$args);
            }
            if (!$sql->execute())
            {
                die("Error Executing Statement: " . htmlentities($query));
            }
            $result = $sql->result_metadata();
            $fields = array();
            while ($field = $result->fetch_field())
            {
                $name = $field->name;
                $fields[$name] =&$$name;
            }
            call_user_func_array(array($sql,"bind_result"),$fields);
            $results = array();
            while ($sql->fetch())
            {
                $temp = array();
                foreach ($fields as $key => $value)
                {
                    $temp[$key] = $val;
                }
                array_push($results,$temp);
            }
            $sql->free_result();
            $sql->close();
            return $results;
        }
        public function query()
        {
            $args = func_get_args();
            $query = array_shift($args);
            $sql = MOD_mysqli::$con->prepare($query);
            if (!$sql)
            {
                die("Error Preparing Statement: " . htmlentities($query));
            }
            if (count($args) > 0)
            {
                $types = str_repeat("s",count($args));
                array_unshift($args,$types);
                $ref = new ReflectionClass("mysqli_stmt");
                $method = $ref->getMethod("bind_param");
                $method->invokeArgs($sql,$args);
            }
            if (!$sql->execute())
            {
                die("Error Executing Statement: " . htmlentities($query));
            }
            $toReturn = 0;
            if (isset($sql->insert_id))
            {
                $toReturn = $sql->insert_id;
            }
            $sql->close();
            return $toReturn;
        }
    }
?>
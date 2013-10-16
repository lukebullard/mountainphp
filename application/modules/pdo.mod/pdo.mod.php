<?php
    /*
     * PDO Module for Mountain Framework v2.0
     * Luke Bullard, October 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    class MOD_pdo extends MF_Mod {
        protected $objects;
        protected $selected = false;
        
        public function __construct()
        {
            require("application/config/pdo.conf.php");
            $autoload = false;
            foreach ($pdo as $key => $value)
            {
                if ($key == "autoload")
                {
                    $autoload = $value;
                    continue;
                }
                $this->objects[$key] = $value;
                $this->objects[$key]['loaded'] = false;
            }
            if ($autoload == false)
            {
                return;
            }
            $this->chooseConnection($autoload);
        }
        
        protected function load($key)
        {
            if (!isset($this->objects[$key],$this->objects[$key]['type']))
            {
                return;
            }
            if (isset($this->objects[$key]['link']))
            {
                return;
            }
            switch ($this->objects[$key]['type'])
            {
                case "mysql":
                    if (!isset($this->objects[$key]['username'],$this->objects[$key]['password'],$this->objects[$key]['hostname']))
                    {
                        echo "PDO Error: Invalid MySQL Database Login Details. Hidden for protection.<br />";
                        return;
                    }
                    $dsn = "mysql:dbname=" . $this->objects[$key]['database'] . ";host=" . $this->objects[$key]['hostname'];
                    $username = $this->objects[$key]['username'];
                    $password = $this->objects[$key]['password'];
                    try {
                        $this->objects[$key]['link'] = new PDO($dsn,$username,$password);
                    } catch (PDOException $e) {
                        echo "Connection Failed: " . $e->getMessage();
                        return;
                    }
                    break;
                default:
                    return;
            }
            $this->objects[$key]['loaded'] = true;
        }
        
        public function chooseConnection($key)
        {
            if (!isset($this->objects[$key]))
            {
                return;
            }
            if (!$this->objects[$key]['loaded'])
            {
                $this->load($key);
            }
            if ($this->objects[$key]['loaded'] == false)
            {
                return;
            }
            $this->selected = $this->objects[$key]['link'];
        }
        
        public function getConnection()
        {
            if (!$this->selected)
            {
                echo "No selected connection in PDO getConnection!";
            }
            return $this->selected;
        }
        
        public function rollback()
        {
            if (!$this->selected)
            {
                echo "No selected connection in PDO rollback!";
            }
            $this->selected->rollBack();
        }
        
        public function beginTransaction()
        {
        if (!$this->selected)
            {
                echo "No selected connection in PDO beginTransaction!";
            }
            $this->selected->beginTransaction();
        }
        
        public function commit()
        {
            if (!$this->selected)
            {
                echo "No selected connection in PDO commit!";
            }
            $this->selected->commit();
        }
        public function select()
        {
            if (!$this->selected)
            {
                echo "No selected connection in PDO select!";
            }
            $args = func_get_args();
            $sql = array_shift($args);
            $statement = $this->selected->prepare($sql);
            if (!$statement)
            {
                die("(PDO) Error preparing statement: \"" . $sql . "\"");
            }
            $statementArgs = null;
            if (count($args) > 0)
            {
                $statementArgs = $args;
            }
            if (!$statement->execute($statementArgs))
            {
                die("(PDO) Error executing statement: \"" . $sql . "\"");
            }
            $toReturn = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
            return $toReturn;
        }
        public function query()
        {
            if (!$this->selected)
            {
                echo "No selected connection in PDO query!";
            }
            $args = func_get_args();
            $sql = array_shift($args);
            $statement = $this->selected->prepare($sql);
            if (!$statement)
            {
                die("(PDO) Error preparing statement: \"" . $sql . "\"");
            }
            $statementArgs = null;
            if (count($args) > 0)
            {
                $statementArgs = $args;
            }
            if (!$statement->execute($statementArgs))
            {
                die("(PDO) Error executing statement: \"" . $sql . "\"");
            }
            $toReturn = $this->selected->lastInsertId();
            $statement->closeCursor();
            return $statement;
        }
    }
?>
<?php
namespace App\Lib;
use PDO;

class DataBase 
{
    private $dbname = 'agenda';
    private $host = '127.0.0.1';
    private $port = 80;
    private $username = 'root';
    private $password = '';
    private $driver = 'mysql';

    private $db;

    private function getDSN()
    {
        return sprintf("%s:host=%s;dbname=%s", $this->driver, $this->host, $this->dbname);
    }

    public function connect()
    {
        try
        {
            $this->db = new PDO($this->getDSN(), $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) 
        {
            print "Error!: " . $e->getMessage() . "<br/>";
            return false;
        }

        return $this->db;
    }
    public function getStructureTable($table)
    {
        $stm = $this->db->prepare("DESCRIBE $table");
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}

<?php
namespace RID\SyncDB\Tests;
require_once $_SERVER['DOCUMENT_ROOT']. '/vendor/autoload.php';
use RID\SyncDB;

class SyncDBTest extends \PHPUnit_Extensions_Database_TestCase
{
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
         if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO('mysql:dbname=rabbitmq_test;host=localhost', 'root', '');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, 'rabbitmq_test');
        }

        return $this->conn;
    }

    protected function getDataSet()
    {
 	       return $this->createMySQLXMLDataSet(dirname(__FILE__).'/fixture/firstable_state.xml');
    }

    public function testInsertToDB () 
    {
        $id = '3726'; 
        $message = '6.2.1b3nr2oXMHMhGVzPcUByWndV0xfWY732XlubxVSSO533nxocjvIieHnMdlc';
        $publisher = '6';
        $consumer = '2';
        $date = '2015-05-29 13:40:07';

        $sql = "INSERT INTO `level_2` (`id`,`data`,`publisher`,`consumer`, `date`) VALUES ('{$id}','{$message}','{$publisher}','$consumer','$date')";
        $statement = $this->getConnection()->getConnection()->query($sql);
        $queryTable = $this->getConnection()->createQueryTable('level_2', 'SELECT `id`,`data`,`publisher`,`consumer`, `date` FROM level_2');
/*
        $ds = new \PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $ds->addTable('level_2', , 'SELECT `id`,`data`,`publisher`,`consumer`, `date` FROM level_2');
        $queryTable = $ds->getTable("level_2")

*/
        $expectedTable = $this->createMySQLXMLDataSet(dirname(__FILE__).'/fixture/state_testInsertToDB.xml')->getTable("level_2");
        $this->assertTablesEqual($queryTable, $expectedTable);
    }

    public function testInsertToDB2 () 
    {
        $sql = "SELECT * FROM `level_2`";
        $statement = $this->getConnection()->getConnection()->query($sql);
        $result = $statement->fetchAll();
        $this->assertEquals(1, sizeof($result));
    }
}

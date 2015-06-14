<?php
namespace RID\CommunicationData\Tests;
require_once $_SERVER['DOCUMENT_ROOT']. '/vendor/autoload.php';
use RID\CommunicationData;

class ReceiveDataRabbitTest extends \PHPUnit_Framework_TestCase
{
    public function testCalc()
    {
    	$connectParams = array('host'=>'192.168.10.102', 'port'=> 5672, 'login' => 'pk', 'pswd' => '123');
        $receive = new \RID\CommunicationData\ReceiveDataRabbit($connectParams);
        $result = $receive->Calc(30, 12);
        // assert that our calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }
}
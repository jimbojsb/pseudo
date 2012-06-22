<?php
class ResultCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testGetResultWithoutMocking()
    {
        $r = new Pseudo\ResultCollection();
        $this->setExpectedException("Pseudo\\Exception");
        $r->getResult("SELECT 1");
    }
}
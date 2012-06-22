<?php
class ResultTest extends PHPUnit_Framework_TestCase
{
    public function testSetErrorCode()
    {
        $r = new Pseudo\Result;
        $r->setErrorCode("HY000");
        $this->assertEquals("HY000", $r->getErrorCode());
        $this->setExpectedException("Pseudo\\Exception");
        $r->setErrorCode("121");
    }
}
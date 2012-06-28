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

    public function testNextRow()
    {
        $row1 = [
            'id' => 1,
            'foo' => 'bar',
        ];
        $row2 = [
            'id'  => 2,
            'foo' => 'baz'
        ];
        $r = new Pseudo\Result();
        $r->addRow($row1);
        $r->addRow($row2);

        $this->assertEquals($row1, $r->nextRow());
        $this->assertEquals($row2, $r->nextRow());
        $this->assertEquals(false, $r->nextRow());
    }

    public function testAddRow()
    {
        $row = [
            'id'  => 1,
            'foo' => 'bar'
        ];
        $params = [
            'bar'
        ];

        $r = new Pseudo\Result;
        $r->addRow($row);
        $this->assertEquals(1, count($r->getRows()));

        $r = new Pseudo\Result;
        $r->addRow($row, $params);
        $this->assertEquals(1, count($r->getRows($params)));
    }

    public function testReset()
    {
        $row = [
            'id'  => 1,
            'foo' => 'bar'
        ];
        $r = new Pseudo\Result();
        $r->addRow($row);
        $this->assertEquals($row, $r->nextRow());
        $this->assertEquals(null, $r->nextRow());
        $r->reset();
        $this->assertEquals($row, $r->nextRow());
    }
}
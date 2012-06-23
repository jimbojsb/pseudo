<?php
class PdoStatementTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllWithNoArguments()
    {
        $rows = [
            'id'  => 1,
            'foo' => 'bar'
        ];
        $expectedFetchResult = [
            [
                'id'  => 1,
                0     => 1,
                'foo' => 'bar',
                1     => 'bar'
            ]
        ];
        $r = new Pseudo\Result([$rows]);
        $s = new Pseudo\PdoStatement();
        $s->setResult($r);
        $fetchResult = $s->fetchAll();
        $this->assertEquals($expectedFetchResult, $fetchResult);
    }

    public function testFetchAllWithFetchAssoc()
    {
        $rows = [
            'id'  => 1,
            'foo' => 'bar'
        ];
        $r = new Pseudo\Result([$rows]);
        $s = new Pseudo\PdoStatement();
        $s->setResult($r);
        $fetchResult = $s->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals([$rows], $fetchResult);
    }

    public function testFetchAllWithFetchNum()
    {
        $rows = [
            'id'  => 1,
            'foo' => 'bar'
        ];
        $expectedFetchResult = [
            [
                0     => 1,
                1     => 'bar'
            ]
        ];
        $r = new Pseudo\Result([$rows]);
        $s = new Pseudo\PdoStatement();
        $s->setResult($r);
        $fetchResult = $s->fetchAll(PDO::FETCH_NUM);
        $this->assertEquals($expectedFetchResult, $fetchResult);
    }

    public function testFetchAllWithFetchObj()
    {
        $rows = [
            'id'  => 1,
            'foo' => 'bar'
        ];
        $expectedFetchResult = [
            (object) [
                'id'  => 1,
                'foo' => 'bar'
            ]
        ];
        $r = new Pseudo\Result([$rows]);
        $s = new Pseudo\PdoStatement();
        $s->setResult($r);
        $fetchResult = $s->fetchAll(PDO::FETCH_OBJ);
        $this->assertEquals($expectedFetchResult, $fetchResult);
    }

    public function testRowCount()
    {
        $s = new Pseudo\PdoStatement();
        $r = new Pseudo\Result();
        $s->setResult($r);
        $this->assertEquals(0, $s->rowCount());
        $r->setAffectedRowCount(5);
        $this->assertEquals(5, $s->rowCount());
    }

    public function testErrorCode()
    {
        $r = new Pseudo\Result();
        $r->setErrorCode("HY000");
        $p = new Pseudo\PdoStatement($r);
        $this->assertEquals("HY000", $p->errorCode());
    }

    public function testErrorInfo()
    {
        $r = new Pseudo\Result();
        $r->setErrorInfo("Storage engine error");
        $p = new Pseudo\PdoStatement($r);
        $this->assertEquals("Storage engine error", $p->errorInfo());
    }

    public function testColumnCount()
    {
        $r = new Pseudo\Result();
        $r->addRow(
            [
                'id'  => 1,
                'foo' => 'bar'
            ]
        );
        $p = new Pseudo\PdoStatement($r);
        $this->assertEquals(2, $p->columnCount());
    }

    public function testSetFetchMode()
    {
        $p = new Pseudo\PdoStatement();
        $success = $p->setFetchMode(PDO::FETCH_ASSOC);
        $this->assertEquals(1, $success);
        $success = $p->setFetchMode(456);
        $this->assertEquals(false, $success);
    }

    public function testFetch()
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
        $p = new Pseudo\PdoStatement($r);

        $data = $p->fetch();
        $this->assertEquals(false, $data);

        $r->addRow($row1);
        $r->addRow($row2);

        $p->setFetchMode(PDO::FETCH_ASSOC);
        $this->assertEquals($row1, $p->fetch());
        $this->assertEquals($row2, $p->fetch());
    }

    public function testFetchObject()
    {
        $row1 = [
            'id' => 1,
            'foo' => 'bar',
        ];
        $testObject = (object) $row1;
        $r = new Pseudo\Result();
        $r->addRow($row1);
        $s = new Pseudo\PdoStatement($r);
        $this->assertEquals($testObject, $s->fetchObject());
    }
}
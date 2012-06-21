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


}
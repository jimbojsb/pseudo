<?php
class PdoTest extends PHPUnit_Framework_TestCase
{
    public function testMockQueryIsStored()
    {
        $sql = "SELECT 1";
        $p = new Pseudo\Pdo();
        $p->mock($sql, [[1]]);
        $queries = $p->getMockedQueries();
        $this->assertTrue($queries->exists($sql));
        $resultRows = $queries->getResult($sql)->getRows();
        $this->assertEquals($resultRows[0], [1]);
    }


    public function testQueryReturnsMockedResults()
    {
        $p = new Pseudo\Pdo();
        $expectedRows = new Pseudo\Result();
        $expectedRows->addRow(
            [
                "foo" => "bar",
                "id"  => 1
            ]
        );
        $p->mock("SELECT * FROM test WHERE foo='bar'", $expectedRows);
        $result = $p->query("SELECT * FROM test WHERE foo='bar'");
        $this->assertEquals($expectedRows->getRows(), $result->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testLastInsertId()
    {
        $sql = "INSERT INTO foo VALUES ('1')";
        $r = new Pseudo\Result();
        $p = new Pseudo\Pdo();
        $p->mock($sql, $r);
        $p->query($sql);
        $this->assertEquals(0, $p->lastInsertId());
        $r->setInsertId(1);
        $p->query($sql);
        $this->assertEquals(1, $p->lastInsertId());
    }

    public function testErrorInfo()
    {
        $sql = "SELECT 1";
        $r = new Pseudo\Result();
        $p = new Pseudo\Pdo();
        $p->mock($sql, $r);
        $p->query($sql);
        $this->assertEquals(0, $p->lastInsertId());
        $r->setInsertId(1);
        $p->query($sql);
        $this->assertEquals(1, $p->lastInsertId());
    }

    public function testErrorCode()
    {
        $sql = "SELECT 1";
        $r = new Pseudo\Result();
        $p = new Pseudo\Pdo();
        $p->mock($sql, $r);
        $p->query($sql);
        $this->assertEquals(0, $p->lastInsertId());
        $r->setInsertId(1);
        $p->query($sql);
        $this->assertEquals(1, $p->lastInsertId());
    }
}
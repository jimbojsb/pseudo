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

    public function testTransactionStates()
    {
        $p = new Pseudo\Pdo();
        $this->assertEquals($p->inTransaction(), false);

        $this->assertEquals($p->beginTransaction(), true);
        $this->assertEquals($p->inTransaction(), true);

        $this->assertEquals($p->commit(), true);
        $this->assertEquals($p->inTransaction(), false);

        $p->beginTransaction();
        $this->assertEquals($p->beginTransaction(), false);
        $this->assertEquals($p->inTransaction(), true);
        $this->assertEquals($p->rollBack(), true);
        $this->assertEquals($p->commit(), false);
    }

    public function testExec()
    {
        $sql = "SELECT 1";
        $p = new Pseudo\Pdo();
        $r = new Pseudo\Result();
        $p->mock($sql, $r);
        $results = $p->exec($sql);
        $this->assertEquals(0, $results);
        $r->setAffectedRowCount(5);
        $this->assertEquals(5, $p->exec($sql));
    }

    public function testPrepare()
    {
        $sql = "SELECT * FROM test WHERE foo='bar'";
        $p = new Pseudo\Pdo();
        $p->mock($sql);
        $statement = $p->prepare($sql);
        $this->assertInstanceOf("Pseudo\\PdoStatement", $statement);

    }
}
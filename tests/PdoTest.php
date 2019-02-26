<?php
class PdoTest extends PHPUnit_Framework_TestCase
{
    public function testMock()
    {
        $sql1 = "SELECT * FROM test WHERE foo='bar'";
        $result1 = [
            [
                'id'  => 1,
                'foo' => 'bar'
            ]
        ];

        $p = new Pseudo\Pdo();
        $p->mock($sql1, $result1);
        $queries = $p->getMockedQueries();
        $this->assertTrue($queries->exists($sql1));

        $sql2 = "SELECT * FROM test WHERE foo=:param1";
        $params2 = ["param1" => "bar"];

        $sql3 = "SELECT * FROM test WHERE foo=?";
        $params3 = ['bar'];

        $params4 = ['baz'];
        $result2 = [
            [
                'id'  => 2,
                'foo' => 'baz'
            ]
        ];

        $p->mock($sql2, $result1, $params2);
        $p->mock($sql3, $result1, $params3);
        $p->mock($sql3, $result2, $params4);

        $this->assertEquals(3, count($p->getMockedQueries()));


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

    public function testLastInsertIdPreparedStatement()
    {
        $sql = "SELECT * FROM test WHERE foo='bar'";
        $p = new Pseudo\Pdo();
        $r = new Pseudo\Result();
        $r->setInsertId(10);
        $p->mock($sql, $r);
        $statement = $p->prepare($sql);
        $statement->execute();
        $this->assertEquals(10, $p->lastInsertId());
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

    public function testLoad()
    {
        $r = new Pseudo\ResultCollection();
        $r->addQuery("SELECT 1", [[1]]);
        $serialized = serialize($r);
        if (file_exists('testload')) {
            unlink('testload');
        }
        file_put_contents('testload', $serialized);
        $p = new Pseudo\Pdo();
        $p->load('testload');
        $this->assertEquals($r, $p->getMockedQueries());
        unlink('testload');
    }

    public function testSave()
    {
        $r = new Pseudo\ResultCollection();
        $r->addQuery("SELECT 1", [[1]]);
        $serialized = serialize($r);
        if (file_exists('testsave')) {
            unlink('testsave');
        }
        $p = new Pseudo\Pdo($r);
        $p->save('testsave');
        $queries = unserialize(file_get_contents('testsave'));
        $this->assertEquals($r, $queries);
        unlink('testsave');
    }
    
    public function testDebuggingRawQueries()
    {
        $message = null;
        $p = new Pseudo\Pdo();
        try {
            $p->prepare('SELECT 123');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->assertRegExp('/SELECT 123/', $message);
    }
}

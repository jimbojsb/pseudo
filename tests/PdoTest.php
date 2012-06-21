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
        $this->markTestIncomplete();
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
        $this->assertEquals($expectedRows->getRows(), $result->fetchAll());
    }
}
<?php
class QueryLogTest extends PHPUnit_Framework_TestCase
{
    public function testAddQuery()
    {
        $sql = "SELECT foo FROM bar";
        $queryLog = new \Pseudo\QueryLog();
        $queryLog->addQuery($sql);
        $queries = $queryLog->getQueries();
        $this->assertEquals(1, count($queries));
        $this->assertTrue($queries[0]->isEqualTo($sql));
    }

    public function testAddQueryWithParams()
    {
        $sql = "SELECT foo FROM ?";
        $params = ["bar"];
        $queryLog = new \Pseudo\QueryLog();
        $queryLog->addQuery($sql, $params);
        $queries = $queryLog->getQueries();
        $this->assertEquals(1, count($queries));
        $this->assertTrue($queries[0]->isEqualTo($sql, $params));
    }
}
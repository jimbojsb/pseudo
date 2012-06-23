<?php
namespace Pseudo;

class ResultCollection implements \Countable
{
    private $queries = [];

    public function count()
    {
        return count($this->queries);
    }

    public function addQuery($sql, $results)
    {
        $sqlHash = $this->hashSql($sql);

        if (is_array($results)) {
            $storedResults = new Result($results);
        } else if ($results instanceof Result) {
            $storedResults = $results;
        } else {
            $storedResults = new Result;
        }

        $this->queries[$sqlHash] = $storedResults;
    }

    public function exists($sql)
    {
        return isset($this->queries[$this->hashSql($sql)]);
    }

    public function getResult($sql)
    {
        $result = $this->queries[$this->hashSql($sql)];
        if ($result instanceof Result) {
            return $result;
        } else {
            throw new Exception("Attempting an operation on an un-mocked query is not allowed");
        }
    }

    private function hashSql($sql)
    {
        $p = new \PHPSQLParser();
        $hash = sha1(serialize($p->parse($sql)));
        return $hash;
    }

}
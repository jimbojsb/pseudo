<?php
namespace Pseudo;

class ResultCollection
{
    private $queries = [];

    public function addQuery($sql, $results)
    {
        $sqlHash = $this->hashSql($sql);

        if (is_array($results)) {
            $storedResults = new Result($results);
        } else {
            $storedResults = $results;
        }

        $this->queries[$sqlHash] = $storedResults;
    }

    public function exists($sql)
    {
        return isset($this->queries[$this->hashSql($sql)]);
    }

    public function getResult($sql)
    {
        return $this->queries[$this->hashSql($sql)];
    }

    private function hashSql($sql)
    {
        $p = new \PHPSQLParser();
        $hash = sha1(serialize($p->parse($sql)));
        return $hash;
    }

}
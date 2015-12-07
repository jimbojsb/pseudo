<?php
namespace Pseudo;

class ResultCollection implements \Countable
{
    private $queries = [];
    
    public function count()
    {
        return count($this->queries);
    }

    public function addQuery($sql, $results, $params = null)
    {
        $query = new ParsedQuery($sql);

        if (is_array($results)) {
            $storedResults = new Result($results, $params);
        } else if ($results instanceof Result) {
            $storedResults = $results;
        } else {
            $storedResults = new Result;
        }

        $this->queries[$query->getHash()] = $storedResults;
    }

    public function exists($sql)
    {
        $query = new ParsedQuery($sql);
        return isset($this->queries[$query->getHash()]);
    }

    public function getResult($query)
    {
        if (!($query instanceof ParsedQuery)) {
            $query = new ParsedQuery($query);
        }
        $result = (isset($this->queries[$query->getHash()])) ? $this->queries[$query->getHash()] : null;
        if ($result instanceof Result) {
            return $result;
        } else {
            $message = "Attempting an operation on an un-mocked query is not allowed, the raw query: "
                . $query->getRawQuery();
            throw new Exception($message);
        }
    }
}

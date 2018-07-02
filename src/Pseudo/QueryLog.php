<?php
namespace Pseudo;

class QueryLog implements \IteratorAggregate, \ArrayAccess, \Countable
{
    private $queries = [];

    public function count()
    {
        return count($this->queries);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->queries);
    }

    public function offsetExists($offset)
    {
        return isset($this->queries[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->queries[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->queries[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->queries[$offset]);
    }

    public function addQuery($sql)
    {
        $this->queries[] = new ParsedQuery($sql);
    }

    public function getQueries()
    {
        return $this->queries;
    }
}

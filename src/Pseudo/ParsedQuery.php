<?php
namespace Pseudo;

class ParsedQuery
{
    private $parsedQuery;
    private $rawQuery;
    private $hash;

    /**
     * @param string $query
     */
    public function __construct($query)
    {
        $parser = new \PHPSQLParser();
        $this->parsedQuery = $parser->parse($query);
        $this->rawQuery = $query;
        $this->hash = sha1(serialize($this->parsedQuery));
    }

    public function isEqualTo($query)
    {
        if (!($query instanceof self)) {
            $query = new self($query);
        }
        return $this->hash === $query->getHash();
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getRawQuery()
    {
        return $this->rawQuery;
    }
}
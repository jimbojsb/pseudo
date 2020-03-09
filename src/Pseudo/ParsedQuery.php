<?php
namespace Pseudo;

class ParsedQuery
{
    private $parsedQuery;
    private $rawQuery;
    private $hash;
    private $params;

    /**
     * @param string $query
     */
    public function __construct($query, $params = null)
    {
        $parser = new \PHPSQLParser();
        $this->parsedQuery = $parser->parse($query);
        $this->rawQuery = $query;
        $this->params = $params;
        $serializedParams = "";
        if ($this->params != null) {
          $serializedParams = serialize($this->params);
        }
        $this->hash = sha1(serialize($this->parsedQuery) . $serializedParams);
    }

    public function isEqualTo($query, $params = null)
    {
        if (!($query instanceof self)) {
            $query = new self($query, $params);
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
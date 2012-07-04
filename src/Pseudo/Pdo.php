<?php
namespace Pseudo;

class Pdo extends \PDO
{
    private $mockedQueries;
    private $queryLog = [];
    private $inTransaction = false;
    private $realPdo = null;


    public function prepare($statement)
    {
        $result = $this->mockedQueries->getResult($statement);
        $statement = new PdoStatement($result);
        return $statement;
    }

    public function beginTransaction()
    {
        if (!$this->inTransaction) {
            $this->inTransaction = true;
            return true;
        }
        return false;
        // not yet implemented
    }

    public function commit()
    {
        if ($this->inTransaction()) {
            $this->inTransaction = false;
            return true;
        }
        return false;
        // not yet implemented
    }

    public function rollBack()
    {
        if ($this->inTransaction()) {
            $this->inTransaction = false;
            return true;
        }
        // not yet implemented
    }

    public function inTransaction()
    {
        return $this->inTransaction;
    }

    public function setAttribute($attribute, $value)
    {
        // not yet implemented
    }

    public function exec($statement)
    {
        $result = $this->query($statement);
        if ($result) {
            return $result->rowCount();
        }
        return 0;
    }

    /**
     * @param string $statement
     * @return PdoStatement
     */
    public function query($statement)
    {
        if ($this->mockedQueries->exists($statement)) {
            $result = $this->mockedQueries->getResult($statement);
            if ($result) {
                $this->queryLog[] = $statement;
                $statement = new PdoStatement();
                $statement->setResult($result);
                return $statement;
            }
        } else {

        }
    }

    /**
     * @param null $name
     * @return int
     */
    public function lastInsertId($name = null)
    {
        $result = $this->getLastResult();
        if ($result) {
            return $result->getInsertId();
        }
        return 0;
    }

    /**
     * @return result
     */
    private function getLastResult()
    {
        $lastQuery = $this->queryLog[count($this->queryLog) - 1];
        $result = $this->mockedQueries->getResult($lastQuery);
        return $result;
    }

    public function errorCode()
    {
        // not yet implemented
    }

    public function errorInfo()
    {
        // not yet implemented
    }

    public function getAttribute($attribute)
    {
        // not yet implemented
    }

    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        // not yet implemented
    }

    public function __construct()
    {
        $this->mockedQueries = new ResultCollection();
    }

    public function record(PDO $pdo)
    {
        $this->realPdo = $pdo;
    }

    public function save($filePath)
    {
        file_put_contents($filePath, serialize($this->mockedQueries));
    }

    public function load($filePath)
    {
        $this->mockedQueries = unserialize(file_get_contents($filePath));
    }

    public function mock($sql, $expectedResults = null, $params = null)
    {
        $this->mockedQueries->addQuery($sql, $expectedResults, $params);
    }

    public function getMockedQueries()
    {
        return $this->mockedQueries;
    }
}
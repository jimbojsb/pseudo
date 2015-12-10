<?php
namespace Pseudo;

class Pdo extends \PDO
{
    private $mockedQueries;
    private $inTransaction = false;
    private $queryLog;

    public function prepare($statement, $driver_options = null)
    {
        $result = $this->mockedQueries->getResult($statement);
        return new PdoStatement($result, $this->queryLog, $statement);
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
                $this->queryLog->addQuery($statement);
                $statement = new PdoStatement();
                $statement->setResult($result);
                return $statement;
            }
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

    /**
     * @param ResultCollection $collection
     */
    public function __construct(ResultCollection $collection = null) 
    {
        $this->mockedQueries = $collection ?: new ResultCollection();
        $this->queryLog = new QueryLog();
    }

    /**
     * @param string $filePath
     */
    public function save($filePath)
    {
        file_put_contents($filePath, serialize($this->mockedQueries));
    }

    /**
     * @param $filePath
     */
    public function load($filePath)
    {
        $this->mockedQueries = unserialize(file_get_contents($filePath));
    }

    /**
     * @param $sql
     * @param null $expectedResults
     * @param null $params
     */
    public function mock($sql, $expectedResults = null, $params = null)
    {
        $this->mockedQueries->addQuery($sql, $expectedResults, $params);
    }

    /**
     * @return ResultCollection
     */
    public function getMockedQueries()
    {
        return $this->mockedQueries;
    }
}

<?php
namespace Pseudo;

class Pdo extends \PDO
{
    protected $mockedQueries;

    public function prepare($statement, array $driver_options = null)
    {
        parent::prepare($statement, $driver_options);
    }

    public function beginTransaction()
    {
        parent::beginTransaction();
    }

    public function commit()
    {
        parent::commit();
    }

    public function rollBack()
    {
        parent::rollBack();
    }

    public function inTransaction()
    {
        parent::inTransaction();
    }

    public function setAttribute($attribute, $value)
    {
        parent::setAttribute($attribute, $value);
    }

    public function exec($statement)
    {
        parent::exec($statement);
    }

    public function query($statement)
    {
        if ($this->mockedQueries->exists($statement)) {
            $result = $this->mockedQueries->getResult($statement);
            if ($result) {
                $statement = new PdoStatement();
                $statement->setResult($result);
                return $statement;
            }
        } else {

        }
    }

    public function lastInsertId($name = null)
    {
        parent::lastInsertId($name);
    }

    public function errorCode()
    {
        parent::errorCode();
    }

    public function errorInfo()
    {
        parent::errorInfo();
    }

    public function getAttribute($attribute)
    {
        parent::getAttribute($attribute);
    }

    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        parent::quote($string, $parameter_type);
    }

    public static function getAvailableDrivers()
    {
        parent::getAvailableDrivers();
    }


    public function __construct()
    {
        $this->mockedQueries = new ResultCollection();
    }

    public function record(PDO $pdo)
    {

    }

    public function mock($sql, $expectedResults, $params = null)
    {
        $this->mockedQueries->addQuery($sql, $expectedResults);
    }

    public function getMockedQueries()
    {
        return $this->mockedQueries;
    }

    public function __call($name, $args)
    {
    }
}
<?php
namespace Pseudo;

class PdoStatement extends \PDOStatement
{

    /**
     * @var Result;
     */
    private $result;
    private $fetchMode = \PDO::FETCH_BOTH; //DEFAULT FETCHMODE
    private $boundParams = [];
    private $boundColumns = [];

    /**
     * @var QueryLog
     */
    private $queryLog;

    /**
     * @var string
     */
    private $statement;

    /**
     * @param Querylog $queryLog
     * @param string $statement
     * @param Result|null $result
     */
    public function __construct($result = null, QueryLog $queryLog = null, $statement = null)
    {
        if (!($result instanceof Result)) {
            $result = new Result();
        }
        $this->result = $result;
        if (!($queryLog instanceof QueryLog)) {
            $queryLog = new QueryLog();
        }
        $this->queryLog = $queryLog;
        $this->statement = $statement;
    }

    public function setResult(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @param array|null $input_parameters
     * @return bool
     */
    public function execute($input_parameters = null)
    {
        $input_parameters = array_merge((array)$input_parameters, $this->boundParams);
        try {
            $this->result->setParams($input_parameters, !empty($this->boundParams));
            $success = (bool) $this->result->getRows($input_parameters ?: []);
            $this->queryLog->addQuery($this->statement);
            return $success;
        } catch (Exception $e) {
            return false;
        }
    }

    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        // scrolling cursors not implemented
        $row = $this->result->nextRow();
        if ($row) {
            return $this->proccessFetchedRow($row, $fetch_style);
        }
        return false;
    }

    public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR, $length = null, $driver_options = null)
    {
        $this->boundParams[$parameter] =&$variable;
        return true;
    }

    public function bindColumn($column, &$param, $type = null, $maxlen = null, $driverdata = null)
    {
        $this->boundColumns[$column] =&$param;
        return true;
    }

    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
    {
        $this->boundParams[$parameter] = $value;
        return true;
    }

    public function rowCount()
    {
        return $this->result->getAffectedRowCount();
    }

    public function fetchColumn($column_number = 0)
    {
        $row = $this->result->nextRow();
        if ($row) {
            $row = $this->proccessFetchedRow($row, \PDO::FETCH_NUM);
            return $row[$column_number];
        }
        return false;
    }

    public function fetchAll($fetch_style = \PDO::FETCH_BOTH, $fetch_argument = null, $ctor_args = 'array()')
    {
        $rows = $this->result->getRows() ?: [];
        $returnArray = [];
        foreach ($rows as $row) {
            $returnArray[] = $this->proccessFetchedRow($row, $fetch_style);
        }
        return $returnArray;
    }

    private function proccessFetchedRow($row, $fetchMode)
    {
		$i = 0;
        switch ($fetchMode ?: $this->fetchMode) {
            case \PDO::FETCH_BOTH:
                $returnRow = [];
                $keys = array_keys($row);
                $c = 0;
                foreach ($keys as $key) {
                    $returnRow[$key] = $row[$key];
                    $returnRow[$c++] = $row[$key];
                }
                return $returnRow;
            case \PDO::FETCH_ASSOC:
                return $row;
            case \PDO::FETCH_NUM:
                return array_values($row);
            case \PDO::FETCH_OBJ:
                return (object) $row;
            case \PDO::FETCH_BOUND:
                if ($this->boundColumns) {
                    if ($this->result->isOrdinalArray($this->boundColumns)) {
                        foreach ($this->boundColumns as &$column) {
                            $column = array_values($row)[++$i];
                        }
                    } else {

                        foreach ($this->boundColumns as $columnName => &$column) {
                            $column = $row[$columnName];
                        }
                    }
                    return true;
                }
                break;
            case \PDO::FETCH_COLUMN:
               $returnRow = array_values( $row );
               return $returnRow[0];
        }
        return null;
    }

    /**
     * @param string|null $class_name
     * @param array|null $ctor_args
     * @return bool|mixed
     */
    public function fetchObject($class_name = "stdClass", $ctor_args = null)
    {
        $row = $this->result->nextRow();
        if ($row) {
            $reflect  = new \ReflectionClass($class_name);
            $obj = $reflect->newInstanceArgs($ctor_args ?: []);
            foreach ($row as $key => $val) {
                $obj->$key = $val;
            }
            return $obj;
        }
        return false;
    }

    /**
     * @return string
     */
    public function errorCode()
    {
        return $this->result->getErrorCode();
    }

    /**
     * @return string
     */
    public function errorInfo()
    {
        return $this->result->getErrorInfo();
    }

    /**
     * @return int
     */
    public function columnCount()
    {
        $rows = $this->result->getRows();
        if ($rows) {
            $row = array_shift($rows);
            return count(array_keys($row));
        }
        return 0;
    }

    /**
     * @param int $mode
     * @param array|null $params
     * @return bool|int
     */
    public function setFetchMode($mode, $params = null)
    {
        $r = new \ReflectionClass(new Pdo());
        $constants = $r->getConstants();
        $constantNames = array_keys($constants);
        $allowedConstantNames = array_filter($constantNames, function($val) {
            return strpos($val, 'FETCH_') === 0;
        });
        $allowedConstantVals = [];
        foreach ($allowedConstantNames as $name) {
            $allowedConstantVals[] = $constants[$name];
        }

        if (in_array($mode, $allowedConstantVals)) {
            $this->fetchMode = $mode;
            return 1;
        }
        return false;
    }

    public function nextRowset()
    {
        // not implemented
    }

    public function closeCursor()
    {
        // not implemented
    }

    public function debugDumpParams()
    {
        // not implemented
    }


    // some functions make no sense when not actually talking to a database, so they are not implemented

    public function setAttribute($attribute, $value)
    {
        // not implemented
    }

    public function getAttribute($attribute)
    {
        // not implemented
    }

    public function getColumnMeta($column)
    {
        // not implemented
    }

    public function getBoundParams()
    {
        return $this->boundParams;
    }
}

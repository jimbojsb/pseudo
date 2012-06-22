<?php
namespace Pseudo;

class PdoStatement extends \PDOStatement
{

    /**
     * @var Result;
     */
    private $result;

    public function __construct($result = null)
    {
        if (!($result instanceof Result)) {
            $result = new Result();
        }
        $this->result = $result;;
    }

    public function setResult(Result $result)
    {
        $this->result = $result;
    }

    public function execute(array $input_parameters = null)
    {
        parent::execute($input_parameters);
    }

    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        parent::fetch($fetch_style, $cursor_orientation, $cursor_offset);
    }

    public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR, $length = null, $driver_options = null)
    {
        parent::bindParam($parameter, $variable, $data_type, $length, $driver_options);
    }

    public function bindColumn($column, &$param, $type = null, $maxlen = null, $driverdata = null)
    {
        parent::bindColumn($column, $param, $type, $maxlen, $driverdata);
    }

    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
    {
        parent::bindValue($parameter, $value, $data_type);
    }

    public function rowCount()
    {
        return $this->result->getAffectedRowCount();
    }

    public function fetchColumn($column_number = 0)
    {
        parent::fetchColumn($column_number);
    }

    public function fetchAll($fetch_style = \PDO::FETCH_BOTH, $fetch_argument = null, $ctor_args = 'array()')
    {
        $rows = $this->result->getRows();
        $returnArray = [];
        switch ($fetch_style) {
            case \PDO::FETCH_BOTH:
                foreach ($rows as $row) {
                    $keys = array_keys($row);
                    $c = 0;
                    foreach ($keys as $key) {
                        $returnRow[$key] = $row[$key];
                        $returnRow[$c++] = $row[$key];
                    }
                    $returnArray[] = $returnRow;
                }
                break;
            case \PDO::FETCH_ASSOC:
                $returnArray = $rows;
                break;
            case \PDO::FETCH_NUM:
                foreach ($rows as $row) {
                    $keys = array_keys($row);
                    $c = 0;
                    foreach ($keys as $key) {
                        $returnRow[$c++] = $row[$key];
                    }
                    $returnArray[] = $returnRow;
                }
                break;
            case \PDO::FETCH_OBJ:
                foreach ($rows as $row) {
                    $returnArray[] = (object) $row;
                }
                break;
        }
        return $returnArray;
    }

    public function fetchObject($class_name = "stdClass", array $ctor_args = null)
    {
        parent::fetchObject($class_name, $ctor_args);
    }

    public function errorCode()
    {
        return $this->result->getErrorCode();
    }

    public function errorInfo()
    {
        return $this->result->getErrorInfo();
    }

    public function setAttribute($attribute, $value)
    {
        parent::setAttribute($attribute, $value);
    }

    public function getAttribute($attribute)
    {
        parent::getAttribute($attribute);
    }

    public function columnCount()
    {
        parent::columnCount();
    }

    public function getColumnMeta($column)
    {
        parent::getColumnMeta($column);
    }

    public function setFetchMode($mode)
    {
        parent::setFetchMode($mode);
    }

    public function nextRowset()
    {
        parent::nextRowset();
    }

    public function closeCursor()
    {
        parent::closeCursor();
    }

    public function debugDumpParams()
    {
        parent::debugDumpParams();
    }

}
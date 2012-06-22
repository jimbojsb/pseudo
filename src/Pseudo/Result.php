<?php
namespace Pseudo;

class Result
{
    private $rows;
    private $errorCode;
    private $errorInfo;
    private $affectedRowCount = 0;
    private $insertId = 0;

    public function __construct($rows = null)
    {
        if (is_array($rows)) {
            $this->rows = $rows;
        }
    }



    public function addRow(array $row)
    {
        $this->rows[] = $row;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function setInsertId($insertId)
    {
        $this->insertId = $insertId;
    }

    public function getInsertId()
    {
        return $this->insertId;
    }

    public function setErrorCode($errorCode)
    {
        if (ctype_alnum($errorCode) && strlen($errorCode) == 5) {
            $this->errorCode = $errorCode;
        } else {
            throw new Exception("Error codes must be in ANSI SQL standard format");
        }
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function setErrorInfo($errorInfo)
    {
        $this->errorInfo = $errorInfo;
    }

    public function getErrorInfo()
    {
        return $this->errorInfo;
    }

    public function setAffectedRowCount($affectedRowCount)
    {
        $this->affectedRowCount = $affectedRowCount;
    }

    public function getAffectedRowCount()
    {
        return $this->affectedRowCount;
    }
}
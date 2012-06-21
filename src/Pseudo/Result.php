<?php
namespace Pseudo;

class Result
{
    private $rows;
    private $errorCode;
    private $errorInfo;
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
        $this->errorCode = $errorCode;
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
}
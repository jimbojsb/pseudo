<?php
namespace Pseudo;

class Result
{
    private $rows;
    private $errorCode;
    private $errorInfo;
    private $affectedRowCount = 0;
    private $insertId = 0;
    private $rowOffset = 0;

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

    public function nextRow()
    {
        $row = $this->rows[$this->rowOffset];
        if ($row) {
            $this->rowOffset++;
            return $row;
        } else {
            return false;
        }
    }

    public function setInsertId($insertId)
    {
        $this->insertId = $insertId;
    }

    public function getInsertId()
    {
        return $this->insertId;
    }

    /**
     * @param $errorCode
     * @throws Exception
     */
    public function setErrorCode($errorCode)
    {
        if (ctype_alnum($errorCode) && strlen($errorCode) == 5) {
            $this->errorCode = $errorCode;
        } else {
            throw new Exception("Error codes must be in ANSI SQL standard format");
        }
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param $errorInfo
     */
    public function setErrorInfo($errorInfo)
    {
        $this->errorInfo = $errorInfo;
    }

    /**
     * @return string
     */
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
<?php
namespace Pseudo;

class Result
{
    private $rows;
    private $errorCode;
    private $errorMessage;

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
}
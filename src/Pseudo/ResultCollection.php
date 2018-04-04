<?php
namespace Pseudo;

class ResultCollection implements \Countable
{
    private $queries = [];
    /**
     * Holds configuration settings for an object.
     * Defining the field options:
     * array['sqlDebug'] boolean Display the raw query alongside the un-mocked query exception (default `true`).
     * @var array (See above)
     */
    private $options = [
        'sqlDebug' => false
    ];
    
    /**
     * @param array $options Holds configuration, see the property description.
     */
    public function __construct(array $options = [])
    {
        $this->options = array_replace_recursive($this->options, $options);
    }

    public function count()
    {
        return count($this->queries);
    }

    public function addQuery($sql, $results)
    {
        $query = new ParsedQuery($sql);

        if (is_array($results)) {
            $storedResults = new Result($results);
        } else if ($results instanceof Result) {
            $storedResults = $results;
        } else {
            $storedResults = new Result;
        }

        $this->queries[$query->getHash()] = $storedResults;
    }

    public function exists($sql)
    {
        $query = new ParsedQuery($sql);
        return isset($this->queries[$query->getHash()]);
    }

    public function getResult($query)
    {
        if (!($query instanceof ParsedQuery)) {
            $query = new ParsedQuery($query);
        }
        $result = (isset($this->queries[$query->getHash()])) ? $this->queries[$query->getHash()] : null;
        if ($result instanceof Result) {
            return $result;
        } else {
            $message = "Attempting an operation on an un-mocked query is not allowed";
            if ($this->options['sqlDebug']) {
                $message .= ', the raw query: ' . $query->getRawQuery();
            }
            throw new Exception($message);
        }
    }
}
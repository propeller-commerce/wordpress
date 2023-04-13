<?php

namespace GraphQL\Exception;

use RuntimeException;
use stdClass;

/**
 * This exception is triggered when the GraphQL endpoint returns an error in the provided query
 *
 * Class QueryError
 *
 * @package GraphQl\Exception
 */
class QueryError extends RuntimeException
{
    /**
     * @var array
     */
    protected $errorDetails;
    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $errors;
    /**
     * QueryError constructor.
     *
     * @param object $errorDetails
     */
    public function __construct($errorDetails)
    {
        $this->errorDetails = (array) $errorDetails->errors[0];

        $this->data = new stdClass();
        if (isset($errorDetails->data) && !empty($errorDetails->data)) {
            $this->data = $errorDetails->data;
        }
        $this->errors = (array) $errorDetails->errors;

        parent::__construct($this->errorDetails['message']);
    }

    /**
     * @return array
     */
    public function getErrorDetails()
    {
        return $this->errorDetails;
    }

    /**
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
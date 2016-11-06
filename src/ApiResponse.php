<?php

namespace OpenSourceRefinery\HttpFoundation;

class ApiResponse
{
    const FORMAT_RAW = 'RAW';
    const FORMAT_JSON = 'JSON';
    const FORMAT_XML = 'XML';

    protected $redirectUrl = null;
    protected $created_timestamp;
    protected $status = 0;
    protected $code = 0;
    protected $message = null;
    protected $errors = [];
    protected $meta = [];
    protected $offset = 0;
    protected $limit = 0; // zero is unlimited
    protected $first = 1;
    protected $previous = null;
    protected $next = null;
    protected $last = null;
    protected $payload = null; //actual data that was requested

    public function __construct($data = null)
    {
        $this->created_timestamp = new \DateTime();
        $this->create($data);
    }

    public function get()
    {
        return $this;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload($payload)
    {

        $this->payload = $payload;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {

        //numerical representation of whats going on.
        // -1 : app error / failure
        // 0 : ok / success
        // 1 : logic error
        // 2 : authenication needed

        $this->status = intval($status);

        return $this;
    }


    public function getCode()
    {
        return $this->code;
    }

    // Application error level code
    // ie: 10000 - form failure
    // errors will have the individual errors
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }


    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }


    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }


    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getFirst()
    {
        return $this->first;
    }

    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }


    public function getPrevious()
    {
        return $this->previous;
    }

    public function setPrevious($previous)
    {
        $this->previous = $previous;

        return $this;
    }

    public function getNext()
    {
        return $this->next;
    }

    public function setNext($next)
    {
        $this->next = $next;

        return $this;
    }


    public function getLast()
    {
        return $this->last;
    }

    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    // Action specific errors
    // ie: on (code) form failure
    // - 1001 state required
    // - 1002 city required
    public function setError($code, $field, $message)
    {
        $this->createError($code, $field, $message);

        return $this;
    }

    public function unsetError($code)
    {
        unset($this->errors[$code]);
        return $this;
    }

    protected function createError($code, $field, $message)
    {

        $error = new \stdClass();
        $error->code = $code;
        $error->field = $field;
        $error->message = $message;

        $this->errors[$code] = $error;
    }

    public function setMeta($key, $value)
    {
        $this->createMeta($key, $value);

        return $this;
    }

    public function unsetMeta($key)
    {
        unset($this->meta[$key]);

        return $this;
    }

    protected function createMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }

    protected function create($data = [])
    {
        if(empty($data) || !is_array($data)){
            return;
        }
        if(array_key_exists('payload', $data)){
            $this->setPayload($data['paylaod']);
        }

        if(array_key_exists('status', $data)){
            $this->setStatus($data['status']);
        }

        if(array_key_exists('code', $data)){
            $this->setCode($data['code']);
        }

        if(array_key_exists('message', $data)){
            $this->setMessage($data['message']);
        }

        if(array_key_exists('offset', $data)){
            $this->setOffset($data['offset']);
        }

        if(array_key_exists('limit', $data)){
            $this->setLimit($data['limit']);
        }

        if(array_key_exists('first', $data)){
            $this->setFirst($data['first']);
        }

        if(array_key_exists('previous', $data)){
            $this->setPrevious($data['previous']);
        }

        if(array_key_exists('next', $data)){
            $this->setNext($data['next']);
        }

        if(array_key_exists('last', $data)){
            $this->setLast($data['last']);
        }

    }

    public static function getInstance()
    {
        return new ApiResponse();
    }


    public static function generate()
    {
        // return new ApiResponse()->get();
    }

    /**
    * this generates a generic output of the values set inside the class.
    * use this as the last step when sending the output through something like the symfony reponse class.
    */
    public function output($format = self::FORMAT_RAW)
    {
        $output = new \stdClass();

        $output->payload = $this->payload;
        $output->status = $this->status;
        $output->code = $this->code;
        $output->message = $this->message;
        $output->offset = $this->offset;
        $output->limit = $this->limit;
        $output->first = $this->first;
        $output->previous = $this->previous;
        $output->next = $this->next;
        $output->last = $this->last;
        $output->errors = $this->errors;

        switch($format){
            case self::FORMAT_JSON:
                $output = json_encode($output);
                break;
            case self::FORMAT_XML:
                throw new \LogicException('Format not assembled');
                break;
            case self::FORMAT_RAW:
            default:
                //DO NOTHING WE ARE ALREADY RAW
                break;
        }

        return $output;
    }



}

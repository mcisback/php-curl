<?php

namespace Mc\Curl;

// Custom Curl Exception
class CurlException extends \Exception {

    public function __construct( Error $error ) {
        $this->error = $error;

        parent::__construct(
        	"Curl\Error (" . $error->getErrCode() . "): " . $error->getErrMsg() 
        );
    }

    public function getError() : Error {
    	return $this->error;
    }

}

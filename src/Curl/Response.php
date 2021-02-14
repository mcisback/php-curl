<?php

namespace Mc\Curl;

class Response {

    use GetInfoTrait;
    use ErrorTrait;

    function __construct( $res, $ch, $respHeaders=[] ) {
        $this->response = $res;
        $this->ch = $ch;
        $this->respHeaders = $respHeaders;
    }

    public function raw() {
        return $this->response;
    }

    public function json() {
        return json_decode( $this->response );
    }

    public function httpCode() {
        return $this->getinfo( CURLINFO_HTTP_CODE );
    }

    public function headers() {
        return $this->respHeaders;
    }

}

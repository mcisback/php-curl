<?php

namespace Mc\Curl;

trait ErrorTrait {
	public $doThrow = false;
	public $error = false;

	public function checkError() {

        if( $this->doThrow ) {
            // Throw New Curl Error
            Error::throw( $this->ch, $this->response );
        } else {
            $this->error = Error::new( $this->ch, $this->response );
        }

        return $this;
    }

    public function error() : Error {
        return $this->error;
    }
}
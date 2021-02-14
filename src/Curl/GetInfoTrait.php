<?php

namespace Mc\Curl;

trait GetInfoTrait {
	public function getinfo( $opt ) {
	    return curl_getinfo( $this->ch, $opt );
	}
}

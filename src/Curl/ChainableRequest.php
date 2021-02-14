<?php

/**
 * ! Chainable Curl Class For Fun
 * ! Author: Marco Caggiano <mcisback@gmail.com>
 * ! Website: https://www.marcocaggiano.com
 */

namespace Mc\Curl;


class ChainableRequest {
    
    use GetInfoTrait;
    use ErrorTrait;

    function __construct( string $url = '' ) {

        $this->ch = curl_init();
        $this->_headers = [];
        $this->respHeaders = [];
        $this->queryParams = [];
        $this->url = '';
        // $this->doThrow = false;
        // $this->error = false;

        if( !empty( $url ) ) {
            return $this->url( $url );
        }
        
        return $this;

    }

    public function setopt( ...$opts ) {
        curl_setopt( $this->ch, ...$opts );

        return $this;
    }

    public function url( string $url ) {
        if( empty( $url ) ) {
            return $this;
        }

        $this->url = $url;

        return $this;
    }

    public function qsFromArray( array $params ) {
        $this->queryParams = $params;

        return $this;
    }

    public function qsParam( string $key, $value ) {
        $this->queryParams[ $key ] = $value;

        return $this;
    }

    public function headers( array $headersArray ) {
        return $this->_headers = $headersArray;
    }

    public function header( string $id, string $content ) {
        $this->_headers[ $id ] = $content;

        return $this;
    }

    public function contentType( string $content ) {
        return $this->header( 'Content-Type', $content );
    }

    public function contentJson() {
        return $this->contentType( 'application/json' );
    }

    public function contentHtml() {
        return $this->contentType( 'text/html' );
    }

    public function authBearer( string $token ) {
        return $this->header( 'Authorization', "Bearer $token" );
    }

    public function authBearer64( $token ) {
        return $this->authBearer( base64_encode( $token ) );
    }

    public function authBasic( string $user, string $pass ) {
        return $this->header('Authorization', 'Basic ' . \base64_encode("$user:$pass"));
    }

    public function method( string $reqType ) {
        return $this->setopt( CURLOPT_CUSTOMREQUEST, $reqType );
    }

    public function get( string $url = '' ) {
        return $this->method( 'GET' )->url( $url );
    }

    public function post( string $url = '') {
        return $this->method( 'POST' )->url( $url );
    }

    public function put( string $url = '') {
        return $this->method( 'PUT' )->url( $url );
    }

    public function head( string $url = '') {
        return $this->method( 'HEAD' )->url( $url );
    }

    public function option( string $url = '') {
        return $this->method( 'OPTION' )->url( $url );
    }

    public function patch( string $url = '') {
        return $this->method( 'PATCH' )->url( $url );
    }

    public function body( $postData ) {
        return $this->setopt( CURLOPT_POSTFIELDS, $postData );
    }

    public function bodyJson( $postData ) {
        return $this->body( json_encode( $postData ) );
    }

    public function rT( bool $value = true ) {
        return $this->setopt( CURLOPT_RETURNTRANSFER, $value );
    }

    public function follow( bool $value = true ) {
        return $this->setopt( CURLOPT_FOLLOWLOCATION, $value ? 1 : 0 );
    }

    public function verifySslPeer( bool $value = true ) {
        return $this->setopt( CURLOPT_SSL_VERIFYPEER, $value );
    }

    public function verifySslHost( bool $value = true ) {
        return $this->setopt( CURLOPT_SSL_VERIFYHOST, $value );
    }

    public function exec() {
        if( !empty( $this->_headers ) ) {
            $this->setopt( CURLOPT_HTTPHEADER, $this->_headers );
        }

        if( !empty( $this->queryParams ) ) {
            $queryString = http_build_query( $this->queryParams );

            $this->url( $this->url . '?' . $queryString );
        }

        $this->setopt( CURLOPT_URL, $this->url );

        $this->respHeaders = [];

        $this->setopt( CURLOPT_HEADERFUNCTION, array( $this, 'headerFunction' ) );

        $this->response = new Response( 
            curl_exec( $this->ch ), 
            $this->ch, 
            $this->respHeaders 
        );

        return $this->checkError();

    }

    protected function headerFunction( $ch, $header ) {
        $len = strlen( $header );
        $header = explode( ':', $header, 2 );

        if ( count( $header ) < 2 ) // ignore invalid headers
            return $len;

        $this->respHeaders[ strtolower( trim( $header[0] ) ) ] = trim( $header[1] );

        return $len;
    }

    public function throwOnError( $doThrow = true ) {
        $this->doThrow = $doThrow;

        return $this;
    }

    public function response() : Response {
        return $this->response->checkError();
    }

    public function close() {
        curl_close( $this->ch );
    }

    function __destruct() {
        $this->close();
    }

}

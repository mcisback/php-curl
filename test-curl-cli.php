<?php

require_once __DIR__ . '/vendor/autoload.php';

use Mc\Curl\ChainableRequest as Curl;
use Mc\Curl\CurlException;

$curl = new Curl();

$res = $curl
	->get('https://en0cw95kmcql88.x.pipedream.net/')
	->contentJson()
	->rT()
	->follow()
	->exec()
	->response()
;

echo 'Answer HEADERS: ';
echo print_r( $res->headers() );
echo "\n";

echo 'Answer: ';
echo print_r( $res->json() );
echo "\n";

$curl2 = new Curl();

$res2 = $curl2
	->post('https://en0cw95kmcql88.x.pipedream.net/')
	->contentJson()
	->rT()
	->follow()
	->bodyJson([
    	'order' => 1,
    	'product' => 'example',
    	'quantity' => 2,
    	'user' => 'MazingaZ'
	])
	->exec()
	->response()
;

echo 'Answer2 HEADERS: ';
echo print_r( $res2->headers() );
echo "\n";

echo 'Answer2: ';
echo print_r( $res2->json() );
echo "\n";

$res3 = $curl
	->get('https://dapodjapgibberish.gibberish/')
	->contentJson()
	->rT()
	->follow()
	->throwOnError( false )
	->exec()
	->response()
;

echo "Error From Response: ";
print_r( $res3->error() );

$res3 = $curl
	->get('https://dapodjapgibberish.gibberish/')
	->contentJson()
	->rT()
	->follow()
	->throwOnError( false )
	->exec()
;

echo "Error From Request: ";
print_r( $res3->error() );

echo "Throw Exception On Error: \n";

try {
	$res3 = $curl
	->get('https://dapodjapgibberish.gibberish/')
	->contentJson()
	->rT()
	->follow()
	->throwOnError( true )
	->exec()
;
} catch(Mc\Curl\CurlException $ex) {
	echo "Printing Error From CurlException: ";
	print_r( $ex->getError() );

	echo "\n";
}


echo "Test Query String 1: \n";

$curl = new Curl();

$res4 = $curl
	->get('https://localhost/woo1/index.php/wp-json/wc/v3/products')
	->verifySslPeer( false )
	->verifySslHost( false )
	->authBasic( 
		'ck_6ac8c948ae2357abe9c2412e87c1706973fe2b00',
		'cs_54eb318cb2fd7d31b48f8db4c90ce05e02e6457d'
	)
	->rt()
	->follow()
	->qsFromArray([
		'sku' => '883985597549'
	])
	->exec()
;

print_r( $res4 );

echo "Test Query String 2: \n";

$curl = new Curl();

$res5 = $curl
	->get('https://localhost/woo1/index.php/wp-json/wc/v3/products')
	->verifySslPeer( false )
	->verifySslHost( false )
	->authBasic( 
		'ck_6ac8c948ae2357abe9c2412e87c1706973fe2b00',
		'cs_54eb318cb2fd7d31b48f8db4c90ce05e02e6457d'
	)
	->rt()
	->follow()
	->qsParam( 'page', 2 )
	->qsParam( 'per_page', 5 )
	->exec()
;

print_r( $res5 );

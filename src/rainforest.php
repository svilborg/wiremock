<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

$httpClient = new Client();

try {

    $response = $httpClient->get('http://wiremock:8080/request', [
        'query' => [
            'type' => 'product',
            'api_key' => 'test',
            'url' => 'https://www.amazon.co.uk/Redshow-mmb-02-Operated-Spiegelkuglen-Built/dp/B07HM4KGPT',
        ]
    ]);

    $json = $response->getBody()->getContents();

    echo json_encode(json_decode($json), JSON_PRETTY_PRINT);

} catch (Exception|GuzzleException $e) {
    var_dump($e->getMessage());
}
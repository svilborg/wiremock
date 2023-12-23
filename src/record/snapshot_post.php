<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use WireMock\Client\WireMock;

$wireMock = WireMock::create('wiremock', 8080);

$wireMock->stubFor(WireMock::proxyAllTo('https://api.rainforestapi.com'));

$httpClient = new Client([
    RequestOptions::PROXY => [
        'http' => 'http://wiremock:8080',
        'https' => 'http://wiremock:8080',
    ],
    RequestOptions::VERIFY => false, # disable SSL certificate validation
    RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
]);

try {

    $response = $httpClient->get('http://api.rainforestapi.com/request', [
        'query' => [
            'type' => 'product',
            'api_key' => 'test',
            'url' => 'https://www.amazon.co.uk/Redshow-mmb-02-Operated-Spiegelkuglen-Built/dp/B07HM4KGPT',
        ]
    ]);

    $json = $response->getBody()->getContents();

} catch (Exception|GuzzleException $e) {
    var_dump($e->getMessage());
}

$result = $wireMock->snapshotRecord(Wiremock::recordSpec());

var_dump($result->getMappings());
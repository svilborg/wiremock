<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use WireMock\Client\WireMock;

$wireMock = WireMock::create('wiremock', 8080);

$wireMock->stubFor(WireMock::proxyAllTo('https://cat-fact.herokuapp.com'));

$http = new Client([
    RequestOptions::PROXY => [
        'http' => 'http://wiremock:8080',
        'https' => 'http://wiremock:8080',
    ],
    RequestOptions::VERIFY => false, # disable SSL certificate validation
    RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
]);

$response = $http->request('GET', 'http://cat-fact.herokuapp.com/facts/58e009390aac31001185ed10');

$result = $wireMock->snapshotRecord(Wiremock::recordSpec());


var_dump($result->getMappings());
<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

$httpClient = new Client();


// Exact Url matching

try {
    $response = $httpClient->request('GET', 'http://wiremock:8080/123');
    $json = $response->getBody()->getContents();

    output($json);

} catch (Exception $e) {
    var_dump($e->getMessage());
}

// Regexp Url Matching

try {
    $httpClient = new Client();
    $response = $httpClient->request('GET', 'http://wiremock:8080/sample/abc');
    $json = $response->getBody()->getContents();

    output($json);

} catch (Exception $e) {
    var_dump($e->getMessage());
}

function output(string $json): void
{
    echo "\n";
    echo json_encode(json_decode($json), JSON_PRETTY_PRINT);
    echo "\n";
    echo "\n";
}
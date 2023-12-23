<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use WireMock\Client\WireMock;

$wireMock = WireMock::create('wiremock', 8080);

$wireMock->startRecording('https://cat-fact.herokuapp.com');

$response = (new Client())->request('GET', 'http://wiremock:8080/facts/5887e1d85c873e0011036889');

$wireMock->stopRecording();

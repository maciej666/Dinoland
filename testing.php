<?php

require __DIR__.'/vendor/autoload.php';

//good documentation: http://docs.guzzlephp.org/en/latest/quickstart.html#making-a-request

$client = new \GuzzleHttp\Client([
    'base_url' => 'http://dino.dev/app_dev.php/',
//    opcje przekazywane domyślnie do każdego zapytania
    'defaults' => [
        'exceptions' => false, //zawsze zwraca odpowiedź
    ]
]);

$health = rand(1,1000);
$strength = rand(1,1000);
$speed = rand(1,1000);
$backup = rand(1,1000);
$data = array(
    'health' => $health,
    'backup' => $backup,
    'speed' => $speed,
    'strength' => $strength,
);

$dino = 'ApiMail@ty.pl';

//1) POST to create parameters
$response = $client->post('api/dino', [
    'body' => json_encode($data)
]);

//Location to jest header z info o przekierowaniu po
//zwróceniu response
$dinoUrl = $response->getHeader('Location');

//2) GET to fetch User
//$response = $client->get('api/dino/'.$dino);

////3) GET a collection of users
$response = $client->get('api/dino');
//


echo "\n\n";
echo $response->getStatusCode();
echo "\n\n";
echo $response->getBody();
echo "\n\n";
foreach ($response->getHeaders() as $name => $values) {
    echo $name . ': ' . implode(', ', $values) . "\r\n";
}
echo "\n\n";

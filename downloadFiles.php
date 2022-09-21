<?php

require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

use Google\Service\Drive;
use LucasWpl\GoogleTest\Controller\GoogleController;

$client = GoogleController::getClient();
$service = new Drive($client);

$fileId = '1yp4GJa9vSoCPdDspvwuwaGd8_HsXQL-J';
$response = $service->files->get($fileId, [
    'alt' => 'media'
]);
$content = $response->getBody()->getContents();

file_put_contents("./var/img/{$fileId}.jpeg", $content);

<?php

require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use LucasWpl\GoogleTest\Controller\GoogleController;

$client = GoogleController::getClient();
$service = new Drive($client);

$file = new DriveFile();
$file->setName(uniqid() . '.jpeg');
$file->setDescription('A test document');
$file->setMimeType('image/jpeg');

$data = file_get_contents('https://source.unsplash.com/random/200x200');

$createdFile = $service->files->create($file, array(
    'data' => $data,
    'mimeType' => 'image/jpeg',
    'uploadType' => 'multipart'
));

print_r($createdFile);

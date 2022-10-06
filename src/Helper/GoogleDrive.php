<?php

namespace LucasWpl\GoogleTest\Helper;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Psr\Http\Message\ResponseInterface;

class GoogleDrive
{
    private const CREDENTIALS_FILENAME = 'credentials.json';

    public function createFolder(string $name): string
    {
        $client = $this->getClient();
        $service = new Drive($client);

        $fileMetadata = new Drive\DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder'
        ]);

        $file = $service->files->create($fileMetadata, [
            'fields' => 'id'
        ]);

        return $file->id;
    }

    public function saveFile(
        string $filename,
        string $description,
        string $extension = 'jpeg',
        string $mimeType = 'image/jpeg'
    ): object {
        $client = $this->getClient();
        $service = new Drive($client);

        $file = new DriveFile();
        $file->setName(uniqid() . '.' . $extension);
        $file->setDescription($description);
        $file->setMimeType($mimeType);

        $data = file_get_contents($filename);

        return $service->files->create($file, [
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart'
        ]);
    }

    public function getFiles(int $pageSize = 10)
    {
        $client = $this->getClient();
        $service = new Drive($client);

        $optParams = [
            'pageSize' => $pageSize,
            'fields' => 'nextPageToken, files(id, name)'
        ];
        return $service->files->listFiles($optParams);
    }

    public function downloadFile(string $fileId, string $pathToSave): void
    {

        $client = $this->getClient();
        $service = new Drive($client);

        /** @var ResponseInterface */
        $response = $service->files->get($fileId, [
            'alt' => 'media'
        ]);
        file_put_contents($pathToSave, $response->getBody()->getContents());
    }

    private function getClient()
    {
        if (!file_exists(self::CREDENTIALS_FILENAME)) {
            throw new \Exception(
                'Crie o arquivo ' . self::CREDENTIALS_FILENAME . ' na raiz do projeto, tutorial disponível no README',
            );
        }

        $client = new Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setScopes(Drive::DRIVE);
        $client->setAuthConfig(self::CREDENTIALS_FILENAME);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        try {
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {
                    $authUrl = $client->createAuthUrl();
                    printf("Open the following link in your browser:\n%s\n", $authUrl);
                    print 'Enter verification code: ';
                    $authCode = trim(fgets(STDIN));

                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    if (array_key_exists('error', $accessToken)) {
                        throw new \Exception(join(', ', $accessToken));
                    }
                }
                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }
        } catch (\Exception $e) {
            // TODO handle error
        }
        return $client;
    }
}

<?php


namespace LucasWpl\GoogleTest\Test\Unit;

use LucasWpl\GoogleTest\Helper\GoogleDrive;
use LucasWpl\GoogleTest\Test\TestCase;

class NFeSignFTest extends TestCase
{
    public function testSaveFile()
    {
        $googleDrive = $this->getInstance();
        $fileSaved = $googleDrive->saveFile(
            'https://source.unsplash.com/random/200x200',
            'Test description'
        );

        $this->assertNotNull($fileSaved->name);
    }

    public function testGetFiles()
    {
        $googleDrive = $this->getInstance();
        $files = $googleDrive->getFiles();

        $this->assertIsArray($files->getFiles());
    }

    public function testSaveAndDownloadAFile()
    {
        $googleDrive = $this->getInstance();
        $fileSaved = $googleDrive->saveFile(
            'https://source.unsplash.com/random/200x200',
            'Test description'
        );
        $googleDrive->downloadFile($fileSaved->getId(), "var/img/{$fileSaved->getId()}.jpeg");

        $this->assertFileExists("var/img/{$fileSaved->getId()}.jpeg");
    }

    private function getInstance()
    {
        return new GoogleDrive;
    }
}

<?php

namespace Aradon\EpubParser;

use Aradon\EpubParser\exceptions\EpubException;
use ZipArchive;

class EpubFile
{
    private ZipArchive $file;

    public function __construct(string $filePath)
    {
        $this->file = new ZipArchive();

        $openResult = $this->file->open($filePath, ZipArchive::RDONLY);

        if ($openResult !== true) {
            throw match ($openResult) {
                ZipArchive::ER_INCONS => new EpubException('Zip archive inconsistent'),
                ZipArchive::ER_INVAL => new EpubException('Invalid argument'),
                ZipArchive::ER_MEMORY => new EpubException('Memory malloc failure'),
                ZipArchive::ER_NOENT => new EpubException('No such file'),
                ZipArchive::ER_NOZIP => new EpubException('Not a zip archive'),
                ZipArchive::ER_OPEN => new EpubException('Can\'t open file'),
                ZipArchive::ER_READ => new EpubException('Read error'),
                ZipArchive::ER_SEEK => new EpubException('Seek error'),
                default => new EpubException('Unknown error: ' . $openResult),
            };
        }

        // read container data
        $data = $this->file->getFromName('META-INF/container.xml');
        if ($data === false) {
            throw new EpubException('Failed to access epub container data');
        }
    }

    public function isValid(): bool
    {
        return true;
    }
}

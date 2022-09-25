<?php

namespace Tests;

use Aradon\EpubParser\EpubFile;
use Aradon\EpubParser\exceptions\EpubException;

class EpubParserTest extends EpubTestCase
{
    public function testConstructor(): void
    {
        new EpubFile($this->getEpubPath('0to1.epub'));

        $this->expectException(EpubException::class);
        $this->expectExceptionMessage('No such file');
        new EpubFile('invalid_file_path');
    }

    public function testGetMeta(): void
    {
        $epub = new EpubFile($this->getEpubPath('0to1.epub'));
        $this->assertIsObject($epub->getMeta());
    }
}

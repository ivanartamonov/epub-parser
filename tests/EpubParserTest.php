<?php

namespace Tests;

use Aradon\EpubParser\EpubFile;
use PHPUnit\Framework\TestCase;

class EpubParserTest extends TestCase
{
    public function testIsValid()
    {
        $file = new EpubFile();

        $this->assertTrue($file->isValid());
    }
}

<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class EpubTestCase extends TestCase
{
    public function getEpubExamplesPath(): string
    {
        return __DIR__ . '/_data/epub_examples/';
    }

    public function getEpubPath(string $name): string
    {
        return $this->getEpubExamplesPath() . $name;
    }
}

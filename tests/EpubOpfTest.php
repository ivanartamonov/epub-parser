<?php

namespace Tests;

use Aradon\EpubParser\EpubOPF;

class EpubOpfTest extends EpubTestCase
{
    public function testGetVersion(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><package version="2.0"></package>';
        $opf = new EpubOPF($xml);
        $this->assertEquals(2, $opf->getVersion());

        $xml = '<?xml version="1.0" encoding="UTF-8"?><package version="3.0"></package>';
        $opf = new EpubOPF($xml);
        $this->assertEquals(3, $opf->getVersion());
    }
}

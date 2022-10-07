<?php

namespace Aradon\EpubParser;

use DOMDocument;
use DOMXPath;

class EpubOPF
{
    private DOMXPath $xml;
    private ?EpubMeta $meta = null;

    public function __construct(string $xml)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $this->xml = new DOMXPath($doc);
    }

    public function getVersion(): int
    {
        return (int) $this->xml->query('//@version')->item(0)->nodeValue;
    }

    public function getMeta(): EpubMeta
    {
        if ($this->meta === null) {
            $this->meta = new EpubMeta($this->xml);
        }

        return $this->meta;
    }
}

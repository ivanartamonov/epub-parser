<?php

namespace Tests;

use Aradon\EpubParser\EpubMeta;

class EpubMetaTest extends EpubTestCase
{
    public function testSimpleDcElements(): void
    {
        $meta = new EpubMeta($this->getSampleXml());

        $this->assertEquals('Sample title', $meta->getTitle());
        $this->assertEquals('Sample description', $meta->getDescription());
        $this->assertEquals('ISBN:123456', $meta->getIdentifier());
        $this->assertEquals('en', $meta->getLang());
        $this->assertEquals('Publisher Name', $meta->getPublisher());
        $this->assertEquals('Contributor', $meta->getContributor());
        $this->assertEquals('Author Name 1', $meta->getAuthor());
        $this->assertEquals('2022', $meta->getDate());
        $this->assertEquals('Rights', $meta->getRights());
        $this->assertEquals('Source', $meta->getSource());
        $this->assertEquals('Subject', $meta->getSubject());
        $this->assertEquals('Type', $meta->getType());
    }

    private function getSampleXml(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<package 
    version="2.0"
    unique-identifier="bookid"
    xmlns="http://www.idpf.org/2007/opf"
    xmlns:opf="http://www.idpf.org/2007/opf"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
>
<metadata>
    <dc:title>Sample title</dc:title>
    <dc:description>Sample description</dc:description>
    <dc:date>2022</dc:date>
    <dc:creator>Author Name 1</dc:creator>
    <dc:contributor>Contributor</dc:contributor>
    <dc:language>en</dc:language>
    <dc:identifier>ISBN:123456</dc:identifier>
    <dc:publisher>Publisher Name</dc:publisher>
    <dc:rights>Rights</dc:rights>
    <dc:source>Source</dc:source>
    <dc:subject>Subject</dc:subject>
    <dc:type>Type</dc:type>
</metadata>

<guide>
    <reference href="images/cover.jpg" type="cover" title="cover"/>
</guide>
</package>
XML;
    }
}

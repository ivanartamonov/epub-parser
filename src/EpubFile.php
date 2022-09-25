<?php

namespace Aradon\EpubParser;

use Aradon\EpubParser\exceptions\EpubException;
use DOMDocument;
use DOMElement;
use DOMXPath;
use ZipArchive;

class EpubFile
{
    private ZipArchive $file;
    private EpubMeta $meta;

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

        $this->initMeta();
    }

    public function getMeta(): EpubMeta
    {
        return $this->meta;
    }

    private function initMeta(): void
    {
        $containerData = $this->file->getFromName('META-INF/container.xml');

        if ($containerData === false) {
            throw new EpubException('Failed to access epub container data');
        }

        $doc = new DOMDocument();
        $doc->loadXML($containerData);
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('n', 'urn:oasis:names:tc:opendocument:xmlns:container');
        $nodes = $xpath->query('//n:rootfiles/n:rootfile[@media-type="application/oebps-package+xml"]');

        /**
         * Although the EPUB Container provides the ability to include more than one rendition
         * of the content, Reading System support for multiple renditions remains largely unrealized,
         * outside specialized environments where the purpose and meaning of the renditions
         * is established by the involved parties.
         *
         * @link https://www.w3.org/publishing/epub3/epub-ocf.html#sec-container-metainf-container.xml
         */
        $rootFileNode = $nodes->item(0);

        if ($rootFileNode instanceof DOMElement) {
            $metaFilePath = $rootFileNode->getAttribute('full-path');
        } else {
            throw new EpubException('Can\'t find root file path');
        }

        $metaDataRawXml = $this->file->getFromName($metaFilePath);
        if (!$metaDataRawXml) {
            throw new EpubException('Failed to access epub metadata');
        }

        $this->meta = new EpubMeta($metaDataRawXml);
    }
}

<?php

namespace Aradon\EpubParser;

use DOMDocument;
use DOMXPath;

class EpubMeta
{
    private DOMXPath $xml;

    public function __construct(string $xml)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $this->xml = new DOMXPath($doc);
    }

    /**
     * The [DC11] title element represents an instance of a name given to the EPUB Publication.
     * The metadata section MUST include at least one title element containing the title for the EPUB Publication.
     *
     * @link https://www.w3.org/publishing/epub3/epub-packages.html#sec-opf-dctitle
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     *
     * Reading Systems MUST recognize the first title element in document order as the main title
     * of the EPUB Publication (i.e., the primary one to present to users).
     * This specification does not define how to process additional title elements.
     */
    public function getTitle(): string
    {
        return (string) $this->getValue('//opf:metadata/dc:title');
    }

    /**
     * The [DC11] identifier element contains an identifier associated with the given Rendition,
     * such as a UUID, DOI or ISBN.
     *
     * @link https://www.w3.org/publishing/epub3/epub-packages.html#sec-opf-dcidentifier
     *
     * The metadata section MUST include an identifier element that contains an unambiguous
     * identifier for the Rendition. This identifier MUST be marked as the Unique Identifier
     * via the package element unique-identifier attribute.
     */
    public function getIdentifier(): string
    {
        return (string) $this->getValue('//opf:metadata/dc:identifier');
    }

    /**
     * The [DC11] language element specifies the language of the content of the given Rendition.
     * This value is not inherited by the individual resources of the Rendition.
     *
     * https://www.w3.org/publishing/epub3/epub-packages.html#sec-opf-dclanguage
     *
     * The metadata section MUST include at least one language element with a value conforming to [BCP47].
     */
    public function getLang(): string
    {
        return (string) $this->getValue('//opf:metadata/dc:language');
    }

    /**
     * Description may include but is not limited to: an abstract, a table of contents,
     * a graphical representation, or a free-text account of the resource.
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getDescription(): ?string
    {
        return $this->getValue('//opf:metadata/dc:description');
    }

    /**
     * Examples of a Contributor include a person, an organization, or a service.
     * Typically, the name of a Contributor should be used to indicate the entity.
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getContributor(): ?string
    {
        return $this->getValue('//opf:metadata/dc:contributor');
    }

    /**
     * Examples of a Publisher include a person, an organization, or a service.
     * Typically, the name of a Publisher should be used to indicate the entity.
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getPublisher(): ?string
    {
        return $this->getValue('//opf:metadata/dc:publisher');
    }

    /**
     * A point or period of time associated with an event in the lifecycle of the resource.
     *
     * Date may be used to express temporal information at any level of granularity.
     * Recommended best practice is to use an encoding scheme, such as the W3CDTF profile of ISO 8601 [W3CDTF].
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getDate(): ?string
    {
        return $this->getValue('//opf:metadata/dc:date');
    }

    /**
     * Information about rights held in and over the resource.
     *
     * Typically, rights information includes a statement about various property rights
     * associated with the resource, including intellectual property rights.
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getRights(): ?string
    {
        return $this->getValue('//opf:metadata/dc:rights');
    }

    /**
     * A related resource from which the described resource is derived.
     *
     * The described resource may be derived from the related resource in whole or in part.
     * Recommended best practice is to identify the related resource by means of a string
     * conforming to a formal identification system.
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getSource(): ?string
    {
        return $this->getValue('//opf:metadata/dc:source');
    }

    /**
     * The topic of the resource.
     *
     * Typically, the subject will be represented using keywords, key phrases, or classification codes.
     * Recommended best practice is to use a controlled vocabulary.
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getSubject(): ?string
    {
        return $this->getValue('//opf:metadata/dc:subject');
    }

    /**
     * The nature or genre of the resource.
     *
     * Recommended best practice is to use a controlled vocabulary such as the DCMI Type Vocabulary [DCMITYPE].
     * To describe the file format, physical medium, or dimensions of the resource, use the Format element.
     *
     * @link https://www.dublincore.org/specifications/dublin-core/dces/
     */
    public function getType(): ?string
    {
        return $this->getValue('//opf:metadata/dc:type');
    }

    /**
     * The [DC11] creator element represents the name of a person, organization, etc. responsible
     * for the creation of the content of the Rendition.
     * The role property can be attached to the element to indicate the function
     * the creator played in the creation of the content.
     *
     * The creator element SHOULD contain the name of the creator as the Author intends it to be displayed to a user.
     *
     * When determining display priority, Reading Systems MUST use the document order of creator
     * elements in the metadata section, where the first creator element encountered is the primary creator.
     *
     * @link https://www.w3.org/publishing/epub3/epub-packages.html#sec-opf-dccreator
     */
    public function getAuthor(): string
    {
        return $this->getValue('//opf:metadata/dc:creator');
    }

    private function getValue(string $query): ?string
    {
        $nodes = $this->xml->query($query);

        if ($nodes->length) {
            return $nodes->item(0)->nodeValue;
        } else {
            return '';
        }
    }
}

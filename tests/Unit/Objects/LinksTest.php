<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Exception\LinksException;
use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\Links;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class LinksTest extends AbstractTestCase
{
    /** @var Links */
    private Links $links;

    public function setUp(): void
    {
        parent::setUp();

        $this->links = $this->generateEmptyLinks();
    }

    public function testLinksInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(ExportPreparationInterface::class, $this->links);
    }

    /**
     * @throws Exception
     */
    public function testAddingValidLink() : void
    {
        $this->links->add($this->generateLink());
        $this->assertEquals('https://self', $this->links->get('self')->href);
    }

    /**
     * @throws Exception
     */
    public function testAddingDuplicatedLink() : void {
        $this->expectExceptionCode(LinksException::DUPLICATED_LINK);

        $this->links->add($this->generateLink());
        $this->links->add($this->generateLink());
    }

    /**
     * @throws Exception
     */
    public function testGettingNonExistingLink() : void
    {
        $this->expectExceptionCode(LinksException::LINK_NOT_FOUND);
        $this->links->get('nonExistingLink');
    }

    /**
     * @throws Exception
     */
    public function testDataPreparation() : void {
        $this->links->add($this->generateLinkWithMeta());
        $this->links->add($this->generateRelatedLink());

        $this->assertEquals($this->arrayLinks, $this->links->prepare());
    }
}
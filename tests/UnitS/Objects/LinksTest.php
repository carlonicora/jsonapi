<?php
namespace CarloNicora\JsonApi\tests\UnitS\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\Links;
use CarloNicora\JsonApi\tests\UnitS\Abstracts\AbstractTestCase;
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
        $this->expectExceptionCode(1);

        $this->links->add($this->generateLink());
        $this->links->add($this->generateLink());
    }

    /**
     * @throws Exception
     */
    public function testGettingNonExistingLink() : void
    {
        $this->expectExceptionCode(2);
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
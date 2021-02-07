<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Exception\LinksException;
use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\Link;
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
        self::assertInstanceOf(ExportPreparationInterface::class, $this->links);
    }

    /**
     * @throws Exception
     */
    public function testAddingValidLink() : void
    {
        $this->links->add($this->generateLink());
        self::assertEquals('https://self', $this->links->get('self')->href);
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

        self::assertEquals($this->arrayLinks, $this->links->prepare());
    }

    /**
     * @throws Exception
     */
    public function testHas() : void {
        $this->links->add(
            new Link(
                'one',
                'one'
            )
        );
        self::assertTrue($this->links->has('one'));
    }

    /**
     * @throws Exception
     */
    public function testRemove() : void {
        $this->links->add(
            new Link(
                'one',
                'one'
            )
        );
        $this->links->remove('one');
        self::assertFalse($this->links->has('one'));
    }

    /**
     * @throws Exception
     */
    public function testUpdate() : void {
        $this->links->add(
            new Link(
                'one',
                'one'
            )
        );

        $this->links->update(
            new Link(
                'one',
                'two'
            )
        );
        self::assertEquals('two', $this->links->get('one')->href);
    }
}
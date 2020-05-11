<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\links;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class linksTest extends abstractTestCase
{
    /** @var links */
    private links $links;

    public function setUp(): void
    {
        parent::setUp();

        $this->links = $this->generateEmptyLinks();
    }

    public function testLinksInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(exportPreparationInterface::class, $this->links);
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
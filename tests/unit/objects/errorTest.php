<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\error;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class errorTest extends abstractTestCase
{
    /** @var error */
    private error $error;

    public function setUp(): void
    {
        parent::setUp();

        $this->error = $this->generateError();
    }

    public function testErrorInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(exportPreparationInterface::class, $this->error);
    }

    public function testErrorPreparation() : void
    {
        $this->assertEquals($this->arrayError, $this->error->prepare());
    }

    /**
     * @throws Exception
     */
    public function testErrorWithLinksPreparation() : void
    {
        $this->error->links->add($this->generateLink());
        $this->assertEquals($this->arrayErrorWithLink, $this->error->prepare());
    }

    /**
     * @throws Exception
     */
    public function testErrorWithMetaPreparation() : void
    {
        $this->error->meta->add('metaOne', 1);
        $this->error->meta->add('metaTwo', 2);
        $this->assertEquals($this->arrayErrorWithMeta, $this->error->prepare());
    }
}
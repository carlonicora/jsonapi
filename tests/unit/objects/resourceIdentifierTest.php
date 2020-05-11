<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\resourceIdentifier;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class resourceIdentifierTest extends abstractTestCase
{
    /** @var resourceIdentifier */
    private resourceIdentifier $resourceIdentifier;

    public function setUp(): void
    {
        parent::setUp();

        $this->resourceIdentifier = $this->generateResourceIdentifier();
    }

    public function testErrorInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(exportPreparationInterface::class, $this->resourceIdentifier);
    }

    public function testResourceIdentifierPreparation() : void
    {
        $this->assertEquals($this->arrayResourceIdentifier, $this->resourceIdentifier->prepare());
    }

    /**
     * @throws Exception
     */
    public function testResourceIdentifierWithMetaPreparation() : void
    {
        $this->resourceIdentifier->meta->add('metaOne', 1);
        $this->resourceIdentifier->meta->add('metaTwo', 2);
        $this->assertEquals($this->arrayResourceIdentifierWithMeta, $this->resourceIdentifier->prepare());
    }
}
<?php
namespace CarloNicora\JsonApi\tests\UnitS\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\ResourceIdentifier;
use CarloNicora\JsonApi\tests\UnitS\Abstracts\AbstractTestCase;
use Exception;

class ResourceIdentifierTest extends AbstractTestCase
{
    /** @var ResourceIdentifier */
    private ResourceIdentifier $resourceIdentifier;

    public function setUp(): void
    {
        parent::setUp();

        $this->resourceIdentifier = $this->generateResourceIdentifier();
    }

    public function testErrorInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(ExportPreparationInterface::class, $this->resourceIdentifier);
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
        $this->resourceIdentifier->resourceIdentifierMeta->add('metaOne', 1);
        $this->resourceIdentifier->resourceIdentifierMeta->add('metaTwo', 2);
        $this->assertEquals($this->arrayResourceIdentifierWithMeta, $this->resourceIdentifier->prepare());
    }
}
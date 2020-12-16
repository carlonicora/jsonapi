<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\ResourceIdentifier;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
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
        self::assertInstanceOf(ExportPreparationInterface::class, $this->resourceIdentifier);
    }

    public function testResourceIdentifierPreparation() : void
    {
        self::assertEquals($this->arrayResourceIdentifier, $this->resourceIdentifier->prepare());
    }

    /**
     * @throws Exception
     */
    public function testResourceIdentifierWithMetaPreparation() : void
    {
        $this->resourceIdentifier->resourceIdentifierMeta->add('metaOne', 1);
        $this->resourceIdentifier->resourceIdentifierMeta->add('metaTwo', 2);
        self::assertEquals($this->arrayResourceIdentifierWithMeta, $this->resourceIdentifier->prepare());
    }
}
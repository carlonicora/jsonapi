<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\ResourceLinkage;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class ResourceLinkageTest extends AbstractTestCase
{
    /** @var ResourceLinkage */
    private ResourceLinkage $resourceLinkage;

    public function setUp(): void
    {
        parent::setUp();

        $this->resourceLinkage = $this->generateResourceLinkage();
    }

    public function testResourceLinkageInstanceOfExportPreparationInterface() : void
    {
        self::assertInstanceOf(ExportPreparationInterface::class, $this->resourceLinkage);
    }

    public function testEmptyResourceLinkagePreparation() : void
    {
        self::assertEquals([], $this->resourceLinkage->prepare());
    }

    public function testAddResource() : void
    {
        $resource = $this->generateResourceObject();
        $this->resourceLinkage->add($resource);

        self::assertCount(1, $this->resourceLinkage->resources);
    }

    /**
     * @throws Exception
     */
    public function testSingleResourcePreparation() : void
    {
        $resource = $this->generateResourceObjectWithAttributes();
        $this->resourceLinkage->add($resource);

        self::assertEquals($this->arrayLinkageSingleResource, $this->resourceLinkage->prepare());
    }
}
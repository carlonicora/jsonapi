<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class ResourceObjectTest extends AbstractTestCase
{
    /** @var ResourceObject */
    private ResourceObject $resource;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->resource = $this->generateResourceObjectWithAttributes();
    }

    public function testErrorInstanceOfExportPreparationInterface() : void
    {
        self::assertInstanceOf(ExportPreparationInterface::class, $this->resource);
    }

    public function testResourcePreparation() : void
    {
        self::assertEquals($this->arrayResource, $this->resource->prepare());
    }

    /**
     * @throws Exception
     */
    public function testFullResourcePreparation() : void
    {
        $resource = $this->generateCompleteResourceObject();
        self::assertEquals($this->arrayResourceFull, $resource->prepare());
    }

    /**
     * @throws Exception
     */
    public function testAddingRelationship() : void
    {
        $resource = $this->generateCompleteResourceObject();
        $imageResource = $this->generateSecondaryResourceObject();

        $resource->relationship('avatar')->resourceLinkage->add($imageResource);

        self::assertArrayHasKey('avatar', $resource->relationships);
        self::assertEquals('10', $resource->relationship('avatar')->resourceLinkage->resources[0]->id);
    }

    /**
     * @throws Exception
     */
    public function testImportData() : void
    {
        $resource = new ResourceObject(null, null, $this->arrayResourceFullWithRelatedLink);

        self::assertEquals($this->arrayResourceFullWithRelatedLink, $resource->prepare());
    }

    /**
     * @throws Exception
     */
    public function testCreateEmptyResource() : void
    {
        $this->expectExceptionCode(1);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $resource = new ResourceObject();
    }

    /**
     * @throws Exception
     */
    public function testImportInvalidResource() : void
    {
        $this->expectExceptionCode(2);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $resource = new ResourceObject(null, null, ['id' => '1']);
    }
}
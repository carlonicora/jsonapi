<?php
namespace CarloNicora\JsonApi\tests\UnitS\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\JsonApi\tests\UnitS\Abstracts\AbstractTestCase;
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
        $this->assertInstanceOf(ExportPreparationInterface::class, $this->resource);
    }

    public function testResourcePreparation() : void
    {
        $this->assertEquals($this->arrayResource, $this->resource->prepare());
    }

    /**
     * @throws Exception
     */
    public function testFullResourcePreparation() : void
    {
        $resource = $this->generateCompleteResourceObject();
        $this->assertEquals($this->arrayResourceFull, $resource->prepare());
    }

    /**
     * @throws Exception
     */
    public function testAddingRelationship() : void
    {
        $resource = $this->generateCompleteResourceObject();
        $imageResource = $this->generateSecondaryResourceObject();

        $resource->relationship('avatar')->resourceLinkage->add($imageResource);

        $this->assertArrayHasKey('avatar', $resource->relationships);
        $this->assertEquals('10', $resource->relationship('avatar')->resourceLinkage->resources[0]->id);
    }

    /**
     * @throws Exception
     */
    public function testImportData() : void
    {
        $resource = new ResourceObject(null, null, $this->arrayResourceFullWithRelatedLink);

        $this->assertEquals($this->arrayResourceFullWithRelatedLink, $resource->prepare());
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
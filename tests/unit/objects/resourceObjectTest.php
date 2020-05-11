<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\resourceObject;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class resourceObjectTest extends abstractTestCase
{
    /** @var resourceObject */
    private resourceObject $resource;

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
        $this->assertInstanceOf(exportPreparationInterface::class, $this->resource);
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
        $resource = new resourceObject(null, null, $this->arrayResourceFullWithRelatedLink);

        $this->assertEquals($this->arrayResourceFullWithRelatedLink, $resource->prepare());
    }

    /**
     * @throws Exception
     */
    public function testCreateEmptyResource() : void
    {
        $this->expectExceptionCode(1);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $resource = new resourceObject();
    }

    /**
     * @throws Exception
     */
    public function testImportInvalidResource() : void
    {
        $this->expectExceptionCode(2);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $resource = new resourceObject(null, null, ['id' => '1']);
    }
}
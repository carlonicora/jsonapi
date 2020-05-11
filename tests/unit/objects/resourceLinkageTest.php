<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\resourceLinkage;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class resourceLinkageTest extends abstractTestCase
{
    /** @var resourceLinkage */
    private resourceLinkage $resourceLinkage;

    public function setUp(): void
    {
        parent::setUp();

        $this->resourceLinkage = $this->generateResourceLinkage();
    }

    public function testResourceLinkageInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(exportPreparationInterface::class, $this->resourceLinkage);
    }

    public function testEmptyResourceLinkagePreparation() : void
    {
        $this->assertEquals([], $this->resourceLinkage->prepare());
    }

    public function testAddResource() : void
    {
        $resource = $this->generateResourceObject();
        $this->resourceLinkage->add($resource);

        $this->assertCount(1, $this->resourceLinkage->resources);
    }

    /**
     * @throws Exception
     */
    public function testSingleResourcePreparation() : void
    {
        $resource = $this->generateResourceObjectWithAttributes();
        $this->resourceLinkage->add($resource);

        $this->assertEquals($this->arrayLinkageSingleResource, $this->resourceLinkage->prepare());
    }

    /**
     * @throws Exception
     */
    public function testMulitpleResourcePreparation() : void
    {
        $resource = $this->generateResourceObjectWithAttributes();
        $resource->meta->add('metaOne', 1);
        $resource->meta->add('metaTwo', 2);

        $this->resourceLinkage->add($resource);
        $this->resourceLinkage->add($resource);

        $this->assertEquals($this->arrayLinkageMultipleResource, $this->resourceLinkage->prepare());
    }
}
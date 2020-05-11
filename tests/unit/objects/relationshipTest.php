<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\relationship;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;

class relationshipTest extends abstractTestCase
{
    /** @var relationship */
    private relationship $relationship;

    public function setUp(): void
    {
        parent::setUp();

        $this->relationship = $this->generateRelationship();
    }

    public function testResourceLinkageInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(exportPreparationInterface::class, $this->relationship);
    }

    public function testEmptyResourceLinkagePreparation() : void
    {
        $this->assertEquals(['data' => []], $this->relationship->prepare());
    }
}
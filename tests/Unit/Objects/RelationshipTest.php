<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\Relationship;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;

class RelationshipTest extends AbstractTestCase
{
    /** @var Relationship */
    private Relationship $relationship;

    public function setUp(): void
    {
        parent::setUp();

        $this->relationship = $this->generateRelationship();
    }

    public function testResourceLinkageInstanceOfExportPreparationInterface() : void
    {
        self::assertInstanceOf(ExportPreparationInterface::class, $this->relationship);
    }

    public function testEmptyResourceLinkagePreparation() : void
    {
        self::assertEquals(['data' => []], $this->relationship->prepare());
    }
}
<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Exception\AttributeException;
use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\Attributes;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class AttributesTest extends AbstractTestCase
{
    /** @var Attributes */
    private Attributes $attributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->attributes = $this->generateEmptyAttributes();
    }

    public function testMetaInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(ExportPreparationInterface::class, $this->attributes);
    }

    public function testAddingValidAttribute() : void
    {
        try {
            $this->attributes->add('name', 'Carlo');
            $this->assertEquals('Carlo', $this->attributes->get('name'));
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @throws Exception
     */
    public function testAddingDuplicatedAttribute() : void {
        $this->expectExceptionCode(AttributeException::DUPLICATED_ATTRIBUTE);

        $this->attributes->add('name', 'Carlo');
        $this->attributes->add('name', 'Carlo');
    }

    /**
     * @throws Exception
     */
    public function testGettingNonExistingMeta() : void
    {
        $this->expectExceptionCode(AttributeException::ATTRIBUTE_NOT_FOUND);
        $this->attributes->get('nonExistingAttribute');
    }

    /**
     * @throws Exception
     */
    public function testAttributePrepare() : void
    {
        $this->attributes->add('name', 'Carlo');

        $this->assertEquals($this->arrayAttributesName, $this->attributes->prepare());
    }
}
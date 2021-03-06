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
        self::assertInstanceOf(ExportPreparationInterface::class, $this->attributes);
    }

    public function testAddingValidAttribute() : void
    {
        try {
            $this->attributes->add('name', 'Carlo');
            self::assertEquals('Carlo', $this->attributes->get('name'));
        } catch (Exception) {
            self::fail();
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
    public function testUpdatingAttribute() : void {
        $this->attributes->add('name', 'Carlos');
        $this->attributes->update('name', 'Carlo');

        self::assertEquals('Carlo', $this->attributes->get('name'));
    }

    public function testEmptyAttributeException() : void
    {
        $exception = new AttributeException();
        self::assertEquals('', $exception->getMessage());
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

        self::assertEquals($this->arrayAttributesName, $this->attributes->prepare());
    }

    /**
     * @throws Exception
     */
    public function testAttributeCount() : void
    {
        $this->attributes->add('name', 'Carlo');
        $this->attributes->add('anotherName', 'Carlo');

        self::assertEquals(2, $this->attributes->count());
    }

    /**
     * @throws Exception
     */
    public function testAttributeHas() : void
    {
        $this->attributes->add('name', 'Carlo');

        self::assertTrue($this->attributes->has('name'));
    }

    /**
     * @throws Exception
     */
    public function testAttributeRemove() : void
    {
        $this->attributes->add('name', 'Carlo');
        $this->attributes->remove('name');

        self::assertFalse($this->attributes->has('name'));
    }
}
<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\attributes;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class attributesTest extends abstractTestCase
{
    /** @var attributes */
    private attributes $attributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->attributes = $this->generateEmptyAttributes();
    }

    public function testMetaInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(exportPreparationInterface::class, $this->attributes);
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
        $this->expectExceptionCode(1);

        $this->attributes->add('name', 'Carlo');
        $this->attributes->add('name', 'Carlo');
    }

    /**
     * @throws Exception
     */
    public function testGettingNonExistingMeta() : void
    {
        $this->expectExceptionCode(2);
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
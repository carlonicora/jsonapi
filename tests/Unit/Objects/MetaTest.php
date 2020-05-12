<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\Meta;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class MetaTest extends AbstractTestCase
{
    /** @var Meta */
    private Meta $meta;

    public function setUp(): void
    {
        parent::setUp();

        $this->meta = $this->generateEmptyMeta();
    }

    public function testMetaInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(ExportPreparationInterface::class, $this->meta);
    }

    public function testAddingValidMeta() : void
    {
        try {
            $this->meta->add('metaOne', 1);
            $this->assertEquals(1, $this->meta->get('metaOne'));
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @throws Exception
     */
    public function testAddingDuplicatedMeta() : void {
        $this->expectExceptionCode(1);

        $this->meta->add('metaOne', 1);
        $this->meta->add('metaOne', 1);
    }

    /**
     * @throws Exception
     */
    public function testGettingNonExistingMeta() : void
    {
        $this->expectExceptionCode(2);
        $this->meta->get('nonExistingMeta');
    }
}
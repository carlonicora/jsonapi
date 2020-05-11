<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\meta;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class metaTest extends abstractTestCase
{
    /** @var meta */
    private meta $meta;

    public function setUp(): void
    {
        parent::setUp();

        $this->meta = $this->generateEmptyMeta();
    }

    public function testMetaInstanceOfExportPreparationInterface() : void
    {
        $this->assertInstanceOf(exportPreparationInterface::class, $this->meta);
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
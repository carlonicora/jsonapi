<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Objects\Error;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class ErrorTest extends AbstractTestCase
{
    /** @var Error */
    private Error $error;

    public function setUp(): void
    {
        parent::setUp();

        $this->error = $this->generateError();
    }

    public function testErrorInstanceOfExportPreparationInterface() : void
    {
        self::assertInstanceOf(ExportPreparationInterface::class, $this->error);
    }

    public function testErrorPreparation() : void
    {
        self::assertEquals($this->arrayError, $this->error->prepare());
    }

    /**
     * @throws Exception
     */
    public function testErrorWithLinksPreparation() : void
    {
        $this->error->links->add($this->generateLink());
        self::assertEquals($this->arrayErrorWithLink, $this->error->prepare());
    }

    /**
     * @throws Exception
     */
    public function testErrorWithMetaPreparation() : void
    {
        $this->error->meta->add('metaOne', 1);
        $this->error->meta->add('metaTwo', 2);
        self::assertEquals($this->arrayErrorWithMeta, $this->error->prepare());
    }
}
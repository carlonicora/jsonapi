<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\Exception\LinksException;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class LinkTest extends AbstractTestCase
{
    public function testCreateLink() : void {
        $link = $this->generateLink();

        self::assertEquals('https://self', $link->prepare()['self']);
    }

    /**
     * @throws Exception
     */
    public function testCreateLinkWithMeta() : void {
        $link = $this->generateLinkWithMeta();

        self::assertEquals('https://self', $link->prepare()['self']['href']);
        self::assertEquals(1, $link->prepare()['self']['meta']['metaOne']);
    }

    public function testEmptyLinksException() : void
    {
        $exception = new LinksException();
        self::assertEquals('', $exception->getMessage());
    }
}
<?php
namespace CarloNicora\JsonApi\tests\Unit\Objects;

use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;

class LinkTest extends AbstractTestCase
{
    public function testCreateLink() : void {
        $link = $this->generateLink();

        $this->assertEquals('https://self', $link->prepare()['self']);
    }

    /**
     * @throws Exception
     */
    public function testCreateLinkWithMeta() : void {
        $link = $this->generateLinkWithMeta();

        $this->assertEquals('https://self', $link->prepare()['self']['href']);
        $this->assertEquals(1, $link->prepare()['self']['Meta']['metaOne']);
    }
}
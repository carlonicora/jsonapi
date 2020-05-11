<?php
namespace carlonicora\jsonapi\tests\unit\objects;

use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;

class linkTest extends abstractTestCase
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
        $this->assertEquals(1, $link->prepare()['self']['meta']['metaOne']);
    }
}
<?php
namespace carlonicora\jsonapi\tests\unit;

use carlonicora\jsonapi\document;
use carlonicora\jsonapi\interfaces\exportInterface;
use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\objects\link;
use carlonicora\jsonapi\objects\resourceObject;
use carlonicora\jsonapi\tests\unit\abstracts\abstractTestCase;
use Exception;
use JsonException;

class documentTest extends abstractTestCase
{
    public function testDocumentCreation() : void
    {
        $document = $this->generateDocumentEmpty();

        $this->assertInstanceOf(exportInterface::class, $document);
        $this->assertInstanceOf(exportPreparationInterface::class, $document);
    }

    public function testCheckDocumentMinimalRequirementsMeta() : void
    {
        $document = $this->generateDocumentEmpty();

        $this->assertEquals($this->arrayMetaOnly, $document->prepare());
    }

    /**
     * @throws JsonException
     */
    public function testCheckDocumentMinimalExport() : void
    {
        $document = $this->generateDocumentEmpty();
        $this->assertEquals($this->jsonDocumentMinimal, $document->export());
    }

    /**
     * @throws Exception
     */
    public function testSuperDuperDocumentPreparation() : void
    {
        $document = new document();

        $article = new resourceObject('article', '1');
        $article->attributes->add('title', 'title');
        $article->links->add(new link('self', 'https://article/1'));

        $author = new resourceObject('user', '10');
        $author->attributes->add('name', 'Carlo');
        $author->links->add(new link('self', 'https://user/10'));

        $image101 = new resourceObject('image', '101');
        $image101->attributes->add('url', 'https://image/101.jpg');
        $image101->links->add(new link('self', 'https://image/101'));

        $image102 = new resourceObject('image', '102');
        $image102->attributes->add('url', 'https://image/102.jpg');
        $image102->links->add(new link('self', 'https://image/102'));

        $image103 = new resourceObject('image', '103');
        $image103->attributes->add('url', 'https://image/103.jpg');
        $image103->links->add(new link('self', 'https://image/103'));

        $author->relationship('images')->resourceLinkage->add($image103);
        $article->relationship('author')->resourceLinkage->add($author);
        $article->relationship('images')->resourceLinkage->add($image101);
        $article->relationship('images')->resourceLinkage->add($image102);

        $document->addResource($article);

        $this->assertEquals($this->arrayDocumentSuperDuper, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testSuperDuperDocumentPreparationWithForcedList() : void
    {
        $document = new document();
        $document->forceResourceList();

        $article = new resourceObject('article', '1');
        $article->attributes->add('title', 'title');
        $article->links->add(new link('self', 'https://article/1'));

        $author = new resourceObject('user', '10');
        $author->attributes->add('name', 'Carlo');
        $author->links->add(new link('self', 'https://user/10'));

        $image101 = new resourceObject('image', '101');
        $image101->attributes->add('url', 'https://image/101.jpg');
        $image101->links->add(new link('self', 'https://image/101'));

        $image102 = new resourceObject('image', '102');
        $image102->attributes->add('url', 'https://image/102.jpg');
        $image102->links->add(new link('self', 'https://image/102'));

        $image103 = new resourceObject('image', '103');
        $image103->attributes->add('url', 'https://image/103.jpg');
        $image103->links->add(new link('self', 'https://image/103'));

        $image101->relationship('images')->resourceLinkage->add($image103);
        $author->relationship('images')->resourceLinkage->add($image103);
        $article->relationship('author')->resourceLinkage->add($author);
        $article->relationship('images')->resourceLinkage->add($image101);
        $article->relationship('images')->resourceLinkage->add($image102);

        $document->addResource($article);

        $this->assertEquals($this->arrayDocumentSuperDuperWithForcedList, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testImportingData() : void
    {
        $document = new document($this->arrayDocumentSuperDuperWithForcedList);

        $document->forceResourceList();

        $this->assertEquals($this->arrayDocumentSuperDuperWithForcedList, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testImportingDataSingleEntity() : void
    {
        $document = new document($this->arrayDocumentSuperDuperWithForcedListSingleEntity);

        $this->assertEquals($this->arrayDocumentSuperDuperWithForcedListSingleEntity, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testImportingDataWithMeta() : void
    {
        $array = $this->arrayDocumentSuperDuperWithForcedList;
        $array['meta'] = [
           'metaOne' => 1,
           'metaTwo' => 2
        ];

        $document = new document($array);

        $document->forceResourceList();

        $this->assertEquals($array, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testMissingIncludedWhileImportingData() : void
    {
        $this->expectExceptionCode(1);
        $array = $this->arrayDocumentSuperDuperWithForcedList;
        $array['included'] = [];
        $document = new document($array);
        $document->forceResourceList();
    }
}
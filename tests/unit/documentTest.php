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
    public function testCheckDocumentMinimalWithLinksExport() : void
    {
        $document = $this->generateDocumentEmpty();
        $document->links->add(new link('self', 'https://self'));
        $this->assertEquals('https://self', $document->prepare()['links']['self']);
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

    /**
     * @throws Exception
     */
    public function testArrayImport() : void
    {
        $array = [
            'data' => [
                'type' => 'journal',
                'id' => 'andsjad897asd',
                'attributes' => [
                    'title' => 'About phlow - the community media movement'
                ],
                'links' => [
                    'self' => 'https://app.phlow.com/@carlo/journals/about-phlow-the-community-media-movement'
                ],
                'relationships' => [
                    'author' => [
                        'links' => [
                            'related' => 'https://app.phlow.com/@carlo'
                        ],
                        'data' => [
                            'type' => 'user',
                            'id' => 'adslau79ulaksdu',
                            'meta' => [
                                'isPrimaryAuthor' => true
                            ]
                        ]
                    ],
                    'images' => [
                        'data' => [
                            [
                                'type' => 'image',
                                'id' => '26037dd7-481b-4110-97f3-a879a08d1e20',
                                'meta' => [
                                    'isCover' => true
                                ]
                            ],
                            [
                                'type' => 'image',
                                'id' => '2563cc0c-3202-4554-be70-3c9850d5369e',
                                'meta' => [
                                    'isCover' => false
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'links' => [
                'self' => 'https://self'
            ],
            'included' => [
                [
                    'type' => 'user',
                    'id' => 'adslau79ulaksdu',
                    'attributes' => [
                        'name' => 'Carlo Nicora',
                        'username' => 'carlo',
                        'url' => 'https://carlonicora.com'
                    ],
                    'meta' => [
                        'hasJournals' => true,
                        'hasPhotos' => true
                    ],
                    'links' => [
                        'self' => 'https://app.phlow.com/@carlo'
                    ]
                ],
                [
                    'type' => 'image',
                    'id' => '26037dd7-481b-4110-97f3-a879a08d1e20',
                    'attributes' => [
                        'url' => 'https://acc-phlow.imgix.net/wZaN92gl7WlRmDWrKp/26037dd7-481b-4110-97f3-a879a08d1e20.jpg?w=750&ixlib=js-1.1.0&s=28c961bf9a05855320fe853155b1cd7f'
                    ],
                    'links' => [
                        'self' => 'https://acc-phlow.imgix.net/wZaN92gl7WlRmDWrKp/26037dd7-481b-4110-97f3-a879a08d1e20.jpg?w=750&ixlib=js-1.1.0&s=28c961bf9a05855320fe853155b1cd7f'
                    ]
                ],
                [
                    'type' => 'image',
                    'id' => '2563cc0c-3202-4554-be70-3c9850d5369e',
                    'attributes' => [
                        'url' => 'https://acc-phlow.imgix.net/wZaN92gl7WlRmDWrKp/2563cc0c-3202-4554-be70-3c9850d5369e.jpg?w=750&ixlib=js-1.1.0&s=da188c73f2b571d1afd9b1625f482e05'
                    ],
                    'links' => [
                        'self' => 'https://acc-phlow.imgix.net/wZaN92gl7WlRmDWrKp/2563cc0c-3202-4554-be70-3c9850d5369e.jpg?w=750&ixlib=js-1.1.0&s=da188c73f2b571d1afd9b1625f482e05'
                    ]
                ]
            ]
        ];

        $document = new document($array);
        $this->assertEquals($array, $document->prepare());
    }
}
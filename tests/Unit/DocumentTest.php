<?php
namespace CarloNicora\JsonApi\tests\Unit;

use CarloNicora\JsonApi\Document;
use CarloNicora\JsonApi\Objects\Error;
use CarloNicora\JsonApi\Objects\Link;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\JsonApi\tests\Unit\Abstracts\AbstractTestCase;
use Exception;
use JsonException;

class DocumentTest extends AbstractTestCase
{
    /**
     * @throws Exception
     */
    public function testCheckDocumentMinimalRequirementsMeta() : void
    {
        $document = $this->generateDocumentEmpty();

        self::assertEquals($this->arrayMetaOnly, $document->prepare());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testCheckDocumentMinimalExport() : void
    {
        $document = $this->generateDocumentEmpty();
        self::assertEquals($this->jsonDocumentMinimal, $document->export());
    }

    /**
     * @throws Exception
     */
    public function testDocumentErrorExport() : void
    {
        $document = $this->generateDocumentEmpty();
        $exception = new Exception('Generic Error', 500);
        $document->addError( new Error($exception));

        self::assertEquals(['errors' => [['status' => '500', 'detail' => 'Generic Error']]], $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testCheckDocumentMinimalWithLinksExport() : void
    {
        $document = $this->generateDocumentEmpty();
        $document->links->add(new Link('self', 'https://self'));
        self::assertEquals('https://self', $document->prepare()['links']['self']);
    }

    /**
     * @throws Exception
     */
    public function testSuperDuperDocumentPreparation() : void
    {
        $document = new Document();

        $article = new ResourceObject('article', '1');
        $article->attributes->add('title', 'title');
        $article->attributes->add('additionalAttribute1', 'addAttr1');
        $article->attributes->add('additionalAttribute2', 'addAttr2');
        $article->links->add(new Link('self', 'https://article/1'));

        $author = new ResourceObject('user', '10');
        $author->attributes->add('name', 'Carlo');
        $author->attributes->add('additionalAttribute1', 'addAttr1');
        $author->attributes->add('additionalAttribute2', 'addAttr2');
        $author->links->add(new Link('self', 'https://user/10'));

        $image101 = new ResourceObject('image', '101');
        $image101->attributes->add('url', 'https://image/101.jpg');
        $image101->attributes->add('additionalAttribute1', 'addAttr1');
        $image101->attributes->add('additionalAttribute2', 'addAttr2');
        $image101->links->add(new Link('self', 'https://image/101'));

        $image102 = new ResourceObject('image', '102');
        $image102->attributes->add('url', 'https://image/102.jpg');
        $image102->attributes->add('additionalAttribute1', 'addAttr1');
        $image102->attributes->add('additionalAttribute2', 'addAttr2');
        $image102->links->add(new Link('self', 'https://image/102'));

        $image103 = new ResourceObject('image', '103');
        $image103->attributes->add('url', 'https://image/103.jpg');
        $image103->attributes->add('additionalAttribute1', 'addAttr1');
        $image103->attributes->add('additionalAttribute2', 'addAttr2');
        $image103->links->add(new Link('self', 'https://image/103'));

        $author->relationship('images')->resourceLinkage->add($image103);
        $article->relationship('author')->resourceLinkage->add($author);
        $article->relationship('images')->resourceLinkage->add($image101);
        $article->relationship('images')->resourceLinkage->add($image102);

        $document->addResource($article);

        self::assertEquals($this->arrayDocumentSuperDuper, $document->prepare());
    }

    public function testAddResourceList() : void
    {
        $document = new Document();

        $resourceList = [
            new ResourceObject('type', '1'),
            new ResourceObject('type', '2')
        ];
        $document->addResourceList($resourceList);

        $expectedResult = [
            'data' => [
                ['type' => 'type', 'id' => '1'],
                ['type' => 'type', 'id' => '2']
            ]
        ];

        self::assertEquals($expectedResult, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testSuperDuperDocumentPreparationWithForcedList() : void
    {
        $document = new Document();
        $document->forceResourceList();

        $article = new ResourceObject('article', '1');
        $article->attributes->add('title', 'title');
        $article->links->add(new Link('self', 'https://article/1'));

        $author = new ResourceObject('user', '10');
        $author->attributes->add('name', 'Carlo');
        $author->links->add(new Link('self', 'https://user/10'));

        $image101 = new ResourceObject('image', '101');
        $image101->attributes->add('url', 'https://image/101.jpg');
        $image101->links->add(new Link('self', 'https://image/101'));

        $image102 = new ResourceObject('image', '102');
        $image102->attributes->add('url', 'https://image/102.jpg');
        $image102->links->add(new Link('self', 'https://image/102'));

        $image103 = new ResourceObject('image', '103');
        $image103->attributes->add('url', 'https://image/103.jpg');
        $image103->links->add(new Link('self', 'https://image/103'));

        $image101->relationship('images')->resourceLinkage->add($image103);
        $author->relationship('images')->resourceLinkage->add($image103);
        $article->relationship('author')->resourceLinkage->add($author);
        $article->relationship('images')->resourceLinkage->add($image101);
        $article->relationship('images')->resourceLinkage->add($image102);

        $document->addResource($article);

        self::assertEquals($this->arrayDocumentSuperDuperWithForcedList, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testImportingData() : void
    {
        $document = new Document($this->arrayDocumentSuperDuperWithForcedList);

        $document->forceResourceList();

        self::assertEquals($this->arrayDocumentSuperDuperWithForcedList, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testImportingDataSingleEntity() : void
    {
        $document = new Document($this->arrayDocumentSuperDuperWithForcedListSingleEntity);

        self::assertEquals($this->arrayDocumentSuperDuperWithForcedListSingleEntity, $document->prepare());
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

        $document = new Document($array);

        $document->forceResourceList();

        self::assertEquals($array, $document->prepare());
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
                        'url' => 'https://CarloNicora.com'
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

        $document = new Document($array);
        self::assertEquals($array, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testPartialArrayImport() : void
    {
        $document = new Document($this->arrayDocumentSuperDuper);

        $include = ['author', 'author.images'];

        $document->setIncludedResourceTypes($include);

        self::assertEquals($this->arrayDocumentSuperDuperPartial, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testPartialLimitedArrayImport() : void
    {
        $document = new Document($this->arrayDocumentSuperDuper);

        $include = ['author', 'author.images'];
        $fields = [
            'article' => ['title'],
            'user' => ['name'],
            'image' => ['url']
        ];

        $document->setIncludedResourceTypes($include);
        $document->setRequiredFields($fields);

        self::assertEquals($this->arrayDocumentSuperDuperPartialLimited, $document->prepare());
    }

    /**
     *
     */
    public function testGetContentType(): void
    {
        $document = new Document();
        self::assertEquals('application/vnd.api+json', $document->getContentType());
    }

    /**
     * @throws Exception
     */
    public function testImportingErrorData() : void
    {
        $document = new Document();
        $document->importArray($this->errors);

        self::assertEquals($this->errors, $document->prepare());
    }

    /**
     * @throws Exception
     */
    public function testInvalidData(): void
    {
        $document = new Document();

        $this->expectExceptionMessage('Invalid jsonapi document format');
        $document->importArray([
            'data' => [
                1
            ]
        ]);
    }
}
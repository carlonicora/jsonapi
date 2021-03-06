<?php
namespace CarloNicora\JsonApi\tests\Unit\Traits;

trait ArrayDeclarationTrait
{
    /** @var array|string[]  */
    protected array $arrayMetaOnly = ['meta' => []];

    /** @var array|string[]  */
    protected array $arrayResourceIdentifier = [
        'type' => 'user',
        'id' => '1'
    ];

    /** @var array|string[]  */
    protected array $arrayResource = [
        'type' => 'user',
        'id' => '1',
        'attributes' => [
            'name' => 'Carlo'
        ]
    ];

    protected array $arrayResourceIdentifierWithMeta = [
        'type' => 'user',
        'id' => '1',
        'meta' => [
            'metaOne' => 1,
            'metaTwo' => 2
        ]
    ];

    /** @var array  */
    protected array $arrayLinks = [
        'self' => [
            'href' => 'https://self',
            'meta' => [
                'metaOne' => 1,
                'metaTwo' => 2
            ]
        ],
        'related' => 'https://related'
    ];

    /** @var array  */
    protected array $arrayError = [
        'status' => 'status',
        'id' => 'id',
        'code' => 'code',
        'title' => 'title',
        'detail' => 'detail'
    ];

    /** @var array  */
    protected array $arrayErrorWithLink = [
        'status' => 'status',
        'id' => 'id',
        'code' => 'code',
        'title' => 'title',
        'detail' => 'detail',
        'links' => [
            'self' => 'https://self'
        ]
    ];

    /** @var array  */
    protected array $arrayErrorWithMeta = [
        'status' => 'status',
        'id' => 'id',
        'code' => 'code',
        'title' => 'title',
        'detail' => 'detail',
        'meta' => [
            'metaOne' => 1,
            'metaTwo' => 2
        ]
    ];

    /** @var array|string[]  */
    protected array $arrayAttributesName = [
        'name' => 'Carlo'
    ];

    /** @var array  */
    protected array $arrayResourceFull = [
        'type' => 'user',
        'id' => '1',
        'meta' => [
            'metaOne' => 1,
            'metaTwo' => 2
        ],
        'attributes' => [
            'name' => 'Carlo'
        ],
        'links' => [
            'self' => 'https://self'
        ]
    ];

    /** @var array  */
    protected array $arrayResourceFullWithRelatedLink = [
        'type' => 'user',
        'id' => '1',
        'meta' => [
            'metaOne' => 1,
            'metaTwo' => 2
        ],
        'attributes' => [
            'name' => 'Carlo'
        ],
        'links' => [
            'self' => 'https://self',
            'related' => [
                'href' => 'https://related',
                'meta' => [
                    'metaThree' => 3
                ]
            ]
        ]
    ];

    /** @var array|string[]  */
    protected array $arrayLinkageSingleResource = [
        'type' => 'user',
        'id' => '1'
    ];

    /** @var array|string[]  */
    protected array $arrayLinkageMultipleResource = [
        [
            'type' => 'user',
            'id' => '1',
            'meta' => [
                'metaOne' => 1,
                'metaTwo' => 2
            ]
        ],
        [
            'type' => 'user',
            'id' => '1',
            'meta' => [
                'metaOne' => 1,
                'metaTwo' => 2
            ]
        ]
    ];

    /** @var array  */
    protected array $arrayDocumentSuperDuper = [
        'data' => [
            'type' => 'article',
            'id' => '1',
            'attributes' => [
                'title' => 'title',
                'additionalAttribute1' => 'addAttr1',
                'additionalAttribute2' => 'addAttr2'
            ],
            'links' => [
                'self' => 'https://article/1'
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => '10'
                    ]
                ],
                'images' => [
                    'data' => [
                        [
                            'type' => 'image',
                            'id' => '101'
                        ],
                        [
                            'type' => 'image',
                            'id' => '102'
                        ]
                    ]
                ]
            ]
        ],
        'included' => [
            [
                'type' => 'user',
                'id' => '10',
                'attributes' => [
                    'name' => 'Carlo',
                    'additionalAttribute1' => 'addAttr1',
                    'additionalAttribute2' => 'addAttr2'
                ],
                'links' => [
                    'self' => 'https://user/10'
                ],
                'relationships' => [
                    'images' => [
                        'data' => [
                            'type' => 'image',
                            'id' => '103'
                        ]
                    ]
                ]
            ],
            [
                'type' => 'image',
                'id' => '103',
                'attributes' => [
                    'url' => 'https://image/103.jpg',
                    'additionalAttribute1' => 'addAttr1',
                    'additionalAttribute2' => 'addAttr2'
                ],
                'links' => [
                    'self' => 'https://image/103'
                ]
            ],
            [
                'type' => 'image',
                'id' => '101',
                'attributes' => [
                    'url' => 'https://image/101.jpg',
                    'additionalAttribute1' => 'addAttr1',
                    'additionalAttribute2' => 'addAttr2'
                ],
                'links' => [
                    'self' => 'https://image/101'
                ]
            ],
            [
                'type' => 'image',
                'id' => '102',
                'attributes' => [
                    'url' => 'https://image/102.jpg',
                    'additionalAttribute1' => 'addAttr1',
                    'additionalAttribute2' => 'addAttr2'
                ],
                'links' => [
                    'self' => 'https://image/102'
                ]
            ],
        ]
    ];

    protected array $arrayDocumentSuperDuperPartial = [
        'data' => [
            'type' => 'article',
            'id' => '1',
            'attributes' => [
                'title' => 'title',
                'additionalAttribute1' => 'addAttr1',
                'additionalAttribute2' => 'addAttr2'
            ],
            'links' => [
                'self' => 'https://article/1'
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => '10'
                    ]
                ],
                'images' => [
                    'data' => [
                        [
                            'type' => 'image',
                            'id' => '101'
                        ],
                        [
                            'type' => 'image',
                            'id' => '102'
                        ]
                    ]
                ]
            ]
        ],
        'included' => [
            [
                'type' => 'user',
                'id' => '10',
                'attributes' => [
                    'name' => 'Carlo',
                    'additionalAttribute1' => 'addAttr1',
                    'additionalAttribute2' => 'addAttr2'
                ],
                'links' => [
                    'self' => 'https://user/10'
                ],
                'relationships' => [
                    'images' => [
                        'data' => [
                            'type' => 'image',
                            'id' => '103'
                        ]
                    ]
                ]
            ],
            [
                'type' => 'image',
                'id' => '103',
                'attributes' => [
                    'url' => 'https://image/103.jpg',
                    'additionalAttribute1' => 'addAttr1',
                    'additionalAttribute2' => 'addAttr2'
                ],
                'links' => [
                    'self' => 'https://image/103'
                ]
            ]
        ]
    ];

    protected array $arrayDocumentSuperDuperPartialLimited = [
        'data' => [
            'type' => 'article',
            'id' => '1',
            'attributes' => [
                'title' => 'title'
            ],
            'links' => [
                'self' => 'https://article/1'
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => '10'
                    ]
                ],
                'images' => [
                    'data' => [
                        [
                            'type' => 'image',
                            'id' => '101'
                        ],
                        [
                            'type' => 'image',
                            'id' => '102'
                        ]
                    ]
                ]
            ]
        ],
        'included' => [
            [
                'type' => 'user',
                'id' => '10',
                'attributes' => [
                    'name' => 'Carlo'
                ],
                'links' => [
                    'self' => 'https://user/10'
                ],
                'relationships' => [
                    'images' => [
                        'data' => [
                            'type' => 'image',
                            'id' => '103'
                        ]
                    ]
                ]
            ],
            [
                'type' => 'image',
                'id' => '103',
                'attributes' => [
                    'url' => 'https://image/103.jpg'
                ],
                'links' => [
                    'self' => 'https://image/103'
                ]
            ]
        ]
    ];

    /** @var array  */
    protected array $arrayDocumentSuperDuperWithForcedList = [
        'data' => [
            [
                'type' => 'article',
                'id' => '1',
                'attributes' => [
                    'title' => 'title'
                ],
                'links' => [
                    'self' => 'https://article/1'
                ],
                'relationships' => [
                    'author' => [
                        'data' => [
                            'type' => 'user',
                            'id' => '10'
                        ]
                    ],
                    'images' => [
                        'data' => [
                            [
                                'type' => 'image',
                                'id' => '101'
                            ],
                            [
                                'type' => 'image',
                                'id' => '102'
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'included' => [
            [
                'type' => 'user',
                'id' => '10',
                'attributes' => [
                    'name' => 'Carlo'
                ],
                'links' => [
                    'self' => 'https://user/10'
                ],
                'relationships' => [
                    'images' => [
                        'data' => [
                            'type' => 'image',
                            'id' => '103'
                        ]
                    ]
                ]
            ],
            [
                'type' => 'image',
                'id' => '103',
                'attributes' => [
                    'url' => 'https://image/103.jpg'
                ],
                'links' => [
                    'self' => 'https://image/103'
                ]
            ],
            [
                'type' => 'image',
                'id' => '101',
                'attributes' => [
                    'url' => 'https://image/101.jpg'
                ],
                'links' => [
                    'self' => 'https://image/101'
                ],
                'relationships' => [
                    'images' => [
                        'data' => [
                            'type' => 'image',
                            'id' => '103'
                        ]
                    ]
                ]
            ],
            [
                'type' => 'image',
                'id' => '102',
                'attributes' => [
                    'url' => 'https://image/102.jpg'
                ],
                'links' => [
                    'self' => 'https://image/102'
                ]
            ],
        ]
    ];

    /** @var array  */
    protected array $arrayDocumentSuperDuperWithForcedListSingleEntity = [
        'data' => [
            'type' => 'article',
            'id' => '1',
            'attributes' => [
                'title' => 'title'
            ],
            'links' => [
                'self' => 'https://article/1'
            ],
            'relationships' => [
                'author' => [
                    'links' => [
                        'self' => 'https://author/1'
                    ],
                    'meta' => [
                        'metaOneString' => '1',
                        'metaTwo' => 2
                    ],
                    'data' => [
                        'type' => 'user',
                        'id' => '10'
                    ]
                ],
                'images' => [
                    'data' => [
                        [
                            'type' => 'image',
                            'id' => '101'
                        ],
                        [
                            'type' => 'image',
                            'id' => '102'
                        ]
                    ]
                ]
            ]
        ],
        'included' => [
            [
                'type' => 'user',
                'id' => '10',
                'attributes' => [
                    'name' => 'Carlo'
                ],
                'links' => [
                    'self' => 'https://user/10'
                ],
                'relationships' => [
                    'images' => [
                        'data' => [
                            'type' => 'image',
                            'id' => '103'
                        ]
                    ]
                ]
            ],
            [
                'type' => 'image',
                'id' => '103',
                'attributes' => [
                    'url' => 'https://image/103.jpg'
                ],
                'links' => [
                    'self' => 'https://image/103'
                ]
            ],
            [
                'type' => 'image',
                'id' => '101',
                'attributes' => [
                    'url' => 'https://image/101.jpg'
                ],
                'links' => [
                    'self' => 'https://image/101'
                ],
                'relationships' => [
                    'images' => [
                        'data' => [
                            'type' => 'image',
                            'id' => '103'
                        ]
                    ]
                ]
            ],
            [
                'type' => 'image',
                'id' => '102',
                'attributes' => [
                    'url' => 'https://image/102.jpg'
                ],
                'links' => [
                    'self' => 'https://image/102'
                ]
            ],
        ]
    ];

    /**
     * @var array
     */
    protected array $errors = [
        'errors' => [
            [
                'status' => '500',
                'detail' => 'detail',
                'id' => '1',
                'code' => '2',
                'title' => 'title'
            ]
        ]
    ];
}
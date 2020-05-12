<?php
namespace CarloNicora\JsonApi\tests\Unit\Traits;

trait ArrayDeclarationTrait
{
    /** @var array|string[]  */
    protected array $arrayMetaOnly = ['Meta' => []];

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
        'Meta' => [
            'metaOne' => 1,
            'metaTwo' => 2
        ]
    ];

    /** @var array  */
    protected array $arrayLinks = [
        'self' => [
            'href' => 'https://self',
            'Meta' => [
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
        'Links' => [
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
        'Meta' => [
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
        'Meta' => [
            'metaOne' => 1,
            'metaTwo' => 2
        ],
        'attributes' => [
            'name' => 'Carlo'
        ],
        'Links' => [
            'self' => 'https://self'
        ]
    ];

    /** @var array  */
    protected array $arrayResourceFullWithRelatedLink = [
        'type' => 'user',
        'id' => '1',
        'Meta' => [
            'metaOne' => 1,
            'metaTwo' => 2
        ],
        'attributes' => [
            'name' => 'Carlo'
        ],
        'Links' => [
            'self' => 'https://self',
            'related' => [
                'href' => 'https://related',
                'Meta' => [
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
            'Meta' => [
                'metaOne' => 1,
                'metaTwo' => 2
            ]
        ],
        [
            'type' => 'user',
            'id' => '1',
            'Meta' => [
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
                'title' => 'title'
            ],
            'Links' => [
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
                'Links' => [
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
                'Links' => [
                    'self' => 'https://image/103'
                ]
            ],
            [
                'type' => 'image',
                'id' => '101',
                'attributes' => [
                    'url' => 'https://image/101.jpg'
                ],
                'Links' => [
                    'self' => 'https://image/101'
                ]
            ],
            [
                'type' => 'image',
                'id' => '102',
                'attributes' => [
                    'url' => 'https://image/102.jpg'
                ],
                'Links' => [
                    'self' => 'https://image/102'
                ]
            ],
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
                'Links' => [
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
                'Links' => [
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
                'Links' => [
                    'self' => 'https://image/103'
                ]
            ],
            [
                'type' => 'image',
                'id' => '101',
                'attributes' => [
                    'url' => 'https://image/101.jpg'
                ],
                'Links' => [
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
                'Links' => [
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
            'Links' => [
                'self' => 'https://article/1'
            ],
            'relationships' => [
                'author' => [
                    'Links' => [
                        'self' => 'https://author/1'
                    ],
                    'Meta' => [
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
                'Links' => [
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
                'Links' => [
                    'self' => 'https://image/103'
                ]
            ],
            [
                'type' => 'image',
                'id' => '101',
                'attributes' => [
                    'url' => 'https://image/101.jpg'
                ],
                'Links' => [
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
                'Links' => [
                    'self' => 'https://image/102'
                ]
            ],
        ]
    ];
}
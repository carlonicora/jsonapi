# PHP library for {json:api}

[![Build Status](https://travis-ci.com/carlonicora/jsonapi.svg?branch=master)](https://travis-ci.com/carlonicora/jsonapi)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

jsonapi is a PHP library to manage [{json:api}](https://jsonapi.org) documents. The library also offers the possiblity
to manage an http response directly from the library.  

## Installation

**Composer**:
```bash
composer require carlonicora/jsonapi
```

**Git**:
```bash
git clone https://github.com/carlonicora/jsonapi.git
```

## Config

The jsonapi library does not require any configuration.

## Usage

This library is organised around the objects identifiable in the 
[{json:api} documentation](https://jsonapi.org/format/).

### document

The main object is the `document` object, which gives you access to the main elements a {json:api} document can contain. 

```php
use \carlonicora\jsonapi\document;

$document = new document();
```

You can also import a {json:api} document from an array.

```php
use \carlonicora\jsonapi\document;

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
```

## Versioning

This project use [Semantic Versioning](https://semver.org/) for its tags.

## Authors

* **Carlo Nicora** - Initial version - [GitHub](https://github.com/carlonicora) |
[phlow](https://phlow.com/@carlo)

## Contributions

Please, feel free to contribute, fork the repo and submit PR.

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT) - see the
[LICENSE.md](LICENSE.md) file for details 
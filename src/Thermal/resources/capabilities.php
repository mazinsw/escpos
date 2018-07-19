<?php

return [
    'models' => [
        'MP-4200 TH' => [
            'profile' => [
                'inherited' => 'escbema',
                'initialize' => "\x1d\xf950",
                'codepages' => [
                    'CP850' => "\et\x02",
                    'CP437' => "\et\x03",
                    'CP860' => "\et\x04",
                    'CP858' => "\et\x05",
                    'CP866' => "\et\x06",
                    'CP864' => "\et\x07",
                    'UTF-8' => "\et\x08",
                    'BIG5E' => "\et\x09",
                    'JIS' => "\et\x0a",
                    'SHIFTJIS' => "\et\x0b",
                    'GB2312' => "\et\x0c"
                ]
            ],
            'columns' => 50,
            'brand' => 'Bematech',
            'name' => 'Bematech MP-4200 TH',
            'codepage' => 'CP850',
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 42
                ],
                [
                    'name' => 'Font C',
                    'columns' => 50
                ],
                [
                    'name' => 'Font B',
                    'columns' => 56
                ]
            ]
        ],
        'TM-T81' => [
            'profile' => 'escpos',
            'columns' => 42,
            'brand' => 'Epson',
            'name' => 'Epson TM-T81',
            'codepage' => 'CP850',
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 42
                ],
                [
                    'name' => 'Font B',
                    'columns' => 56
                ]
            ]
        ]
    ],
    'profiles' => [
        'escpos' => [
            'codepages' => [
                'CP437' => "\et\x00",
                'KATAKANA' => "\et\x01",
                'CP850' => "\et\x02",
                'CP860' => "\et\x03",
                'CP863' => "\et\x04",
                'CP865' => "\et\x05",
                'CP1252' => "\et\x10",
                'CP866' => "\et\x11",
                'CP852' => "\et\x12",
                'CP858' => "\et\x13"
            ],
            'graphics' => [
                'mode' => 'bit',
                'open' => "\e3\x10",
                'cmd' => "\e*!",
                'line' => "\eJ\x00",
                'close' => "\e2"
            ]
        ],
        'escbema' => [
            'inherited' => 'escpos',
        ],
        'elgin' => [
            'inherited' => 'escpos',
            'drawer' => [
                '0' => "\ev\x8C",
                '1' => "\e\x80\x78"
            ],
            'expanded' => [
                'open' => "\eW\x01",
                'close' => "\eW\x00"
            ],
            'double_height' => [
                'open' => "\ed\x01",
                'close' => "\ed\x00"
            ],
            'bold' => [
                'open' => "\eE",
                'close' => "\eF"
            ],
            'codepages' => [
                'ABICOMP' => "\et\x01",
                'CP850' => "\et\x02",
                'CP437' => "\et\x03",
                'CP860' => "\et\x04",
                'CP858' => "\et\x05"
            ]
        ]
    ]
];

<?php

return [
    'models' => [
        'MP-5100 TH' => 'escbema',
        'MP-4200 TH' => 'escbema',
        'MP-20 MI' => 'escbema',
        'MP-100S TH' => 'escbema',
        'TM-T20' => 'escpos',
        'TM-T81' => 'escpos',
        'TM-T88' => 'escpos',
        'NIX' => 'elgin',
        'VOX+' => 'elgin',
        'VOX' => 'elgin',
        'I9' => 'elgin',
        'I7' => 'elgin',
        'DS300' => 'daruma',
        'DS348' => 'daruma',
        'DR600' => 'daruma',
        'DR700' => 'daruma',
        'DR800' => 'daruma',
        'IM113' => 'diebold',
        'IM402' => 'diebold',
        'IM433' => 'diebold',
        'IM453' => 'diebold',
        'TSP-143' => 'diebold',
        'IM833' => [
            'profile' => 'diebold',
            'name' => 'Mecaf Perfecta'
        ],
        'PertoPrinter' => 'perto',
        'CMP-20' => [
            'profile' => 'generic',
            'brand' => 'Citizen'
        ]
    ],
    'profiles' => [
        'escpos' => [
            'brand' => 'Epson',
            'columns' => 42,
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
            ],
            'codepages' => [
                'CP437' => "\et\x00",
                'KATAKANA' => "\et\x01",
                'CP850' => "\et\x02",
                'CP860' => "\et\x03",
                'CP863' => "\et\x04",
                'CP865' => "\et\x05",
                'Windows-1252' => "\et\x10",
                'CP866' => "\et\x11",
                'CP852' => "\et\x12",
                'CP858' => "\et\x13"
            ]
        ],
        'escbema' => [
            'brand' => 'Bematech',
            'columns' => 50,
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
            ],
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
        'elgin' => [
            'profile' => 'escpos',
            'brand' => 'Elgin',
            'codepages' => [
                'ABICOMP' => "\et\x01",
                'CP850' => "\et\x02",
                'CP437' => "\et\x03",
                'CP860' => "\et\x04",
                'CP858' => "\et\x05"
            ]
        ],
        'daruma' => [
            'profile' => 'elgin',
            'columns' => 48,
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 48
                ],
                [
                    'name' => 'Font B',
                    'columns' => 52
                ]
            ],
            'codepages' => [
                'ISO-8859-1' => "",
                'CP850' => "",
                'ABICOMP' => "",
                'CP437' => ""
            ]
        ],
        'diebold' => [
            'profile' => 'daruma',
            'codepages' => [
                'ABICOMP' => "\et\x01",
                'CP850' => "\et\x02",
                'CP437' => "\et\x03",
                'Windows-1252' => "\et\x04"
            ]
        ],
        'perto' => [
            'profile' => 'elgin',
            'brand' => 'Perto',
            'columns' => 48,
            'codepage' => 'CP850',
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 48
                ],
                [
                    'name' => 'Font B',
                    'columns' => 57
                ]
            ]
        ],
        'generic' => [
            'brand' => 'Generic',
            'columns' => 32,
            'codepage' => 'CP850',
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 32
                ],
                [
                    'name' => 'Font B',
                    'columns' => 42
                ]
            ],
            'initialize' => "\eR\x0C",
            'codepages' => [
                'CP437' => "\et\x00",
                'KATAKANA' => "\et\x01",
                'CP850' => "\et\x02",
                'CP860' => "\et\x03",
                'CP863' => "\et\x04",
                'CP865' => "\et\x05",
                'Windows-1252' => "\et\x06",
                'CP866' => "\et\x07",
                'CP852' => "\et\x08",
                'CP858' => "\et\x09",
                'Windows-1253' => "\et\x0A",
                'CP737' => "\et\x0B",
                'CP857' => "\et\x0C",
                'ISO-8859-9' => "\et\x0D",
                'CP864' => "\et\x0E",
                'CP862' => "\et\x0F",
                'ISO-8859-2' => "\et\x10"
            ]
        ]
    ]
];

<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

return [
    'Article' => [
        [
            'publisher' => 'Google',
            'text' => 'Google Article',
            'link' => 'https://developers.google.com/search/docs/appearance/structured-data/article',
        ],
    ],
    'CreativeWork' => [
        [
            'publisher' => 'Yandex',
            'text' => 'Yandex CreativeWork',
            'link' => 'https://yandex.com/support/webmaster/supported-schemas/essay.html',
        ],
    ],
    'SoftwareApplication' => [
        [
            'publisher' => 'Google',
            'text' => 'Google SoftwareApplication',
            'link' => 'https://developers.google.com/search/docs/appearance/structured-data/software-app',
        ],
        [
            'publisher' => 'Yandex',
            'text' => 'Yandex SoftwareApplication',
            'link' => 'https://yandex.com/support/webmaster/supported-schemas/software.html',
        ],
    ],
];

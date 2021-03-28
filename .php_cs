<?php

$config = TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

$header = <<<EOF
This file is part of the Schema Generator.

For the full copyright and license information, please view the
LICENSE.txt file that was distributed with this source code.
EOF;

$config->addRules([
    'header_comment' => ['header' => $header, 'separate' => 'both'],
]);

return $config;

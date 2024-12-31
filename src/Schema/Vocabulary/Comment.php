<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidCommentException;

final readonly class Comment
{
    private string $text;

    public function __construct(
        array|string $text,
    ) {
        if (\is_array($text)) {
            $text = $text['@value'] ?? throw InvalidCommentException::fromMissingValueKey($text);
        }

        $this->text = $this->normaliseText($text);
    }

    private function normaliseText(string $text): string
    {
        $replacements = [
            '[[' => '',
            ']]' => '',
            '&amp;' => '&',
            '&quot;' => '"',
            '&lt;' => '<',
            '&gt;' => '>',
            '&#x2014;' => ' - ',
            '\n' => "\n",
            '<a href="/' => '<a href="https://schema.org/',
        ];

        $lines = \explode(
            "\n",
            \str_replace(
                \array_keys($replacements),
                \array_values($replacements),
                \strip_tags($text, ['<a>']),
            ),
        );

        \array_walk($lines, static function (string &$line): void {
            $line = \trim($line);
        });

        return \implode("\n", $lines);
    }

    public function text(): string
    {
        return $this->text;
    }
}

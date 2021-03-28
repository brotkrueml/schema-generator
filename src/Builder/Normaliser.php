<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Builder;

final class Normaliser
{
    public function normaliseComment(string|array $comment): string
    {
        if ($comment['@value'] ?? false) {
            $comment = $comment['@value'];
        }

        if (!\is_string($comment)) {
            throw new \RuntimeException(
                \sprintf(
                    'Comment is an array but has no key "@value": "%s"',
                    \json_encode($comment)
                ),
                1616329438
            );
        }

        $replacements = [
            '[[' => '',
            ']]' => '',
            '&amp;' => '&',
            '&quot;' => '"',
            '&lt;' => '<',
            '&gt;' => '>',
            '&#x2014;' => ' - ',
            '\\n' => "\n",
            '<a href="/' => '<a href="https://schema.org/',
        ];

        $commentLines = \explode(
            '\n',
            \str_replace(
                \array_keys($replacements),
                \array_values($replacements),
                \strip_tags($comment, ['<a>'])
            )
        );

        $commentLines = \array_filter($commentLines, static function (string $line): bool {
            return $line !== '';
        });

        \array_walk($commentLines, static function (string &$line): void {
            $line = \trim($line);
        });

        return \implode("\n", $commentLines);
    }

    public function normaliseIdFromClasses(array $classes): array
    {
        if (\array_is_list($classes)) {
            \array_walk($classes, function (array &$class): void {
                $class = $this->normaliseId($class['@id']);
            });

            return $classes;
        }

        return [$this->normaliseId($classes['@id'])];
    }

    public function normaliseId(string $value): string
    {
        return \str_replace('schema:', '', $value);
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Builder;

use Brotkrueml\SchemaGenerator\Builder\Normaliser;
use PHPUnit\Framework\TestCase;

class NormaliserTest extends TestCase
{
    private Normaliser $subject;

    protected function setUp(): void
    {
        $this->subject = new Normaliser();
    }

    /**
     * @test
     * @dataProvider dataProviderForNormaliseComment
     */
    public function normaliseComment(string|array $comment, string $expected): void
    {
        $actual = $this->subject->normaliseComment($comment);

        self::assertSame($expected, $actual);
    }

    public function dataProviderForNormaliseComment(): \Generator
    {
        yield 'as Array' => [
            'comment' => [
                '@language' => 'en',
                '@value' => 'Current location of the item.',
            ],
            'expected' => 'Current location of the item.',
        ];

        yield 'with brackets' => [
            'comment' => 'This is the [[Action]] of navigating to a specific [[startOffset]] timestamp within a [[VideoObject]], typically represented with a URL template structure.',
            'expected' => 'This is the Action of navigating to a specific startOffset timestamp within a VideoObject, typically represented with a URL template structure.',
        ];

        yield 'with &amp;' => [
            'comment' => 'some &amp; text',
            'expected' => 'some & text',
        ];

        yield 'with &quot;' => [
            'comment' => 'some &quot;text&quot;',
            'expected' => 'some "text"',
        ];

        yield 'with &lt; and &gt;' => [
            'comment' => 'Properties that take Energy as values are of the form &lt;Number&gt; &lt;Energy unit of measure&gt;.',
            'expected' => 'Properties that take Energy as values are of the form <Number> <Energy unit of measure>.',
        ];

        yield 'with a dash' => [
            'comment' => 'A contact point&#x2014;for example, a Customer Complaints department.',
            'expected' => 'A contact point - for example, a Customer Complaints department.',
        ];

        yield 'with a relative link' => [
            'comment' => '<a href="/some-path">link</a>',
            'expected' => '<a href="https://schema.org/some-path">link</a>',
        ];

        yield 'tags other than link are stripped' => [
            'comment' => '<b>some <i><a href="https://example.org/">link</a></i></b>',
            'expected' => 'some <a href="https://example.org/">link</a>',
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForNormaliseIdFromClasses
     */
    public function normaliseIdFromClasses(array $classes, array $expected): void
    {
        $actual = $this->subject->normaliseIdFromClasses($classes);

        self::assertSame($expected, $actual);
    }

    public function dataProviderForNormaliseIdFromClasses(): \Generator
    {
        yield 'with one id given' => [
            'classes' => [
                '@id' => 'schema:LocalBusiness',
            ],
            'expected' => ['LocalBusiness'],
        ];

        yield 'with multiple ids given' => [
            'classes' => [
                [
                    '@id' => 'schema:DataFeedItem',
                ],
                [
                    '@id' => 'schema:CreativeWork',
                ],
            ],
            'expected' => ['DataFeedItem', 'CreativeWork'],
        ];
    }

    /**
     * @test
     */
    public function normaliseId(): void
    {
        $actual = $this->subject->normaliseId('schema:Person');

        self::assertSame('Person', $actual);
    }
}

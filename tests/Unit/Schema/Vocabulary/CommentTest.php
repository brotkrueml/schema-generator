<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidCommentException;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Comment::class)]
final class CommentTest extends TestCase
{
    #[Test]
    #[DataProvider('provider')]
    public function textReturnsNormalisedText(string|array $text, string $expected): void
    {
        $subject = new Comment($text);

        $actual = $subject->text();

        self::assertSame($expected, $actual);
    }

    public static function provider(): iterable
    {
        yield 'with simple text' => [
            'text' => 'The season to which this episode belongs.',
            'expected' => 'The season to which this episode belongs.',
        ];

        yield 'with an array' => [
            'text' => [
                '@language' => 'en',
                '@value' => 'Current location of the item.',
            ],
            'expected' => 'Current location of the item.',
        ];

        yield 'with a reference to another term, reference is removed' => [
            'text' => 'A subproperty of [[measurementTechnique]] that can be used for specifying specific methods, in particular via [[MeasurementMethodEnum]].',
            'expected' => 'A subproperty of measurementTechnique that can be used for specifying specific methods, in particular via MeasurementMethodEnum.',
        ];

        yield 'with a "lt" and "gt" entity, which are decoded' => [
            'text' => 'This is the molecular weight of the entity being described, not of the parent. Units should be included in the form \'&lt;Number&gt; &lt;unit&gt;\', for example \'12 amu\' or as \'&lt;QuantitativeValue&gt;.',
            'expected' => 'This is the molecular weight of the entity being described, not of the parent. Units should be included in the form \'<Number> <unit>\', for example \'12 amu\' or as \'<QuantitativeValue>.',
        ];

        yield 'with line breaks' => [
            'text' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In\n      the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written \"2011/2012\"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL.\n      Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via \"1939/1945\".\n\nOpen-ended date ranges can be written with \"..\" in place of the end date. For example, \"2015-11/..\" indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
            'expected' => <<<EXPECTED
The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In
the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written \\"2011/2012\\"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL.
Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via \\"1939/1945\\".

Open-ended date ranges can be written with \\"..\\" in place of the end date. For example, \\"2015-11/..\\" indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.
EXPECTED,
        ];
    }

    #[Test]
    public function throwsExceptionIfArrayDoesNotHasValueKey(): void
    {
        $this->expectException(InvalidCommentException::class);
        $this->expectExceptionCode(1735463278);

        new Comment([
            '@language' => 'en',
        ]);
    }
}

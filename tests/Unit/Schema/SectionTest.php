<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidSectionException;
use Brotkrueml\SchemaGenerator\Schema\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Section::class)]
final class SectionTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForFromShortName')]
    public function fromShortNameReturnsSectionCorrectly(string $shortName, Section $expected): void
    {
        $actual = Section::fromShortName($shortName);

        self::assertSame($expected, $actual);
    }

    public static function providerForFromShortName(): iterable
    {
        yield 'auto' => [
            'shortName' => 'auto',
            'expected' => Section::Auto,
        ];

        yield 'bib' => [
            'shortName' => 'bib',
            'expected' => Section::Bib,
        ];

        yield 'core' => [
            'shortName' => 'core',
            'expected' => Section::Core,
        ];

        yield 'health' => [
            'shortName' => 'health',
            'expected' => Section::Health,
        ];

        yield 'pending' => [
            'shortName' => 'pending',
            'expected' => Section::Pending,
        ];
    }

    #[Test]
    public function fromShortNameThrowsExceptionOnInvalidShortName(): void
    {
        $this->expectException(InvalidSectionException::class);
        $this->expectExceptionCode(1735242208);

        Section::fromShortName('invalid');
    }

    #[Test]
    #[DataProvider('providerForShortName')]
    public function shortName(Section $section, string $expected): void
    {
        $actual = $section->shortName();

        self::assertSame($expected, $actual);
    }

    public static function providerForShortName(): iterable
    {
        yield 'auto' => [
            'section' => Section::Auto,
            'expected' => 'auto',
        ];

        yield 'bib' => [
            'section' => Section::Bib,
            'expected' => 'bib',
        ];

        yield 'core' => [
            'section' => Section::Core,
            'expected' => 'core',
        ];

        yield 'health' => [
            'section' => Section::Health,
            'expected' => 'health',
        ];

        yield 'pending' => [
            'section' => Section::Pending,
            'expected' => 'pending',
        ];
    }

    #[Test]
    #[DataProvider('providerForFromUri')]
    public function fromUri(string $uri, Section $expected): void
    {
        $actual = Section::fromUri($uri);

        self::assertSame($expected, $actual);
    }

    public static function providerForFromUri(): iterable
    {
        yield 'auto' => [
            'uri' => 'https://auto.schema.org',
            'expected' => Section::Auto,
        ];

        yield 'bib' => [
            'uri' => 'https://bib.schema.org',
            'expected' => Section::Bib,
        ];

        yield 'core' => [
            'uri' => '',
            'expected' => Section::Core,
        ];

        yield 'health' => [
            'uri' => 'https://health-lifesci.schema.org',
            'expected' => Section::Health,
        ];

        yield 'pending' => [
            'uri' => 'https://pending.schema.org',
            'expected' => Section::Pending,
        ];
    }

    #[Test]
    public function fromUriThrowsExceptionOnInvalidUri(): void
    {
        $this->expectException(InvalidSectionException::class);
        $this->expectExceptionCode(1735242209);

        Section::fromUri('https://example.com');
    }

    #[Test]
    #[DataProvider('providerForPhpNamespace')]
    public function phpNamespace(Section $section, string $expected): void
    {
        $actual = $section->phpNamespace();

        self::assertSame($expected, $actual);
    }

    public static function providerForPhpNamespace(): iterable
    {
        yield 'auto' => [
            'section' => Section::Auto,
            'expected' => 'Brotkrueml\SchemaAuto\\',
        ];

        yield 'bib' => [
            'section' => Section::Bib,
            'expected' => 'Brotkrueml\SchemaBib\\',
        ];

        yield 'core' => [
            'section' => Section::Core,
            'expected' => 'Brotkrueml\Schema\\',
        ];

        yield 'health' => [
            'section' => Section::Health,
            'expected' => 'Brotkrueml\SchemaHealth\\',
        ];

        yield 'pending' => [
            'section' => Section::Pending,
            'expected' => 'Brotkrueml\SchemaPending\\',
        ];
    }
}

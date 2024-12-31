<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidIdException;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Id::class)]
final class IdTest extends TestCase
{
    #[Test]
    public function idReturnsCorrectId(): void
    {
        $subject = new Id('schema:temporalCoverage');

        self::assertSame('schema:temporalCoverage', $subject->id());
    }

    #[Test]
    public function labelReturnsCorrectId(): void
    {
        $subject = new Id('schema:temporalCoverage');

        self::assertSame('temporalCoverage', $subject->label());
    }

    #[Test]
    #[DataProvider('providerForInvalidId')]
    public function throwsExceptionOnInvalidId(string $id, int $expectedCode): void
    {
        $this->expectException(InvalidIdException::class);
        $this->expectExceptionCode($expectedCode);

        new Id($id);
    }

    public static function providerForInvalidId(): iterable
    {
        yield 'with empty namespace' => [
            ':label',
            1735239834,
        ];

        yield 'with invalid namespace' => [
            'invalid:label',
            1735239835,
        ];

        yield 'with empty label' => [
            'schema:',
            1735239836,
        ];

        yield 'with missing label' => [
            'schema',
            1735239836,
        ];
    }
}

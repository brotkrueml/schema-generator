<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Exception;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidIdException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidIdException::class)]
final class InvalidIdExceptionTest extends TestCase
{
    #[Test]
    public function fromEmptyNamespace(): void
    {
        $actual = InvalidIdException::fromEmptyNamespace(':label');

        self::assertSame('Empty namespace for id given: :label', $actual->getMessage());
        self::assertSame(1735239834, $actual->getCode());
    }

    #[Test]
    public function fromInvalidNamespace(): void
    {
        $actual = InvalidIdException::fromInvalidNamespace('invalid:label');

        self::assertSame('Invalid namespace for id given (must be "schema"): invalid:label', $actual->getMessage());
        self::assertSame(1735239835, $actual->getCode());
    }

    #[Test]
    public function fromMissingLabel(): void
    {
        $actual = InvalidIdException::fromMissingLabel('invalid');

        self::assertSame('Label for id is missing: invalid', $actual->getMessage());
        self::assertSame(1735239836, $actual->getCode());
    }
}

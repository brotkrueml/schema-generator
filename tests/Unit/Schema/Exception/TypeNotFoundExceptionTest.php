<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Exception;

use Brotkrueml\SchemaGenerator\Schema\Exception\TypeNotFoundException;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TypeNotFoundException::class)]
final class TypeNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function typeIdNotFound(): void
    {
        $actual = TypeNotFoundException::typeIdNotFound(new Id('schema:Thing'));

        self::assertSame('No type with id "schema:Thing" found', $actual->getMessage());
        self::assertSame(1735290356, $actual->getCode());
    }
}

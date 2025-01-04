<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Exception;

use Brotkrueml\SchemaGenerator\Schema\Exception\EnumerationNotFoundException;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(EnumerationNotFoundException::class)]
final class EnumerationNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function enumerationIdNotFound(): void
    {
        $actual = EnumerationNotFoundException::enumerationIdNotFound(new Id('schema:InvalidType'));

        self::assertSame('No enumeration with id "schema:InvalidType" found!', $actual->getMessage());
        self::assertSame(1735895580, $actual->getCode());
    }
}

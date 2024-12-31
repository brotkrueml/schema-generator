<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Exception;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidTermException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidTermException::class)]
final class InvalidTermExceptionTest extends TestCase
{
    #[Test]
    public function fromKey(): void
    {
        $actual = InvalidTermException::fromKey('someKey', [
            '@id' => 'someTerm',
        ]);

        self::assertSame('Term is invalid, mandatory key "someKey" is missing: {"@id":"someTerm"}', $actual->getMessage());
        self::assertSame(1735467574, $actual->getCode());
    }
}

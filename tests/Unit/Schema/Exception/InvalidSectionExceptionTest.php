<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Exception;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidSectionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidSectionException::class)]
final class InvalidSectionExceptionTest extends TestCase
{
    #[Test]
    public function fromShortName(): void
    {
        $actual = InvalidSectionException::fromShortName('unknown');

        self::assertSame('Short name "unknown" is invalid!', $actual->getMessage());
        self::assertSame(1735242208, $actual->getCode());
    }

    #[Test]
    public function fromUri(): void
    {
        $actual = InvalidSectionException::fromUri('https://example.com');

        self::assertSame('Uri "https://example.com" is invalid!', $actual->getMessage());
        self::assertSame(1735242209, $actual->getCode());
    }
}

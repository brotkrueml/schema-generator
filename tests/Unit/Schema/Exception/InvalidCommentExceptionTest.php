<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Exception;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidCommentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidCommentException::class)]
final class InvalidCommentExceptionTest extends TestCase
{
    #[Test]
    public function fromMissingValueKey(): void
    {
        $comment = [
            '@language' => 'en',
        ];

        $actual = InvalidCommentException::fromMissingValueKey($comment);

        self::assertSame('Given comment does not have a @value key: {"@language":"en"}', $actual->getMessage());
        self::assertSame(1735463278, $actual->getCode());
    }
}

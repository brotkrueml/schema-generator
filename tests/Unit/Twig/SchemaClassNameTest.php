<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Twig;

use Brotkrueml\SchemaGenerator\Twig\SchemaClassName;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchemaClassName::class)]
final class SchemaClassNameTest extends TestCase
{
    #[Test]
    public function forModel(): void
    {
        $subject = new SchemaClassName();

        $actual = $subject::forModel('3DModel');

        self::assertSame('_3DModel', $actual);
    }
}

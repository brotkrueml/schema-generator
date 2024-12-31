<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Twig;

use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Twig\SchemaSection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchemaSection::class)]
final class SchemaSectionTest extends TestCase
{
    #[Test]
    public function fromShortName(): void
    {
        $subject = new SchemaSection();

        $actual = $subject::fromShortName('health');

        self::assertSame(Section::Health, $actual);
    }
}

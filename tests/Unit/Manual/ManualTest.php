<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Manual;

use Brotkrueml\SchemaGenerator\Manual\Manual;
use Brotkrueml\SchemaGenerator\Manual\Publisher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Manual::class)]
final class ManualTest extends TestCase
{
    private Manual $subject;

    protected function setUp(): void
    {
        $this->subject = new Manual(
            'Article',
            Publisher::Google,
            'Google Article',
            'https://developers.google.com/search/docs/appearance/structured-data/article',
        );
    }

    #[Test]
    public function typeReturnsTypeCorrectly(): void
    {
        self::assertSame('Article', $this->subject->type());
    }

    #[Test]
    public function publisherReturnsPublisherCorrectly(): void
    {
        self::assertSame(Publisher::Google, $this->subject->publisher());
    }

    #[Test]
    public function linkReturnsLinkCorrectly(): void
    {
        self::assertSame('https://developers.google.com/search/docs/appearance/structured-data/article', $this->subject->link());
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Manual;

use Brotkrueml\SchemaGenerator\Manual\ManualsBuilder;
use Brotkrueml\SchemaGenerator\Manual\Publisher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ManualsBuilder::class)]
final class ManualsBuilderTest extends TestCase
{
    private const string FILE_FIXTURE = __DIR__ . '/../../Fixtures/manuals.php';

    #[Test]
    public function build(): void
    {
        $subject = new ManualsBuilder(self::FILE_FIXTURE);

        $actual = $subject->build();

        $actualAsArray = [];
        foreach ($actual as $manual) {
            $actualAsArray[] = $manual;
        }

        self::assertSame('Article', $actualAsArray[0]->type());
        self::assertSame(Publisher::Google, $actualAsArray[0]->publisher());
        self::assertSame('https://developers.google.com/search/docs/appearance/structured-data/article', $actualAsArray[0]->link());

        self::assertSame('CreativeWork', $actualAsArray[1]->type());
        self::assertSame(Publisher::Yandex, $actualAsArray[1]->publisher());
        self::assertSame('https://yandex.com/support/webmaster/supported-schemas/essay.html', $actualAsArray[1]->link());

        self::assertSame('SoftwareApplication', $actualAsArray[2]->type());
        self::assertSame(Publisher::Google, $actualAsArray[2]->publisher());
        self::assertSame('https://developers.google.com/search/docs/appearance/structured-data/software-app', $actualAsArray[2]->link());

        self::assertSame('SoftwareApplication', $actualAsArray[3]->type());
        self::assertSame(Publisher::Yandex, $actualAsArray[3]->publisher());
        self::assertSame('https://yandex.com/support/webmaster/supported-schemas/software.html', $actualAsArray[3]->link());
    }
}

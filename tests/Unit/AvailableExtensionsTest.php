<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit;

use Brotkrueml\SchemaGenerator\AvailableExtensions;
use PHPUnit\Framework\TestCase;

class AvailableExtensionsTest extends TestCase
{
    private AvailableExtensions $subject;

    protected function setUp(): void
    {
        $this->subject = new AvailableExtensions();
    }

    /**
     * @test
     * @dataProvider dataProviderForGetByExtensionUri
     */
    public function getByExtensionUriReturnsCorrectExtension(string $extensionUri, string $expectedExtension): void
    {
        $actual = $this->subject->getExtensionByUri($extensionUri);

        self::assertSame($expectedExtension, $actual->getExtension());
    }

    public function dataProviderForGetByExtensionUri(): \Generator
    {
        yield 'auto' => [
            'extensionUri' => 'https://auto.schema.org',
            'expectedExtension' => 'auto',
        ];

        yield 'bib' => [
            'extensionUri' => 'https://bib.schema.org',
            'expectedExtension' => 'bib',
        ];

        yield 'core' => [
            'extensionUri' => '',
            'expectedExtension' => 'core',
        ];

        yield 'health' => [
            'extensionUri' => 'https://health-lifesci.schema.org',
            'expectedExtension' => 'health',
        ];

        yield 'pending' => [
            'extensionUri' => 'https://pending.schema.org',
            'expectedExtension' => 'pending',
        ];
    }

    /**
     * @test
     */
    public function getByExtensionUriThrowsExceptionOnInvalidUri(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(1617384097);

        $this->subject->getExtensionByUri('notexisting');
    }
}

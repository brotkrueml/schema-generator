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
use Brotkrueml\SchemaGenerator\Enumerations\Namespaces;
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
     * @dataProvider dataProviderForGetExtensionByUri
     */
    public function getExtensionByUriReturnsCorrectExtension(string $extensionUri, string $expectedExtension): void
    {
        $actual = $this->subject->getExtensionByUri($extensionUri);

        self::assertSame($expectedExtension, $actual->getExtension());
    }

    public function dataProviderForGetExtensionByUri(): \Generator
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

    /**
     * @test
     * @dataProvider dataProviderForGetNamespaceByExtension
     */
    public function getNamespaceByExtensionReturnsCorrectExtension(string $extension, string $expectedNamespace): void
    {
        $actual = $this->subject->getNamespaceByExtension($extension);

        self::assertSame($expectedNamespace, $actual);
    }

    public function dataProviderForGetNamespaceByExtension(): \Generator
    {
        yield 'auto' => [
            'extension' => 'auto',
            'expectedNamespace' => Namespaces::AUTO,
        ];

        yield 'bib' => [
            'extension' => 'bib',
            'expectedNamespace' => Namespaces::BIB,
        ];

        yield 'core' => [
            'extension' => 'core',
            'expectedNamespace' => Namespaces::CORE,
        ];

        yield 'health' => [
            'extension' => 'health',
            'expectedNamespace' => Namespaces::HEALTH,
        ];

        yield 'pending' => [
            'extension' => 'pending',
            'expectedNamespace' => Namespaces::PENDING,
        ];
    }

    /**
     * @test
     */
    public function getNamespaceByExtensionThrowsExceptionOnInvalidUri(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(1617384098);

        $this->subject->getNamespaceByExtension('notexisting');
    }
}

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
    public function getExtensionByUriReturnsCorrectExtension(string $uri, string $expectedName): void
    {
        $actual = $this->subject->getExtensionByUri($uri);

        self::assertSame($expectedName, $actual->getName());
    }

    public static function dataProviderForGetExtensionByUri(): \Generator
    {
        yield 'auto' => [
            'uri' => 'https://auto.schema.org',
            'expectedName' => 'auto',
        ];

        yield 'bib' => [
            'uri' => 'https://bib.schema.org',
            'expectedName' => 'bib',
        ];

        yield 'core' => [
            'uri' => '',
            'expectedName' => 'core',
        ];

        yield 'health' => [
            'uri' => 'https://health-lifesci.schema.org',
            'expectedName' => 'health',
        ];

        yield 'pending' => [
            'uri' => 'https://pending.schema.org',
            'expectedName' => 'pending',
        ];
    }

    /**
     * @test
     */
    public function getByuriThrowsExceptionOnInvalidUri(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(1617384097);

        $this->subject->getExtensionByUri('notexisting');
    }

    /**
     * @test
     * @dataProvider dataProviderForGetNamespaceByName
     */
    public function getNamespaceByNameReturnsCorrectNamespace(string $name, string $expectedNamespace): void
    {
        $actual = $this->subject->getNamespaceByName($name);

        self::assertSame($expectedNamespace, $actual);
    }

    public static function dataProviderForGetNamespaceByName(): \Generator
    {
        yield 'auto' => [
            'name' => 'auto',
            'expectedNamespace' => Namespaces::AUTO,
        ];

        yield 'bib' => [
            'name' => 'bib',
            'expectedNamespace' => Namespaces::BIB,
        ];

        yield 'core' => [
            'name' => 'core',
            'expectedNamespace' => Namespaces::CORE,
        ];

        yield 'health' => [
            'name' => 'health',
            'expectedNamespace' => Namespaces::HEALTH,
        ];

        yield 'pending' => [
            'name' => 'pending',
            'expectedNamespace' => Namespaces::PENDING,
        ];
    }

    /**
     * @test
     */
    public function getNamespaceByNameThrowsExceptionOnInvalidUri(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(1617384098);

        $this->subject->getNamespaceByName('notexisting');
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Dto;

use Brotkrueml\SchemaGenerator\Dto\Extension;
use Brotkrueml\SchemaGenerator\Enumerations\Extensions;
use Brotkrueml\SchemaGenerator\Enumerations\Namespaces;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProviderForExtensions
     */
    public function objectIsInitialisedCorrectly(string $extension, string $extensionUri, string $namespace): void
    {
        $subject = new Extension($extension);

        self::assertSame($extension, $subject->getExtension());
        self::assertSame($extensionUri, $subject->getExtensionUri());
        self::assertSame($namespace, $subject->getNamespace());
    }

    public function dataProviderForExtensions(): \Generator
    {
        yield 'auto' => [
            'extension' => 'auto',
            'extensionUri' => Extensions::AUTO,
            'namespace' => Namespaces::AUTO,
        ];

        yield 'bib' => [
            'extension' => 'bib',
            'extensionUri' => Extensions::BIB,
            'namespace' => Namespaces::BIB,
        ];

        yield 'core' => [
            'extension' => 'core',
            'extensionUri' => Extensions::CORE,
            'namespace' => Namespaces::CORE,
        ];

        yield 'health' => [
            'extension' => 'health',
            'extensionUri' => Extensions::HEALTH,
            'namespace' => Namespaces::HEALTH,
        ];

        yield 'pending' => [
            'extension' => 'PENDING',
            'extensionUri' => Extensions::PENDING,
            'namespace' => Namespaces::PENDING,
        ];
    }

    /**
     * @test
     */
    public function exceptionIsThrownOnInvalidExtension(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1617382155);

        new Extension('notexisting');
    }
}

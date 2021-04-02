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
    public function objectIsInitialisedCorrectly(string $name, string $uri, string $namespace): void
    {
        $subject = new Extension($name);

        self::assertSame($name, $subject->getName());
        self::assertSame($uri, $subject->getUri());
        self::assertSame($namespace, $subject->getNamespace());
        self::assertSame($name, (string)$subject);
    }

    public function dataProviderForExtensions(): \Generator
    {
        yield 'auto' => [
            'name' => 'auto',
            'uri' => Extensions::AUTO,
            'namespace' => Namespaces::AUTO,
        ];

        yield 'bib' => [
            'name' => 'bib',
            'uri' => Extensions::BIB,
            'namespace' => Namespaces::BIB,
        ];

        yield 'core' => [
            'name' => 'core',
            'uri' => Extensions::CORE,
            'namespace' => Namespaces::CORE,
        ];

        yield 'health' => [
            'name' => 'health',
            'uri' => Extensions::HEALTH,
            'namespace' => Namespaces::HEALTH,
        ];

        yield 'pending' => [
            'name' => 'PENDING',
            'uri' => Extensions::PENDING,
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

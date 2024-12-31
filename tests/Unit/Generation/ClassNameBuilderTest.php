<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Generation;

use Brotkrueml\SchemaGenerator\Generation\ClassNameBuilder;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClassNameBuilder::class)]
final class ClassNameBuilderTest extends TestCase
{
    private ClassNameBuilder $subject;

    protected function setUp(): void
    {
        $this->subject = new ClassNameBuilder();
    }

    #[Test]
    #[DataProvider('providerForModel')]
    public function getClassNameForModelFromType(string $typeLabel, string $expected): void
    {
        $type = new Type(new Id('schema:' . $typeLabel), new Comment(''), Section::Core);

        $actual = $this->subject->getClassNameForModelFromType($type);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForModel')]
    public function getClassNameForModelFromLabel(string $typeLabel, string $expected): void
    {
        $actual = $this->subject->getClassNameForModelFromLabel($typeLabel);

        self::assertSame($expected, $actual);
    }

    public static function providerForModel(): iterable
    {
        yield 'Article' => [
            'typeLabel' => 'Article',
            'expected' => 'Article',
        ];

        yield '3DModel' => [
            'typeLabel' => '3DModel',
            'expected' => '_3DModel',
        ];
    }

    #[Test]
    #[DataProvider('providerForViewHelper')]
    public function getClassNameForViewHelperFromType(string $typeLabel, string $expected): void
    {
        $type = new Type(new Id('schema:' . $typeLabel), new Comment(''), Section::Core);

        $actual = $this->subject->getClassNameForViewHelperFromType($type);

        self::assertSame($expected, $actual);
    }

    public static function providerForViewHelper(): iterable
    {
        yield 'Article' => [
            'typeLabel' => 'Article',
            'expected' => 'ArticleViewHelper',
        ];

        yield '3DModel' => [
            'typeLabel' => '3DModel',
            'expected' => 'ThreeDModelViewHelper',
        ];
    }
}

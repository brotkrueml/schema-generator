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
use Brotkrueml\SchemaGenerator\Manual\Manuals;
use Brotkrueml\SchemaGenerator\Manual\Publisher;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Manuals::class)]
final class ManualsTest extends TestCase
{
    #[Test]
    public function iterationOverManuals(): void
    {
        $manuals = [
            new Manual(
                'Article',
                Publisher::Google,
                'https://example.com/Article',
            ),
            new Manual(
                'Article',
                Publisher::Yandex,
                'https://example.org/Article',
            ),
            new Manual(
                'Book',
                Publisher::Google,
                'https://example.com/Book',
            ),
        ];

        $subject = new Manuals();
        $subject->addManual($manuals[0]);
        $subject->addManual($manuals[1]);
        $subject->addManual($manuals[2]);

        foreach ($subject as $manual) {
            self::assertContains($manual, $manuals);
        }
    }

    #[Test]
    public function findByTypeReturnsManualsCorrectly(): void
    {
        $manuals = [
            new Manual(
                'Article',
                Publisher::Google,
                'https://example.com/Article',
            ),
            new Manual(
                'Article',
                Publisher::Yandex,
                'https://example.org/Article',
            ),
            new Manual(
                'Book',
                Publisher::Google,
                'https://example.com/Book',
            ),
        ];

        $subject = new Manuals();
        $subject->addManual($manuals[0]);
        $subject->addManual($manuals[1]);
        $subject->addManual($manuals[2]);

        $actual = $subject->findByType(new Type(new Id('schema:Article'), new Comment(''), Section::Core));

        $actualAsArray = [];
        foreach ($actual as $manual) {
            $actualAsArray[] = $manual;
        }

        self::assertCount(2, $actualAsArray);
        self::assertContains($manuals[0], $actualAsArray);
        self::assertContains($manuals[1], $actualAsArray);
    }

    #[Test]
    public function findByTypeReturnsEmptySetIfTypeIsNotFound(): void
    {
        $manuals = [
            new Manual(
                'Article',
                Publisher::Google,
                'https://example.com/Article',
            ),
            new Manual(
                'Article',
                Publisher::Yandex,
                'https://example.org/Article',
            ),
            new Manual(
                'Book',
                Publisher::Google,
                'https://example.com/Book',
            ),
        ];

        $subject = new Manuals();
        $subject->addManual($manuals[0]);
        $subject->addManual($manuals[1]);
        $subject->addManual($manuals[2]);

        $actual = $subject->findByType(new Type(new Id('schema:Person'), new Comment(''), Section::Core));

        foreach ($actual as $_) {
            self::fail('Result set must be empty!');
        }

        // At least one assertion should be here, so we check the instance
        self::assertInstanceOf(Manuals::class, $actual);
    }
}

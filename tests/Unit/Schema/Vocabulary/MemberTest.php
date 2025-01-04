<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Member;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Member::class)]
final class MemberTest extends TestCase
{
    #[Test]
    public function idReturnsIdCorrectly(): void
    {
        $id = new Id('schema:AudiobookFormat');
        $comment = new Comment('Book format: Audiobook.');

        $subject = new Member($id, $comment);

        self::assertSame($id, $subject->id());
    }

    #[Test]
    public function commentReturnsCommentCorrectly(): void
    {
        $id = new Id('schema:AudiobookFormat');
        $comment = new Comment('Book format: Audiobook.');

        $subject = new Member($id, $comment);

        self::assertSame($comment, $subject->comment());
    }

    #[Test]
    public function typeIdsReturnsTypeIdsCorrectly(): void
    {
        $id = new Id('schema:LockerDelivery');
        $comment = new Comment('A DeliveryMethod in which an item is made available via locker.');

        $subject = new Member($id, $comment);

        $typeIds = [
            new Id('schema:Demand'),
            new Id('schema:Offer'),
            new Id('schema:OrderAction'),
        ];

        $subject->addTypeId($typeIds[0]);
        $subject->addTypeId($typeIds[1]);
        $subject->addTypeId($typeIds[2]);

        $actual = $subject->typeIds();

        foreach ($actual as $typeId) {
            self::assertContains($typeId, $actual);
        }
    }
}

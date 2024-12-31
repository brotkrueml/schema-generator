<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Ids;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Ids::class)]
final class IdsTest extends TestCase
{
    #[Test]
    public function iterationOverPreviouslyAddedIds(): void
    {
        $ids = [
            new Id('schema:term1'),
            new Id('schema:term2'),
            new Id('schema:term3'),
        ];

        $subject = new Ids();
        $subject->addId($ids[0]);
        $subject->addId($ids[1]);
        $subject->addId($ids[2]);

        foreach ($subject as $id) {
            self::assertContains($id, $ids);
        }
    }
}

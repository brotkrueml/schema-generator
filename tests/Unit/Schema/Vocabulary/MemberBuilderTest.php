<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Vocabulary\MemberBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MemberBuilder::class)]
final class MemberBuilderTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForBuild')]
    public function build(array $term, array $expected): void
    {
        $subject = new MemberBuilder();

        $actual = $subject->build($term);

        self::assertSame($expected['id'], $actual->id()->id());
        self::assertSame($expected['comment'], $actual->comment()->text());

        foreach ($actual->typeIds() as $typeId) {
            self::assertContains($typeId->id(), $expected['typeIds']);
        }
    }

    public static function providerForBuild(): iterable
    {
        yield 'with one type' => [
            'term' => [
                '@id' => 'schema:RsvpResponseYes',
                '@type' => 'schema:RsvpResponseType',
                'rdfs:comment' => 'The invitee will attend.',
                'rdfs:label' => 'RsvpResponseYes',
            ],
            'expected' => [
                'id' => 'schema:RsvpResponseYes',
                'comment' => 'The invitee will attend.',
                'typeIds' => [
                    'schema:RsvpResponseType',
                ],
            ],
        ];

        yield 'with two types' => [
            'term' => [
                '@id' => 'schema:Radiography',
                '@type' => [
                    'schema:MedicalImagingTechnique',
                    'schema:MedicalSpecialty',
                ],
                'rdfs:comment' => 'Radiography is an imaging technique that uses electromagnetic radiation other than visible light, especially X-rays, to view the internal structure of a non-uniformly composed and opaque object such as the human body.',
                'rdfs:label' => 'Radiography',
                'schema:isPartOf' => [
                    '@id' => 'https://health-lifesci.schema.org',
                ],
            ],
            'expected' => [
                'id' => 'schema:Radiography',
                'comment' => 'Radiography is an imaging technique that uses electromagnetic radiation other than visible light, especially X-rays, to view the internal structure of a non-uniformly composed and opaque object such as the human body.',
                'typeIds' => [
                    'schema:MedicalImagingTechnique',
                    'schema:MedicalSpecialty',
                ],
            ],
        ];
    }
}

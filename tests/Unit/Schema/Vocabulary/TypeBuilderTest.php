<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidTermException;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\PropertyBuilder;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\TypeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(TypeBuilder::class)]
final class TypeBuilderTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForBuild')]
    public function build(array $term, array $expected): void
    {
        $subject = new TypeBuilder(new NullLogger());

        $actual = $subject->build($term);

        self::assertSame($expected['id'], $actual->id()->id());
        self::assertSame($expected['comment'], $actual->comment()->text());
        self::assertSame($expected['section'], $actual->section());

        foreach ($actual->parentIds() as $parentId) {
            self::assertContains($parentId->id(), $expected['parentIds']);
        }
    }

    public static function providerForBuild(): iterable
    {
        yield 'with core section' => [
            'term' => [
                '@id' => 'schema:Person',
                '@type' => 'rdfs:Class',
                'owl:equivalentClass' => [
                    '@id' => 'foaf:Person',
                ],
                'rdfs:comment' => 'A person (alive, dead, undead, or fictional).',
                'rdfs:label' => 'Person',
                'rdfs:subClassOf' => [
                    '@id' => 'schema:Thing',
                ],
                'schema=>contributor' => [
                    '@id' => 'https://schema.org/docs/collab/rNews',
                ],
            ],
            'expected' => [
                'id' => 'schema:Person',
                'comment' => 'A person (alive, dead, undead, or fictional).',
                'section' => Section::Core,
                'parentIds' => [
                    'schema:Thing',
                ],
            ],
        ];

        yield 'with auto section' => [
            'term' => [
                '@id' => 'schema:BusOrCoach',
                '@type' => 'rdfs:Class',
                'rdfs:comment' => 'A bus (also omnibus or autobus) is a road vehicle designed to carry passengers. Coaches are luxury buses, usually in service for long distance travel.',
                'rdfs:label' => 'BusOrCoach',
                'rdfs:subClassOf' => [
                    '@id' => 'schema:Vehicle',
                ],
                'schema:contributor' => [
                    '@id' => 'https://schema.org/docs/collab/Automotive_Ontology_Working_Group',
                ],
                'schema:isPartOf' => [
                    '@id' => 'https://auto.schema.org',
                ],
            ],
            'expected' => [
                'id' => 'schema:BusOrCoach',
                'comment' => 'A bus (also omnibus or autobus) is a road vehicle designed to carry passengers. Coaches are luxury buses, usually in service for long distance travel.',
                'section' => Section::Auto,
                'parentIds' => [
                    'schema:Vehicle',
                ],
            ],
        ];

        yield 'with multiple parents' => [
            'term' => [
                '@id' => 'schema:LocalBusiness',
                '@type' => 'rdfs:Class',
                'rdfs:comment' => 'A particular physical business or branch of an organization.',
                'rdfs:label' => 'LocalBusiness',
                'rdfs:subClassOf' => [
                    [
                        '@id' => 'schema:Place',
                    ],
                    [
                        '@id' => 'schema:Organization',
                    ],
                ],
            ],
            'expected' => [
                'id' => 'schema:LocalBusiness',
                'comment' => 'A particular physical business or branch of an organization.',
                'section' => Section::Core,
                'parentIds' => [
                    'schema:Place',
                    'schema:Organization',
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForBuildThrowsExceptionOnInvalidTerm')]
    public function buildThrowsExceptionOnInvalidTerm(array $term): void
    {
        $this->expectException(InvalidTermException::class);
        $this->expectExceptionCode(1735467574);

        new PropertyBuilder()->build($term);
    }

    public static function providerForBuildThrowsExceptionOnInvalidTerm(): iterable
    {
        yield 'missing @id' => [
            'term' => [
                '@type' => 'rdfs:Class',
                'rdfs:comment' => 'The most generic type of item.',
                'rdfs:label' => 'Thing',
            ],
        ];

        yield 'missing rdfs:comment' => [
            'term' => [
                '@id' => 'schema:Thing',
                '@type' => 'rdfs:Class',
                'rdfs:label' => 'Thing',
            ],
        ];

        yield 'missing @id in parent' => [
            'term' => [
                '@id' => 'schema:Person',
                '@type' => 'rdfs:Class',
                'rdfs:comment' => 'A person (alive, dead, undead, or fictional).',
                'rdfs:label' => 'Person',
                'rdfs:subClassOf' => [
                    'id' => 'schema:Thing',
                ],
            ],
        ];
    }
}

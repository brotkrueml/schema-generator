<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Builder;

use Brotkrueml\SchemaGenerator\Builder\TypeBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TypeBuilderTest extends TestCase
{
    private TypeBuilder $subject;

    protected function setUp(): void
    {
        $this->subject = new TypeBuilder();
    }

    #[Test]
    #[DataProvider('dataProvider')]
    public function termIsConvertedCorrectlyToTypeModel(array $term, array $expected): void
    {
        $actual = $this->subject->build($term);

        self::assertSame($expected['id'], $actual->getId());
        self::assertSame($expected['comment'], $actual->getComment());
        self::assertSame($expected['subClassOf'], $actual->getParentIds());
        self::assertSame($expected['isPartOf'], $actual->getExtension()->getUri());
    }

    public static function dataProvider(): \Generator
    {
        yield 'With root element' => [
            'term' => [
                '@id' => 'schema:Thing',
                '@type' => 'rdfs:Class',
                'rdfs:comment' => 'The most generic type of item.',
                'rdfs:label' => 'Thing',
            ],
            'expected' => [
                'id' => 'Thing',
                'comment' => 'The most generic type of item.',
                'subClassOf' => [],
                'isPartOf' => '',
            ],
        ];

        yield 'With one subClassOf and no isPartOf' => [
            'term' => [
                '@id' => 'schema:CreativeWork',
                '@type' => 'rdfs:Class',
                'rdfs:comment' => 'The most generic kind of creative work, including books, movies, photographs, software programs, etc.',
                'rdfs:label' => 'CreativeWork',
                'rdfs:subClassOf' => [
                    '@id' => 'schema:Thing',
                ],
                'schema:source' => [
                    '@id' => 'http://www.w3.org/wiki/WebSchemas/SchemaDotOrgSources#source_rNews',
                ],
            ],
            'expected' => [
                'id' => 'CreativeWork',
                'comment' => 'The most generic kind of creative work, including books, movies, photographs, software programs, etc.',
                'subClassOf' => ['Thing'],
                'isPartOf' => '',
            ],
        ];

        yield 'With a localised array as comment' => [
            'term' => [
                '@id' => 'schema:ArchiveComponent',
                '@type' => 'rdfs:Class',
                'rdfs:comment' => [
                    '@language' => 'en',
                    '@value' => 'An intangible type to be applied to any archive content, carrying with it a set of properties required to describe archival items and collections.',
                ],
                'rdfs:label' => [
                    '@language' => 'en',
                    '@value' => 'ArchiveComponent',
                ],
                'rdfs:subClassOf' => [
                    '@id' => 'schema:CreativeWork',
                ],
                'schema:isPartOf' => [
                    '@id' => 'https://pending.schema.org',
                ],
                'schema:source' => [
                    '@id' => 'https://github.com/schemaorg/schemaorg/issues/1758',
                ],
            ],
            'expected' => [
                'id' => 'ArchiveComponent',
                'comment' => 'An intangible type to be applied to any archive content, carrying with it a set of properties required to describe archival items and collections.',
                'subClassOf' => ['CreativeWork'],
                'isPartOf' => 'https://pending.schema.org',
            ],
        ];
    }
}

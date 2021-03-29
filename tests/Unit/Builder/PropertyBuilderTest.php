<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Builder;

use Brotkrueml\SchemaGenerator\Builder\PropertyBuilder;
use PHPUnit\Framework\TestCase;

class PropertyBuilderTest extends TestCase
{
    private PropertyBuilder $subject;

    protected function setUp(): void
    {
        $this->subject = new PropertyBuilder();
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function termIsConvertedCorrectlyToTypeModel(array $term, array $expected): void
    {
        $actual = $this->subject->build($term);

        self::assertSame($expected['id'], $actual->getId());
        self::assertSame($expected['types'], $actual->getTypes());
        self::assertSame($expected['extensionUri'], $actual->getExtensionUri());
    }

    public function dataProvider(): \Generator
    {
        yield 'in core extension' => [
            'term' => [
                '@id' => 'schema:dateModified',
                '@type' => 'rdf:Property',
                'rdfs:comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
                'rdfs:label' => 'dateModified',
                'schema:domainIncludes' => [
                    ['@id' => 'schema:DataFeedItem'],
                    ['@id' => 'schema:CreativeWork'],
                ],
            ],
            'expected' => [
                'id' => 'dateModified',
                'types' => ['DataFeedItem', 'CreativeWork'],
                'extensionUri' => '',
            ],
        ];

        yield 'in auto extension' => [
            'term' => [
                '@id' => 'schema:bodyType',
                '@type' => 'rdf:Property',
                'rdfs:comment' => 'Indicates the design and body style of the vehicle (e.g. station wagon, hatchback, etc.).',
                'rdfs:label' => 'bodyType',
                'schema:domainIncludes' => ['@id' => 'schema:Vehicle'],
                'schema:isPartOf' => ['@id' => 'https://auto.schema.org'],
            ],
            'expected' => [
                'id' => 'bodyType',
                'types' => ['Vehicle'],
                'extensionUri' => 'https://auto.schema.org',
            ],
        ];
    }
}

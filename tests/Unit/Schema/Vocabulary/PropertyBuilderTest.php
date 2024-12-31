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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PropertyBuilder::class)]
final class PropertyBuilderTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForBuild')]
    public function build(array $term, array $expected): void
    {
        $subject = new PropertyBuilder();

        $actual = $subject->build($term);

        self::assertSame($expected['id'], $actual->id()->id());
        self::assertSame($expected['comment'], $actual->comment()->text());
        self::assertSame($expected['section'], $actual->section());

        foreach ($actual->typeIds() as $typeId) {
            self::assertContains($typeId->id(), $expected['typeIds']);
        }
    }

    public static function providerForBuild(): iterable
    {
        yield 'with core section' => [
            'term' => [
                '@id' => 'schema:name',
                '@type' => 'rdf:Property',
                'owl:equivalentProperty' => [
                    '@id' => 'dcterms:title',
                ],
                'rdfs:comment' => 'The name of the item.',
                'rdfs:label' => 'name',
                'rdfs:subPropertyOf' => [
                    '@id' => 'rdfs:label',
                ],
                'schema:domainIncludes' => [
                    '@id' => 'schema:Thing',
                ],
                'schema:rangeIncludes' => [
                    '@id' => 'schema:Text',
                ],
            ],
            'expected' => [
                'id' => 'schema:name',
                'comment' => 'The name of the item.',
                'section' => Section::Core,
                'typeIds' => [
                    'schema:Thing',
                ],
            ],
        ];

        yield 'with auto section' => [
            'term' => [
                '@id' => 'schema:emissionsCO2',
                '@type' => 'rdf:Property',
                'rdfs:comment' => 'The CO2 emissions in g/km. When used in combination with a QuantitativeValue, put "g/km" into the unitText property of that value, since there is no UN/CEFACT Common Code for "g/km".',
                'rdfs:label' => 'emissionsCO2',
                'schema:contributor' => [
                    '@id' => 'https://schema.org/docs/collab/Automotive_Ontology_Working_Group',
                ],
                'schema:domainIncludes' => [
                    '@id' => 'schema:Vehicle',
                ],
                'schema:isPartOf' => [
                    '@id' => 'https://auto.schema.org',
                ],
                'schema:rangeIncludes' => [
                    '@id' => 'schema:Number',
                ],
            ],
            'expected' => [
                'id' => 'schema:emissionsCO2',
                'comment' => 'The CO2 emissions in g/km. When used in combination with a QuantitativeValue, put "g/km" into the unitText property of that value, since there is no UN/CEFACT Common Code for "g/km".',
                'section' => Section::Auto,
                'typeIds' => [
                    'schema:Vehicle',
                ],
            ],
        ];

        yield 'with multiple types' => [
            'term' => [
                '@id' => 'schema:contactPoint',
                '@type' => 'rdf:Property',
                'rdfs:comment' => 'A contact point for a person or organization.',
                'rdfs:label' => 'contactPoint',
                'schema:domainIncludes' => [
                    [
                        '@id' => 'schema:Person',
                    ],
                    [
                        '@id' => 'schema:Organization',
                    ],
                    [
                        '@id' => 'schema:HealthInsurancePlan',
                    ],
                ],
                'schema:rangeIncludes' => [
                    '@id' => 'schema:ContactPoint',
                ],
            ],
            'expected' => [
                'id' => 'schema:contactPoint',
                'comment' => 'A contact point for a person or organization.',
                'section' => Section::Core,
                'typeIds' => [
                    'schema:Person',
                    'schema:Organization',
                    'schema:HealthInsurancePlan',
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
                'rdfs:comment' => 'A contact point for a person or organization.',
                'schema:domainIncludes' => [
                    [
                        '@id' => 'schema:Person',
                    ],
                    [
                        '@id' => 'schema:Organization',
                    ],
                    [
                        '@id' => 'schema:HealthInsurancePlan',
                    ],
                ],
            ],
        ];

        yield 'missing rdfs:comment' => [
            'term' => [
                '@id' => 'schema:contactPoint',
                'schema:domainIncludes' => [
                    [
                        '@id' => 'schema:Person',
                    ],
                    [
                        '@id' => 'schema:Organization',
                    ],
                    [
                        '@id' => 'schema:HealthInsurancePlan',
                    ],
                ],
            ],
        ];

        yield 'missing schema:domainIncludes' => [
            'term' => [
                '@id' => 'schema:contactPoint',
                'rdfs:comment' => 'A contact point for a person or organization.',
            ],
        ];
    }
}

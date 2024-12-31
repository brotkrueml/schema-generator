<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidTermException;
use Brotkrueml\SchemaGenerator\Schema\Section;

final readonly class PropertyBuilder
{
    /**
     * @param array<string, string|array> $term
     */
    public function build(array $term): Property
    {
        $section = Section::fromUri($term['schema:isPartOf']['@id'] ?? '');

        $property = new Property(
            new Id($term['@id'] ?? throw InvalidTermException::fromKey('@id', $term)),
            new Comment($term['rdfs:comment'] ?? throw InvalidTermException::fromKey('rdfs:comment', $term)),
            $section,
        );

        $typeIds = $term['schema:domainIncludes'] ?? throw InvalidTermException::fromKey('schema:domainIncludes', $term);
        if (! \array_is_list($typeIds)) {
            $typeIds = [$typeIds];
        }

        foreach ($typeIds as $typeId) {
            $property->addTypeId(new Id($typeId['@id'] ?? throw InvalidTermException::fromKey('schema:domainIncludes|@id', $term)));
        }

        return $property;
    }
}

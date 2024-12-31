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

final readonly class TypeBuilder
{
    /**
     * @param array<string, string|array> $term
     */
    public function build(array $term): Type
    {
        $section = Section::fromUri($term['schema:isPartOf']['@id'] ?? '');

        $type = new Type(
            new Id($term['@id'] ?? throw InvalidTermException::fromKey('@id', $term)),
            new Comment($term['rdfs:comment'] ?? throw InvalidTermException::fromKey('rdfs:comment', $term)),
            $section,
        );

        $parents = $term['rdfs:subClassOf'] ?? [];
        if (! \array_is_list($parents)) {
            $parents = [$parents];
        }

        foreach ($parents as $parent) {
            $type->addParentId(new Id($parent['@id'] ?? throw InvalidTermException::fromKey('schema:domainIncludes|@id', $term)));
        }

        return $type;
    }
}

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

final readonly class MemberBuilder
{
    /**
     * @param array<string, string|array> $term
     */
    public function build(array $term): Member
    {
        $member = new Member(
            new Id($term['@id'] ?? throw InvalidTermException::fromKey('@id', $term)),
            new Comment($term['rdfs:comment'] ?? throw InvalidTermException::fromKey('rdfs:comment', $term)),
        );

        $termTypes = $term['@type'];
        if (\is_string($termTypes)) {
            $termTypes = [$termTypes];
        }

        foreach ($termTypes as $termType) {
            $member->addTypeId(new Id($termType));
        }

        return $member;
    }
}

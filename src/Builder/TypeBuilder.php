<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Builder;

use Brotkrueml\SchemaGenerator\AvailableExtensions;
use Brotkrueml\SchemaGenerator\Dto\Type;
use Brotkrueml\SchemaGenerator\Enumerations\Attributes;

final class TypeBuilder
{
    private AvailableExtensions $availableExtensions;
    private Normaliser $normaliser;

    public function __construct()
    {
        $this->availableExtensions = new AvailableExtensions();
        $this->normaliser = new Normaliser();
    }

    public function build(array $term): Type
    {
        $subClassOf = [];
        if ($term[Attributes::SUBCLASS_OF] ?? false) {
            $subClassOf = $this->normaliser->normaliseIdFromClasses($term[Attributes::SUBCLASS_OF]);
        }

        $isPartOf = '';
        if ($term[Attributes::IS_PART_OF] ?? false) {
            $isPartOf = $term[Attributes::IS_PART_OF][Attributes::ID];
        }

        // Special case: class must not begin with a number
        $id = $this->normaliser->normaliseId($term[Attributes::ID]);
        if (\preg_match('/^[0-9].*/', $id, $matches)) {
            $id = '_' . $matches[0];
        }

        return new Type(
            $id,
            $this->normaliser->normaliseComment($term[Attributes::COMMENT]),
            $subClassOf,
            $this->availableExtensions->getExtensionByUri($isPartOf),
        );
    }
}

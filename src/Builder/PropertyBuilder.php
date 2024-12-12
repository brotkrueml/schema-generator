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
use Brotkrueml\SchemaGenerator\Dto\Property;
use Brotkrueml\SchemaGenerator\Enumerations\Attributes;

final readonly class PropertyBuilder
{
    private AvailableExtensions $availableExtensions;
    private Normaliser $normaliser;

    public function __construct()
    {
        $this->availableExtensions = new AvailableExtensions();
        $this->normaliser = new Normaliser();
    }

    public function build(array $term): Property
    {
        $domainIncludes = [];
        if ($term[Attributes::DOMAIN_INCLUDES] ?? false) {
            $domainIncludes = $this->normaliser->normaliseIdFromClasses($term[Attributes::DOMAIN_INCLUDES]);
        }

        $isPartOf = '';
        if ($term[Attributes::IS_PART_OF] ?? false) {
            $isPartOf = $term[Attributes::IS_PART_OF]['@id'];
        }

        return new Property(
            $this->normaliser->normaliseId($term[Attributes::ID]),
            $domainIncludes,
            $this->availableExtensions->getExtensionByUri($isPartOf),
        );
    }
}

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
use Brotkrueml\SchemaGenerator\Enumerations\Attribute;

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
        if ($term[Attribute::DomainIncludes->value] ?? false) {
            $domainIncludes = $this->normaliser->normaliseIdFromClasses($term[Attribute::DomainIncludes->value]);
        }

        $isPartOf = '';
        if ($term[Attribute::IsPartOf->value] ?? false) {
            $isPartOf = $term[Attribute::IsPartOf->value]['@id'];
        }

        return new Property(
            $this->normaliser->normaliseId($term[Attribute::Id->value]),
            $domainIncludes,
            $this->availableExtensions->getExtensionByUri($isPartOf),
        );
    }
}

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
use Brotkrueml\SchemaGenerator\Enumerations\Attribute;

final readonly class TypeBuilder
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
        if ($term[Attribute::SubClassOf->value] ?? false) {
            $subClassOf = $this->normaliser->normaliseIdFromClasses($term[Attribute::SubClassOf->value]);
        }

        $isPartOf = '';
        if ($term[Attribute::IsPartOf->value] ?? false) {
            $isPartOf = $term[Attribute::IsPartOf->value][Attribute::Id->value];
        }

        $id = $this->normaliser->normaliseId($term[Attribute::Id->value]);

        $className = $id;
        if (\preg_match('/^[0-9].*/', $className, $matches)) {
            // Special case: class must not begin with a number
            $className = '_' . $matches[0];
        }

        return new Type(
            $id,
            $this->normaliser->normaliseComment($term[Attribute::Comment->value]),
            $className,
            $subClassOf,
            $this->availableExtensions->getExtensionByUri($isPartOf),
        );
    }
}

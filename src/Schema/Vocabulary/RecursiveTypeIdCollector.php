<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\TypeNotFoundException;

final readonly class RecursiveTypeIdCollector
{
    /**
     * @return list<Id>
     */
    public function collect(Types $types, Type $type): array
    {
        $collectedTypeIds = [];

        return $this->collectTypes($types, $type, $collectedTypeIds);
    }

    private function collectTypes(Types $types, Type $type, array $collectedTypeIds): array
    {
        $collectedTypeIds[] = $type->id();
        foreach ($type->parentIds() as $parentId) {
            try {
                $parentType = $types->findTypeById($parentId);
                $collectedTypeIds = $this->collectTypes($types, $parentType, $collectedTypeIds);
            } catch (TypeNotFoundException) {
            }
        }

        return $collectedTypeIds;
    }
}

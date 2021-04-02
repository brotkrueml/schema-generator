<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Dto;

final class AdditionalProperties
{
    private array $terms = [];

    public function addPropertiesToType(Type $type, Property ...$properties): void
    {
        if (!isset($this->terms[$type->getId()])) {
            $this->terms[$type->getId()] = [
                'type' => $type,
                'properties' => [],
            ];
        }

        foreach ($properties as $property) {
            if (!\in_array($property->getId(), $this->terms[$type->getId()]['properties'], true)) {
                $this->terms[$type->getId()]['properties'][] = $property->getId();
            }
        }
    }

    public function getTerms(): array
    {
        \ksort($this->terms);
        foreach ($this->terms as &$term) {
            \sort($term['properties']);
        }

        return \array_values($this->terms);
    }
}

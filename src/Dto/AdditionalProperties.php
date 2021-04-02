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
    /**
     * @var array<string, array<string, array<string>>
     */
    private array $terms = [];

    public function __construct(private Extension $extension)
    {
    }

    public function addPropertiesToType(Type $type, Property ...$properties): void
    {
        $extension = $type->getExtension()->getExtension();
        if ($this->extension->getExtension() === $extension) {
            $extension = '';
        }

        if (!isset($this->terms[$extension][$type->getId()])) {
            $this->terms[$extension][$type->getId()] = [];
        }

        foreach ($properties as $property) {
            if (!\in_array($property->getId(), $this->terms[$extension][$type->getId()], true)) {
                $this->terms[$extension][$type->getId()][] = $property->getId();
            }
        }
    }

    public function getTerms(): array
    {
        \ksort($this->terms);
        foreach ($this->terms as &$types) {
            \ksort($types);
            foreach ($types as &$type) {
                \sort($type);
            }
        }

        return $this->terms;
    }
}

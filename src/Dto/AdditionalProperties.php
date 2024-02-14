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

    public function __construct(
        private Extension $extension,
    ) {}

    public function addPropertiesToType(Type $type, Property ...$properties): void
    {
        $extensionName = $type->getExtension()->getName();
        if ($this->extension->getName() === $extensionName) {
            $extensionName = '';
        }

        if (! isset($this->terms[$extensionName][$type->getClassName()])) {
            $this->terms[$extensionName][$type->getClassName()] = [];
        }

        foreach ($properties as $property) {
            if (! \in_array($property->getId(), $this->terms[$extensionName][$type->getClassName()], true)) {
                $this->terms[$extensionName][$type->getClassName()][] = $property->getId();
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

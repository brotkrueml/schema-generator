<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Dto;

final class Property
{
    public function __construct(
        private string $id,
        private array $types,
        private Extension $extension,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getExtension(): Extension
    {
        return $this->extension;
    }
}

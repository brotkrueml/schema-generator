<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Storage;

use Brotkrueml\SchemaGenerator\Dto\Type;

final class TypeStorage
{
    private array $types = [];

    public function add(Type $type): void
    {
        $this->types[] = $type;
    }

    public function getAllByExtension(string $extension): array
    {
        return \array_filter($this->types, static fn (Type $type): bool => $type->getExtensionUri() === $extension);
    }
}

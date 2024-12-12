<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Dto;

final readonly class Property
{
    public function __construct(
        public string $id,
        public array $types,
        public Extension $extension,
    ) {}
}

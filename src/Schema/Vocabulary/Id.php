<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidIdException;

final readonly class Id
{
    private string $namespace;
    private string $label;

    public function __construct(string $id)
    {
        $parts = \explode(':', $id);
        if (\strlen($parts[0]) === 0) {
            throw InvalidIdException::fromEmptyNamespace($id);
        }
        if ($parts[0] !== 'schema') {
            throw InvalidIdException::fromInvalidNamespace($id);
        }
        if (\strlen($parts[1] ?? '') === 0) {
            throw InvalidIdException::fromMissingLabel($id);
        }

        $this->namespace = $parts[0];
        $this->label = $parts[1];
    }

    public function id(): string
    {
        return \sprintf('%s:%s', $this->namespace, $this->label);
    }

    public function label(): string
    {
        return $this->label;
    }
}

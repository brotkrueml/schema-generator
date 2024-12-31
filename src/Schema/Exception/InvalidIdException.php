<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Exception;

final class InvalidIdException extends \DomainException
{
    public static function fromEmptyNamespace(string $id): self
    {
        return new self(
            \sprintf(
                'Empty namespace for id given: %s',
                $id,
            ),
            1735239834,
        );
    }

    public static function fromInvalidNamespace(string $id): self
    {
        return new self(
            \sprintf(
                'Invalid namespace for id given (must be "schema"): %s',
                $id,
            ),
            1735239835,
        );
    }

    public static function fromMissingLabel(string $id): self
    {
        return new self(
            \sprintf(
                'Label for id is missing: %s',
                $id,
            ),
            1735239836,
        );
    }
}

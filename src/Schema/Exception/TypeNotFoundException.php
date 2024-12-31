<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Exception;

use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;

final class TypeNotFoundException extends \DomainException
{
    public static function typeIdNotFound(Id $typeId): self
    {
        return new self(
            \sprintf(
                'No type with id "%s" found',
                $typeId->id(),
            ),
            1735290356,
        );
    }
}

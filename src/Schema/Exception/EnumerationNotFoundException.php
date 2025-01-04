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

final class EnumerationNotFoundException extends \DomainException
{
    public static function enumerationIdNotFound(Id $enumerationId): self
    {
        return new self(
            \sprintf(
                'No enumeration with id "%s" found!',
                $enumerationId->id(),
            ),
            1735895580,
        );
    }
}

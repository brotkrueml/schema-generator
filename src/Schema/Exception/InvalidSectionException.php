<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Exception;

final class InvalidSectionException extends \DomainException
{
    public static function fromShortName(string $shortName): self
    {
        return new self(
            \sprintf(
                'Short name "%s" is invalid!',
                $shortName,
            ),
            1735242208,
        );
    }

    public static function fromUri(string $uri): self
    {
        return new self(
            \sprintf(
                'Uri "%s" is invalid!',
                $uri,
            ),
            1735242209,
        );
    }
}

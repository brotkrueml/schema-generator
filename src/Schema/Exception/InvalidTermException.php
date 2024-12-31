<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Exception;

final class InvalidTermException extends \DomainException
{
    /**
     * @param array<string, string, array> $term
     */
    public static function fromKey(string $key, array $term): self
    {
        return new self(
            \sprintf(
                'Term is invalid, mandatory key "%s" is missing: %s',
                $key,
                \json_encode($term, \JSON_THROW_ON_ERROR),
            ),
            1735467574,
        );
    }
}

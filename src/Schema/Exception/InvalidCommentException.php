<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Exception;

final class InvalidCommentException extends \DomainException
{
    public static function fromMissingValueKey(array $comment): self
    {
        return new self(
            \sprintf(
                'Given comment does not have a @value key: %s',
                \json_encode($comment, \JSON_THROW_ON_ERROR),
            ),
            1735463278,
        );
    }
}

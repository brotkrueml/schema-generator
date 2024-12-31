<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Manual;

final readonly class Manual
{
    public function __construct(
        private string $type,
        private Publisher $publisher,
        private string $link,
    ) {}

    public function type(): string
    {
        return $this->type;
    }

    public function publisher(): Publisher
    {
        return $this->publisher;
    }

    public function link(): string
    {
        return $this->link;
    }
}

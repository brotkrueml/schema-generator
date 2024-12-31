<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Manual;

final readonly class ManualsBuilder
{
    public function __construct(
        private string $manualsFile,
    ) {}

    public function build(): Manuals
    {
        $manuals = new Manuals();

        /** @var array<string, array{publisher: string, link: string}> */
        $manualsWithType = require $this->manualsFile;

        foreach ($manualsWithType as $type => $configurations) {
            foreach ($configurations as $configuration) {
                $manuals->addManual(new Manual($type, Publisher::{$configuration['publisher']}, $configuration['link']));
            }
        }

        return $manuals;
    }
}

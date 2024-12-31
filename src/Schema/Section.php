<?php

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema;

use Brotkrueml\SchemaGenerator\Schema\Exception\InvalidSectionException;

enum Section
{
    case Auto;
    case Bib;
    case Core;
    case Health;
    case Pending;

    public static function fromShortName(string $shortName): self
    {
        return match ($shortName) {
            'auto' => self::Auto,
            'bib' => self::Bib,
            'core' => self::Core,
            'health' => self::Health,
            'pending' => self::Pending,
            default => throw InvalidSectionException::fromShortName($shortName),
        };
    }

    public function shortName(): string
    {
        return match ($this) {
            self::Auto => 'auto',
            self::Bib => 'bib',
            self::Core => 'core',
            self::Health => 'health',
            self::Pending => 'pending',
        };
    }

    public static function fromUri(string $uri): self
    {
        return match ($uri) {
            'https://auto.schema.org' => self::Auto,
            'https://bib.schema.org' => self::Bib,
            '' => self::Core,
            'https://health-lifesci.schema.org' => self::Health,
            'https://pending.schema.org' => self::Pending,
            default => throw InvalidSectionException::fromUri($uri),
        };
    }

    public function phpNamespace(): string
    {
        return match ($this) {
            self::Auto => 'Brotkrueml\SchemaAuto\\',
            self::Bib => 'Brotkrueml\SchemaBib\\',
            self::Core => 'Brotkrueml\Schema\\',
            self::Health => 'Brotkrueml\SchemaHealth\\',
            self::Pending => 'Brotkrueml\SchemaPending\\',
        };
    }
}

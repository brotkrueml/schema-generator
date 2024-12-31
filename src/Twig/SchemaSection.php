<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Twig;

use Brotkrueml\SchemaGenerator\Schema\Section;

final class SchemaSection
{
    public static function fromShortName(string $shortName): Section
    {
        return Section::fromShortName($shortName);
    }
}

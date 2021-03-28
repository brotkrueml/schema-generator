<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Enumerations;

final class Attributes
{
    public const COMMENT = 'rdfs:comment';
    public const DOMAIN_INCLUDES = 'schema:domainIncludes';
    public const ID = '@id';
    public const IS_PART_OF = 'schema:isPartOf';
    public const SUBCLASS_OF = 'rdfs:subClassOf';
    public const SUPERSEDED_BY = 'schema:supersededBy';
}

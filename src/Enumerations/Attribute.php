<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Enumerations;

enum Attribute: string
{
    case Comment = 'rdfs:comment';
    case DomainIncludes = 'schema:domainIncludes';
    case Id = '@id';
    case IsPartOf = 'schema:isPartOf';
    case SubClassOf = 'rdfs:subClassOf';
    case SupersededBy = 'schema:supersededBy';
}

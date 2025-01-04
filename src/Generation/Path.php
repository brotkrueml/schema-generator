<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Generation;

/**
 * Provides the relative path for storing the artifacts to be generated
 */
enum Path: string
{
    case AdditionalProperties = 'Classes/EventListener';
    case Enumeration = 'Classes/Model/Enumeration';
    case Model = 'Classes/Model/Type';
    case ViewHelper = 'Classes/ViewHelpers/Type';
}

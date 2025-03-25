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
 * Provides the template file names used for the artifacts to be generated
 */
enum Template: string
{
    case AdditionalProperties = 'AdditionalProperties.php.twig';
    case Enumeration = 'Enumeration.php.twig';
    case Model = 'Model.php.twig';
    case ViewHelper = 'ViewHelper.php.twig';
}

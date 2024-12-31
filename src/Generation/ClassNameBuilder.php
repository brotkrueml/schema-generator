<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Generation;

use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;

final readonly class ClassNameBuilder
{
    private const NUMBER_TO_STRING = [
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
    ];

    public function getClassNameForModelFromType(Type $type): string
    {
        return $this->getClassNameForModelFromLabel($type->id()->label());
    }

    public function getClassNameForModelFromLabel(string $className): string
    {
        if (\preg_match('/^[0-9]/', $className)) {
            $className = '_' . $className;
        }

        return $className;
    }

    public function getClassNameForViewHelperFromType(Type $type): string
    {
        $className = $type->id()->label() . 'ViewHelper';
        if (\preg_match('/^([0-9])(.+)$/', $className, $matches)) {
            $className = self::NUMBER_TO_STRING[$matches[1]] . $matches[2];
        }

        return $className;
    }
}

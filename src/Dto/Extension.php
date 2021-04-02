<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Dto;

use Brotkrueml\SchemaGenerator\Enumerations\Extensions;
use Brotkrueml\SchemaGenerator\Enumerations\Namespaces;

final class Extension implements \Stringable
{
    private string $extensionUri;
    private string $namespace;

    public function __construct(private string $extension)
    {
        $extensionConstant = Extensions::class . '::' . \strtoupper($extension);
        if (!\defined($extensionConstant)) {
            throw new \InvalidArgumentException(
                \sprintf('Extension "%s" is not defined', $extension),
                1617382155
            );
        }

        $namespaceConstant = Namespaces::class . '::' . \strtoupper($extension);
        if (!\defined($namespaceConstant)) {
            throw new \InvalidArgumentException(
                \sprintf('Namespace for extension "%s" is not defined', $extension),
                1617382156
            );
        }

        $this->extensionUri = \constant($extensionConstant);
        $this->namespace = \constant($namespaceConstant);
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getExtensionUri(): string
    {
        return $this->extensionUri;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function __toString()
    {
        return $this->extension;
    }
}

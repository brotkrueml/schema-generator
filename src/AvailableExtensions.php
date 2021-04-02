<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator;

use Brotkrueml\SchemaGenerator\Dto\Extension;
use Brotkrueml\SchemaGenerator\Enumerations\Extensions;

final class AvailableExtensions
{
    private array $extensions = [];

    public function __construct()
    {
        $extensionClass = new \ReflectionClass(Extensions::class);
        foreach (\array_keys($extensionClass->getConstants()) as $constant) {
            $this->extensions[] = new Extension(\strtolower($constant));
        }
    }

    public function getExtensionByUri(string $uri): Extension
    {
        $extensions = \array_filter(
            $this->extensions,
            static fn (Extension $extension): bool => $extension->getUri() === $uri
        );

        if (\count($extensions) === 0) {
            throw new \DomainException(
                \sprintf('Extension uri "%s" not available', $uri),
                1617384097
            );
        }

        return \array_pop($extensions);
    }

    public function getNamespaceByExtension(string $name): string
    {
        $extensions = \array_values(\array_filter(
            $this->extensions,
            static fn (Extension $extension): bool => $extension->getName() === $name
        ));

        if (\count($extensions) === 0) {
            throw new \DomainException(
                \sprintf('Extension name "%s" is not available', $name),
                1617384098
            );
        }

        return $extensions[0]->getNamespace();
    }
}

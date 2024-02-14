<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator;

use Brotkrueml\SchemaGenerator\Dto\AdditionalProperties;
use Brotkrueml\SchemaGenerator\Dto\Extension;
use Brotkrueml\SchemaGenerator\Dto\Property;
use Brotkrueml\SchemaGenerator\Dto\Type;

final class Generator
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

    private const CONFIG_PATH_MANUALS = __DIR__ . '/../config/Manuals.php';

    private const PATH_ADDITIONAL_PROPERTIES = 'Classes/EventListener';
    private const PATH_MODEL = 'Classes/Model/Type';
    private const PATH_VIEW_HELPERS = 'Classes/ViewHelpers/Type';

    private const ROOT_TYPE_ID = 'Thing';
    private const ROOT_WEBPAGE_TYPE_ID = 'WebPage';

    /**
     * @var array<string, Type>
     */
    private array $availableTypes = [];
    /**
     * @var string[]
     */
    private array $webPageTypeIds = [];

    /**
     * @var array<string, array>
     */
    private array $manuals = [];

    private AdditionalProperties $additionalProperties;

    private Extension $extension;
    private AvailableExtensions $availableExtensions;

    private string $additionalPropertiesPath;
    private string $modelPath;
    private string $viewHelpersPath;

    /**
     * @param array<string, Type> $types
     */
    public function __construct(
        private Writer $writer,
        private array $types,
        string $extension,
        private string $basePath,
    ) {
        $this->definePaths();
        $this->checkPaths();
        $this->removeOldFiles();

        $this->extension = new Extension($extension);
        $this->availableExtensions = new AvailableExtensions();
        $this->additionalProperties = new AdditionalProperties($this->extension);
        $this->webPageTypeIds = $this->identifySpecialTypes($this->types[self::ROOT_WEBPAGE_TYPE_ID]);
        $this->manuals = require self::CONFIG_PATH_MANUALS;
    }

    private function definePaths(): void
    {
        $this->additionalPropertiesPath = $this->basePath . '/' . self::PATH_ADDITIONAL_PROPERTIES;
        $this->modelPath = $this->basePath . '/' . self::PATH_MODEL;
        $this->viewHelpersPath = $this->basePath . '/' . self::PATH_VIEW_HELPERS;
    }

    private function checkPaths(): void
    {
        if (! \is_dir($this->modelPath)) {
            throw new \RuntimeException(
                \sprintf('Path "%s" does not exist', $this->modelPath),
                1616776865,
            );
        }

        if (! \is_dir($this->viewHelpersPath)) {
            throw new \RuntimeException(
                \sprintf('Path "%s" does not exist', $this->viewHelpersPath),
                1616776866,
            );
        }
    }

    private function removeOldFiles(): void
    {
        foreach (\glob($this->modelPath . '/*.php') as $filename) {
            @\unlink($filename);
        }

        foreach (\glob($this->viewHelpersPath . '/*.php') as $filename) {
            @\unlink($filename);
        }
    }

    private function identifySpecialTypes(Type $type): array
    {
        $specialTypesForExtension = \array_values(\array_filter(
            $this->collectSpecialTypes($type),
            fn(Type $type): bool => (string)$type->getExtension() === (string)$this->extension,
        ));
        $specialTypeIds = \array_map(
            static fn(Type $type): string => $type->getId(),
            $specialTypesForExtension,
        );
        \sort($specialTypeIds);

        return $specialTypeIds;
    }

    /**
     * @return Type[]
     */
    private function collectSpecialTypes(Type $type): array
    {
        $specialTypes = [$type];
        foreach ($type->getSubTypeIds() as $subTypeId) {
            $specialTypes = \array_merge($specialTypes, $this->collectSpecialTypes($this->types[$subTypeId]));
        }

        return $specialTypes;
    }

    public function generate(): void
    {
        $this->generateClasses(self::ROOT_TYPE_ID);
        $this->registerAdditionalProperties();
    }

    private function generateClasses(string $typeId): void
    {
        $type = $this->types[$typeId] ?? null;
        if ($type === null) {
            echo \sprintf('Type id "%s" not available!' . "\n", $typeId);
            return;
        }

        $properties = $this->collectProperties($type);

        if ((string)$type->getExtension() === (string)$this->extension) {
            $this->generateModelClass($typeId, $properties);
            $this->generateViewHelperClass($typeId);
            $this->addTypeToAvailableTypes($typeId);
        } elseif ($this->extension->getName() !== 'core') {
            $this->addAdditionalProperties($type, $properties);
        }

        foreach ($type->getSubTypeIds() as $subTypeId) {
            if (\in_array($subTypeId, $this->availableTypes, true)) {
                continue;
            }

            $this->generateClasses($subTypeId);
        }
    }

    /**
     * @return array<Property>
     */
    private function collectProperties(Type $type): array
    {
        $properties = \array_values(\array_filter(
            $type->getProperties(),
            fn(Property $property): bool => \in_array($property->getExtension()->getUri(), ['', $this->extension->getUri()], true),
        ));

        foreach ($type->getParentIds() as $parentTypeId) {
            $properties = \array_merge(
                $properties,
                $this->collectProperties($this->types[$parentTypeId]),
            );
        }

        return $properties;
    }

    private function generateModelClass(string $typeId, array $properties): void
    {
        $propertyIds = \array_map(
            static fn(Property $property): string => $property->getId(),
            $properties,
        );
        $propertyIds = \array_unique($propertyIds);
        \sort($propertyIds);

        $context = [
            'comment' => $this->types[$typeId]->getComment(),
            'manuals' => $this->manuals[$typeId] ?? [],
            'className' => $this->types[$typeId]->getClassName(),
            'isWebPageType' => \in_array($typeId, $this->webPageTypeIds, true),
            'namespace' => $this->extension->getNamespace(),
            'properties' => $propertyIds,
            'type' => $typeId,
        ];

        $this->writer->write($this->modelPath, Writer::TEMPLATE_MODEL, $context);
    }

    private function generateViewHelperClass(string $typeId): void
    {
        $className = $this->types[$typeId]->getClassName() . 'ViewHelper';
        if (\preg_match('/^_([0-9])(.*)$/', $className, $matches)) {
            // Special case: class must not begin with an underscore and a number (view helper is then not recognised)
            $className = self::NUMBER_TO_STRING[$matches[1]] . $matches[2];
        }

        $context = [
            'comment' => $this->types[$typeId]->getComment(),
            'className' => $className,
            'namespace' => $this->extension->getNamespace(),
            'type' => $typeId,
        ];

        $this->writer->write($this->viewHelpersPath, Writer::TEMPLATE_VIEWHELPER, $context);
    }

    private function addTypeToAvailableTypes(string $typeId): void
    {
        $this->availableTypes[] = $this->types[$typeId]->getId();
    }

    private function addAdditionalProperties(Type $type, array $properties): void
    {
        $propertiesForExtension = \array_values(\array_filter(
            $properties,
            fn(Property $property): bool => (string)$property->getExtension() === (string)$this->extension,
        ));

        if (\count($propertiesForExtension) > 0) {
            $this->additionalProperties->addPropertiesToType($type, ...$propertiesForExtension);
        }
    }

    private function registerAdditionalProperties(): void
    {
        if ($this->extension->getName() === 'core') {
            return;
        }

        $context = [
            'availableExtensions' => $this->availableExtensions,
            'namespace' => $this->extension->getNamespace(),
            'additionalProperties' => $this->additionalProperties,
        ];

        $this->writer->write($this->additionalPropertiesPath, Writer::TEMPLATE_ADDITIONAL_PROPERTIES, $context);
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator;

use Twig\Environment;

final class Writer
{
    public const TEMPLATE_ADDITIONAL_PROPERTIES = 'RegisterAdditionalProperties';
    public const TEMPLATE_MODEL = 'Model';
    public const TEMPLATE_VIEWHELPER = 'ViewHelper';

    public function __construct(
        private Environment $twig,
    ) {}

    public function write(string $path, string $template, array $context): void
    {
        $templateFile = $template . '.php.twig';
        $content = $this->twig->render($templateFile, $context);

        $fullPath = \sprintf(
            '%s/%s.php',
            \rtrim($path, '/'),
            $context['className'] ?? $template,
        );

        \file_put_contents($fullPath, $content);
    }
}

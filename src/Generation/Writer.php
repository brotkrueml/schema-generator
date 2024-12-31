<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Generation;

use Twig\Environment;

final readonly class Writer
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function write(string $destinationPath, Template $template, array $context): void
    {
        $content = $this->twig->render($template->value, $context);

        $fullPath = \sprintf(
            '%s/%s.php',
            \rtrim($destinationPath, '/'),
            $context['className'],
        );

        \file_put_contents($fullPath, $content);
    }
}

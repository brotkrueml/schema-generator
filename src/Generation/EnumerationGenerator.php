<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Generation;

use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Enumerations;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Member;

final readonly class EnumerationGenerator
{
    public function __construct(
        private Writer $writer,
    ) {}

    public function generate(Section $section, Enumerations $enumerations, string $basePath): void
    {
        $this->removeOldFiles($basePath);
        $this->generateClasses($section, $enumerations, $basePath);
    }

    private function removeOldFiles(string $basePath)
    {
        foreach (\glob($this->getAbsolutePath($basePath) . '/*.php') as $filename) {
            @\unlink($filename);
        }
    }

    private function getAbsolutePath(string $basePath): string
    {
        return $basePath . '/' . Path::Enumeration->value;
    }

    private function generateClasses(Section $section, Enumerations $enumerations, string $basePath)
    {
        foreach ($enumerations as $enumeration) {
            if ($enumeration->section() !== $section) {
                continue;
            }

            $members = [];
            foreach ($enumeration->members() as $member) {
                $members[] = $member;
            }
            \usort($members, static fn(Member $a, Member $b): int => $a->id()->label() <=> $b->id()->label());

            $context = [
                'className' => $enumeration->id()->label(),
                'enumeration' => $enumeration,
                'members' => $members,
                'namespace' => $section->phpNamespace(),
            ];

            if ($members !== []) {
                $this->writer->write($this->getAbsolutePath($basePath), Template::Enumeration, $context);
            }
        }
    }
}

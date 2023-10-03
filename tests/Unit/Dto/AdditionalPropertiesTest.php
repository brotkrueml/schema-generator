<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Dto;

use Brotkrueml\SchemaGenerator\Dto\AdditionalProperties;
use Brotkrueml\SchemaGenerator\Dto\Extension;
use Brotkrueml\SchemaGenerator\Dto\Property;
use Brotkrueml\SchemaGenerator\Dto\Type;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AdditionalPropertiesTest extends TestCase
{
    private AdditionalProperties $subject;

    protected function setUp(): void
    {
        $this->subject = new AdditionalProperties(new Extension('auto'));
    }

    #[Test]
    public function emptyTermsReturnEmptyArray(): void
    {
        self::assertSame([], $this->subject->getTerms());
    }

    #[Test]
    public function typesAndPropertiesAreReturnedCorrectly(): void
    {
        $type1 = new Type('BibType', '', 'BibType', [], new Extension('bib'));
        $property11 = new Property('x', [], new Extension('bib'));
        $property12 = new Property('a', [], new Extension('bib'));
        $property13 = new Property('b', [], new Extension('bib'));
        $this->subject->addPropertiesToType($type1, $property11, $property12, $property13);

        $type2 = new Type('AutoType', '', 'AutoType', [], new Extension('auto'));
        $property21 = new Property('2', [], new Extension('auto'));
        $property22 = new Property('1', [], new Extension('auto'));
        $property23 = new Property('3', [], new Extension('auto'));
        $this->subject->addPropertiesToType($type2, $property21, $property22, $property23);

        $type3 = new Type('AnotherAutoType', '', 'AnotherAutoType', [], new Extension('auto'));
        $property31 = new Property('z', [], new Extension('auto'));
        $this->subject->addPropertiesToType($type3, $property31);

        $actual = $this->subject->getTerms();
        self::assertCount(2, $actual);
        self::assertCount(2, $actual['']);
        self::assertSame(['1', '2', '3'], $actual['']['AutoType']);
        self::assertSame(['z'], $actual['']['AnotherAutoType']);
        self::assertCount(1, $actual['bib']);
        self::assertSame(['a', 'b', 'x'], $actual['bib']['BibType']);
    }
}

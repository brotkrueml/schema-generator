<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Generation;

use Brotkrueml\SchemaGenerator\Generation\Template;
use Brotkrueml\SchemaGenerator\Generation\Writer;
use Brotkrueml\SchemaGenerator\Manual\Manual;
use Brotkrueml\SchemaGenerator\Manual\Manuals;
use Brotkrueml\SchemaGenerator\Manual\Publisher;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Enumeration;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Member;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Property;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Twig\Environment as TwigEnvironment;

#[CoversClass(Writer::class)]
final class WriterTest extends TestCase
{
    private Writer $subject;

    protected function setUp(): void
    {
        $containerConfiguration = require(__DIR__ . '/../../../config/container.php');

        $containerDummy = new class implements ContainerInterface {
            public function get(string $id): never
            {
                throw new \Exception('should not be called');
            }

            public function has(string $id): bool
            {
                throw new \Exception('should not be called');
            }
        };

        $twig = $containerConfiguration[TwigEnvironment::class]($containerDummy);

        $this->subject = new Writer($twig);
    }

    #[Test]
    public function writeThingAsModel(): void
    {
        $section = Section::Core;

        $type = new Type(
            new Id('schema:Thing'),
            new Comment('The most generic type of item.'),
            $section,
        );

        $properties = [
            'description' => new Property(
                new Id('schema:description'),
                new Comment('A description of the item.'),
                $section,
            ),
            'name' => new Property(
                new Id('schema:name'),
                new Comment('The name of the item.'),
                $section,
            ),
        ];

        $context = [
            'className' => 'Thing',
            'isWebPageType' => false,
            'manuals' => new Manuals(),
            'namespace' => $section->phpNamespace(),
            'properties' => $properties,
            'type' => $type,
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::Model;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/Thing.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\Schema\Model\Type;

use Brotkrueml\Schema\Attributes\Manual;
use Brotkrueml\Schema\Manual\Publisher;
use Brotkrueml\Schema\Attributes\Type;
use Brotkrueml\Schema\Core\Model\AbstractType;

/**
* The most generic type of item.
*/
#[Type('Thing')]
final class Thing extends AbstractType
{
protected static array $propertyNames = [
'description',
'name',
];
}
EXPECTED,
            $actual,
        );
    }

    #[Test]
    public function writeBusinessEntityTypeAsDeprecatedModel(): void
    {
        $section = Section::Core;

        $type = new Type(
            new Id('schema:BusinessEntityType'),
            new Comment('A business entity type is a conceptual entity representing the legal form.'),
            $section,
        );
        $type->setAsEnumeration();

        $properties = [
            'description' => new Property(
                new Id('schema:description'),
                new Comment('A description of the item.'),
                $section,
            ),
            'name' => new Property(
                new Id('schema:name'),
                new Comment('The name of the item.'),
                $section,
            ),
        ];

        $context = [
            'className' => 'BusinessEntityType',
            'isWebPageType' => false,
            'manuals' => new Manuals(),
            'namespace' => $section->phpNamespace(),
            'properties' => $properties,
            'type' => $type,
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::Model;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/BusinessEntityType.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\Schema\Model\Type;

use Brotkrueml\Schema\Attributes\Manual;
use Brotkrueml\Schema\Manual\Publisher;
use Brotkrueml\Schema\Attributes\Type;
use Brotkrueml\Schema\Core\Model\AbstractType;

/**
* A business entity type is a conceptual entity representing the legal form.
* @deprecated This type represents an enumeration, use the specific BusinessEntityType enum instead.
*/
#[Type('BusinessEntityType')]
final class BusinessEntityType extends AbstractType
{
protected static array $propertyNames = [
'description',
'name',
];
}
EXPECTED,
            $actual,
        );
    }

    #[Test]
    public function writeWebPageAsModel(): void
    {
        $section = Section::Core;

        $type = new Type(
            new Id('schema:WebPage'),
            new Comment('A web page.'),
            $section,
        );

        $properties = [
            'description' => new Property(
                new Id('schema:description'),
                new Comment('A description of the item.'),
                $section,
            ),
            'name' => new Property(
                new Id('schema:name'),
                new Comment('The name of the item.'),
                $section,
            ),
        ];

        $context = [
            'className' => 'WebPage',
            'isWebPageType' => true,
            'manuals' => new Manuals(),
            'namespace' => $section->phpNamespace(),
            'properties' => $properties,
            'type' => $type,
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::Model;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/WebPage.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\Schema\Model\Type;

use Brotkrueml\Schema\Attributes\Manual;
use Brotkrueml\Schema\Manual\Publisher;
use Brotkrueml\Schema\Attributes\Type;
use Brotkrueml\Schema\Core\Model\AbstractType;
use Brotkrueml\Schema\Core\Model\WebPageTypeInterface;

/**
* A web page.
*/
#[Type('WebPage')]
final class WebPage extends AbstractType
implements WebPageTypeInterface
{
protected static array $propertyNames = [
'description',
'name',
];
}
EXPECTED,
            $actual,
        );
    }

    #[Test]
    public function writeArticleWithManualAsModel(): void
    {
        $section = Section::Core;

        $type = new Type(
            new Id('schema:Article'),
            new Comment('An article.'),
            $section,
        );

        $properties = [
            'description' => new Property(
                new Id('schema:description'),
                new Comment('A description of the item.'),
                $section,
            ),
            'name' => new Property(
                new Id('schema:name'),
                new Comment('The name of the item.'),
                $section,
            ),
        ];

        $manuals = new Manuals();
        $manuals->addManual(new Manual(
            'Article',
            Publisher::Google,
            'https://example.org/#Article',
        ));
        $manuals->addManual(new Manual(
            'Article',
            Publisher::Yandex,
            'https://example.com/#Article',
        ));

        $context = [
            'className' => 'Article',
            'isWebPageType' => false,
            'manuals' => $manuals,
            'namespace' => $section->phpNamespace(),
            'properties' => $properties,
            'type' => $type,
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::Model;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/Article.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\Schema\Model\Type;

use Brotkrueml\Schema\Attributes\Manual;
use Brotkrueml\Schema\Manual\Publisher;
use Brotkrueml\Schema\Attributes\Type;
use Brotkrueml\Schema\Core\Model\AbstractType;

/**
* An article.
*/
#[Type('Article')]
#[Manual(Publisher::Google, 'https://example.org/#Article')]
#[Manual(Publisher::Yandex, 'https://example.com/#Article')]
final class Article extends AbstractType
{
protected static array $propertyNames = [
'description',
'name',
];
}
EXPECTED,
            $actual,
        );
    }

    #[Test]
    public function writeThingAsViewHelper(): void
    {
        $section = Section::Core;

        $type = new Type(
            new Id('schema:Thing'),
            new Comment('The most generic type of item.'),
            $section,
        );

        $context = [
            'className' => 'ThingViewHelper',
            'namespace' => $section->phpNamespace(),
            'type' => $type,
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::ViewHelper;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/ThingViewHelper.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\Schema\ViewHelpers\Type;

use Brotkrueml\Schema\Core\ViewHelpers\AbstractTypeViewHelper;

/**
* The most generic type of item.
*/
final class ThingViewHelper extends AbstractTypeViewHelper
{
protected string $type = 'Thing';
}
EXPECTED,
            $actual,
        );
    }

    #[Test]
    public function writeBusinessEntityTypeAsDeprecatedViewHelper(): void
    {
        $section = Section::Core;

        $type = new Type(
            new Id('schema:BusinessEntityType'),
            new Comment('A business entity type is a conceptual entity representing the legal form.'),
            $section,
        );
        $type->setAsEnumeration();

        $context = [
            'className' => 'BusinessEntityTypeViewHelper',
            'namespace' => $section->phpNamespace(),
            'type' => $type,
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::ViewHelper;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/BusinessEntityTypeViewHelper.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\Schema\ViewHelpers\Type;

use Brotkrueml\Schema\Core\ViewHelpers\AbstractTypeViewHelper;

/**
* A business entity type is a conceptual entity representing the legal form.
* @deprecated This type represents an enumeration, use the enum with the {f:constant()} ViewHelper instead (available since Fluid 2.12).
*/
final class BusinessEntityTypeViewHelper extends AbstractTypeViewHelper
{
protected string $type = 'BusinessEntityType';
}
EXPECTED,
            $actual,
        );
    }

    #[Test]
    public function additionalProperties(): void
    {
        $section = Section::Pending;

        $additionalPropertiesBySectionAndType = [
            'core' => [
                'AboutPage' => [
                    new Property(new Id('schema:abstract'), new Comment('An abstract is a short description that summarizes a CreativeWork.'), $section),
                ],
            ],
            'auto' => [
                'BusOrCoach' => [
                    new Property(new Id('schema:asin'), new Comment('An Amazon Standard Identification Number (ASIN).'), $section),
                    new Property(new Id('schema:callSign'), new Comment('A callsign, as used in broadcasting and radio communications to identify people, radio and TV stations, or vehicles.'), $section),
                ],
            ],
            'health' => [
                'Drug' => [
                    new Property(new Id('schema:includedInHealthInsurancePlan'), new Comment('The insurance plans that cover this drug.'), $section),
                    new Property(new Id('schema:positiveNotes'), new Comment('Provides positive considerations regarding something.'), $section),
                ],
                'Joint' => [
                    new Property(new Id('schema:funding'), new Comment('A Grant that directly or indirectly provide funding or sponsorship for this item.'), $section),
                ],
            ],
        ];

        $context = [
            'additionalProperties' => $additionalPropertiesBySectionAndType,
            'className' => 'AdditionalProperties',
            'currentSection' => $section,
            'namespace' => $section->phpNamespace(),
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::AdditionalProperties;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/AdditionalProperties.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaPending\EventListener;

use Brotkrueml\Schema\Event\RegisterAdditionalTypePropertiesEvent;
use Brotkrueml\Schema\Model\Type;
use Brotkrueml\SchemaAuto\Model\Type as AutoType;
use Brotkrueml\SchemaHealth\Model\Type as HealthType;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class RegisterAdditionalProperties
{
public function __invoke(RegisterAdditionalTypePropertiesEvent $event): void
{
if ($event->getType() === Type\AboutPage::class) {
$event->registerAdditionalProperty('abstract');
}
if (ExtensionManagementUtility::isLoaded('schema_auto')) {
if ($event->getType() === AutoType\BusOrCoach::class) {
$event->registerAdditionalProperty('asin');
$event->registerAdditionalProperty('callSign');
}
}
if (ExtensionManagementUtility::isLoaded('schema_health')) {
if ($event->getType() === HealthType\Drug::class) {
$event->registerAdditionalProperty('includedInHealthInsurancePlan');
$event->registerAdditionalProperty('positiveNotes');
}
if ($event->getType() === HealthType\Joint::class) {
$event->registerAdditionalProperty('funding');
}
}
}
}
EXPECTED,
            $actual,
        );
    }

    #[Test]
    public function writeRsvpResponseTypeAsEnumeration(): void
    {
        $enumeration = new Enumeration(
            new Id('schema:RsvpResponseType'),
            new Comment('RsvpResponseType is an enumeration type whose instances represent responding to an RSVP request.'),
            Section::Core,
        );

        $members = [
            'RsvpResponseYes' => new Member(
                new Id('schema:RsvpResponseYes'),
                new Comment('The invitee will attend.'),
            ),
            'RsvpResponseMaybe' => new Member(
                new Id('schema:RsvpResponseMaybe'),
                new Comment('The invitee may or may not attend.'),
            ),
            'RsvpResponseNo' => new Member(
                new Id('schema:RsvpResponseNo'),
                new Comment('The invitee will not attend.'),
            ),
        ];

        $context = [
            'className' => 'RsvpResponseType',
            'enumeration' => $enumeration,
            'namespace' => Section::Core->phpNamespace(),
            'members' => $members,
        ];

        $destinationPath = \sys_get_temp_dir();
        $template = Template::Enumeration;

        $this->subject->write($destinationPath, $template, $context);

        $destinationFile = $destinationPath . '/RsvpResponseType.php';
        $actual = $this->trimSpacesFromLines(\file_get_contents($destinationFile));

        self::assertSame(
            <<<'EXPECTED'
<?php
declare(strict_types=1);

namespace Brotkrueml\Schema\Model\Enumeration;

use Brotkrueml\Schema\Core\Model\EnumerationInterface;

/**
* RsvpResponseType is an enumeration type whose instances represent responding to an RSVP request.
* @experimental This enum is considered experimental and may change at any time until it is declared stable.
*/
enum RsvpResponseType implements EnumerationInterface
{
/**
* The invitee will attend.
*/
case RsvpResponseYes;

/**
* The invitee may or may not attend.
*/
case RsvpResponseMaybe;

/**
* The invitee will not attend.
*/
case RsvpResponseNo;


public function canonical(): string
{
return 'https://schema.org/' . $this->name;
}
}
EXPECTED,
            $actual,
        );
    }

    private function trimSpacesFromLines(string $text): string
    {
        $lines = \explode(
            "\n",
            $text,
        );

        \array_walk($lines, static function (string &$line): void {
            $line = \trim($line);
        });

        return \trim(\implode("\n", $lines));
    }
}

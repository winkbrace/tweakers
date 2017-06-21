<?php declare(strict_types = 1);

namespace Tweakers\Tests\Common;

use Tweakers\Exception\InvalidEnumValue;
use Tweakers\Stubs\StubEnum;

class EnumTest extends \PHPUnit\Framework\TestCase
{
    /** @var StubEnum */
    private $status;

    public function setUp()
    {
        $this->status = new StubEnum('draft');
    }

    public function test_creation()
    {
        $this->assertInstanceOf(StubEnum::class, $this->status);
        $this->assertEquals('draft', $this->status->getValue());
        $this->assertEquals('draft', $this->status->lower());
        $this->assertEquals('DRAFT', $this->status->upper());
        $this->assertEquals('Draft', $this->status->ucfirst());
    }

    public function test_get_allowed_values_returns_expected_array()
    {
        $expected = [
            'DRAFT'  => 'draft',
            'OPEN'   => 'open',
            'CLOSED' => 'closed',
        ];

        $this->assertEquals($expected, StubEnum::all());
    }

    public function test_the_status_string_must_have_correct_case()
    {
        $this->expectException(InvalidEnumValue::class);

        new StubEnum('Draft'); // check an existing value with a capital D instead of a lowercase
    }

    public function test_an_invalid_value_is_not_allowed()
    {
        // test an invalid value
        $this->expectException(InvalidEnumValue::class);

        new StubEnum('Invalid value');
    }

    public function test_magic_is_methods()
    {
        $enum = new StubEnum(StubEnum::OPEN);

        $this->assertTrue($enum->isOpen());
        $this->assertFalse($enum->isClosed());
        $this->assertFalse($enum->isDraft());
    }

    public function test_magic_constructor()
    {
        $enum = StubEnum::OPEN();

        $this->assertInstanceOf(StubEnum::class, $enum);
        $this->assertEquals(StubEnum::OPEN, $enum->getValue());
    }

    /** @test */
    public function it_should_tell_if_it_has_a_value()
    {
        $this->assertTrue(StubEnum::has('open'));
        $this->assertFalse(StubEnum::has('unknown'));
    }
}

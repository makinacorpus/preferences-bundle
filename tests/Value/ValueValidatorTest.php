<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Tests\Value;

use MakinaCorpus\Preferences\ValueType;
use MakinaCorpus\Preferences\Value\ValueValidator;
use PHPUnit\Framework\TestCase;

final class ValueValidatorTest extends TestCase
{
    /**
     * Test type of
     */
    public function testGetTypeOf()
    {
        $type = ValueValidator::getTypeOf(new \DateTime());

        self::assertSame(\DateTime::class, $type->nativeType);
        self::assertFalse($type->collection);
        self::assertFalse($type->enum);
        self::assertFalse($type->hashMap);
    }

    /**
     * Test type of null
     */
    public function testGetTypeOfNullIsString()
    {
        $type = ValueValidator::getTypeOf(null);

        self::assertSame('string', $type->nativeType);
        self::assertFalse($type->collection);
    }

    /**
     * Test type of collection
     */
    public function testGetTypeOfCollection()
    {
        $type = ValueValidator::getTypeOf([true, false, true, false]);

        self::assertSame('bool', $type->nativeType);
        self::assertTrue($type->collection);
    }

    /**
     * Test type of empty collection
     */
    public function testGetTypeOfEmptyCollectionIsString()
    {
        $type = ValueValidator::getTypeOf([]);

        self::assertSame('string', $type->nativeType);
        self::assertTrue($type->collection);
    }

    /**
     * If value is not a collection, but definition is, accept it and convert
     */
    public function testValidateSingleValueAsCollection()
    {
        $type = new ValueType('string', true);
        $value = "foo";

        $this->assertSame(["foo"], ValueValidator::validate($type, $value));
    }

    /**
     * If a single value is type mismatch, it must fail
     */
    public function testValidateCollectionWithOneError()
    {
        $type = new ValueType('int', true);
        $value = [1, 2, "3", 4];

        $this->expectException(\InvalidArgumentException::class);
        ValueValidator::validate($type, $value);
    }

    /**
     * Validate a nice collection
     */
    public function testValidateCollection()
    {
        $type = new ValueType('int', true);
        $value = [1, 2, 3, 4];

        $this->assertSame($value, ValueValidator::validate($type, $value));
    }

    /**
     * Simple enum validation
     */
    public function testValidateEnum()
    {
        $type = new ValueType('string', false, ['foo', 'bar']);
        $value = 'foo';

        $this->assertSame('foo', ValueValidator::validate($type, $value));
    }

    /**
     * Enum collection validation
     */
    public function testValidateEnumCollection()
    {
        $type = new ValueType('string', true, ['foo', 'bar']);
        $value = ['bar', 'foo'];

        $this->assertSame(['bar', 'foo'], ValueValidator::validate($type, $value));
    }

    /**
     * Simple enum, but with a wrong value
     */
    public function testValidateEnumWithError()
    {
        $type = new ValueType('string', false, ['foo', 'bar']);
        $value = 'baz';

        $this->expectException(\InvalidArgumentException::class);
        ValueValidator::validate($type, $value);
    }

    /**
     * Enum collection, with a single error
     */
    public function testValidateEnumCollectionWithError()
    {
        $type = new ValueType('string', true, ['foo', 'bar']);
        $value = ['foo', 'baz'];

        $this->expectException(\InvalidArgumentException::class);
        ValueValidator::validate($type, $value);
    }

    /**
     * Data provider
     */
    public static function dataValidateObject()
    {
        return [
            [new ValueType(\ArrayObject::class), new \ArrayObject()]
        ];
    }

    /**
     * @dataProvider dataValidateObject
     */
    public function testValidateObject(ValueType $type, $value)
    {
        self::assertSame($value, ValueValidator::validate($type, $value));
    }

    /**
     * Data provider
     */
    public static function dataValidateObjectWithError()
    {
        return [
            [new ValueType(\Exception::class), new \ArrayObject()]
        ];
    }

    /**
     * @dataProvider dataValidateObjectWithError
     */
    public function testValidateObjectWithError(ValueType $type, $value)
    {
        $this->expectException(\InvalidArgumentException::class);
        ValueValidator::validate($type, $value);
    }

    /**
     * Data provider
     */
    public static function dataValidate()
    {
        return [
            [new ValueType('int'), 12],
            [new ValueType('float'), 12.3],
            [new ValueType('bool'), true],
            [new ValueType('string'), "test"],
        ];
    }

    /**
     * @dataProvider dataValidate
     */
    public function testValidate(ValueType $type, $value)
    {
        self::assertSame($value, ValueValidator::validate($type, $value));
    }

    /**
     * Data provider
     */
    public static function dataValidateWithError()
    {
        return [
            [new ValueType('int'), "12"],
            [new ValueType('bool'), 1],
            [new ValueType('string'), false],
        ];
    }

    /**
     * @dataProvider dataValidateWithError
     */
    public function testValidateWithError(ValueType $type, $value)
    {
        $this->expectException(\InvalidArgumentException::class);
        ValueValidator::validate($type, $value);
    }

    /**
     * callback type is not allowed
     */
    public function testValidateWithCallbackRaiseError()
    {
        $this->expectException(\InvalidArgumentException::class);
        ValueValidator::validate(new ValueType('null'), function () {});
    }

    /**
     * resource type is not allowed
     */
    public function testValidateWithResourceRaiseError()
    {
        $this->expectException(\InvalidArgumentException::class);
        ValueValidator::validate(new ValueType('null'), \fopen(__FILE__, 'r'));
    }
}

<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional;

use ArrayIterator;

class AnyTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->goodArray = array('value', 'wrong');
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = array('wrong', 'wrong', 'wrong');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    function test()
    {
        $this->assertTrue(any($this->goodArray, array($this, 'callback')));
        $this->assertTrue(any($this->goodIterator, array($this, 'callback')));
        $this->assertFalse(any($this->badArray, array($this, 'callback')));
        $this->assertFalse(any($this->badIterator, array($this, 'callback')));
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\any() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        any($this->goodArray, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\any() expects parameter 1 to be array or instance of Traversable');
        any('invalidCollection', 'strlen');
    }

    function testExceptionThrownInArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        any($this->goodArray, array($this, 'exception'));
    }

    function testExceptionThrownInCollection()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        any($this->goodIterator, array($this, 'exception'));
    }

    function callback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value == 'value' && $key === 0;
    }
}

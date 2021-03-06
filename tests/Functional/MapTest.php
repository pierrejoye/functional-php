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

class MapTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'val1', 'k2' => 'val2');
        $this->keyedIterator = new ArrayIterator($this->keyedArray);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $k . $v;
        };
        $this->assertSame(array('0value', '1value'), map($this->array, $fn));
        $this->assertSame(array('0value', '1value'), map($this->iterator, $fn));
        $this->assertSame(array('k1' => 'k1val1', 'k2' => 'k2val2'), map($this->keyedArray, $fn));
        $this->assertSame(array('k1' => 'k1val1', 'k2' => 'k2val2'), map($this->keyedIterator, $fn));
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        map($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        map($this->keyedArray, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        map($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedIterator()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        map($this->keyedIterator, array($this, 'exception'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\map() expects parameter 1 to be array or instance of Traversable');
        map('invalidCollection', 'strlen');
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\map() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        map($this->array, 'undefinedFunction');
    }
}

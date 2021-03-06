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
namespace Functional\Exceptions;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    function testCallbackExceptionWithUndefinedStaticMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, method 'stdClass::method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback(array('stdClass', 'method'), 'func', 1);
    }

    function testCallbackExceptionWithUndefinedFunction()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, function 'undefinedFunction' not found or invalid function name"
        );

        InvalidArgumentException::assertCallback('undefinedFunction', 'func', 1);
    }

    function testCallbackExceptionWithUndefinedMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 2 to be a valid callback, method 'stdClass->method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback(array(new \stdClass(), 'method'), 'func', 2);
    }

    function testCallbackExceptionWithIncorrectArrayIndex()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, method 'stdClass->method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback(array(1 => new \stdClass(), 2 => 'method'), 'func', 1);
    }

    function testExceptionIfStringIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 4 to be array or instance of Traversable"
        );

        InvalidArgumentException::assertCollection('string', 'func', 4);
    }

    function testExceptionIfObjectIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 2 to be array or instance of Traversable"
        );

        InvalidArgumentException::assertCollection(new \stdClass(), 'func', 2);
    }

    function testExceptionIfInvalidMethodName()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'foo() expects parameter 2 to be string, object given'
        );
        InvalidArgumentException::assertMethodName(new \stdClass(), "foo", 2);
    }

    function testExceptionIfInvalidPropertyName()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expects parameter 2 to be string, object given'
        );
        InvalidArgumentException::assertPropertyName(new \stdClass(), "func", 2);
    }
}
